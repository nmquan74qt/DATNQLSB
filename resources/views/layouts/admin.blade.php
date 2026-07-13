<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Admin Dashboard')</title>
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

    <div class="admin-wrapper">
        <!-- Sidebar -->
        <nav class="admin-sidebar no-print">
            <div class="sidebar-header d-flex align-items-center justify-content-between">
                <a href="{{ route('home') }}" class="text-white text-decoration-none d-flex align-items-center">
                    <i class="fa-solid fa-soccer-ball text-success fs-4 me-2"></i>
                    <span class="fw-bold tracking-wide">PITCH<span class="text-success">MANAGE</span></span>
                </a>
            </div>

            <ul class="list-unstyled components">
                <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
                </li>
                <li class="{{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.bookings.index') }}"><i class="fa-solid fa-calendar-check"></i> Đặt Sân</a>
                </li>
                <li class="{{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.customers.index') }}"><i class="fa-solid fa-users"></i> Khách Hàng</a>
                </li>
                <li class="{{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.services.index') }}"><i class="fa-solid fa-cubes"></i> Dịch Vụ</a>
                </li>
                <li class="{{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.payments.index') }}"><i class="fa-solid fa-credit-card"></i> Thanh Toán</a>
                </li>
                <li class="{{ request()->routeIs('admin.invoices.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.invoices.index') }}"><i class="fa-solid fa-file-invoice-dollar"></i> Hóa Đơn</a>
                </li>

                <!-- Manager Only Section -->
                @if(auth()->user()->isManager())
                    <li class="sidebar-divider border-top border-secondary my-3 opacity-25"></li>
                    <li class="px-3 py-1 text-secondary text-uppercase fw-bold" style="font-size: 11px;">Cấu Hình Hệ Thống</li>
                    
                    <li class="{{ request()->routeIs('admin.fields.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.fields.index') }}"><i class="fa-solid fa-circle-play"></i> Sân Bóng</a>
                    </li>
                    <li class="{{ request()->routeIs('admin.field-types.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.field-types.index') }}"><i class="fa-solid fa-layer-group"></i> Loại Sân</a>
                    </li>
                    <li class="{{ request()->routeIs('admin.time-slots.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.time-slots.index') }}"><i class="fa-solid fa-clock"></i> Khung Giờ</a>
                    </li>
                    <li class="{{ request()->routeIs('admin.staffs.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.staffs.index') }}"><i class="fa-solid fa-user-shield"></i> Nhân Viên</a>
                    </li>
                    <li class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.reports.index') }}"><i class="fa-solid fa-chart-pie"></i> Báo Cáo Doanh Thu</a>
                    </li>
                @endif
            </ul>

            <div class="sidebar-footer p-3 border-top border-secondary position-absolute bottom-0 w-100" style="background: rgba(0,0,0,0.1);">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="text-truncate" style="max-width: 150px;">
                        <small class="d-block text-secondary">Vai trò: {{ auth()->user()->role->description }}</small>
                        <span class="fw-bold">{{ auth()->user()->name }}</span>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="admin-content">
            <!-- Top Navbar -->
            <div class="admin-navbar d-flex align-items-center justify-content-between no-print">
                <div class="d-flex align-items-center">
                    <h4 class="mb-0 fw-bold">@yield('page_title', 'Trang Quản Trị')</h4>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-globe me-1"></i> Xem Trang Chủ</a>
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle d-flex align-items-center gap-2" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-circle-user text-success fs-5"></i>
                            <span>{{ auth()->user()->name }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userMenu">
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="fa-solid fa-sign-out me-2"></i> Đăng Xuất</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Notifications / Session Alerts -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show no-print mb-4" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show no-print mb-4" role="alert">
                    <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Main Canvas View -->
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
