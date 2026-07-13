<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\FootballField;
use App\Models\TimeSlot;
use App\Models\Service;
use App\Models\ServiceOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerBookingController extends Controller
{
    public function create(Request $request)
    {
        $fields = FootballField::with('fieldType')->where('status', 'available')->get();
        $timeSlots = TimeSlot::orderBy('start_time', 'asc')->get();
        $services = Service::all();

        // Optional parameters passed from home page to pre-fill
        $selectedFieldId = $request->input('field_id');
        $selectedDate = $request->input('date', Carbon::today()->format('Y-m-d'));

        return view('customer.bookings.create', compact('fields', 'timeSlots', 'services', 'selectedFieldId', 'selectedDate'));
    }

    public function checkAvailability(Request $request)
    {
        $request->validate([
            'field_id' => 'required|exists:football_fields,id',
            'date' => 'required|date|after_or_equal:today',
        ]);

        $fieldId = $request->input('field_id');
        $date = $request->input('date');

        // Find slots that are already booked for this field on this date
        $bookedSlots = BookingDetail::where('football_field_id', $fieldId)
            ->where('booking_date', $date)
            ->whereHas('booking', function ($q) {
                $q->where('status', '!=', 'cancelled');
            })
            ->pluck('time_slot_id')
            ->toArray();

        return response()->json([
            'booked_slots' => $bookedSlots
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'football_field_id' => 'required|exists:football_fields,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'time_slots' => 'required|array',
            'time_slots.*' => 'exists:time_slots,id',
            'services' => 'nullable|array',
            'services.*' => 'integer|min:0',
            'notes' => 'nullable|string|max:500'
        ], [
            'football_field_id.required' => 'Vui lòng chọn sân bóng.',
            'booking_date.required' => 'Vui lòng chọn ngày đặt sân.',
            'booking_date.after_or_equal' => 'Ngày đặt sân phải từ hôm nay trở đi.',
            'time_slots.required' => 'Vui lòng chọn ít nhất một khung giờ.',
        ]);

        $user = auth()->user();
        $fieldId = $request->input('football_field_id');
        $bookingDate = Carbon::parse($request->input('booking_date'));
        $timeSlotIds = $request->input('time_slots');
        $servicesInput = $request->input('services', []);
        $notes = $request->input('notes');

        try {
            DB::beginTransaction();

            // 1. Double Booking Check (Overlap prevention)
            $alreadyBooked = BookingDetail::where('football_field_id', $fieldId)
                ->where('booking_date', $bookingDate->format('Y-m-d'))
                ->whereIn('time_slot_id', $timeSlotIds)
                ->whereHas('booking', function ($q) {
                    $q->where('status', '!=', 'cancelled');
                })
                ->exists();

            if ($alreadyBooked) {
                return back()->withInput()->with('error', 'Sân bóng này đã được đặt trong một hoặc nhiều khung giờ đã chọn. Vui lòng kiểm tra lại lịch trống.');
            }

            // 2. Fetch field and compute field total price
            $field = FootballField::with('fieldType')->find($fieldId);
            $hourlyRate = $field->fieldType->price_per_hour;
            // Each timeslot is 1.5 hours
            $slotDuration = 1.5;

            $totalFieldPrice = 0.00;
            $detailsData = [];

            foreach ($timeSlotIds as $slotId) {
                $slot = TimeSlot::find($slotId);
                $slotPrice = $hourlyRate * $slotDuration * $slot->price_multiplier;
                $totalFieldPrice += $slotPrice;

                $detailsData[] = [
                    'football_field_id' => $fieldId,
                    'time_slot_id' => $slotId,
                    'price' => $slotPrice
                ];
            }

            // 3. Create parent Booking
            $booking = Booking::create([
                'user_id' => $user->id,
                'customer_name' => $user->name,
                'customer_phone' => $user->phone,
                'booking_date' => $bookingDate,
                'total_amount' => 0, // update later
                'status' => 'pending', // awaits staff review
                'notes' => $notes
            ]);

            // 4. Create Booking Details
            foreach ($detailsData as $detail) {
                BookingDetail::create([
                    'booking_id' => $booking->id,
                    'football_field_id' => $detail['football_field_id'],
                    'booking_date' => $bookingDate,
                    'time_slot_id' => $detail['time_slot_id'],
                    'price' => $detail['price']
                ]);
            }

            // 5. Create Service Orders (if any)
            $totalServicesPrice = 0.00;
            foreach ($servicesInput as $serviceId => $quantity) {
                $quantity = (int)$quantity;
                if ($quantity > 0) {
                    $service = Service::find($serviceId);
                    if ($service && $service->stock >= $quantity) {
                        // Deduct stock
                        $service->stock -= $quantity;
                        $service->save();

                        $totalServiceOrder = $service->price * $quantity;
                        $totalServicesPrice += $totalServiceOrder;

                        ServiceOrder::create([
                            'booking_id' => $booking->id,
                            'service_id' => $serviceId,
                            'quantity' => $quantity,
                            'price' => $service->price,
                            'total_amount' => $totalServiceOrder
                        ]);
                    }
                }
            }

            // 6. Update booking total amount
            $booking->total_amount = $totalFieldPrice + $totalServicesPrice;
            $booking->save();

            // 7. Save pending payment record with discount subtraction
            $discount = (float)$request->input('discount', 0);
            $paymentMethod = $request->input('payment_method', 'cash');
            $finalAmount = max(0, $booking->total_amount - $discount);

            \App\Models\Payment::create([
                'booking_id' => $booking->id,
                'amount' => $finalAmount,
                'payment_method' => $paymentMethod,
                'payment_status' => 'pending',
                'transaction_id' => $paymentMethod !== 'cash' ? 'PENDING_' . strtoupper($paymentMethod) . '_' . time() : null
            ]);

            if ($discount > 0) {
                $booking->notes = ($booking->notes ? $booking->notes . ' | ' : '') . "Khách áp dụng mã giảm giá: -" . number_format($discount) . "đ";
                $booking->save();
            }

            DB::commit();

            if ($paymentMethod === 'vnpay' || $paymentMethod === 'momo') {
                return redirect()->route('customer.payment.process', $booking->id);
            }

            return redirect()->route('customer.dashboard')
                ->with('success', 'Yêu cầu đặt sân của bạn đã được gửi. Vui lòng chờ nhân viên xác nhận.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Đã xảy ra lỗi khi đặt sân: ' . $e->getMessage());
        }
    }

    public function cancel(Booking $booking)
    {
        // Check permissions
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Bạn không có quyền thực hiện hành động này.');
        }

        // Only allow cancellation if booking is still pending
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Chỉ có thể hủy đơn đặt sân khi chưa được nhân viên xác nhận.');
        }

        try {
            DB::beginTransaction();

            $booking->status = 'cancelled';
            $booking->save();

            // Restore stocks of ordered services
            foreach ($booking->serviceOrders as $so) {
                $service = $so->service;
                $service->stock += $so->quantity;
                $service->save();
            }

            DB::commit();
            return back()->with('success', 'Đã hủy lịch đặt sân thành công.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi khi hủy lịch đặt sân: ' . $e->getMessage());
        }
    }

    public function getFieldReviews(FootballField $field)
    {
        $reviews = \App\Models\Review::whereHas('booking.bookingDetails', function ($query) use ($field) {
            $query->where('football_field_id', $field->id);
        })
        ->with('user:id,name,avatar')
        ->orderBy('created_at', 'desc')
        ->get();

        $averageRating = $reviews->avg('rating') ?? 0;
        $totalReviews = $reviews->count();

        return response()->json([
            'reviews' => $reviews,
            'average' => round($averageRating, 1),
            'total' => $totalReviews
        ]);
    }
}
