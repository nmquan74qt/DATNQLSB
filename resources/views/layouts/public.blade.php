<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Hệ Thống Đặt Sân Bóng Đá Online')</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    @yield('styles')
</head>
<body>

    <!-- Header / Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark public-navbar sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <i class="fa-solid fa-soccer-ball text-success fs-3 me-2"></i>
                <span class="fw-bold tracking-wide">PITCH<span class="text-success">MANAGE</span></span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#publicNavbar" aria-controls="publicNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="publicNavbar">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-link-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}"><i class="fa-solid fa-home me-1"></i> Trang Chủ</a></li>
                    <li class="nav-link-item"><a class="nav-link" href="{{ route('home') }}#fields"><i class="fa-solid fa-circle-info me-1"></i> Danh Sách Sân</a></li>
                    <li class="nav-link-item"><a class="nav-link" href="{{ route('home') }}#pricing"><i class="fa-solid fa-tags me-1"></i> Bảng Giá Thuê</a></li>
                    <li class="nav-link-item"><a class="nav-link" href="{{ route('home') }}#contact"><i class="fa-solid fa-envelope me-1"></i> Liên Hệ</a></li>
                </ul>
                <div class="d-flex align-items-center gap-2">
                    @auth
                        @if(auth()->user()->isManager() || auth()->user()->isStaff())
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-success"><i class="fa-solid fa-gauge me-1"></i> Dashboard</a>
                        @else
                            <a href="{{ route('customer.dashboard') }}" class="btn btn-success"><i class="fa-solid fa-user me-1"></i> Trang Cá Nhân</a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm"><i class="fa-solid fa-sign-out me-1"></i> Đăng Xuất</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-light"><i class="fa-solid fa-sign-in me-1"></i> Đăng Nhập</a>
                        <a href="{{ route('register') }}" class="btn btn-success"><i class="fa-solid fa-user-plus me-1"></i> Đăng Ký</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-5 mt-5 border-top border-secondary">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3 d-flex align-items-center">
                        <i class="fa-solid fa-soccer-ball text-success me-2"></i>
                        <span>PITCH<span class="text-success">MANAGE</span></span>
                    </h5>
                    <p class="text-secondary">Hệ thống quản lý và đặt sân bóng đá cỏ nhân tạo trực tuyến, hiện đại, uy tín và chuyên nghiệp hàng đầu Việt Nam.</p>
                </div>
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3 text-success">Liên Kết Nhanh</h5>
                    <ul class="list-unstyled text-secondary d-flex flex-column gap-2">
                        <li><a href="{{ route('home') }}" class="text-secondary text-decoration-none hover-light">Trang Chủ</a></li>
                        <li><a href="{{ route('home') }}#fields" class="text-secondary text-decoration-none hover-light">Sân Bóng</a></li>
                        <li><a href="{{ route('home') }}#pricing" class="text-secondary text-decoration-none hover-light">Bảng Giá</a></li>
                        <li><a href="{{ route('home') }}#contact" class="text-secondary text-decoration-none hover-light">Liên Hệ</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3 text-success">Thông Tin Liên Hệ</h5>
                    <p class="text-secondary mb-2"><i class="fa-solid fa-location-dot text-success me-2"></i> Số 12 Chùa Bộc, Đống Đa, Hà Nội</p>
                    <p class="text-secondary mb-2"><i class="fa-solid fa-phone text-success me-2"></i> Hotline: 0987 654 321</p>
                    <p class="text-secondary mb-2"><i class="fa-solid fa-envelope text-success me-2"></i> Email: support@pitchmanage.vn</p>
                </div>
            </div>
            <hr class="border-secondary my-4">
            <div class="text-center text-secondary">
                <p class="mb-0">&copy; {{ date('Y') }} PitchManage. Tất cả quyền được bảo lưu. Phát triển bởi Senior Laravel Developer.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
