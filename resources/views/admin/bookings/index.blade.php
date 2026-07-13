@extends('layouts.admin')

@section('title', 'Quản Lý Đặt Sân - PitchManage')
@section('page_title', 'Danh Sách Lịch Đặt Sân')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border">
            
            <!-- Filters -->
            <div class="card-header bg-white py-3">
                <form action="{{ route('admin.bookings.index') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label for="status" class="form-label small fw-bold">Trạng Thái</label>
                        <select name="status" id="status" class="form-select form-select-sm">
                            <option value="">-- Tất cả trạng thái --</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                            <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Đã hoàn thành</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="date" class="form-label small fw-bold">Ngày Thi Đấu</label>
                        <input type="date" name="date" id="date" class="form-control form-control-sm" value="{{ request('date') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="search" class="form-label small fw-bold">Tìm Kiếm Khách Hàng</label>
                        <input type="text" name="search" id="search" class="form-control form-control-sm" placeholder="Tìm theo tên hoặc số điện thoại..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-1">
                        <button type="submit" class="btn btn-success btn-sm w-100"><i class="fa-solid fa-filter me-1"></i> Lọc</button>
                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-arrows-rotate"></i></a>
                    </div>
                </form>
            </div>

            <!-- Booking List Table -->
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0">
                        <thead class="table-dark text-center">
                            <tr>
                                <th class="px-4 py-3 text-start" style="width: 80px;">Mã Đơn</th>
                                <th class="text-start">Khách Hàng</th>
                                <th class="text-start">Sân Bóng & Khung Giờ</th>
                                <th>Ngày Thi Đấu</th>
                                <th>Tổng Tiền</th>
                                <th>Trạng Thái</th>
                                <th>Ngày Đặt</th>
                                <th class="text-center px-4">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                                <tr>
                                    <td class="px-4 text-start"><span class="code-tag font-mono">#{{ $booking->id }}</span></td>
                                    <td class="text-start">
                                        <div class="fw-bold">{{ $booking->customer_name }}</div>
                                        <small class="text-secondary"><i class="fa-solid fa-phone"></i> {{ $booking->customer_phone }}</small>
                                    </td>
                                    <td class="text-start">
                                        @foreach($booking->bookingDetails as $detail)
                                            <div class="small">
                                                <i class="fa-solid fa-square-poll-vertical text-success me-1"></i>
                                                <strong>{{ $detail->footballField->name }}</strong>
                                                <span class="text-muted">({{ $detail->timeSlot->name }})</span>
                                            </div>
                                        @endforeach
                                    </td>
                                    <td class="text-center"><span class="badge bg-light text-dark border"><i class="fa-regular fa-calendar me-1"></i> {{ $booking->booking_date->format('d/m/Y') }}</span></td>
                                    <td class="text-center fw-bold text-success">{{ number_format($booking->total_amount) }}đ</td>
                                    <td class="text-center">
                                        @if($booking->status === 'pending')
                                            <span class="badge bg-warning text-dark"><i class="fa-solid fa-spinner fa-spin me-1"></i> Chờ duyệt</span>
                                        @elseif($booking->status === 'confirmed')
                                            <span class="badge bg-primary"><i class="fa-solid fa-circle-check me-1"></i> Đã duyệt</span>
                                        @elseif($booking->status === 'completed')
                                            <span class="badge bg-success"><i class="fa-solid fa-circle-check me-1"></i> Hoàn thành</span>
                                        @else
                                            <span class="badge bg-danger"><i class="fa-solid fa-circle-xmark me-1"></i> Đã hủy</span>
                                        @endif
                                    </td>
                                    <td class="text-center text-secondary small">{{ $booking->created_at->format('H:i d/m/Y') }}</td>
                                    <td class="text-center px-4">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-sm btn-outline-success" title="Chi tiết"><i class="fa-solid fa-eye me-1"></i> Xem Chi Tiết</a>
                                            
                                            @if($booking->status === 'pending')
                                                <form action="{{ route('admin.bookings.approve', $booking->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" title="Xác nhận"><i class="fa-solid fa-check"></i> Duyệt</button>
                                                </form>
                                            @endif

                                            @if($booking->status === 'pending' || $booking->status === 'confirmed')
                                                <form action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn hủy lịch này không?');" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hủy lịch"><i class="fa-solid fa-xmark"></i> Hủy</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-secondary">Không tìm thấy đơn đặt sân nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($bookings->hasPages())
                <div class="card-footer py-3">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
