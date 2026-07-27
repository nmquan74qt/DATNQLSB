<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Quản Trị - PitchManage</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- FullCalendar -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .admin-sidebar {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .admin-main {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 transition-colors duration-300" 
      x-data="{ sidebarOpen: true, darkMode: localStorage.getItem('darkMode') === 'true' }" 
      x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" 
      :class="{ 'dark': darkMode }">

    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside class="admin-sidebar fixed inset-y-0 left-0 z-50 bg-white dark:bg-slate-800 border-r border-slate-200 dark:border-slate-700 shadow-xl lg:static lg:block flex-shrink-0"
               :class="{ 'w-64': sidebarOpen, 'w-20': !sidebarOpen, '-translate-x-full lg:translate-x-0': !sidebarOpen && window.innerWidth < 1024 }">
            
            <!-- Sidebar Header -->
            <div class="h-16 flex items-center justify-center border-b border-slate-200 dark:border-slate-700 px-4">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 overflow-hidden">
                    <div class="w-10 h-10 bg-gradient-to-tr from-primary to-blue-500 rounded-xl flex items-center justify-center text-white flex-shrink-0 shadow-lg shadow-primary/30">
                        <i class="fa-solid fa-futbol text-xl"></i>
                    </div>
                    <span class="font-heading font-bold text-xl tracking-tight text-slate-900 dark:text-white whitespace-nowrap transition-opacity duration-300"
                          :class="{ 'opacity-100': sidebarOpen, 'opacity-0 hidden': !sidebarOpen }">
                        Pitch<span class="text-primary">Admin</span>
                    </span>
                </a>
            </div>

            <!-- Sidebar Nav -->
            <nav class="p-4 space-y-2 h-[calc(100vh-4rem)] overflow-y-auto custom-scrollbar">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-primary dark:text-slate-400' }}" title="Dashboard">
                    <i class="fa-solid fa-chart-pie w-6 text-center text-lg"></i>
                    <span class="font-medium whitespace-nowrap" :class="{ 'hidden': !sidebarOpen }">Tổng Quan</span>
                </a>

                <div class="pt-4 pb-2">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider px-3" :class="{ 'hidden': !sidebarOpen }">Quản Lý</p>
                    <div class="h-px bg-slate-200 dark:bg-slate-700 mt-2" :class="{ 'block': !sidebarOpen, 'hidden': sidebarOpen }"></div>
                </div>

                <a href="{{ route('admin.fields.index') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all {{ request()->routeIs('admin.fields.*') ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-primary dark:text-slate-400' }}" title="Sân Bóng">
                    <i class="fa-solid fa-layer-group w-6 text-center text-lg"></i>
                    <span class="font-medium whitespace-nowrap" :class="{ 'hidden': !sidebarOpen }">Sân Bóng</span>
                </a>

                @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.staff.index') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all {{ request()->routeIs('admin.staff.*') ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-primary dark:text-slate-400' }}" title="Nhân Sự">
                    <i class="fa-solid fa-users-gear w-6 text-center text-lg"></i>
                    <span class="font-medium whitespace-nowrap" :class="{ 'hidden': !sidebarOpen }">Nhân Sự & Lương</span>
                </a>
                @endif

                <a href="{{ route('admin.vouchers.index') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all {{ request()->routeIs('admin.vouchers.*') ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-primary dark:text-slate-400' }}" title="Khuyến Mãi">
                    <i class="fa-solid fa-ticket w-6 text-center text-lg"></i>
                    <span class="font-medium whitespace-nowrap" :class="{ 'hidden': !sidebarOpen }">Khuyến Mãi (Voucher)</span>
                </a>

                <a href="{{ route('admin.posts.index') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all {{ request()->routeIs('admin.posts.*') ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-primary dark:text-slate-400' }}" title="Tin Tức / Blog">
                    <i class="fa-solid fa-newspaper w-6 text-center text-lg"></i>
                    <span class="font-medium whitespace-nowrap" :class="{ 'hidden': !sidebarOpen }">Tin Tức / Blog</span>
                </a>

                <a href="{{ route('admin.bookings.index') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all {{ request()->requestUri == '/admin/bookings' || request()->routeIs('admin.bookings.*') ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-primary dark:text-slate-400' }}" title="Lịch Đặt Sân">
                    <i class="fa-solid fa-calendar-check w-6 text-center text-lg"></i>
                    <span class="font-medium whitespace-nowrap flex-grow" :class="{ 'hidden': !sidebarOpen }">Lịch Đặt Sân</span>
                </a>

                <a href="{{ route('admin.customers.index') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all {{ request()->routeIs('admin.customers.*') ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-primary dark:text-slate-400' }}" title="Khách Hàng">
                    <i class="fa-solid fa-users w-6 text-center text-lg"></i>
                    <span class="font-medium whitespace-nowrap" :class="{ 'hidden': !sidebarOpen }">Khách Hàng</span>
                </a>

                @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.payments.index') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all {{ request()->routeIs('admin.payments.*') ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-primary dark:text-slate-400' }}" title="Hóa Đơn">
                    <i class="fa-solid fa-file-invoice-dollar w-6 text-center text-lg"></i>
                    <span class="font-medium whitespace-nowrap" :class="{ 'hidden': !sidebarOpen }">Hóa Đơn</span>
                </a>
                
                <div class="pt-4 pb-2">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider px-3" :class="{ 'hidden': !sidebarOpen }">Hệ Thống</p>
                    <div class="h-px bg-slate-200 dark:bg-slate-700 mt-2" :class="{ 'block': !sidebarOpen, 'hidden': sidebarOpen }"></div>
                </div>

                <a href="{{ route('admin.system.settings') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all {{ request()->routeIs('admin.system.*') ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-primary dark:text-slate-400' }}" title="Cài Đặt Hệ Thống">
                    <i class="fa-solid fa-gear w-6 text-center text-lg"></i>
                    <span class="font-medium whitespace-nowrap" :class="{ 'hidden': !sidebarOpen }">Cài Đặt Hệ Thống</span>
                </a>
                @endif
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 bg-slate-50 dark:bg-slate-900 transition-all duration-300">
            
            <!-- Topbar -->
            <header class="h-16 bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between px-4 lg:px-8 shadow-sm">
                <!-- Left side -->
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-slate-500 hover:text-primary focus:outline-none transition-colors w-10 h-10 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-700 flex items-center justify-center">
                        <i class="fa-solid fa-bars-staggered text-lg"></i>
                    </button>
                    
                    <div class="hidden md:flex items-center relative" x-data="{ search: '' }">
                        <i class="fa-solid fa-search absolute left-3 text-slate-400"></i>
                        <input type="text" x-model="search" placeholder="Tìm kiếm nhanh..." class="pl-10 pr-4 py-2 bg-slate-100 dark:bg-slate-700/50 border-none rounded-xl text-sm focus:ring-2 focus:ring-primary/50 text-slate-700 dark:text-slate-200 w-64 transition-all">
                    </div>
                </div>

                <!-- Right side -->
                <div class="flex items-center gap-3">
                    
                    <button @click="darkMode = !darkMode" class="w-10 h-10 rounded-xl flex items-center justify-center text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-primary transition-colors">
                        <i class="fa-solid fa-moon" x-show="!darkMode"></i>
                        <i class="fa-solid fa-sun" x-show="darkMode" x-cloak></i>
                    </button>

                    <!-- Notifications -->
                    <div class="relative" x-data="{ notifOpen: false }" @click.away="notifOpen = false">
                        <button @click="notifOpen = !notifOpen" class="w-10 h-10 rounded-xl flex items-center justify-center text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-primary transition-colors relative">
                            <i class="fa-regular fa-bell text-lg"></i>
                            <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full animate-ping"></span>
                            <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>
                        <div x-show="notifOpen" x-transition x-cloak class="absolute right-0 mt-2 w-80 bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-700 py-2 z-50">
                            <div class="px-4 py-2 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
                                <h3 class="font-bold text-slate-800 dark:text-white">Thông báo</h3>
                                <a href="javascript:void(0);" class="text-xs text-primary hover:underline">Đánh dấu đã đọc</a>
                            </div>
                            <div class="max-h-80 overflow-y-auto">
                                <a href="javascript:void(0);" class="px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 flex gap-3 transition-colors">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-500 flex items-center justify-center flex-shrink-0">
                                        <i class="fa-solid fa-calendar-plus"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-800 dark:text-slate-200">Khách đặt sân Cỏ Nhân Tạo A1</p>
                                        <p class="text-xs text-slate-500">2 phút trước</p>
                                    </div>
                                </a>
                                <a href="javascript:void(0);" class="px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 flex gap-3 transition-colors">
                                    <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/50 text-emerald-500 flex items-center justify-center flex-shrink-0">
                                        <i class="fa-solid fa-money-bill-wave"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-800 dark:text-slate-200">Đã thanh toán VNPay thành công</p>
                                        <p class="text-xs text-slate-500">10 phút trước</p>
                                    </div>
                                </a>
                            </div>
                            <div class="px-4 py-2 border-t border-slate-100 dark:border-slate-700 text-center">
                                <a href="javascript:void(0);" class="text-sm text-primary hover:underline font-medium">Xem tất cả</a>
                            </div>
                        </div>
                    </div>

                    <!-- User Profile -->
                    <div class="relative ml-2" x-data="{ userOpen: false }" @click.away="userOpen = false">
                        <button @click="userOpen = !userOpen" class="flex items-center gap-2 focus:outline-none">
                            <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name ?? 'Admin').'&background=2563EB&color=fff' }}" alt="User" class="w-10 h-10 rounded-xl object-cover border border-slate-200 dark:border-slate-700 shadow-sm">
                        </button>
                        <div x-show="userOpen" x-transition x-cloak class="absolute right-0 mt-2 w-56 bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-700 py-1 z-50">
                            <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700">
                                <p class="text-sm text-slate-500 dark:text-slate-400">Đăng nhập với tư cách</p>
                                <p class="text-sm font-bold text-slate-800 dark:text-white truncate">{{ auth()->user()->name ?? 'Quản Trị Viên' }}</p>
                            </div>
                            <a href="javascript:void(0);" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-primary transition-colors"><i class="fa-regular fa-user w-5"></i> Hồ sơ cá nhân</a>
                            <a href="{{ route('home') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-primary transition-colors"><i class="fa-solid fa-globe w-5"></i> Về trang khách</a>
                            <div class="h-px bg-slate-100 dark:bg-slate-700 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"><i class="fa-solid fa-arrow-right-from-bracket w-5"></i> Đăng xuất</button>
                            </form>
                        </div>
                    </div>

                </div>
            </header>

            <!-- Main Scrollable Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 lg:p-8">
                
                @if(session('success'))
                    <div class="mb-6 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-600 dark:text-emerald-400 px-4 py-3 rounded-xl flex items-center justify-between animate-slide-up" x-data="{ show: true }" x-show="show">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-check-circle text-lg"></i>
                            <span class="font-medium">{{ session('success') }}</span>
                        </div>
                        <button @click="show = false"><i class="fa-solid fa-times"></i></button>
                    </div>
                @endif

                @yield('content')
                
            </main>

        </div>
    </div>

    @stack('scripts')
</body>
</html>
