<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Payment\VNPayService;
use Illuminate\Support\Facades\DB;

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
                $booking->update(['status' => 'confirmed']); // Hoặc 'completed' tùy luồng, 'confirmed' hợp lý hơn cho đặt trước
                
                \App\Models\Payment::create([
                    'booking_id' => $booking->id,
                    'transaction_id' => $request->vnp_TransactionNo ?? 'VNP' . time(),
                    'amount' => $request->vnp_Amount / 100, // VNPay sends amount * 100
                    'payment_method' => 'vnpay',
                    'status' => 'success'
                ]);
            }
            if (auth()->check()) {
                return redirect()->route('customer.dashboard')->with('success', 'Giao dịch VNPay thành công!');
            }
            return redirect()->route('home')->with('success', 'Giao dịch VNPay thành công! Mã đơn hàng: ' . $bookingCode);
        }
        
        if ($booking && $booking->status == 'pending') {
            $booking->update(['status' => 'cancelled']);
        }

        if (auth()->check()) {
            return redirect()->route('customer.dashboard')->with('error', 'Giao dịch bị hủy hoặc thất bại.');
        }
        return redirect()->route('home')->with('error', 'Giao dịch bị hủy hoặc thất bại.');
    }
}
