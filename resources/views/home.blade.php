@extends('layouts.app')

@section('title', 'Đặt Sân Bóng Nhanh Chóng')

@section('content')
    <!-- Hero Banner with Bright Background -->
    <div class="relative w-full h-[85vh] flex items-center justify-center bg-white">
        <!-- Bright Background Image -->
        <div class="absolute inset-0 w-full h-full z-0 overflow-hidden">
            <img src="https://images.pexels.com/photos/114296/pexels-photo-114296.jpeg?auto=compress&cs=tinysrgb&w=2070&h=1000&dpr=1" class="w-full h-full object-cover opacity-20 scale-105" alt="Background" />
            <div class="absolute inset-0 bg-gradient-to-b from-white/90 via-white/70 to-bg-light"></div>
        </div>

        <!-- Particles / Accents -->
        <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-primary rounded-full mix-blend-multiply filter blur-[128px] opacity-10 animate-float"></div>
            <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-secondary rounded-full mix-blend-multiply filter blur-[128px] opacity-10 animate-float" style="animation-delay: 2s;"></div>
        </div>

        <!-- Hero Content -->
        <div class="relative z-10 text-center px-4 max-w-5xl mx-auto" data-aos="zoom-out" data-aos-duration="1000">
            <span class="inline-block py-1 px-3 rounded-full bg-primary/10 border border-primary/20 text-primary text-sm font-bold mb-6 animate-slide-up" style="animation-delay: 0.1s;">
                <i class="fa-solid fa-star text-warning mr-1"></i> Hệ thống Đặt Sân Chuyên Nghiệp Nhất
            </span>
            <h1 class="text-5xl md:text-7xl font-heading font-extrabold text-slate-900 mb-6 tracking-tight leading-tight animate-slide-up" style="animation-delay: 0.2s;">
                Đam Mê Bùng Cháy <br/>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary">Đặt Sân Bóng Nhanh Chóng</span>
            </h1>
            <p class="text-lg md:text-xl text-slate-600 mb-10 max-w-2xl mx-auto font-medium animate-slide-up" style="animation-delay: 0.3s;">
                Trải nghiệm tiện ích đặt sân cao cấp, kiểm tra lịch trống theo thời gian thực và quản lý dễ dàng chỉ với vài cú click chuột.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 animate-slide-up" style="animation-delay: 0.4s;">
                <a href="{{ route('fields.index') }}" class="px-8 py-4 rounded-xl bg-primary hover:bg-secondary text-white font-bold text-lg shadow-lg shadow-primary/30 transition-all duration-300 transform hover:-translate-y-1 w-full sm:w-auto">
                    <i class="fa-solid fa-calendar-check mr-2"></i> Đặt Ngay
                </a>
                <a href="{{ route('fields.index') }}" class="px-8 py-4 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-bold text-lg shadow-sm transition-all duration-300 transform hover:-translate-y-1 w-full sm:w-auto">
                    <i class="fa-solid fa-tags mr-2"></i> Xem Bảng Giá
                </a>
            </div>
        </div>

        <!-- Floating Search Bar -->
        <div id="search-section" class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-1/2 w-full max-w-4xl px-4 z-20" data-aos="fade-up" data-aos-delay="600">
            <div class="glassmorphism rounded-2xl p-6 sm:p-4 border-2 border-white/50 dark:border-slate-700/50 shadow-[0_20px_50px_-12px_rgba(0,0,0,0.25)]">
                <form action="{{ route('fields.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 px-1">Loại Sân</label>
                        <select class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-primary/50 font-medium transition-all">
                            <option value="">Tất cả</option>
                            <option value="5">Sân 5 Người</option>
                            <option value="7">Sân 7 Người</option>
                            <option value="11">Sân 11 Người</option>
                        </select>
                    </div>
                    <div class="flex-1">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 px-1">Ngày Đặt</label>
                        <input type="date" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-primary/50 font-medium transition-all">
                    </div>
                    <div class="flex-1">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 px-1">Khung Giờ</label>
                        <select class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-primary/50 font-medium transition-all">
                            <option value="">Tất cả khung giờ</option>
                            <option value="morning">Sáng (06:00 - 12:00)</option>
                            <option value="afternoon">Chiều (12:00 - 18:00)</option>
                            <option value="evening">Tối (18:00 - 23:00)</option>
                        </select>
                    </div>
                    <div class="sm:flex items-end">
                        <button type="submit" class="w-full sm:w-auto px-8 py-3 rounded-xl bg-secondary hover:bg-emerald-500 text-white font-bold shadow-lg shadow-secondary/30 transition-all duration-300 hover:scale-105 h-[48px] flex items-center justify-center">
                            <i class="fa-solid fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="pt-32 pb-16 px-4 bg-bg-light dark:bg-slate-900 relative">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <!-- Stat Card 1 -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 text-center border border-slate-100 dark:border-slate-700 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 group" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-16 h-16 mx-auto bg-blue-50 dark:bg-blue-900/30 text-primary rounded-2xl flex items-center justify-center text-2xl mb-4 group-hover:scale-110 group-hover:rotate-6 transition-transform">
                        <i class="fa-solid fa-futbol"></i>
                    </div>
                    <h3 class="text-3xl font-heading font-extrabold text-slate-900 dark:text-white mb-1">12</h3>
                    <p class="text-slate-500 font-medium">Sân Đang Hoạt Động</p>
                </div>
                
                <!-- Stat Card 2 -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 text-center border border-slate-100 dark:border-slate-700 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 group" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 mx-auto bg-emerald-50 dark:bg-emerald-900/30 text-secondary rounded-2xl flex items-center justify-center text-2xl mb-4 group-hover:scale-110 group-hover:-rotate-6 transition-transform">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                    <h3 class="text-3xl font-heading font-extrabold text-slate-900 dark:text-white mb-1">145</h3>
                    <p class="text-slate-500 font-medium">Lượt Đặt Hôm Nay</p>
                </div>

                <!-- Stat Card 3 -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 text-center border border-slate-100 dark:border-slate-700 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 group" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-16 h-16 mx-auto bg-purple-50 dark:bg-purple-900/30 text-purple-600 rounded-2xl flex items-center justify-center text-2xl mb-4 group-hover:scale-110 group-hover:rotate-6 transition-transform">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <h3 class="text-3xl font-heading font-extrabold text-slate-900 dark:text-white mb-1">5k+</h3>
                    <p class="text-slate-500 font-medium">Khách Hàng</p>
                </div>

                <!-- Stat Card 4 -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 text-center border border-slate-100 dark:border-slate-700 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 group" data-aos="fade-up" data-aos-delay="400">
                    <div class="w-16 h-16 mx-auto bg-amber-50 dark:bg-amber-900/30 text-warning rounded-2xl flex items-center justify-center text-2xl mb-4 group-hover:scale-110 group-hover:-rotate-6 transition-transform">
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <h3 class="text-3xl font-heading font-extrabold text-slate-900 dark:text-white mb-1">4.9</h3>
                    <p class="text-slate-500 font-medium">Đánh Giá Trung Bình</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Fields -->
    <div class="py-20 bg-white dark:bg-slate-800 border-t border-slate-100 dark:border-slate-700">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="text-primary font-bold tracking-wider uppercase text-sm">Chất lượng hàng đầu</span>
                <h2 class="text-4xl md:text-5xl font-heading font-bold text-slate-900 dark:text-white mt-2 mb-4">Các Sân Bóng Nổi Bật</h2>
                <div class="w-24 h-1.5 bg-gradient-to-r from-primary to-secondary mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($fields as $index => $field)
                <!-- Field Card -->
                <div class="group bg-white dark:bg-slate-900 rounded-2xl overflow-hidden border border-slate-100 dark:border-slate-700 shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 relative" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
                    <div class="relative h-64 overflow-hidden">
                        <img src="{{ $field->image_url ?? 'https://images.pexels.com/photos/114296/pexels-photo-114296.jpeg?auto=compress&cs=tinysrgb&w=800&h=600&dpr=1' }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="{{ $field->name }}">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-80"></div>
                        <div class="absolute top-4 right-4 {{ $field->status == 'available' ? 'bg-emerald-500' : 'bg-red-500' }} text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                            @if($field->status == 'available')
                                <i class="fa-solid fa-check-circle mr-1"></i> Hoạt Động
                            @else
                                <i class="fa-solid fa-lock mr-1"></i> Bảo Trì
                            @endif
                        </div>
                        <div class="absolute bottom-4 left-4 text-white">
                            <span class="bg-white/20 backdrop-blur-md px-3 py-1 rounded-lg text-sm font-medium border border-white/30">{{ $field->fieldType->name ?? 'Sân' }}</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-2 font-heading">{{ $field->name }}</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm mb-4 line-clamp-2">{{ $field->description ?? 'Sân bóng chất lượng cao, trang bị hiện đại.' }}</p>
                        
                        <div class="flex items-center justify-between mt-6">
                            <div>
                                <span class="text-xs text-slate-400 block uppercase font-bold tracking-wider">Trạng thái</span>
                                <span class="text-sm font-bold {{ $field->status == 'available' ? 'text-emerald-500' : 'text-red-500' }}">{{ $field->status == 'available' ? 'Sẵn sàng' : 'Không khả dụng' }}</span>
                            </div>
                            <a href="{{ route('field.detail', $field->slug ?? $field->id) }}" class="bg-slate-100 dark:bg-slate-800 hover:bg-primary hover:text-white text-slate-700 dark:text-slate-200 w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-300 group-hover:w-32 shadow-sm group-hover:shadow-primary/30 group-hover:bg-primary group-hover:text-white relative overflow-hidden">
                                <i class="fa-solid fa-arrow-right absolute transition-all duration-300 group-hover:opacity-0 group-hover:translate-x-8"></i>
                                <span class="absolute opacity-0 font-bold whitespace-nowrap transition-all duration-300 -translate-x-8 group-hover:opacity-100 group-hover:translate-x-0">Đặt Ngay</span>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                    <div class="col-span-3 text-center py-12 text-slate-500">
                        Chưa có sân bóng nào trên hệ thống.
                    </div>
                @endforelse
            </div>
            
            <div class="text-center mt-12" data-aos="fade-up">
                <a href="{{ route('fields.index') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-xl border-2 border-primary text-primary hover:bg-primary hover:text-white font-bold transition-all shadow-sm hover:shadow-primary/30">
                    Xem Tất Cả Các Sân <i class="fa-solid fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </div>
@endsection
