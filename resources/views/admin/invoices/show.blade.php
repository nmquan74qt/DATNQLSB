@extends('layouts.admin')

@section('title', 'Hóa Đơn ' . $invoice->invoice_number . ' - PitchManage')
@section('page_title', 'Hóa Đơn Chi Tiết')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        
        <!-- Action Buttons -->
        <div class="d-flex justify-content-between align-items-center mb-4 no-print">
            <a href="{{ route('admin.invoices.index') }}" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left me-1"></i> Quay Lại</a>
            <button onclick="window.print();" class="btn btn-success"><i class="fa-solid fa-print me-1"></i> In Hóa Đơn / Xuất PDF</button>
        </div>

        <!-- Print-styled Invoice Area -->
        <div class="card border print-container shadow-sm bg-white p-5">
            <!-- Header -->
            <div class="row align-items-center mb-4">
                <div class="col-md-6 mb-3 mb-md-0 text-center text-md-start">
                    <h3 class="fw-bold text-success mb-1 d-flex align-items-center justify-content-center justify-content-md-start">
                        <i class="fa-solid fa-soccer-ball me-2"></i> PITCH<span class="text-dark">MANAGE</span>
                    </h3>
                    <p class="text-secondary small mb-0">Địa chỉ: Số 12 Chùa Bộc, Đống Đa, Hà Nội</p>
                    <p class="text-secondary small mb-0">Điện thoại: 0987 654 321</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <h3 class="fw-bold text-dark mb-1">HÓA ĐƠN THANH TOÁN</h3>
                    <span class="text-muted small">Số hóa đơn: <strong>{{ $invoice->invoice_number }}</strong></span>
                </div>
            </div>

            <hr class="opacity-25 my-4">

            <!-- Customer & Invoice Info -->
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <h6 class="fw-bold text-secondary text-uppercase mb-2 small">Khách Hàng Thanh Toán</h6>
                    <div class="fw-bold text-dark fs-5">{{ $invoice->customer_name }}</div>
                    <div class="text-secondary mt-1"><i class="fa-solid fa-phone me-1.5 text-success"></i> Số điện thoại: {{ $invoice->customer_phone }}</div>
                </div>
                <div class="col-md-6 text-md-end">
                    <h6 class="fw-bold text-secondary text-uppercase mb-2 small">Thông Tin Hóa Đơn</h6>
                    <div class="text-secondary mb-1">Ngày xuất: <strong>{{ $invoice->created_at->format('H:i d/m/Y') }}</strong></div>
                    <div class="text-secondary mb-1">Mã đặt sân: <strong>#{{ $invoice->booking_id }}</strong></div>
                    <div class="text-secondary">Nhân viên lập: <strong>{{ $invoice->user ? $invoice->user->name : 'N/A' }}</strong></div>
                </div>
            </div>

            <!-- Invoice Details Table -->
            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle text-center mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">STT</th>
                            <th class="text-start ps-3">Nội Dung</th>
                            <th>Đơn Giá</th>
                            <th style="width: 100px;">Số Lượng</th>
                            <th class="text-end pe-3" style="width: 150px;">Thành Tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->invoiceDetails as $index => $detail)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="text-start ps-3 fw-semibold text-dark">{{ $detail->item_name }}</td>
                                <td>{{ number_format($detail->price) }}đ</td>
                                <td>{{ $detail->quantity }}</td>
                                <td class="text-end pe-3 fw-bold text-success">{{ number_format($detail->total_amount) }}đ</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Summary Totals -->
            <div class="row justify-content-end mb-5">
                <div class="col-md-5">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-secondary">Tổng cộng:</span>
                        <span class="fw-semibold text-dark">{{ number_format($invoice->total_amount) }}đ</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                        <span class="text-secondary">Giảm giá khấu trừ:</span>
                        <span class="fw-semibold text-danger">-{{ number_format($invoice->discount) }}đ</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-success fs-5">Thực thu thanh toán:</span>
                        <span class="fw-bold text-success fs-4">{{ number_format($invoice->final_amount) }}đ</span>
                    </div>
                </div>
            </div>

            <!-- Footer Signatures -->
            <div class="row text-center mt-5">
                <div class="col-6">
                    <span class="text-secondary small italic d-block mb-4">Khách Hàng Ký Tên</span>
                    <div style="height: 60px;"></div>
                    <span class="fw-bold text-dark">{{ $invoice->customer_name }}</span>
                </div>
                <div class="col-6">
                    <span class="text-secondary small italic d-block mb-4">Nhân Viên Xác Nhận</span>
                    <div style="height: 60px;"></div>
                    <span class="fw-bold text-dark">{{ $invoice->user ? $invoice->user->name : 'N/A' }}</span>
                </div>
            </div>

            <div class="text-center text-muted small mt-5 no-print border-top pt-4 opacity-50">
                Cảm ơn Quý khách đã tin tưởng sử dụng dịch vụ của PitchManage!
            </div>
        </div>
    </div>
</div>
@endsection
