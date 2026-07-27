<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\TimeSlot;
use App\Models\Payment;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'field_id' => 'required|exists:fields,id',
            'booking_date' => 'required|date',
            'slots' => 'required|array|min:1',
            'slots.*.start_time' => 'required',
            'slots.*.end_time' => 'required',
            'total_amount' => 'required|numeric'
        ]);

        $timeSlots = [];
        // First loop: Check availability for ALL slots before saving anything
        foreach ($request->slots as $slotData) {
            $timeSlot = TimeSlot::firstOrCreate([
                'start_time' => $slotData['start_time'] . (strlen($slotData['start_time']) == 5 ? ':00' : ''),
                'end_time' => $slotData['end_time'] . (strlen($slotData['end_time']) == 5 ? ':00' : '')
            ], [
                'price_modifier' => 0,
                'is_active' => true
            ]);

            $exists = BookingDetail::whereHas('booking', function($q) use ($request) {
                $q->where('booking_date', $request->booking_date)
                  ->whereIn('status', ['pending', 'confirmed']);
            })
            ->where('field_id', $request->field_id)
            ->where('time_slot_id', $timeSlot->id)
            ->exists();

            if ($exists) {
                return response()->json(['success' => false, 'message' => 'Một hoặc nhiều khung giờ bạn chọn đã có người đặt. Vui lòng tải lại trang.']);
            }
            
            $timeSlots[] = $timeSlot;
        }

        $notes = 'Đang chờ thanh toán qua ' . $request->payment_method;
        if ($request->voucher_code) {
            $voucher = \App\Models\Voucher::where('code', $request->voucher_code)->first();
            if ($voucher) {
                $voucher->increment('used_count');
                $notes .= ' (Có áp dụng mã ' . $request->voucher_code . ')';
            }
        }

        $bookingCode = $request->booking_code ?? ('BK' . strtoupper(Str::random(6)));
        
        $booking = Booking::create([
            'user_id' => auth()->id() ?? 1, // fallback if not logged in
            'booking_code' => $bookingCode,
            'booking_date' => $request->booking_date,
            'total_amount' => $request->total_amount,
            'status' => 'pending', 
            'notes' => $notes
        ]);

        // Second loop: Create booking details
        $perSlotPrice = $request->total_amount / count($timeSlots); // Rough average distribution for details
        foreach ($timeSlots as $ts) {
            $booking->details()->create([
                'field_id' => $request->field_id,
                'time_slot_id' => $ts->id,
                'price' => $perSlotPrice
            ]);
        }

        if ($request->payment_method === 'vnpay') {
            $vnpayService = new \App\Services\Payment\VNPayService();
            $redirectUrl = $vnpayService->createPaymentUrl(
                $booking->booking_code, 
                $booking->total_amount, 
                "Thanh toan dat san " . $booking->booking_code
            );
            
            return response()->json([
                'success' => true, 
                'message' => 'Đang chuyển hướng đến VNPay...',
                'booking_code' => $booking->booking_code,
                'redirect_url' => $redirectUrl
            ]);
        }


        return response()->json([
            'success' => true, 
            'message' => 'Đặt sân thành công!',
            'booking_code' => $booking->booking_code
        ]);
    }

    public function checkVoucher(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        
        $voucher = \App\Models\Voucher::where('code', strtoupper($request->code))
            ->where('is_active', true)
            ->where('used_count', '<', \Illuminate\Support\Facades\DB::raw('max_uses'))
            ->where(function($q) {
                $q->whereNull('valid_from')->orWhere('valid_from', '<=', now());
            })
            ->where(function($q) {
                $q->whereNull('valid_to')->orWhere('valid_to', '>=', now());
            })
            ->first();

        if (!$voucher) {
            return response()->json(['success' => false, 'message' => 'Mã Voucher không hợp lệ, đã hết hạn hoặc hết lượt sử dụng.']);
        }

        return response()->json([
            'success' => true,
            'voucher' => [
                'code' => $voucher->code,
                'discount_percent' => $voucher->discount_percent,
                'discount_amount' => $voucher->discount_amount
            ]
        ]);
    }

    public function checkPaymentStatus($code)
    {
        $booking = Booking::where('booking_code', $code)->first();
        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Not found']);
        }

        return response()->json([
            'success' => true,
            'status' => $booking->status // 'pending', 'confirmed', etc.
        ]);
    }

    public function simulateWebhook($code)
    {
        $booking = Booking::where('booking_code', $code)->first();
        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking not found']);
        }

        if ($booking->status === 'pending') {
            $booking->update([
                'status' => 'confirmed',
                'notes' => $booking->notes . ' (Đã thanh toán tự động)'
            ]);
            
            // Create payment record
            Payment::create([
                'booking_id' => $booking->id,
                'amount' => $booking->total_amount,
                'payment_method' => str_contains(strtolower($booking->notes), 'momo') ? 'momo' : 'vnpay',
                'payment_status' => 'completed',
                'transaction_id' => 'SIMULATED_' . time()
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Simulated webhook processed']);
    }
}
