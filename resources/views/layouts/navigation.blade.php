<nav class="fixed top-0 left-0 w-full z-50 bg-white/90 backdrop-blur-md shadow-sm border-b border-slate-100 transition-all duration-300" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-3">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center text-white shadow-md shadow-primary/30 group-hover:scale-105 transition-transform">
                        <i class="fa-solid fa-futbol text-xl"></i>
                    </div>
                    <span class="font-heading font-extrabold text-2xl tracking-tight text-slate-900">
                        Pitch<span class="text-primary">Manage</span>
                    </span>
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex md:items-center md:space-x-8">
                <a href="{{ route('home') }}" class="font-medium text-slate-600 hover:text-primary transition-colors">Trang Chủ</a>
                <a href="{{ route('fields.index') }}" class="font-medium text-slate-600 hover:text-primary transition-colors">Đặt Sân</a>
                <a href="{{ route('fields.index') }}" class="font-medium text-slate-600 hover:text-primary transition-colors">Bảng Giá</a>
                <a href="{{ route('blog.index') }}" class="font-medium text-slate-600 hover:text-primary transition-colors">Tin Tức</a>
            </div>

            <!-- Auth -->
            <div class="hidden md:flex items-center space-x-4">
                @auth
                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" class="flex items-center gap-2 focus:outline-none group bg-slate-50 hover:bg-slate-100 px-2 py-1.5 rounded-full border border-slate-100 transition-colors">
                            <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=10B981&color=fff' }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover">
                            <span class="font-bold text-sm text-slate-700 hidden lg:block">{{ auth()->user()->name }}</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 mr-2 transition-transform" :class="{'rotate-180': open}"></i>
                        </button>
                        <!-- Dropdown menu -->
                        <div x-show="open" x-transition.origin.top.right x-cloak class="absolute right-0 mt-3 w-48 bg-white rounded-xl shadow-lg border border-slate-100 py-1 overflow-hidden z-50">
                            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'staff')
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-primary/5 hover:text-primary transition-colors"><i class="fa-solid fa-chart-line w-5"></i> Trang Quản Trị</a>
                            @else
                                <a href="{{ route('customer.dashboard') }}" class="block px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-primary/5 hover:text-primary transition-colors"><i class="fa-regular fa-calendar-check w-5"></i> Lịch Đặt Của Tôi</a>
                            @endif
                            <a href="#" class="block px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-primary/5 hover:text-primary transition-colors"><i class="fa-regular fa-user w-5"></i> Tài Khoản</a>
                            <div class="h-px bg-slate-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-sm font-medium text-red-500 hover:bg-red-50 transition-colors"><i class="fa-solid fa-arrow-right-from-bracket w-5"></i> Đăng Xuất</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="font-bold text-slate-600 hover:text-primary transition-colors px-4 py-2">Đăng Nhập</a>
                    <a href="{{ route('register') }}" class="bg-primary hover:bg-secondary text-white px-5 py-2.5 rounded-xl font-bold shadow-md shadow-primary/20 transition-all transform hover:-translate-y-0.5">Đăng Ký</a>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-2xl text-slate-700 focus:outline-none p-2">
                    <i class="fa-solid fa-bars" x-show="!mobileMenuOpen"></i>
                    <i class="fa-solid fa-times" x-show="mobileMenuOpen" x-cloak></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Panel -->
    <div x-show="mobileMenuOpen" x-transition.opacity x-cloak class="md:hidden bg-white border-t border-slate-100 absolute w-full shadow-xl">
        <div class="px-4 pt-2 pb-6 space-y-1">
            <a href="{{ route('home') }}" class="block px-3 py-3 rounded-xl text-base font-bold text-slate-700 hover:bg-slate-50 hover:text-primary">Trang Chủ</a>
            <a href="{{ route('fields.index') }}" class="block px-3 py-3 rounded-xl text-base font-bold text-slate-600 hover:bg-slate-50 hover:text-primary">Đặt Sân</a>
            <a href="{{ route('blog.index') }}" class="block px-3 py-3 rounded-xl text-base font-bold text-slate-600 hover:bg-slate-50 hover:text-primary">Tin Tức</a>
            
            <div class="h-px bg-slate-100 my-2"></div>
            
            @auth
                <div class="px-3 py-3 flex items-center gap-3">
                    <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name) }}" alt="Avatar" class="w-10 h-10 rounded-full border border-slate-200">
                    <div>
                        <div class="font-bold text-slate-900">{{ auth()->user()->name }}</div>
                        <div class="text-sm font-medium text-slate-500">{{ auth()->user()->email }}</div>
                    </div>
                </div>
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'staff')
                    <a href="{{ route('admin.dashboard') }}" class="block px-3 py-3 rounded-xl text-base font-bold text-slate-600 hover:bg-slate-50 hover:text-primary"><i class="fa-solid fa-chart-line w-6"></i> Trang Quản Trị</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-3 rounded-xl text-base font-bold text-red-500 hover:bg-red-50"><i class="fa-solid fa-arrow-right-from-bracket w-6"></i> Đăng Xuất</button>
                </form>
            @else
                <div class="grid grid-cols-2 gap-4 mt-4 px-3">
                    <a href="{{ route('login') }}" class="text-center px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-700 rounded-xl font-bold">Đăng Nhập</a>
                    <a href="{{ route('register') }}" class="text-center px-4 py-2.5 bg-primary text-white rounded-xl font-bold shadow-sm">Đăng Ký</a>
                </div>
            @endauth
        </div>
    </div>
</nav>
