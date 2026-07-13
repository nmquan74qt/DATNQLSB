<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Process payment based on method
     */
    public function process(Booking $booking)
    {
        // Only allow if booking belongs to current user
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $payment = $booking->payment;

        if (!$payment) {
            return redirect()->route('customer.dashboard')->with('error', 'Không tìm thấy thông tin thanh toán.');
        }

        if ($payment->payment_status === 'completed') {
            return redirect()->route('customer.dashboard')->with('info', 'Đơn đặt sân này đã được thanh toán.');
        }

        if ($payment->payment_method === 'vnpay') {
            return $this->createVNPayUrl($booking, $payment);
        } elseif ($payment->payment_method === 'momo') {
            return redirect()->route('customer.payment.momo.qr', $booking->id);
        }

        // Default fallback
        return redirect()->route('customer.dashboard')->with('success', 'Đơn đặt sân của bạn đang chờ xử lý.');
    }

    /**
     * Display MoMo dynamic QR code page
     */
    public function momoQR(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $payment = $booking->payment;
        if (!$payment || $payment->payment_method !== 'momo') {
            return redirect()->route('customer.dashboard');
        }

        $bankId = env('VIETQR_BANK_ID', '');
        $accountNo = env('VIETQR_ACCOUNT_NO', '');
        $accountName = env('VIETQR_ACCOUNT_NAME', '');
        
        $amount = $payment->amount;
        $orderInfo = 'Thanh toan don ' . $booking->id;
        
        // Generate VietQR URL
        $qrUrl = "https://img.vietqr.io/image/{$bankId}-{$accountNo}-compact2.png?amount={$amount}&addInfo=" . urlencode($orderInfo) . "&accountName=" . urlencode($accountName);

        return view('customer.payments.momo', compact('booking', 'payment', 'qrUrl'));
    }

    /**
     * Generate VNPay URL and redirect
     */
    private function createVNPayUrl(Booking $booking, Payment $payment)
    {
        $vnp_TmnCode = env('VNPAY_TMN_CODE');
        $vnp_HashSecret = env('VNPAY_HASH_SECRET');
        $vnp_Url = env('VNPAY_URL');
        $vnp_Returnurl = env('VNPAY_RETURN_URL');
        
        $vnp_TxnRef = $booking->id . '_' . time(); // Mã đơn hàng
        $vnp_OrderInfo = 'Thanh toan don dat san ' . $booking->id;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $payment->amount * 100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = '';
        $vnp_IpAddr = request()->ip();

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return redirect($vnp_Url);
    }

    /**
     * Handle VNPay Return
     */
    public function vnpayReturn(Request $request)
    {
        $vnp_HashSecret = env('VNPAY_HASH_SECRET');
        
        $inputData = array();
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        
        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }
        
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        
        if ($secureHash == $vnp_SecureHash) {
            if ($request->input('vnp_ResponseCode') == '00') {
                // Success
                $txnRef = $request->input('vnp_TxnRef');
                $bookingId = explode('_', $txnRef)[0];
                
                $booking = Booking::find($bookingId);
                if ($booking) {
                    $payment = $booking->payment;
                    if ($payment && $payment->payment_status !== 'completed') {
                        $payment->payment_status = 'completed';
                        $payment->transaction_id = $request->input('vnp_TransactionNo');
                        $payment->save();
                        
                        return redirect()->route('customer.dashboard')->with('success', 'Thanh toán đơn đặt sân thành công bằng VNPay!');
                    }
                }
            } else {
                return redirect()->route('customer.dashboard')->with('error', 'Thanh toán bị hủy hoặc không thành công.');
            }
        } else {
            return redirect()->route('customer.dashboard')->with('error', 'Chữ ký VNPay không hợp lệ.');
        }

        return redirect()->route('customer.dashboard');
    }
}
