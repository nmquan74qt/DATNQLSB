@extends('layouts.app')

@section('title', 'Khám Phá Sân Bóng')

@section('content')
    <!-- Header with Animated Blobs & Parallax -->
    <div class="relative pt-32 pb-20 overflow-hidden bg-white border-b border-slate-100">
        <div class="absolute inset-0 z-0 parallax-bg">
            <img src="https://images.unsplash.com/photo-1518605363189-9854359db5a3?auto=format&fit=crop&w=2070&q=80" class="w-full h-full object-cover opacity-10" alt="Background" />
        </div>
        
        <!-- Animated Blobs -->
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-primary rounded-full mix-blend-multiply filter blur-[100px] opacity-10 animate-blob"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-secondary rounded-full mix-blend-multiply filter blur-[100px] opacity-10 animate-blob animation-delay-2000"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center" data-aos="fade-up">
            <h1 class="text-5xl md:text-6xl font-heading font-extrabold text-slate-900 mb-6 tracking-tight hero-title">
                Khám Phá Sân Bóng <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary">Premium</span>
            </h1>
            <p class="text-xl text-slate-600 max-w-2xl mx-auto font-medium">Hệ thống sân bãi đạt chuẩn quốc tế, trang thiết bị hiện đại, đáp ứng mọi nhu cầu từ giải trí đến thi đấu chuyên nghiệp.</p>
        </div>
    </div>

    <div class="bg-slate-50 dark:bg-slate-900 py-16 transition-colors duration-300 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-12 gap-8">
                
                <!-- Advanced Filter Sidebar (Grid 3/12) -->
                <div class="col-span-12 lg:col-span-3 space-y-6" data-aos="fade-right">
                    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm sticky top-24">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6 border-b border-slate-100 dark:border-slate-700 pb-4 font-heading">Bộ Lọc Tìm Kiếm</h3>
                        
                        <form action="{{ route('fields.index') }}" method="GET" class="space-y-6">
                            <!-- Search -->
                            <div>
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Từ khóa</label>
                                <div class="relative">
                                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="text" name="q" placeholder="Tên sân..." class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-xl pl-10 pr-4 py-3 text-sm focus:ring-2 focus:ring-primary/50 text-slate-700 dark:text-slate-200 shadow-inner transition-all">
                                </div>
                            </div>

                            <!-- Field Type -->
                            <div>
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-3">Loại Sân</label>
                                <div class="space-y-2">
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <input type="radio" name="type" value="" class="w-4 h-4 text-primary bg-slate-100 border-slate-300 focus:ring-primary dark:focus:ring-primary dark:ring-offset-slate-800 focus:ring-2 dark:bg-slate-700 dark:border-slate-600">
                                        <span class="text-sm font-medium text-slate-600 dark:text-slate-400 group-hover:text-primary transition-colors">Tất cả</span>
                                    </label>
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <input type="radio" name="type" value="5" class="w-4 h-4 text-primary bg-slate-100 border-slate-300 focus:ring-primary dark:focus:ring-primary dark:ring-offset-slate-800 focus:ring-2 dark:bg-slate-700 dark:border-slate-600">
                                        <span class="text-sm font-medium text-slate-600 dark:text-slate-400 group-hover:text-primary transition-colors">Sân 5 Người</span>
                                    </label>
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <input type="radio" name="type" value="7" class="w-4 h-4 text-primary bg-slate-100 border-slate-300 focus:ring-primary dark:focus:ring-primary dark:ring-offset-slate-800 focus:ring-2 dark:bg-slate-700 dark:border-slate-600">
                                        <span class="text-sm font-medium text-slate-600 dark:text-slate-400 group-hover:text-primary transition-colors">Sân 7 Người</span>
                                    </label>
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <input type="radio" name="type" value="11" class="w-4 h-4 text-primary bg-slate-100 border-slate-300 focus:ring-primary dark:focus:ring-primary dark:ring-offset-slate-800 focus:ring-2 dark:bg-slate-700 dark:border-slate-600">
                                        <span class="text-sm font-medium text-slate-600 dark:text-slate-400 group-hover:text-primary transition-colors">Sân 11 Người</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Price Range -->
                            <div>
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-3">Mức Giá / Giờ</label>
                                <input type="range" min="100000" max="2000000" step="50000" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer dark:bg-slate-700 accent-primary">
                                <div class="flex justify-between mt-2 text-xs font-bold text-slate-500">
                                    <span>100k</span>
                                    <span>2tr+</span>
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-primary text-white font-bold py-3 rounded-xl shadow-md shadow-primary/30 hover:shadow-primary/50 transition-all hover:-translate-y-1 mt-4 magnetic-btn overflow-hidden relative">
                                <span class="relative z-10 btn-text flex items-center justify-center gap-2"><i class="fa-solid fa-filter"></i> Áp Dụng Bộ Lọc</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Fields Grid (Grid 9/12) -->
                <div class="col-span-12 lg:col-span-9">
                    
                    <!-- Toolbar -->
                    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-sm mb-6 flex justify-between items-center" data-aos="fade-up">
                        <p class="text-sm font-bold text-slate-600 dark:text-slate-400">Hiển thị <span class="text-primary">{{ $fields->count() }}</span> sân bóng</p>
                        <select class="bg-slate-50 dark:bg-slate-900 border-none rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-primary/50 text-slate-700 dark:text-slate-200 font-medium">
                            <option value="newest">Mới nhất</option>
                            <option value="price_asc">Giá: Thấp đến Cao</option>
                            <option value="price_desc">Giá: Cao đến Thấp</option>
                        </select>
                    </div>

                    <!-- Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @forelse($fields as $index => $field)
                            <!-- Field Card (Glass Reflection Effect) -->
                            <div class="group bg-white dark:bg-slate-800 rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 relative" data-aos="fade-up" data-aos-delay="{{ ($index % 3) * 100 }}">
                                
                                <!-- Glass Reflection overlay -->
                                <div class="absolute inset-0 bg-gradient-to-tr from-white/0 via-white/30 to-white/0 opacity-0 group-hover:opacity-100 transition-opacity duration-700 z-20 pointer-events-none transform -translate-x-full group-hover:translate-x-full" style="transition-property: transform, opacity;"></div>
                                
                                <div class="relative h-56 overflow-hidden interactive">
                                    <img src="https://images.unsplash.com/photo-1518605363189-9854359db5a3?auto=format&fit=crop&w=800&q=80" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="{{ $field->name }}">
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent opacity-80 group-hover:opacity-60 transition-opacity duration-300"></div>
                                    
                                    <!-- Badges -->
                                    <div class="absolute top-4 right-4 flex gap-2">
                                        @if($field->status === 'available')
                                            <div class="bg-emerald-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg shadow-emerald-500/30">Trống</div>
                                        @else
                                            <div class="bg-amber-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg shadow-amber-500/30">Đã đặt</div>
                                        @endif
                                    </div>
                                    
                                    <div class="absolute bottom-4 left-4 text-white z-10">
                                        <span class="bg-white/20 backdrop-blur-md px-3 py-1 rounded-lg text-xs font-bold border border-white/30 uppercase tracking-wider">{{ $field->fieldType->name }}</span>
                                    </div>
                                </div>
                                <div class="p-6 relative z-10">
                                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2 font-heading group-hover:text-primary transition-colors line-clamp-1">{{ $field->name }}</h3>
                                    
                                    <!-- Meta -->
                                    <div class="flex items-center gap-4 text-xs font-medium text-slate-500 dark:text-slate-400 mb-4">
                                        <span class="flex items-center gap-1"><i class="fa-solid fa-users text-primary"></i> Sức chứa: {{ $field->fieldType->capacity }}</span>
                                        <span class="flex items-center gap-1"><i class="fa-solid fa-star text-warning"></i> 4.9 (120)</span>
                                    </div>

                                    <div class="flex items-center justify-between mt-6 pt-4 border-t border-slate-100 dark:border-slate-700">
                                        <div>
                                            <span class="text-xs text-slate-400 block uppercase font-bold tracking-wider">Giá thuê</span>
                                            <span class="text-xl font-extrabold text-slate-900 dark:text-white">{{ number_format($field->base_price) }}đ<span class="text-sm font-medium text-slate-500">/h</span></span>
                                        </div>
                                        <a href="{{ route('field.detail', $field->slug ?? $field->id) }}" class="bg-slate-100 dark:bg-slate-700 hover:bg-primary hover:text-white text-slate-700 dark:text-slate-200 w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-300 shadow-sm hover:shadow-primary/30 group-hover:w-12">
                                            <i class="fa-solid fa-arrow-right transition-transform group-hover:translate-x-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-12 py-20 text-center">
                                <!-- Empty State -->
                                <div class="w-32 h-32 mx-auto bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center text-4xl text-slate-400 mb-6">
                                    <i class="fa-regular fa-face-frown"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-slate-800 dark:text-white mb-2 font-heading">Không tìm thấy sân bóng</h3>
                                <p class="text-slate-500 max-w-md mx-auto">Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm của bạn để tìm được sân ưng ý nhé.</p>
                                <a href="{{ route('fields.index') }}" class="mt-6 inline-block bg-primary text-white font-bold px-6 py-3 rounded-xl shadow-md">Xóa bộ lọc</a>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($fields->hasPages())
                        <div class="mt-10" data-aos="fade-up">
                            {{ $fields->links('pagination::tailwind') }}
                        </div>
                    @endif
                </div>
            </div>
            
        </div>
    </div>
@endsection

@push('scripts')
<style>
/* CSS cho hiệu ứng Blob */
@keyframes blob {
  0% { transform: translate(0px, 0px) scale(1); }
  33% { transform: translate(30px, -50px) scale(1.1); }
  66% { transform: translate(-20px, 20px) scale(0.9); }
  100% { transform: translate(0px, 0px) scale(1); }
}
.animate-blob {
  animation: blob 7s infinite;
}
.animation-delay-2000 {
  animation-delay: 2s;
}
</style>
@endpush
