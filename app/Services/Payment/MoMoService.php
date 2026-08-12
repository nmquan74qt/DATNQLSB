<?php

namespace App\Services\Payment;

class MoMoService
{
    protected $endpoint;
    protected $partnerCode;
    protected $accessKey;
    protected $secretKey;

    public function __construct()
    {
        $this->endpoint = env('MOMO_ENDPOINT', 'https://test-payment.momo.vn/v2/gateway/api/create');
        $this->partnerCode = env('MOMO_PARTNER_CODE', 'MOMOBKUN20180529');
        $this->accessKey = env('MOMO_ACCESS_KEY', 'klm05TvNCyandm7G');
        $this->secretKey = env('MOMO_SECRET_KEY', 'at67qH6mk8g5i1Peje2nFlP0IuPOnB');
    }

    public function createPaymentUrl($orderId, $amount, $orderDesc)
    {
        return route('payment.momo.mock', [
            'orderId' => $orderId,
            'amount' => $amount
        ]);
    }

    public function verifyPayment($requestData)
    {
        if (isset($requestData['resultCode']) && $requestData['resultCode'] == '0') {
            return ['success' => true, 'message' => 'Giao dịch thành công', 'transId' => $requestData['transId'] ?? 'MOMO' . time()];
        }
        return ['success' => false, 'message' => 'Giao dịch không thành công'];
    }
}
