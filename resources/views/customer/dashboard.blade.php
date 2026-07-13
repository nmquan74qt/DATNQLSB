@extends('layouts.customer')

@section('title', 'Lịch Sử Đặt Sân')

@section('content_customer')
<div class="professional-table-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="professional-table-title mb-0">Lịch sử đặt sân</h3>
        <a href="{{ route('customer.bookings.create') }}" class="btn btn-success rounded-pill px-4" style="background-color: #219653; border: none;">
            <i class="fa-solid fa-plus me-1"></i> Đặt sân mới
        </a>
    </div>
    
    <div class="table-responsive">
        <table class="table-custom">
            <thead>
                <tr>
                    <th>Mã đơn</th>
                    <th>Tên sân</th>
                    <th>Ngày</th>
                    <th>Khung giờ</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                    <tr>
                        <td class="fw-bold" style="font-family: monospace; font-size: 1rem;">
                            {{ strtoupper(substr(md5($booking->id . 'booking'), 0, 7)) }}-{{ $booking->id }}
                        </td>
                        <td>
                            @foreach($booking->bookingDetails as $detail)
                                <div class="fw-medium">{{ $detail->footballField->name }}</div>
                            @endforeach
                        </td>
                        <td>{{ $booking->booking_date->format('d/m/Y') }}</td>
                        <td>
                            @foreach($booking->bookingDetails as $detail)
                                <div>{{ substr($detail->timeSlot->start_time, 0, 5) }} - {{ substr($detail->timeSlot->end_time, 0, 5) }}</div>
                            @endforeach
                        </td>
                        <td class="fw-bold text-success">{{ number_format($booking->total_amount) }}đ</td>
                        <td>
                            @if($booking->status === 'pending')
                                <span class="status-badge pending">Chờ duyệt</span>
                            @elseif($booking->status === 'confirmed')
                                <span class="status-badge paid">Đã duyệt</span>
                            @elseif($booking->status === 'completed')
                                <span class="status-badge completed">Đã xong</span>
                            @else
                                <span class="status-badge cancelled">Đã hủy</span>
                            @endif
                        </td>
                        <td style="min-width: 180px;">
                            <div class="d-flex align-items-center">
                                <div style="width: 75px; text-align: left;">
                                    <a href="{{ route('customer.bookings.show', $booking->id) }}" class="action-link mb-0" style="margin-right: 0;">
                                        <i class="fa-regular fa-eye"></i> Xem
                                    </a>
                                </div>
                                <div style="text-align: left;">
                                    @if($booking->status === 'completed')
                                        @if(!$booking->review)
                                            <a href="{{ route('customer.bookings.show', $booking->id) }}#review-section" class="action-link text-warning mb-0" style="margin-right: 0; color: #f59e0b !important;">
                                                <i class="fa-regular fa-star"></i> Đánh giá
                                            </a>
                                        @else
                                            <span class="action-link text-success mb-0" style="margin-right: 0; cursor: default; opacity: 0.8;">
                                                <i class="fa-solid fa-check"></i> Đã đánh giá
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-secondary">
                            Bạn chưa có lịch đặt sân nào.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($bookings->hasPages())
        <div class="mt-4">
            {{ $bookings->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
