@extends('layouts.public')

@section('title', 'PitchManage - Đặt Sân Bóng Đá Trực Tuyến Hàng Đầu')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-extrabold text-white mb-3">
                        Đam Mê Bất Tận,<br>
                        <span class="text-success text-shadow">Đặt Sân Dễ Dàng!</span>
                    </h1>
                    <p class="lead text-light mb-4">Hệ thống sân cỏ nhân tạo tiêu chuẩn, chất lượng ánh sáng đỉnh cao, phục vụ nước uống và dụng cụ thi đấu đầy đủ. Hỗ trợ đặt lịch nhanh chóng chỉ trong 1 phút.</p>
                    <div class="d-flex gap-3">
                        <a href="{{ route('customer.bookings.create') }}" class="btn btn-success btn-lg px-4 py-3"><i class="fa-solid fa-calendar-days me-2"></i> Đặt Sân Ngay</a>
                        <a href="#pricing" class="btn btn-outline-light btn-lg px-4 py-3"><i class="fa-solid fa-tags me-2"></i> Xem Bảng Giá</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Showcase Fields Section -->
    <section id="fields" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-success fw-bold text-uppercase tracking-wider">Hệ thống sân bóng</span>
                <h2 class="fw-bold text-dark mt-2">Sân Bóng Nổi Bật</h2>
                <div class="bg-success mx-auto mt-2" style="width: 60px; height: 4px; border-radius: 2px;"></div>
            </div>
            
            <div class="row g-4">
                @forelse($fields as $field)
                    <div class="col-md-4">
                        <div class="card card-hover h-100 overflow-hidden border">
                            <div style="height: 220px; overflow: hidden; background: #e2e8f0; position: relative;">
                                @if($field->image)
                                    <img src="{{ asset($field->image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $field->name }}">
                                @else
                                    <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center bg-dark text-white">
                                        <i class="fa-solid fa-soccer-ball fa-3x text-success mb-2 animate-bounce"></i>
                                        <span class="fw-semibold">PitchManage Arena</span>
                                    </div>
                                @endif
                                <span class="badge bg-success position-absolute top-3 end-3 px-3 py-2 shadow-sm" style="top: 15px; right: 15px;">
                                    {{ $field->fieldType->name }}
                                </span>
                            </div>
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-2">{{ $field->name }}</h5>
                                <p class="text-secondary mb-3 small">{{ Str::limit($field->description, 100, '...') }}</p>
                                <hr class="my-3 opacity-25">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <small class="text-secondary d-block">Giá tiêu chuẩn:</small>
                                        <span class="fw-bold text-success">{{ number_format($field->fieldType->price_per_hour) }}đ / giờ</span>
                                    </div>
                                    <a href="{{ route('customer.bookings.create', ['field_id' => $field->id]) }}" class="btn btn-sm btn-success">
                                        Đặt lịch <i class="fa-solid fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-secondary">Hiện tại chưa có dữ liệu sân bóng.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Pricing Table -->
    <section id="pricing" class="py-5 bg-white border-top border-bottom">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-success fw-bold text-uppercase tracking-wider">Chi Phí Thuê Sân</span>
                <h2 class="fw-bold text-dark mt-2">Bảng Giá Dịch Vụ</h2>
                <div class="bg-success mx-auto mt-2" style="width: 60px; height: 4px; border-radius: 2px;"></div>
            </div>

            <div class="row g-4 justify-content-center">
                @foreach($fieldTypes as $type)
                    <div class="col-md-4">
                        <div class="card text-center h-100 border p-4 shadow-sm card-hover">
                            <div class="card-body">
                                <h4 class="fw-bold mb-3">{{ $type->name }}</h4>
                                <div class="price-tag mb-3">{{ number_format($type->price_per_hour) }}đ <span class="fs-6 text-secondary fw-normal">/ Giờ</span></div>
                                <p class="text-secondary small mb-4">{{ $type->description }}</p>
                                <ul class="list-unstyled text-start mb-4 d-flex flex-column gap-2">
                                    <li><i class="fa-solid fa-circle-check text-success me-2"></i> Hệ thống chiếu sáng đầy đủ</li>
                                    <li><i class="fa-solid fa-circle-check text-success me-2"></i> Cỏ nhân tạo chất lượng tốt nhất</li>
                                    <li><i class="fa-solid fa-circle-check text-success me-2"></i> Có chỗ để xe máy, xe ô tô rộng rãi</li>
                                    <li><i class="fa-solid fa-circle-check text-success me-2"></i> Nước uống, bib miễn phí khi mua gói</li>
                                </ul>
                                <a href="{{ route('customer.bookings.create') }}" class="btn btn-outline-success w-100 py-2">
                                    Chọn loại sân này
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="alert alert-warning mt-5 rounded-lg border-warning-subtle text-center p-3 max-w-2xl mx-auto" role="alert">
                <i class="fa-solid fa-circle-info text-warning me-2 fs-5"></i>
                <span><strong>Lưu ý:</strong> Giá trên là giá thuê sân giờ bình thường. Vào các khung giờ cao điểm (từ 16:30 - 21:00) giá sẽ nhân hệ số phụ thu (từ 1.2x - 1.5x) theo quy định của hệ thống.</span>
            </div>
        </div>
    </section>

    <!-- Services Banner / Showcase -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-success fw-bold text-uppercase tracking-wider">Tiện Ích Đi Kèm</span>
                <h2 class="fw-bold text-dark mt-2">Dịch Vụ Hỗ Trợ</h2>
                <div class="bg-success mx-auto mt-2" style="width: 60px; height: 4px; border-radius: 2px;"></div>
            </div>

            <div class="row g-4 text-center">
                <div class="col-md-3">
                    <div class="p-4 border rounded-3 bg-white h-100 card-hover">
                        <i class="fa-solid fa-bottle-water fa-3x text-success mb-3"></i>
                        <h5 class="fw-bold">Nước giải khát</h5>
                        <p class="text-secondary small mb-0">Phục vụ nước tinh khiết Aquafina, Sting lạnh, nước bù khoáng Pocari Sweat...</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-4 border rounded-3 bg-white h-100 card-hover">
                        <i class="fa-solid fa-soccer-ball fa-3x text-success mb-3"></i>
                        <h5 class="fw-bold">Thuê Bóng Thi Đấu</h5>
                        <p class="text-secondary small mb-0">Bóng Động Lực chính hãng số 4 và số 5 đạt tiêu chuẩn thi đấu cao cấp.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-4 border rounded-3 bg-white h-100 card-hover">
                        <i class="fa-solid fa-shirt fa-3x text-success mb-3"></i>
                        <h5 class="fw-bold">Áo Bib Đồng Đội</h5>
                        <p class="text-secondary small mb-0">Cho thuê áo bib tập luyện phân biệt đội hình nhiều màu sắc nổi bật.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-4 border rounded-3 bg-white h-100 card-hover">
                        <i class="fa-solid fa-shoe-prints fa-3x text-success mb-3"></i>
                        <h5 class="fw-bold">Thuê Giày Đinh TF</h5>
                        <p class="text-secondary small mb-0">Giày chuyên dụng đinh dăm bám sân cỏ nhân tạo nhiều size phù hợp.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact & Map Section -->
    <section id="contact" class="py-5 bg-white border-top">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6">
                    <span class="text-success fw-bold text-uppercase tracking-wider">Hỗ Trợ & Liên Hệ</span>
                    <h2 class="fw-bold text-dark mt-2 mb-4">Kết Nối Với Chúng Tôi</h2>
                    <p class="text-secondary mb-4">Bạn cần hỗ trợ đặt sân cho tập thể, tổ chức giải đấu nội bộ hay cần thêm thông tin chi tiết dịch vụ? Điền thông tin vào mẫu bên dưới, nhân viên chăm sóc khách hàng của chúng tôi sẽ gọi lại ngay lập tức.</p>
                    
                    <form action="{{ route('contact.submit') }}" method="POST">
                        @csrf
                        @if(session('success'))
                            <div class="alert alert-success py-2 mb-3">{{ session('success') }}</div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger py-2 mb-3">{{ session('error') }}</div>
                        @endif
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control py-2" placeholder="Ví dụ: Nguyễn Văn A" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="tel" name="phone" class="form-control py-2" placeholder="Ví dụ: 0987654321" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nội dung câu hỏi <span class="text-danger">*</span></label>
                            <textarea name="message" class="form-control" rows="4" placeholder="Nhập tin nhắn của bạn ở đây..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success px-4 py-2 w-100"><i class="fa-solid fa-paper-plane me-2"></i>Gửi Tin Nhắn Liên Hệ</button>
                    </form>
                </div>
                <div class="col-lg-6">
                    <div class="rounded-3 overflow-hidden border shadow-sm" style="height: 450px;">
                        <!-- Embed standard Google Maps placeholder pointing to Hanoi, Vietnam -->
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.506666992683!2d105.82635907471399!3d21.012384788339595!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab817926b645%3A0x6bda1931393692a7!2zMTIgQ2jDuWEgQuG7mWMsIEh5IFF1YW4sIMSQ4buRbmcgRGEsIEjDoCBO4buZaSwgVmlldG5hbQ!5e0!3m2!1sen!2s!4v1700000000000!5m2!1sen!2s" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
