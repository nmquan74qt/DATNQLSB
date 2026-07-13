@extends('layouts.public')

@section('title', 'Đặt Lịch Sân Bóng - PitchManage')
@section('page_title', 'Đặt Lịch Sân Bóng Đá')

@section('styles')
<style>
    .payment-option-card {
        border: 2px solid #cbd5e1;
        border-radius: var(--border-radius-md);
        padding: 16px;
        cursor: pointer;
        transition: all 0.2s ease;
        background-color: #fff;
    }
    .payment-option-card:hover {
        border-color: var(--primary-color);
        background-color: #f8fafc;
    }
    .payment-option-card.selected {
        border-color: var(--primary-color);
        background-color: #ecfdf5;
        box-shadow: 0 0 0 1px var(--primary-color);
    }
    .session-header {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        color: #198754;
        margin-top: 15px;
        margin-bottom: 10px;
        letter-spacing: 0.05em;
    }
    .price-badge-btn {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px 14px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .price-badge-btn:hover {
        border-color: var(--primary-color);
        background-color: #fff;
    }
    .price-badge-btn.selected {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: #fff !important;
    }
    .price-badge-btn.selected .price-val {
        color: #fff !important;
    }
    .price-badge-btn.booked {
        background-color: #f1f5f9;
        border-color: #e2e8f0;
        color: #94a3b8 !important;
        cursor: not-allowed;
        text-decoration: line-through;
    }
    .price-badge-btn.booked .price-val {
        color: #94a3b8 !important;
    }
</style>
@endsection

@section('content')
<div class="container py-5">
<form action="{{ route('customer.bookings.store') }}" method="POST" id="mainBookingForm">
    @csrf
    <!-- Hidden input for discount -->
    <input type="hidden" name="discount" id="form_discount" value="0">
    <input type="hidden" name="payment_method" id="form_payment_method" value="cash">

    <!-- STEP 1: CHỌN SÂN & KHUNG GIỜ -->
    <div id="bookingStep1">
        <div class="row g-4">
            <!-- Left Column: Field info, session price table, and calendar date-picker -->
            <div class="col-lg-8">
                <!-- Field Picker & Details -->
                <div class="card border mb-4">
                    <div class="card-body p-4">
                        <div class="row g-3 align-items-center mb-3">
                            <div class="col-md-6">
                                <label for="football_field_id" class="form-label fw-bold">Chọn Sân Bóng</label>
                                <select name="football_field_id" id="football_field_id" class="form-select form-select-lg" required>
                                    <option value="" disabled selected>-- Chọn sân bóng --</option>
                                    @foreach($fields as $field)
                                        <option value="{{ $field->id }}" data-name="{{ $field->name }}" data-type="{{ $field->fieldType->name }}" data-price="{{ $field->fieldType->price_per_hour }}" {{ $selectedFieldId == $field->id ? 'selected' : '' }}>
                                            {{ $field->name }} ({{ $field->fieldType->name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="booking_date" class="form-label fw-bold">Chọn Ngày Đá</label>
                                <input type="date" name="booking_date" id="booking_date" class="form-control form-control-lg" min="{{ date('Y-m-d') }}" value="{{ $selectedDate }}" required>
                            </div>
                        </div>

                        <!-- Dynamic Field Header (simulating the headers in screenshots) -->
                        <div id="fieldDetailHeader" class="mt-4 p-3 bg-light rounded border d-none">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                                <div>
                                    <h4 class="fw-bold mb-1 text-success" id="detailFieldName">-</h4>
                                    <div class="d-flex flex-wrap align-items-center gap-2 mt-1">
                                        <span class="text-warning small" id="detailFieldRating"><i class="fa-solid fa-star"></i> Đang tải...</span>
                                        <span class="text-secondary small border-start ps-2"><i class="fa-solid fa-location-dot"></i> Hà Nội</span>
                                    </div>
                                </div>
                                <span class="badge bg-success px-3 py-2 fs-6" id="detailFieldType">-</span>
                            </div>
                            <div class="d-flex flex-wrap gap-2 mt-3">
                                <span class="badge bg-white text-success border"><i class="fa-solid fa-shower me-1"></i> Phòng thay đồ</span>
                                <span class="badge bg-white text-success border"><i class="fa-solid fa-square-parking me-1"></i> Bãi đỗ xe</span>
                                <span class="badge bg-white text-success border"><i class="fa-solid fa-lightbulb me-1"></i> Đèn chiếu sáng</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Price List & Time Slots Picker -->
                <div class="card border mb-4">
                    <div class="card-header py-3 bg-white">
                        <h5 class="m-0 fw-bold"><i class="fa-solid fa-tags text-success me-2"></i>Bảng Giá & Khung Giờ</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="alert alert-info py-2 px-3 small border-info-subtle mb-3">
                            <i class="fa-solid fa-circle-info"></i> Bấm chọn một hoặc nhiều khung giờ trống dưới đây để đăng ký.
                        </div>

                        <!-- Sessions grids -->
                        <!-- Buổi sáng -->
                        <div class="session-header"><i class="fa-solid fa-sun me-1"></i> Buổi Sáng (Khung giờ trước 12:00)</div>
                        <div class="row g-3 mb-3">
                            @foreach($timeSlots->filter(fn($s) => $s->start_time < '12:00:00') as $slot)
                                <div class="col-md-6 col-xl-4">
                                    <div class="price-badge-btn" data-slot-id="{{ $slot->id }}" data-multiplier="{{ $slot->price_multiplier }}" data-name="{{ $slot->name }}">
                                        <span class="small"><i class="fa-regular fa-clock me-1 text-secondary"></i> {{ $slot->name }}</span>
                                        <span class="fw-bold text-success price-val" data-base-price="0">0đ</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Buổi chiều -->
                        <div class="session-header"><i class="fa-solid fa-cloud-sun me-1"></i> Buổi Chiều (12:00 - 18:00)</div>
                        <div class="row g-3 mb-3">
                            @foreach($timeSlots->filter(fn($s) => $s->start_time >= '12:00:00' && $s->start_time < '18:00:00') as $slot)
                                <div class="col-md-6 col-xl-4">
                                    <div class="price-badge-btn" data-slot-id="{{ $slot->id }}" data-multiplier="{{ $slot->price_multiplier }}" data-name="{{ $slot->name }}">
                                        <span class="small"><i class="fa-regular fa-clock me-1 text-secondary"></i> {{ $slot->name }}</span>
                                        <span class="fw-bold text-success price-val" data-base-price="0">0đ</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Buổi tối -->
                        <div class="session-header"><i class="fa-solid fa-moon me-1"></i> Buổi Tối (Khung giờ sau 18:00)</div>
                        <div class="row g-3">
                            @foreach($timeSlots->filter(fn($s) => $s->start_time >= '18:00:00') as $slot)
                                <div class="col-md-6 col-xl-4">
                                    <div class="price-badge-btn" data-slot-id="{{ $slot->id }}" data-multiplier="{{ $slot->price_multiplier }}" data-name="{{ $slot->name }}">
                                        <span class="small"><i class="fa-regular fa-clock me-1 text-secondary"></i> {{ $slot->name }}</span>
                                        <span class="fw-bold text-success price-val" data-base-price="0">0đ</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Hidden Inputs Container -->
                        <div id="step1HiddenInputs"></div>
                    </div>
                </div>

                <!-- Field Reviews Container -->
                <div class="card border mb-4 d-none" id="reviewsContainerCard">
                    <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center">
                        <h5 class="m-0 fw-bold"><i class="fa-solid fa-comments text-primary me-2"></i>Đánh Giá Từ Khách Hàng</h5>
                        <span class="badge bg-warning text-dark fs-6" id="headerReviewSummary">0 sao (0 đánh giá)</span>
                    </div>
                    <div class="card-body p-4" style="max-height: 400px; overflow-y: auto;" id="reviewsList">
                        <div class="text-center text-secondary py-3">Chưa có đánh giá nào cho sân này.</div>
                    </div>
                </div>
            </div>

            <!-- Right Column Sticky Booking summary card -->
            <div class="col-lg-4">
                <div class="card border sticky-top" style="top: 90px;">
                    <div class="card-body p-4">
                        <div class="text-success mb-3">
                            <span class="display-6 fw-bold" id="step1HourlyPrice">0đ</span>
                            <small class="text-secondary fw-normal">/ giờ</small>
                        </div>
                        <hr class="my-3 opacity-25">
                        
                        <div class="mb-3">
                            <label class="form-label text-secondary small mb-1"><i class="fa-regular fa-calendar-check me-1"></i> Ngày đã chọn</label>
                            <div class="fw-bold fs-5 text-dark" id="step1SelectedDate">-</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-secondary small mb-1"><i class="fa-regular fa-clock me-1"></i> Khung giờ đã chọn</label>
                            <div id="step1SelectedSlotsList">
                                <span class="text-secondary small italic">Chưa chọn khung giờ nào</span>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4 border-top pt-3">
                            <span class="fw-bold text-dark">Tổng cộng:</span>
                            <span class="fw-bold text-success fs-3" id="step1SummaryTotalPrice">0đ</span>
                        </div>

                        <button type="button" class="btn btn-success btn-lg w-100 py-3 fw-bold" id="btnGoToStep2">
                            Đặt sân ngay <i class="fa-solid fa-arrow-right ms-1"></i>
                        </button>
                        <small class="text-secondary text-center d-block mt-2">Bạn chưa bị trừ tiền ở bước này</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- STEP 2: THANH TOÁN ĐẶT SÂN (CHECKOUT) -->
    <div id="bookingStep2" class="d-none">
        <div class="row g-4">
            <!-- Left Column: Checkout information forms -->
            <div class="col-lg-8">
                <!-- Back trigger -->
                <button type="button" class="btn btn-outline-secondary mb-4 btn-sm" id="btnBackToStep1">
                    <i class="fa-solid fa-arrow-left me-1"></i> Quay lại chọn giờ
                </button>
                <h3 class="fw-bold text-dark mb-4">Thanh toán đặt sân</h3>

                <!-- Booking Info Summary Header card -->
                <div class="card border mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="m-0 fw-bold"><i class="fa-solid fa-circle-play text-success me-2"></i>Thông tin đặt sân</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded bg-success text-white d-flex align-items-center justify-content-center border" style="width: 100px; height: 60px;">
                                <i class="fa-solid fa-soccer-ball fa-2x"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1 text-success" id="checkoutFieldName">-</h5>
                                <div class="text-secondary small"><i class="fa-solid fa-location-dot me-1"></i> Hà Nội</div>
                                <div class="text-dark fw-semibold mt-1">
                                    <i class="fa-regular fa-calendar-check me-1 text-success"></i> 
                                    <span id="checkoutSelectedDate">-</span> | 
                                    <span id="checkoutSelectedSlots">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Details Form -->
                <div class="card border mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="m-0 fw-bold"><i class="fa-solid fa-user-edit text-success me-2"></i>Thông tin người đặt</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="customer_name" class="form-label">Họ và tên *</label>
                                <input type="text" name="customer_name" id="customer_name" class="form-control" value="{{ auth()->user()->name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="customer_phone" class="form-label">Số điện thoại *</label>
                                <input type="text" name="customer_phone" id="customer_phone" class="form-control" value="{{ auth()->user()->phone }}" required>
                            </div>
                        </div>
                        <div class="mb-0">
                            <label for="notes" class="form-label">Ghi chú</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Ghi chú thêm (không bắt buộc)"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Payment Option cards -->
                <div class="card border mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="m-0 fw-bold"><i class="fa-solid fa-credit-card text-success me-2"></i>Phương thức thanh toán</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex flex-column gap-3">
                            <!-- Cash Payment Option -->
                            <div class="payment-option-card selected" data-method="cash">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded bg-success-subtle text-success p-2">
                                            <i class="fa-solid fa-money-bill-1 fs-4"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">Thanh toán tại sân</div>
                                            <small class="text-secondary">Thanh toán trực tiếp bằng tiền mặt khi đến sân thi đấu</small>
                                        </div>
                                    </div>
                                    <i class="fa-solid fa-circle-check text-success fs-4 icon-selected"></i>
                                </div>
                            </div>

                            <!-- MoMo Payment Option -->
                            <div class="payment-option-card" data-method="momo">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded bg-danger-subtle text-danger p-2">
                                            <i class="fa-solid fa-wallet fs-4"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">Thanh toán MoMo</div>
                                            <small class="text-secondary">Thanh toán trực tuyến quét mã ví điện tử MoMo</small>
                                        </div>
                                    </div>
                                    <i class="fa-regular fa-circle text-secondary fs-4 icon-selected"></i>
                                </div>
                            </div>

                            <!-- VNPay Payment Option -->
                            <div class="payment-option-card" data-method="vnpay">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded bg-primary-subtle text-primary p-2">
                                            <i class="fa-solid fa-building-columns fs-4"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">Thanh toán VNPay</div>
                                            <small class="text-secondary">Thanh toán trực tuyến quét QR chuyển khoản ngân hàng VNPay</small>
                                        </div>
                                    </div>
                                    <i class="fa-regular fa-circle text-secondary fs-4 icon-selected"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column Order Summary checkout card -->
            <div class="col-lg-4">
                <div class="card border sticky-top" style="top: 90px;">
                    <div class="card-header bg-white py-3">
                        <h5 class="m-0 fw-bold">Tóm tắt đơn hàng</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3 d-flex align-items-center gap-2">
                            <div class="rounded bg-success text-white d-flex align-items-center justify-content-center border" style="width: 50px; height: 35px;">
                                <i class="fa-solid fa-soccer-ball"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark small" id="summaryCardFieldName">-</div>
                                <div class="text-secondary small" id="summaryCardFieldType">-</div>
                            </div>
                        </div>

                        <div class="text-dark small fw-semibold mb-3 border-bottom pb-2">
                            <div class="mb-1" id="summaryCardDate">-</div>
                            <div class="text-secondary small mb-2" id="summaryCardSlots">-</div>
                        </div>

                        <!-- Coupon Code -->
                        <div class="mb-4">
                            <label for="coupon_input" class="form-label text-secondary small mb-1">Mã giảm giá</label>
                            <div class="input-group">
                                <input type="text" id="coupon_input" class="form-control form-control-sm" placeholder="Ví dụ: SALE30">
                                <button type="button" id="btnApplyCoupon" class="btn btn-success btn-sm">Áp dụng</button>
                            </div>
                            <div id="couponMessage" class="small mt-1"></div>
                        </div>

                        <!-- Prices break-up -->
                        <div class="d-flex justify-content-between mb-2 small text-secondary">
                            <span>Tạm tính:</span>
                            <span class="fw-semibold text-dark" id="summarySubtotal">0đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small text-secondary d-none" id="discountLine">
                            <span>Giảm giá:</span>
                            <span class="fw-semibold text-danger" id="summaryDiscount">-0đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 small text-secondary">
                            <span>Phí dịch vụ:</span>
                            <span class="fw-semibold text-success">Miễn phí</span>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center border-top pt-3 mb-4">
                            <span class="fw-bold text-dark">Tổng cộng:</span>
                            <span class="fw-bold text-success fs-3" id="summaryFinalTotal">0đ</span>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg w-100 py-3 fw-bold">
                            Xác nhận thanh toán
                        </button>
                        <small class="text-secondary text-center d-block mt-2">Bằng việc đặt sân, bạn đồng ý với <a href="#" class="text-success text-decoration-none">điều khoản dịch vụ</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fieldSelect = document.getElementById('football_field_id');
        const dateInput = document.getElementById('booking_date');
        const slotBtns = document.querySelectorAll('.price-badge-btn');
        const step1Date = document.getElementById('step1SelectedDate');
        const step1SlotsList = document.getElementById('step1SelectedSlotsList');
        const step1HourlyPrice = document.getElementById('step1HourlyPrice');
        const step1SummaryTotalPrice = document.getElementById('step1SummaryTotalPrice');
        const hiddenInputsContainer = document.getElementById('step1HiddenInputs');

        // Step transition controls
        const bookingStep1 = document.getElementById('bookingStep1');
        const bookingStep2 = document.getElementById('bookingStep2');
        const btnGoToStep2 = document.getElementById('btnGoToStep2');
        const btnBackToStep1 = document.getElementById('btnBackToStep1');

        // Step 2 elements
        const checkoutFieldName = document.getElementById('checkoutFieldName');
        const checkoutSelectedDate = document.getElementById('checkoutSelectedDate');
        const checkoutSelectedSlots = document.getElementById('checkoutSelectedSlots');
        const summaryCardFieldName = document.getElementById('summaryCardFieldName');
        const summaryCardFieldType = document.getElementById('summaryCardFieldType');
        const summaryCardDate = document.getElementById('summaryCardDate');
        const summaryCardSlots = document.getElementById('summaryCardSlots');
        
        // Prices summary
        const summarySubtotal = document.getElementById('summarySubtotal');
        const summaryDiscount = document.getElementById('summaryDiscount');
        const discountLine = document.getElementById('discountLine');
        const summaryFinalTotal = document.getElementById('summaryFinalTotal');
        const formDiscount = document.getElementById('form_discount');

        // Coupon code handling
        const couponInput = document.getElementById('coupon_input');
        const btnApplyCoupon = document.getElementById('btnApplyCoupon');
        const couponMessage = document.getElementById('couponMessage');

        // Payment option selection
        const paymentCards = document.querySelectorAll('.payment-option-card');
        const formPaymentMethod = document.getElementById('form_payment_method');

        let selectedSlots = new Set();
        let baseFieldPrice = 0;
        let discountRate = 0; // percentage e.g., 0.3 for 30%

        // 1. Availability check when field or date updates
        function updateAvailability() {
            const fieldId = fieldSelect.value;
            const date = dateInput.value;

            // Reset step 1 summaries
            selectedSlots.clear();
            slotBtns.forEach(btn => {
                btn.classList.remove('selected', 'booked');
            });
            updateStep1Summary();

            if (!fieldId || !date) return;

            // Update Field Detail Header information from selected option
            const selectedOption = fieldSelect.options[fieldSelect.selectedIndex];
            baseFieldPrice = parseFloat(selectedOption.getAttribute('data-price'));
            const fieldName = selectedOption.getAttribute('data-name');
            const fieldType = selectedOption.getAttribute('data-type');

            document.getElementById('fieldDetailHeader').classList.remove('d-none');
            document.getElementById('detailFieldName').textContent = fieldName;
            document.getElementById('detailFieldType').textContent = fieldType;
            step1HourlyPrice.textContent = baseFieldPrice.toLocaleString('vi-VN') + 'đ';

            // Calculate prices dynamically for each slot
            slotBtns.forEach(btn => {
                const multiplier = parseFloat(btn.getAttribute('data-multiplier'));
                // Slot is 1.5 hours
                const price = baseFieldPrice * 1.5 * multiplier;
                btn.querySelector('.price-val').textContent = price.toLocaleString('vi-VN') + 'đ';
            });

            // Perform fetch lookup
            fetch("{{ route('customer.bookings.check-availability') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ field_id: fieldId, date: date })
            })
            .then(res => res.json())
            .then(data => {
                const bookedList = data.booked_slots || [];
                slotBtns.forEach(btn => {
                    const slotId = parseInt(btn.getAttribute('data-slot-id'));
                    if (bookedList.includes(slotId)) {
                        btn.classList.add('booked');
                    }
                });
            })
            .catch(err => console.error("Error looking up availability:", err));

            // Fetch Reviews
            fetch(`/customer/fields/${fieldId}/reviews`)
            .then(res => res.json())
            .then(data => {
                const ratingEl = document.getElementById('detailFieldRating');
                const summaryEl = document.getElementById('headerReviewSummary');
                const reviewsList = document.getElementById('reviewsList');
                const containerCard = document.getElementById('reviewsContainerCard');

                if (data.total > 0) {
                    ratingEl.innerHTML = `<i class="fa-solid fa-star"></i> ${data.average} (${data.total} đánh giá)`;
                    summaryEl.textContent = `${data.average} sao (${data.total} đánh giá)`;
                    containerCard.classList.remove('d-none');
                    
                    let html = '';
                    data.reviews.forEach(review => {
                        let stars = '';
                        for(let i=1; i<=5; i++) {
                            if (i <= review.rating) {
                                stars += '<i class="fa-solid fa-star"></i>';
                            } else {
                                stars += '<i class="fa-regular fa-star text-secondary"></i>';
                            }
                        }
                        
                        const date = new Date(review.created_at).toLocaleDateString('vi-VN');
                        const commentText = review.comment || 'Không có bình luận.';
                        
                        html += `
                            <div class="d-flex mb-4 pb-3 border-bottom">
                                <div class="me-3">
                                    <div class="bg-secondary text-white rounded-circle d-flex justify-content-center align-items-center fw-bold" style="width: 45px; height: 45px;">
                                        ${review.user.name.charAt(0).toUpperCase()}
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="fw-bold mb-0 text-dark">${review.user.name}</h6>
                                        <small class="text-secondary">${date}</small>
                                    </div>
                                    <div class="text-warning small mb-2">
                                        ${stars}
                                    </div>
                                    <p class="mb-0 text-dark">${commentText}</p>
                                </div>
                            </div>
                        `;
                    });
                    reviewsList.innerHTML = html;
                } else {
                    ratingEl.innerHTML = `<i class="fa-solid fa-star"></i> Chưa có đánh giá`;
                    containerCard.classList.add('d-none');
                    reviewsList.innerHTML = '<div class="text-center text-secondary py-3">Chưa có đánh giá nào cho sân này.</div>';
                }
            })
            .catch(err => console.error("Error fetching reviews:", err));
        }

        fieldSelect.addEventListener('change', updateAvailability);
        dateInput.addEventListener('change', updateAvailability);

        // Run immediately if values pre-selected
        if (fieldSelect.value && dateInput.value) {
            updateAvailability();
        }

        // 2. Select slots
        slotBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                if (btn.classList.contains('booked')) return;

                const slotId = btn.getAttribute('data-slot-id');
                if (selectedSlots.has(slotId)) {
                    selectedSlots.delete(slotId);
                    btn.classList.remove('selected');
                } else {
                    selectedSlots.add(slotId);
                    btn.classList.add('selected');
                }
                updateStep1Summary();
            });
        });

        // 3. Update Step 1 summary text
        function updateStep1Summary() {
            // Selected Date formatting
            const d = dateInput.value;
            if (d) {
                const dateObj = new Date(d);
                const daysOfWeek = ['Chủ Nhật', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7'];
                const dayName = daysOfWeek[dateObj.getDay()];
                const day = String(dateObj.getDate()).padStart(2, '0');
                const month = String(dateObj.getMonth() + 1).padStart(2, '0');
                const year = dateObj.getFullYear();
                step1Date.textContent = `${dayName}, ${day}/${month}/${year}`;
            } else {
                step1Date.textContent = '-';
            }

            // Slots and prices computation
            step1SelectedSlotsList.innerHTML = '';
            hiddenInputsContainer.innerHTML = '';
            let total = 0;

            if (selectedSlots.size > 0 && fieldSelect.selectedIndex > 0) {
                selectedSlots.forEach(slotId => {
                    const btn = document.querySelector(`.price-badge-btn[data-slot-id="${slotId}"]`);
                    const name = btn.getAttribute('data-name');
                    const multiplier = parseFloat(btn.getAttribute('data-multiplier'));
                    const price = baseFieldPrice * 1.5 * multiplier;
                    total += price;

                    // Text representation
                    const badge = document.createElement('span');
                    badge.className = 'badge bg-success-subtle text-success border border-success-subtle me-1.5 mb-1.5 px-2.5 py-1.5';
                    badge.innerHTML = `<i class="fa-regular fa-clock me-1"></i> ${name}`;
                    step1SelectedSlotsList.appendChild(badge);

                    // Hidden Inputs
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'time_slots[]';
                    input.value = slotId;
                    hiddenInputsContainer.appendChild(input);
                });
            } else {
                step1SelectedSlotsList.innerHTML = '<span class="text-secondary small italic">Chưa chọn khung giờ nào</span>';
            }

            step1SummaryTotalPrice.textContent = total.toLocaleString('vi-VN') + 'đ';
        }

        // 4. Transitions between steps
        btnGoToStep2.addEventListener('click', function() {
            if (!fieldSelect.value) {
                alert('Vui lòng chọn sân bóng trước.');
                return;
            }
            if (!dateInput.value) {
                alert('Vui lòng chọn ngày đá.');
                return;
            }
            if (selectedSlots.size === 0) {
                alert('Vui lòng chọn ít nhất một khung giờ thi đấu.');
                return;
            }

            // Prefill Step 2 details dynamically
            const selectedOption = fieldSelect.options[fieldSelect.selectedIndex];
            const name = selectedOption.getAttribute('data-name');
            const type = selectedOption.getAttribute('data-type');
            
            checkoutFieldName.textContent = name;
            summaryCardFieldName.textContent = name;
            summaryCardFieldType.textContent = type;

            checkoutSelectedDate.textContent = step1Date.textContent;
            summaryCardDate.textContent = step1Date.textContent;

            // Selected slots text
            let slotsTextArr = [];
            selectedSlots.forEach(slotId => {
                const btn = document.querySelector(`.price-badge-btn[data-slot-id="${slotId}"]`);
                slotsTextArr.push(btn.getAttribute('data-name'));
            });
            checkoutSelectedSlots.textContent = slotsTextArr.join(', ');
            summaryCardSlots.textContent = slotsTextArr.join(', ');

            // Calculate values
            updateStep2CheckoutPrices();

            // Toggle screens with smooth scrolls
            bookingStep1.classList.add('d-none');
            bookingStep2.classList.remove('d-none');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        btnBackToStep1.addEventListener('click', function() {
            bookingStep2.classList.add('d-none');
            bookingStep1.classList.remove('d-none');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        // 5. Calculate Step 2 Pricing & Coupon Deductions
        function updateStep2CheckoutPrices() {
            let subtotal = 0;
            selectedSlots.forEach(slotId => {
                const btn = document.querySelector(`.price-badge-btn[data-slot-id="${slotId}"]`);
                const multiplier = parseFloat(btn.getAttribute('data-multiplier'));
                subtotal += baseFieldPrice * 1.5 * multiplier;
            });

            summarySubtotal.textContent = subtotal.toLocaleString('vi-VN') + 'đ';

            // Discount calculation
            let discountValue = subtotal * discountRate;
            formDiscount.value = Math.round(discountValue);

            if (discountValue > 0) {
                discountLine.classList.remove('d-none');
                summaryDiscount.textContent = `-${Math.round(discountValue).toLocaleString('vi-VN')}đ`;
            } else {
                discountLine.classList.add('d-none');
            }

            let finalTotal = Math.max(0, subtotal - discountValue);
            summaryFinalTotal.textContent = Math.round(finalTotal).toLocaleString('vi-VN') + 'đ';
        }

        // Apply coupon code check
        btnApplyCoupon.addEventListener('click', function() {
            const coupon = couponInput.value.trim().toUpperCase();
            
            if (coupon === 'SALE30') {
                discountRate = 0.30;
                couponMessage.innerHTML = '<span class="text-success fw-bold"><i class="fa-solid fa-circle-check"></i> Đã áp dụng mã SALE30 (Giảm 30%)</span>';
            } else if (coupon === 'SALE10') {
                discountRate = 0.10;
                couponMessage.innerHTML = '<span class="text-success fw-bold"><i class="fa-solid fa-circle-check"></i> Đã áp dụng mã SALE10 (Giảm 10%)</span>';
            } else if (coupon === '') {
                discountRate = 0;
                couponMessage.textContent = '';
            } else {
                discountRate = 0;
                couponMessage.innerHTML = '<span class="text-danger"><i class="fa-solid fa-circle-xmark"></i> Mã giảm giá không đúng hoặc đã hết hạn</span>';
            }
            updateStep2CheckoutPrices();
        });

        // 6. Payment cards toggle
        paymentCards.forEach(card => {
            card.addEventListener('click', function() {
                paymentCards.forEach(c => {
                    c.classList.remove('selected');
                    c.querySelector('.icon-selected').className = 'fa-regular fa-circle text-secondary fs-4 icon-selected';
                });

                card.classList.add('selected');
                card.querySelector('.icon-selected').className = 'fa-solid fa-circle-check text-success fs-4 icon-selected';
                
                const method = card.getAttribute('data-method');
                formPaymentMethod.value = method;
            });
        });
    });
</script>
@endsection
