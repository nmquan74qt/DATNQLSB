@extends('layouts.admin')

@section('title', 'Báo Cáo Doanh Thu - PitchManage')
@section('page_title', 'Báo Cáo Thống Kê Doanh Thu')

@section('content')
<!-- Filter Dates Panel -->
<div class="card border mb-4">
    <div class="card-header py-3 bg-white">
        <h5 class="m-0 fw-bold"><i class="fa-solid fa-filter text-success me-2"></i>Bộ Lọc Thời Gian</h5>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('admin.reports.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="start_date" class="form-label fw-semibold small">Từ Ngày</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
            </div>
            <div class="col-md-4">
                <label for="end_date" class="form-label fw-semibold small">Đến Ngày</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
            </div>
            <div class="col-md-4 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-success w-100"><i class="fa-solid fa-sync me-1"></i> Cập Nhật Báo Cáo</button>
                <!-- Excel export CSV trigger -->
                <a href="{{ route('admin.reports.export-csv', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}" class="btn btn-primary text-nowrap">
                    <i class="fa-solid fa-file-excel me-1"></i> Xuất Excel
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Aggregated Metrics -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card bg-white border p-4 text-center">
            <h6 class="text-secondary fw-semibold text-uppercase mb-2 small">Doanh Thu Thực Thu</h6>
            <h2 class="fw-bold text-success mb-0">{{ number_format($totalRevenue) }}đ</h2>
            <small class="text-muted mt-2 d-block">Từ hóa đơn đã hoàn thành</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-white border p-4 text-center">
            <h6 class="text-secondary fw-semibold text-uppercase mb-2 small">Tổng Chiết Khấu Giảm Giá</h6>
            <h2 class="fw-bold text-danger mb-0">{{ number_format($totalDiscount) }}đ</h2>
            <small class="text-muted mt-2 d-block">Giảm trừ cho khách hàng</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-white border p-4 text-center">
            <h6 class="text-secondary fw-semibold text-uppercase mb-2 small">Số Hóa Đơn Đã Xuất</h6>
            <h2 class="fw-bold text-dark mb-0">{{ $totalInvoices }}</h2>
            <small class="text-muted mt-2 d-block">Trong khoảng thời gian đã lọc</small>
        </div>
    </div>
</div>

<!-- Breakdown and Details tables -->
<div class="row g-4">
    
    <!-- Revenue by Field Type -->
    <div class="col-lg-4">
        <div class="card bg-white border h-100">
            <div class="card-header py-3 bg-transparent border-bottom">
                <h5 class="fw-bold m-0"><i class="fa-solid fa-chart-pie text-success me-2"></i>Doanh Thu Theo Loại Sân</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-center">
                        <thead class="table-light">
                            <tr>
                                <th class="text-start ps-4">Loại Sân</th>
                                <th>Lượt Đặt</th>
                                <th class="text-end pe-4">Doanh Thu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($revenueByFieldType as $item)
                                <tr>
                                    <td class="text-start ps-4 fw-bold text-dark">{{ $item->field_type_name }}</td>
                                    <td><span class="badge bg-light text-dark border">{{ $item->count }} lượt</span></td>
                                    <td class="text-end pe-4 fw-bold text-success">{{ number_format($item->revenue) }}đ</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-secondary">Chưa có doanh thu sân.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Paid Invoices Details -->
    <div class="col-lg-8">
        <div class="card bg-white border h-100">
            <div class="card-header py-3 bg-transparent border-bottom">
                <h5 class="fw-bold m-0"><i class="fa-solid fa-list-ul text-success me-2"></i>Danh Sách Giao Dịch Trong Kỳ</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0 text-center">
                        <thead class="table-light">
                            <tr>
                                <th class="text-start ps-4">Mã HĐ</th>
                                <th class="text-start">Khách Hàng</th>
                                <th>Thực Thu</th>
                                <th>Phương Thức</th>
                                <th class="text-end pe-4">Ngày Xuất</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $invoice)
                                <tr>
                                    <td class="text-start ps-4 fw-bold"><a href="{{ route('admin.invoices.show', $invoice->id) }}" class="text-decoration-none text-dark">{{ $invoice->invoice_number }}</a></td>
                                    <td class="text-start">
                                        <div class="fw-semibold">{{ $invoice->customer_name }}</div>
                                        <small class="text-secondary">{{ $invoice->customer_phone }}</small>
                                    </td>
                                    <td class="fw-bold text-success">{{ number_format($invoice->final_amount) }}đ</td>
                                    <td>
                                        @if($invoice->booking && $invoice->booking->payment && $invoice->booking->payment->payment_method === 'cash')
                                            <span class="badge bg-light text-dark border">Tiền mặt</span>
                                        @else
                                            <span class="badge bg-light text-dark border">Chuyển khoản</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4 text-secondary small">{{ $invoice->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-secondary">Không có hóa đơn thanh toán nào trong kỳ.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($invoices->hasPages())
                <div class="card-footer py-3 bg-transparent border-top">
                    {{ $invoices->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
