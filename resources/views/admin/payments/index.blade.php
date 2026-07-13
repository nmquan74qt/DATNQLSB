@extends('layouts.admin')

@section('title', 'Lịch Sử Thanh Toán - PitchManage')
@section('page_title', 'Lịch Sử Giao Dịch Thanh Toán')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border">
            <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 py-3">
                <h5 class="m-0 fw-bold"><i class="fa-solid fa-credit-card text-success me-2"></i>Lịch sử thanh toán giao dịch</h5>
                
                <!-- Search Box -->
                <div class="d-flex align-items-center gap-2">
                    <form action="{{ route('admin.payments.index') }}" method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Tìm theo tên khách, mã giao dịch..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-success btn-sm"><i class="fa-solid fa-search"></i></button>
                        @if(request()->filled('search'))
                            <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary btn-sm ms-2"><i class="fa-solid fa-arrows-rotate"></i></a>
                        @endif
                    </form>
                </div>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0 text-center">
                        <thead class="table-dark">
                            <tr>
                                <th class="px-4 py-3 text-start">Giao Dịch ID</th>
                                <th>Đặt Sân</th>
                                <th>Khách Hàng</th>
                                <th>Phương Thức</th>
                                <th>Số Tiền Giao Dịch</th>
                                <th>Trạng Thái</th>
                                <th class="pe-4 text-end">Thời Gian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                                <tr>
                                    <td class="px-4 text-start"><span class="code-tag {{ $payment->transaction_id ? 'code-tag-success' : 'code-tag-dark' }} font-mono">{{ $payment->transaction_id ?: 'CASH_PAY' }}</span></td>
                                    <td><a href="{{ route('admin.bookings.show', $payment->booking_id) }}" class="text-decoration-none"><span class="code-tag font-mono">#{{ $payment->booking_id }}</span></a></td>
                                    <td class="text-start">
                                        <div class="fw-bold">{{ $payment->booking->customer_name }}</div>
                                        <small class="text-secondary">{{ $payment->booking->customer_phone }}</small>
                                    </td>
                                    <td>
                                        @if($payment->payment_method === 'cash')
                                            <span class="badge bg-light text-dark border"><i class="fa-solid fa-money-bill-1 text-success me-1"></i> Tiền mặt</span>
                                        @else
                                            <span class="badge bg-light text-dark border"><i class="fa-solid fa-building-columns text-primary me-1"></i> Chuyển khoản</span>
                                        @endif
                                    </td>
                                    <td class="fw-bold text-success">{{ number_format($payment->amount) }}đ</td>
                                    <td>
                                        @if($payment->payment_status === 'completed')
                                            <span class="badge bg-success"><i class="fa-solid fa-circle-check me-1"></i> Thành công</span>
                                        @else
                                            <span class="badge bg-warning text-dark"><i class="fa-solid fa-spinner fa-spin me-1"></i> Chờ duyệt</span>
                                        @endif
                                    </td>
                                    <td class="pe-4 text-end text-secondary small">
                                        {{ $payment->paid_at ? $payment->paid_at->format('H:i d/m/Y') : $payment->created_at->format('H:i d/m/Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-secondary">Không tìm thấy giao dịch nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($payments->hasPages())
                <div class="card-footer py-3">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
