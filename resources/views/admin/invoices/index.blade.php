@extends('layouts.admin')

@section('title', 'Quản Lý Hóa Đơn - PitchManage')
@section('page_title', 'Hóa Đơn Thanh Toán')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border">
            <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 py-3">
                <h5 class="m-0 fw-bold"><i class="fa-solid fa-file-invoice-dollar text-success me-2"></i>Danh sách hóa đơn</h5>
                
                <!-- Search Box -->
                <div class="d-flex align-items-center gap-2">
                    <form action="{{ route('admin.invoices.index') }}" method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Tìm theo số HĐ, tên khách..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-success btn-sm"><i class="fa-solid fa-search"></i></button>
                        @if(request()->filled('search'))
                            <a href="{{ route('admin.invoices.index') }}" class="btn btn-outline-secondary btn-sm ms-2"><i class="fa-solid fa-arrows-rotate"></i></a>
                        @endif
                    </form>
                </div>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0 text-center">
                        <thead class="table-dark">
                            <tr>
                                <th class="px-4 py-3 text-start">Số Hóa Đơn</th>
                                <th>Đặt Sân</th>
                                <th>Khách Hàng</th>
                                <th>Tổng Tiền</th>
                                <th>Khấu Trừ</th>
                                <th>Thực Thu</th>
                                <th>Trạng Thái</th>
                                <th>Nhân Viên Lập</th>
                                <th class="text-center px-4">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $invoice)
                                <tr>
                                    <td class="px-4 text-start"><span class="code-tag code-tag-primary font-mono">{{ $invoice->invoice_number }}</span></td>
                                    <td><a href="{{ route('admin.bookings.show', $invoice->booking_id) }}" class="text-decoration-none"><span class="code-tag font-mono">#{{ $invoice->booking_id }}</span></a></td>
                                    <td class="text-start">
                                        <div class="fw-bold">{{ $invoice->customer_name }}</div>
                                        <small class="text-secondary">{{ $invoice->customer_phone }}</small>
                                    </td>
                                    <td>{{ number_format($invoice->total_amount) }}đ</td>
                                    <td class="text-danger">-{{ number_format($invoice->discount) }}đ</td>
                                    <td class="fw-bold text-success">{{ number_format($invoice->final_amount) }}đ</td>
                                    <td>
                                        @if($invoice->status === 'paid')
                                            <span class="badge bg-success"><i class="fa-solid fa-circle-check me-1"></i> Đã Thanh Toán</span>
                                        @else
                                            <span class="badge bg-warning text-dark"><i class="fa-solid fa-spinner fa-spin me-1"></i> Chưa Thanh Toán</span>
                                        @endif
                                    </td>
                                    <td>{{ $invoice->user ? $invoice->user->name : 'N/A' }}</td>
                                    <td class="text-center px-4">
                                        <a href="{{ route('admin.invoices.show', $invoice->id) }}" class="btn btn-sm btn-outline-success"><i class="fa-solid fa-print me-1"></i> In PDF / Xem</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-secondary">Chưa có hóa đơn nào được xuất.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($invoices->hasPages())
                <div class="card-footer py-3">
                    {{ $invoices->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
