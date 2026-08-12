<?php

namespace App\Services\Payment;

class VNPayService
{
    protected $vnp_TmnCode;
    protected $vnp_HashSecret;
    protected $vnp_Url;
    protected $vnp_Returnurl;

    public function __construct()
    {
        $this->vnp_TmnCode = env('VNP_TMN_CODE', 'DUMMY_CODE');
        $this->vnp_HashSecret = env('VNP_HASH_SECRET', 'DUMMY_SECRET');
        $this->vnp_Url = env('VNP_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html');
        $this->vnp_Returnurl = route('payment.vnpay.return');
    }

    public function createPaymentUrl($orderId, $amount, $orderDesc)
    {
        return route('payment.vnpay.mock', [
            'orderId' => $orderId,
            'amount' => $amount
        ]);
    }

    public function verifyPayment($requestData)
    {
        // MOCK BYPASS cho Đồ án
        if (isset($requestData['vnp_ResponseCode']) && $requestData['vnp_ResponseCode'] == '00') {
            return ['success' => true, 'message' => 'Giao dịch thành công'];
        }
        return ['success' => false, 'message' => 'Giao dịch không thành công'];
    }
}
