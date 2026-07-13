<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\FootballField;
use App\Models\TimeSlot;
use App\Models\Service;
use App\Models\ServiceOrder;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\User;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'bookingDetails.footballField', 'bookingDetails.timeSlot'])
            ->orderBy('booking_date', 'desc')
            ->orderBy('created_at', 'desc');

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('date')) {
            $query->whereDate('booking_date', $request->input('date'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%$search%")
                  ->orWhere('customer_phone', 'like', "%$search%");
            });
        }

        $bookings = $query->paginate(10)->withQueryString();
        $fields = FootballField::all();

        return view('admin.bookings.index', compact('bookings', 'fields'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'bookingDetails.footballField.fieldType', 'bookingDetails.timeSlot', 'serviceOrders.service', 'payment', 'invoice']);
        $services = Service::where('stock', '>', 0)->get();
        return view('admin.bookings.show', compact('booking', 'services'));
    }

    public function approve(Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Chỉ có thể xác nhận đơn đặt sân đang chờ duyệt.');
        }

        $booking->status = 'confirmed';
        $booking->save();

        return back()->with('success', 'Đã xác nhận đặt sân thành công.');
    }

    public function cancel(Booking $booking)
    {
        if ($booking->status === 'completed' || $booking->status === 'cancelled') {
            return back()->with('error', 'Không thể hủy đơn đặt sân đã hoàn thành hoặc đã hủy.');
        }

        $booking->status = 'cancelled';
        $booking->save();

        return back()->with('success', 'Đã hủy lịch đặt sân thành công.');
    }

    public function addServices(Request $request, Booking $booking)
    {
        if ($booking->status === 'cancelled' || $booking->status === 'completed') {
            return back()->with('error', 'Không thể thêm dịch vụ cho đơn đặt sân đã hủy hoặc đã hoàn thành.');
        }

        $request->validate([
            'service_id' => 'required|exists:services,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $service = Service::find($request->input('service_id'));
        $qty = (int)$request->input('quantity');

        if ($service->stock < $qty) {
            return back()->with('error', "Không đủ số lượng trong kho. Còn lại: {$service->stock}");
        }

        // Deduct service stock
        $service->stock -= $qty;
        $service->save();

        // Check if service order already exists for this booking
        $existingOrder = ServiceOrder::where('booking_id', $booking->id)
            ->where('service_id', $service->id)
            ->first();

        if ($existingOrder) {
            $existingOrder->quantity += $qty;
            $existingOrder->total_amount = $existingOrder->quantity * $existingOrder->price;
            $existingOrder->save();
        } else {
            ServiceOrder::create([
                'booking_id' => $booking->id,
                'service_id' => $service->id,
                'quantity' => $qty,
                'price' => $service->price,
                'total_amount' => $service->price * $qty
            ]);
        }

        // Update booking total amount
        $bookingDetailsTotal = $booking->bookingDetails()->sum('price');
        $serviceOrdersTotal = $booking->serviceOrders()->sum('total_amount');
        $booking->total_amount = $bookingDetailsTotal + $serviceOrdersTotal;
        $booking->save();

        return back()->with('success', 'Đã thêm dịch vụ thành công.');
    }

    public function removeService(Booking $booking, ServiceOrder $order)
    {
        if ($booking->status === 'cancelled' || $booking->status === 'completed') {
            return back()->with('error', 'Không thể thay đổi dịch vụ cho đơn đã khóa.');
        }

        // Restore stock
        $service = $order->service;
        $service->stock += $order->quantity;
        $service->save();

        $order->delete();

        // Update booking total
        $bookingDetailsTotal = $booking->bookingDetails()->sum('price');
        $serviceOrdersTotal = $booking->serviceOrders()->sum('total_amount');
        $booking->total_amount = $bookingDetailsTotal + $serviceOrdersTotal;
        $booking->save();

        return back()->with('success', 'Đã xóa dịch vụ thành công.');
    }

    public function checkout(Request $request, Booking $booking)
    {
        if ($booking->status !== 'confirmed') {
            return back()->with('error', 'Chỉ có thể thanh toán cho đơn đặt sân đã được xác nhận.');
        }

        $request->validate([
            'payment_method' => 'required|in:cash,bank_transfer',
            'discount' => 'nullable|numeric|min:0',
        ]);

        $paymentMethod = $request->input('payment_method');
        $discount = (float)$request->input('discount', 0);

        try {
            DB::beginTransaction();

            $booking->status = 'completed';
            $booking->save();

            // Total calculations
            $totalAmount = $booking->total_amount;
            $finalAmount = max(0, $totalAmount - $discount);

            // Create Payment
            Payment::create([
                'booking_id' => $booking->id,
                'amount' => $finalAmount,
                'payment_method' => $paymentMethod,
                'payment_status' => 'completed',
                'paid_at' => Carbon::now(),
                'transaction_id' => 'TXN' . time()
            ]);

            // Generate Invoice Number
            $invoiceNumber = 'HD-' . Carbon::now()->format('Ymd') . '-' . rand(1000, 9999);

            // Create Invoice
            $invoice = Invoice::create([
                'booking_id' => $booking->id,
                'user_id' => auth()->id(),
                'invoice_number' => $invoiceNumber,
                'customer_name' => $booking->customer_name,
                'customer_phone' => $booking->customer_phone,
                'total_amount' => $totalAmount,
                'discount' => $discount,
                'final_amount' => $finalAmount,
                'status' => 'paid'
            ]);

            // Add Booking Details to Invoice details
            foreach ($booking->bookingDetails as $detail) {
                $fieldName = $detail->footballField->name;
                $fieldTypeName = $detail->footballField->fieldType->name;
                $slotName = $detail->timeSlot->name;
                
                InvoiceDetail::create([
                    'invoice_id' => $invoice->id,
                    'item_name' => "$fieldName - $fieldTypeName ($slotName)",
                    'quantity' => 1,
                    'price' => $detail->price,
                    'total_amount' => $detail->price
                ]);
            }

            // Add Services to Invoice details
            foreach ($booking->serviceOrders as $so) {
                InvoiceDetail::create([
                    'invoice_id' => $invoice->id,
                    'item_name' => $so->service->name,
                    'quantity' => $so->quantity,
                    'price' => $so->price,
                    'total_amount' => $so->total_amount
                ]);
            }

            DB::commit();

            return redirect()->route('admin.bookings.show', $booking->id)
                ->with('success', 'Đã hoàn thành thanh toán và xuất hóa đơn.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Đã xảy ra lỗi trong quá trình thanh toán: ' . $e->getMessage());
        }
    }
}
