@extends('layouts.customer')

@section('title', 'Cổng thanh toán MoMo - PitchManage')
@section('page_title', 'Thanh toán MoMo')

@section('styles')
<style>
    :root {
        --momo-primary: #a50064;
        --momo-secondary: #d82d8b;
        --momo-bg: #fdf2f8;
    }
    body {
        background-color: var(--momo-bg);
    }
    .momo-card {
        border-radius: 20px;
        border: none;
        box-shadow: 0 10px 40px rgba(165, 0, 100, 0.08);
        overflow: hidden;
    }
    .momo-header {
        background: linear-gradient(135deg, var(--momo-primary), var(--momo-secondary));
        color: white;
        padding: 25px 30px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .momo-logo-container {
        background: white;
        border-radius: 12px;
        padding: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
    }
    .qr-container {
        position: relative;
        padding: 20px;
        border: 2px dashed #fbcfe8;
        border-radius: 16px;
        display: inline-block;
        background: white;
    }
    .qr-scanner-line {
        position: absolute;
        top: 20px;
        left: 20px;
        right: 20px;
        height: 2px;
        background: var(--momo-secondary);
        box-shadow: 0 0 10px var(--momo-secondary);
        animation: scan 2s linear infinite;
        z-index: 10;
    }
    @keyframes scan {
        0%, 100% { top: 20px; }
        50% { top: calc(100% - 20px); }
    }
    .order-summary {
        background-color: #f8fafc;
        border-radius: 16px;
        padding: 24px;
        border: 1px solid #f1f5f9;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px dashed #cbd5e1;
    }
    .summary-row:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }
    .btn-momo {
        background-color: var(--momo-primary);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 14px 24px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-momo:hover {
        background-color: var(--momo-secondary);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(216, 45, 139, 0.3);
    }
    .instruction-step {
        display: flex;
        align-items: flex-start;
        margin-bottom: 16px;
    }
    .step-number {
        background-color: var(--momo-primary);
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: bold;
        margin-right: 12px;
        flex-shrink: 0;
        margin-top: 2px;
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">
            
            <a href="{{ route('customer.dashboard') }}" class="text-decoration-none text-secondary mb-4 d-inline-block fw-semibold">
                <i class="fa-solid fa-arrow-left me-2"></i> Quay lại bảng điều khiển
            </a>

            <div class="card momo-card">
                <!-- Header -->
                <div class="momo-header">
                    <div>
                        <h4 class="fw-bold mb-1">Cổng thanh toán MoMo</h4>
                        <div class="text-white-50 small">Quét mã QR để thanh toán tự động</div>
                    </div>
                    <div class="momo-logo-container shadow-sm">
                        <img src="https://upload.wikimedia.org/wikipedia/vi/f/fe/MoMo_Logo.png" alt="MoMo" class="img-fluid">
                    </div>
                </div>

                <!-- Body -->
                <div class="card-body p-0">
                    <div class="row g-0">
                        <!-- Left Side: QR Code -->
                        <div class="col-md-6 p-4 p-md-5 d-flex flex-column align-items-center justify-content-center border-end">
                            <h5 class="fw-bold text-dark mb-4 text-center">Quét mã để thanh toán</h5>
                            
                            <div class="qr-container mb-4 shadow-sm">
                                <div class="qr-scanner-line"></div>
                                <img src="{{ $qrUrl }}" alt="MoMo QR Code" style="width: 220px; height: 220px; object-fit: contain; border-radius: 8px;">
                            </div>
                            
                            <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                                <img src="https://upload.wikimedia.org/wikipedia/vi/f/fe/MoMo_Logo.png" alt="MoMo" style="height: 20px;">
                                <img src="https://vietqr.net/portal-v2/assets/images/vietqr-logo.svg" alt="VietQR" style="height: 16px;">
                            </div>
                            <p class="text-secondary small text-center mb-0 px-3">
                                Hỗ trợ quét bằng ứng dụng <strong>MoMo</strong> hoặc ứng dụng <strong>Ngân hàng (VietQR)</strong>
                            </p>
                        </div>

                        <!-- Right Side: Details -->
                        <div class="col-md-6 p-4 p-md-5 bg-white">
                            <div class="order-summary mb-4">
                                <h6 class="fw-bold text-dark mb-3 text-uppercase">Thông tin đơn hàng</h6>
                                
                                <div class="summary-row">
                                    <span class="text-secondary">Mã đơn đặt sân</span>
                                    <span class="fw-bold text-dark">#{{ $booking->id }}</span>
                                </div>
                                <div class="summary-row">
                                    <span class="text-secondary">Ngân hàng thụ hưởng</span>
                                    <span class="fw-bold text-dark">
                                        @php
                                            $bankId = env('VIETQR_BANK_ID', '');
                                            $bankName = $bankId == '970436' ? 'Vietcombank' : ($bankId == '970422' ? 'MBBank' : 'Mã BIN ' . $bankId);
                                        @endphp
                                        {{ $bankName }}
                                    </span>
                                </div>
                                <div class="summary-row">
                                    <span class="text-secondary">Chủ tài khoản nhận</span>
                                    <span class="fw-bold text-dark">{{ env('VIETQR_ACCOUNT_NAME', 'TÀI KHOẢN NHẬN') }}</span>
                                </div>
                                <div class="summary-row">
                                    <span class="text-secondary">Nội dung chuyển khoản</span>
                                    <span class="fw-bold" style="color: var(--momo-primary);">Thanh toan don {{ $booking->id }}</span>
                                </div>
                                <div class="summary-row mt-2 pt-3 border-top">
                                    <span class="text-secondary">Tổng thanh toán</span>
                                    <span class="fw-bold fs-4" style="color: var(--momo-primary);">{{ number_format($payment->amount) }}đ</span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h6 class="fw-bold text-dark mb-3">Hướng dẫn thanh toán</h6>
                                <div class="instruction-step">
                                    <div class="step-number">1</div>
                                    <div class="small text-secondary">Mở ứng dụng <strong>MoMo</strong> hoặc ứng dụng <strong>Ngân hàng</strong> trên điện thoại.</div>
                                </div>
                                <div class="instruction-step">
                                    <div class="step-number">2</div>
                                    <div class="small text-secondary">Chọn chức năng <strong>Quét Mã QR</strong> và quét mã bên trái.</div>
                                </div>
                                <div class="instruction-step">
                                    <div class="step-number">3</div>
                                    <div class="small text-secondary">Kiểm tra số tiền và nội dung, sau đó xác nhận thanh toán.</div>
                                </div>
                            </div>

                            <a href="{{ route('customer.dashboard') }}" class="btn btn-momo w-100 shadow">
                                <i class="fa-solid fa-check-circle me-2"></i> Tôi đã hoàn tất chuyển khoản
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer bg-light border-0 py-3 text-center">
                    <small class="text-secondary">
                        <i class="fa-solid fa-shield-halved me-1 text-success"></i> Giao dịch được mã hóa và bảo mật bởi cổng thanh toán.
                    </small>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
