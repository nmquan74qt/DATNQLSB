@extends('layouts.public')

@section('title', 'Chi Tiết Lịch Đặt Sân - Khách Hàng')
@section('page_title', 'Chi Tiết Đơn Đặt Sân #' . $booking->id)

@section('content')
<div class="container py-5">
<div class="row">
    <div class="col-lg-8">
        <!-- Fields & Slots Booked -->
        <div class="card border mb-4">
            <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center">
                <h5 class="m-0 fw-bold"><i class="fa-solid fa-circle-play text-success me-2"></i>Lịch Đá Đã Chọn</h5>
                <span class="text-secondary small">Ngày đá: <strong>{{ $booking->booking_date->format('d/m/Y') }}</strong></span>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tên Sân</th>
                                <th>Loại Sân</th>
                                <th>Khung Giờ</th>
                                <th>Giá Tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($booking->bookingDetails as $detail)
                                <tr>
                                    <td class="fw-bold">{{ $detail->footballField->name }}</td>
                                    <td><span class="badge bg-light text-dark border">{{ $detail->footballField->fieldType->name }}</span></td>
                                    <td><span class="badge bg-success"><i class="fa-regular fa-clock me-1"></i> {{ $detail->timeSlot->name }}</span></td>
                                    <td class="fw-bold text-success">{{ number_format($detail->price) }}đ</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Ordered Services -->
        <div class="card border mb-4">
            <div class="card-header py-3 bg-white">
                <h5 class="m-0 fw-bold"><i class="fa-solid fa-cubes text-success me-2"></i>Dịch Vụ Tiện Ích Đã Gọi</h5>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tên Dịch Vụ</th>
                                <th>Giá Bán / Thuê</th>
                                <th>Số Lượng</th>
                                <th>Tổng Tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($booking->serviceOrders as $so)
                                <tr>
                                    <td class="text-start ps-4 fw-semibold">{{ $so->service->name }}</td>
                                    <td>{{ number_format($so->price) }}đ / {{ $so->service->unit }}</td>
                                    <td>{{ $so->quantity }} {{ $so->service->unit }}</td>
                                    <td class="fw-bold text-success">{{ number_format($so->total_amount) }}đ</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-secondary">
                                        Không sử dụng thêm dịch vụ đi kèm.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
    </div>

    <!-- Review Section -->
    @if($booking->status === 'completed')
    <div class="card border mb-4" id="review-section">
        <div class="card-header py-3 bg-white">
            <h5 class="m-0 fw-bold"><i class="fa-solid fa-star text-warning me-2"></i>Đánh Giá & Bình Luận</h5>
        </div>
        <div class="card-body p-4">
            @if($booking->review)
                <div class="d-flex flex-column align-items-start bg-light p-4 rounded border border-warning-subtle">
                    <div class="d-flex justify-content-between w-100 mb-2">
                        <div class="fw-bold fs-5 text-dark">
                            Đánh giá của bạn
                        </div>
                        <span class="text-secondary small">{{ $booking->review->created_at->format('H:i d/m/Y') }}</span>
                    </div>
                    <div class="text-warning fs-4 mb-3">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $booking->review->rating)
                                <i class="fa-solid fa-star"></i>
                            @else
                                <i class="fa-regular fa-star text-secondary"></i>
                            @endif
                        @endfor
                    </div>
                    <div class="bg-white p-3 rounded border w-100 italic text-dark">
                        {{ $booking->review->comment ?? 'Không có bình luận nào.' }}
                    </div>
                </div>
            @else
                <form action="{{ route('customer.bookings.review', $booking->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Chất lượng sân & Dịch vụ</label>
                        <div class="rating-stars d-flex gap-2 fs-3 text-secondary" id="starRating">
                            <i class="fa-solid fa-star star-btn" data-value="1" style="cursor: pointer;"></i>
                            <i class="fa-solid fa-star star-btn" data-value="2" style="cursor: pointer;"></i>
                            <i class="fa-solid fa-star star-btn" data-value="3" style="cursor: pointer;"></i>
                            <i class="fa-solid fa-star star-btn" data-value="4" style="cursor: pointer;"></i>
                            <i class="fa-solid fa-star star-btn" data-value="5" style="cursor: pointer;"></i>
                        </div>
                        <input type="hidden" name="rating" id="ratingInput" value="5">
                        <div class="text-warning fw-semibold mt-1" id="ratingText">Tuyệt vời (5 sao)</div>
                    </div>
                    <div class="mb-3">
                        <label for="comment" class="form-label fw-bold">Bình luận của bạn (Tùy chọn)</label>
                        <textarea name="comment" id="comment" rows="3" class="form-control" placeholder="Chia sẻ trải nghiệm của bạn với sân bóng này..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-warning fw-bold text-dark w-100 py-2">
                        <i class="fa-solid fa-paper-plane me-1"></i> Gửi Đánh Giá
                    </button>
                </form>
            @endif
        </div>
    </div>
    @endif
</div>

<!-- Booking Summary & Invoice -->
    <div class="col-lg-4">
        <div class="card border mb-4">
            <div class="card-header py-3 bg-dark text-white">
                <h5 class="m-0 fw-bold"><i class="fa-solid fa-circle-info text-success me-2"></i>Thông Tin Chung</h5>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <small class="text-secondary d-block">Trạng thái đặt sân:</small>
                    @if($booking->status === 'pending')
                        <span class="badge bg-warning text-dark fs-6 py-2 px-3 mt-1 w-100 text-center"><i class="fa-solid fa-spinner fa-spin me-1"></i> Chờ xác nhận</span>
                    @elseif($booking->status === 'confirmed')
                        <span class="badge bg-primary fs-6 py-2 px-3 mt-1 w-100 text-center"><i class="fa-solid fa-circle-check me-1"></i> Đã xác nhận</span>
                    @elseif($booking->status === 'completed')
                        <span class="badge bg-success fs-6 py-2 px-3 mt-1 w-100 text-center"><i class="fa-solid fa-circle-check me-1"></i> Đã hoàn thành</span>
                    @else
                        <span class="badge bg-danger fs-6 py-2 px-3 mt-1 w-100 text-center"><i class="fa-solid fa-circle-xmark me-1"></i> Đã hủy</span>
                    @endif
                </div>

                <hr class="my-3 opacity-25">

                <div class="mb-2">
                    <small class="text-secondary">Tên khách hàng:</small>
                    <div class="fw-bold text-dark">{{ $booking->customer_name }}</div>
                </div>
                <div class="mb-3">
                    <small class="text-secondary">Số điện thoại:</small>
                    <div class="fw-bold text-dark">{{ $booking->customer_phone }}</div>
                </div>
                <div class="mb-3">
                    <small class="text-secondary">Ngày yêu cầu đặt:</small>
                    <div class="fw-bold text-dark">{{ $booking->created_at->format('H:i d/m/Y') }}</div>
                </div>

                @if($booking->notes)
                    <div class="mb-3">
                        <small class="text-secondary">Ghi chú:</small>
                        <p class="mb-0 bg-light p-2.5 rounded border small italic">{{ $booking->notes }}</p>
                    </div>
                @endif

                <hr class="my-3 opacity-25">

                <div class="bg-light p-3 rounded border border-success-subtle mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-secondary">Tổng cộng:</span>
                        <span class="fw-bold text-success fs-5">{{ number_format($booking->total_amount) }}đ</span>
                    </div>
                    @if($booking->invoice)
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-secondary">Giảm giá:</span>
                            <span class="fw-semibold text-danger">-{{ number_format($booking->invoice->discount) }}đ</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                            <span class="fw-bold text-success fs-5">Thực thu:</span>
                            <span class="fw-bold text-success fs-4">{{ number_format($booking->invoice->final_amount) }}đ</span>
                        </div>
                    @endif
                </div>

                @if($booking->invoice && $booking->status === 'completed')
                    <a href="{{ route('admin.invoices.show', $booking->invoice->id) }}" class="btn btn-outline-success w-100 py-2 fw-semibold mb-2">
                        <i class="fa-solid fa-print me-1"></i> Xem Hóa Đơn Chi Tiết
                    </a>
                @endif
                
                @if($booking->status === 'pending')
                    <form action="{{ route('customer.bookings.cancel', $booking->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn hủy lịch đặt sân này không?');">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100 py-2.5 fw-semibold mt-2">
                            <i class="fa-solid fa-trash-can me-1"></i> Hủy Lịch Đặt Sân
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stars = document.querySelectorAll('.star-btn');
        const ratingInput = document.getElementById('ratingInput');
        const ratingText = document.getElementById('ratingText');

        const texts = [
            'Tệ (1 sao)',
            'Không hài lòng (2 sao)',
            'Bình thường (3 sao)',
            'Hài lòng (4 sao)',
            'Tuyệt vời (5 sao)'
        ];

        if (stars.length > 0) {
            // Default 5 stars on load
            updateStars(5);

            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const value = parseInt(this.getAttribute('data-value'));
                    ratingInput.value = value;
                    updateStars(value);
                });

                star.addEventListener('mouseover', function() {
                    const value = parseInt(this.getAttribute('data-value'));
                    highlightStars(value);
                });

                star.addEventListener('mouseout', function() {
                    const value = parseInt(ratingInput.value);
                    updateStars(value);
                });
            });

            function highlightStars(value) {
                stars.forEach(s => {
                    if (parseInt(s.getAttribute('data-value')) <= value) {
                        s.classList.remove('text-secondary');
                        s.classList.add('text-warning');
                    } else {
                        s.classList.remove('text-warning');
                        s.classList.add('text-secondary');
                    }
                });
            }

            function updateStars(value) {
                highlightStars(value);
                ratingText.textContent = texts[value - 1];
            }
        }
    });
</script>
@endsection
