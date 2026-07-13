@extends('layouts.public')

@section('title')
    @yield('title', 'Cổng Khách Hàng')
@endsection

@section('styles')
    @yield('styles')
    <style>
        body {
            background-color: #f4f7f6;
            font-family: 'Outfit', sans-serif;
        }
        .customer-sidebar {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.03);
            padding: 20px 10px;
        }
        .customer-sidebar .nav-link {
            color: #495057;
            border-radius: 8px;
            padding: 12px 20px;
            margin-bottom: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            font-size: 0.95rem;
        }
        .customer-sidebar .nav-link:hover {
            background-color: #f8f9fa;
            color: #219653;
        }
        .customer-sidebar .nav-link.active {
            background-color: #e8f5e9;
            color: #219653;
        }
        .customer-sidebar .nav-link i {
            width: 24px;
            text-align: center;
            margin-right: 12px;
            font-size: 1.1rem;
        }
        .customer-sidebar .nav-link.text-danger:hover {
            background-color: #feeceb;
            color: #dc3545 !important;
        }
        .customer-layout-wrapper {
            min-height: calc(100vh - 76px - 300px);
            padding: 2rem 0;
        }
        
        /* Table Styles to match screenshot */
        .professional-table-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.03);
            padding: 30px;
        }
        .professional-table-title {
            font-weight: 700;
            color: #212529;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
        }
        .table-custom {
            width: 100%;
            border-collapse: collapse;
        }
        .table-custom th {
            color: #6c757d;
            font-weight: 500;
            padding: 15px 10px;
            border-bottom: 1px solid #eee;
            font-size: 0.9rem;
            white-space: nowrap;
        }
        .table-custom td {
            padding: 15px 10px;
            vertical-align: middle;
            border-bottom: 1px solid #f8f9fa;
            color: #212529;
            font-size: 0.95rem;
            white-space: nowrap; /* Prevent text from wrapping */
        }
        .table-custom tr:last-child td {
            border-bottom: none;
        }
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .status-badge.completed {
            background-color: #e8f5e9;
            color: #219653;
        }
        .status-badge.paid {
            background-color: #f1f3f5;
            color: #495057;
        }
        .status-badge.pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-badge.cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        .action-link {
            color: #219653;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-right: 15px;
        }
        .action-link:hover {
            color: #198754;
            text-decoration: underline;
        }
        .action-link i {
            font-size: 0.85rem;
        }
    </style>
@endsection

@section('content')
<div class="customer-layout-wrapper">
    <div class="container">
        
        <!-- Notifications / Session Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4 rounded-3" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4 rounded-3" role="alert">
                <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Professional Customer Sidebar -->
            <div class="col-lg-3">
                <div class="customer-sidebar sticky-top" style="top: 100px; z-index: 10;">
                    <div class="text-center mb-4 pt-3">
                        @if(auth()->user()->avatar)
                            <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="rounded-circle mb-3 border border-2 border-white shadow-sm" style="width: 70px; height: 70px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center mb-3 shadow-sm border border-2 border-white" style="width: 70px; height: 70px; font-size: 2rem; font-weight: 600;">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <p class="text-secondary small mb-0">{{ auth()->user()->email }}</p>
                    </div>
                    
                    <nav class="nav flex-column px-2">
                        <a class="nav-link {{ request()->routeIs('customer.profile.edit') ? 'active' : '' }}" href="{{ route('customer.profile.edit') }}">
                            <i class="fa-regular fa-user"></i> Thông tin cá nhân
                        </a>
                        <a class="nav-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}" href="{{ route('customer.dashboard') }}">
                            <i class="fa-regular fa-calendar-check"></i> Lịch sử đặt sân
                        </a>
                        <a class="nav-link" href="{{ route('customer.profile.edit') }}#password">
                            <i class="fa-solid fa-lock"></i> Đổi mật khẩu
                        </a>
                        
                        <form action="{{ route('logout') }}" method="POST" class="d-grid mt-4">
                            @csrf
                            <button type="submit" class="nav-link text-danger bg-transparent border-0 w-100 text-start">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i> Đăng xuất
                            </button>
                        </form>
                    </nav>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="col-lg-9">
                @yield('content_customer')
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    @yield('scripts')
@endsection
