<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Payment\VNPayService;
use Illuminate\Support\Facades\DB;
use App\Notifications\SystemNotification;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceMail;

class PaymentController extends Controller
{
    protected $vnpayService;

    public function __construct(VNPayService $vnpayService)
    {
        $this->vnpayService = $vnpayService;
    }

    public function createPayment(Request $request)
    {
        $orderId = time() . '_' . rand(1000, 9999);
        $amount = 300000; // Mock 300k
        $desc = "Thanh toan don hang " . $orderId;

        $url = $this->vnpayService->createPaymentUrl($orderId, $amount, $desc);
        
        return redirect($url);
    }

    public function vnpayReturn(Request $request)
    {
        $vnpayService = new \App\Services\Payment\VNPayService();
        $result = $vnpayService->verifyPayment($request->all());

        $bookingCode = $request->vnp_TxnRef;
        $booking = \App\Models\Booking::where('booking_code', $bookingCode)->first();

        if ($result['success']) {
            if ($booking) {
                // Task 4: Đối chiếu số dư (VNPay amount is in VND * 100)
                $returnedAmount = $request->vnp_Amount / 100;
                if ($returnedAmount != $booking->total_amount) {
                    return redirect()->route('fields.index')->with('error', 'Giao dịch VNPay thất bại do số tiền không khớp (Nghi ngờ gian lận)!');
                }

                // Task 4: Chống double-payment
                $existingPayment = \App\Models\Payment::where('booking_id', $booking->id)
                                    ->where('status', 'success')
                                    ->first();
                
                if (!$existingPayment) {
                    $booking->update(['status' => 'confirmed']); // Hoặc 'completed' tùy luồng, 'confirmed' hợp lý hơn cho đặt trước
                    
                    $newPayment = \App\Models\Payment::create([
                        'booking_id' => $booking->id,
                        'transaction_id' => $request->vnp_TransactionNo ?? 'VNP' . time(),
                        'amount' => $returnedAmount,
                        'payment_method' => 'vnpay',
                        'status' => 'success'
                    ]);
                    
                    // Gửi Email Hóa Đơn
                    if ($booking->user && $booking->user->email) {
                        try {
                            Mail::to($booking->user->email)->send(new InvoiceMail($booking, $newPayment));
                        } catch (\Exception $e) {
                            \Illuminate\Support\Facades\Log::error('Error sending invoice email: ' . $e->getMessage());
                        }
                    }
                }
            }
            if (auth()->check()) {
                auth()->user()->notify(new SystemNotification(
                    '✅ Thanh toán thành công',
                    "Đơn đặt sân {$bookingCode} đã được thanh toán qua VNPay thành công!",
                    'success'
                ));
                if (auth()->user()->role === 'admin' || auth()->user()->role === 'staff') {
                    return redirect()->route('fields.index')->with('success', 'Giao dịch VNPay thành công!');
                }
                return redirect()->route('fields.index')->with('success', 'Giao dịch VNPay thành công!');
            }
            return redirect()->route('fields.index')->with('success', 'Giao dịch VNPay thành công! Mã đơn hàng: ' . $bookingCode);
        }
        
        if ($booking && $booking->status == 'pending') {
            $booking->update(['status' => 'cancelled']);
        }

        if (auth()->check()) {
            if (auth()->user()->role === 'admin' || auth()->user()->role === 'staff') {
                return redirect()->route('admin.dashboard')->with('error', 'Giao dịch bị hủy hoặc thất bại.');
            }
            return redirect()->route('customer.dashboard')->with('error', 'Giao dịch bị hủy hoặc thất bại.');
        }
        return redirect()->route('home')->with('error', 'Giao dịch bị hủy hoặc thất bại.');
    }

    public function momoReturn(Request $request)
    {
        $momoService = new \App\Services\Payment\MoMoService();
        $result = $momoService->verifyPayment($request->all());

        $bookingCode = $request->orderId;
        $booking = \App\Models\Booking::where('booking_code', $bookingCode)->first();

        if ($result['success']) {
            if ($booking) {
                // Task 4: Đối chiếu số dư
                $returnedAmount = $request->amount ?? $booking->total_amount; // Fallback if real momo doesn't pass it back directly in this field
                if (isset($request->amount) && $request->amount != $booking->total_amount) {
                    return redirect()->route('fields.index')->with('error', 'Giao dịch MoMo thất bại do số tiền không khớp (Nghi ngờ gian lận)!');
                }

                // Task 4: Chống double-payment
                $existingPayment = \App\Models\Payment::where('booking_id', $booking->id)
                                    ->where('status', 'success')
                                    ->first();

                if (!$existingPayment) {
                    $booking->update(['status' => 'confirmed']);
                    
                    $newPayment = \App\Models\Payment::create([
                        'booking_id' => $booking->id,
                        'transaction_id' => $result['transId'] ?? 'MOMO' . time(),
                        'amount' => $booking->total_amount,
                        'payment_method' => 'momo',
                        'status' => 'success'
                    ]);
                    
                    // Gửi Email Hóa Đơn
                    if ($booking->user && $booking->user->email) {
                        try {
                            Mail::to($booking->user->email)->send(new InvoiceMail($booking, $newPayment));
                        } catch (\Exception $e) {
                            \Illuminate\Support\Facades\Log::error('Error sending invoice email: ' . $e->getMessage());
                        }
                    }
                }
            }
            if (auth()->check()) {
                auth()->user()->notify(new SystemNotification(
                    '✅ Thanh toán thành công',
                    "Đơn đặt sân {$bookingCode} đã được thanh toán qua MoMo thành công!",
                    'success'
                ));
                if (auth()->user()->role === 'admin' || auth()->user()->role === 'staff') {
                    return redirect()->route('fields.index')->with('success', 'Giao dịch MoMo thành công!');
                }
                return redirect()->route('fields.index')->with('success', 'Giao dịch MoMo thành công!');
            }
            return redirect()->route('fields.index')->with('success', 'Giao dịch MoMo thành công! Mã đơn hàng: ' . $bookingCode);
        }
        
        if ($booking && $booking->status == 'pending') {
            $booking->update(['status' => 'cancelled']);
        }

        if (auth()->check()) {
            if (auth()->user()->role === 'admin' || auth()->user()->role === 'staff') {
                return redirect()->route('admin.dashboard')->with('error', 'Giao dịch MoMo bị hủy hoặc thất bại.');
            }
            return redirect()->route('customer.dashboard')->with('error', 'Giao dịch MoMo bị hủy hoặc thất bại.');
        }
        return redirect()->route('home')->with('error', 'Giao dịch MoMo bị hủy hoặc thất bại.');
    }
}
