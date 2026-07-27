@extends('layouts.app')

@section('title', 'Tin Tức & Sự Kiện')

@section('content')
    <!-- Header Parallax -->
    <div class="relative pt-32 pb-20 overflow-hidden bg-slate-900 group">
        <div class="absolute inset-0 z-0 parallax-bg">
            <img src="https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?w=2070&q=80" class="w-full h-full object-cover opacity-30 group-hover:scale-105 transition-transform duration-1000" alt="News Background" />
        </div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center" data-aos="fade-up">
            <h1 class="text-5xl md:text-6xl font-heading font-extrabold text-white mb-6 hero-title">
                Tin Tức & <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary">Sự Kiện</span>
            </h1>
            <p class="text-xl text-slate-300 max-w-2xl mx-auto">Cập nhật những thông tin mới nhất về giải đấu, chính sách và chương trình khuyến mãi.</p>
        </div>
    </div>

    <!-- Blog Grid -->
    <div class="bg-slate-50 dark:bg-slate-900 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($posts as $index => $post)
                    <div class="bg-white dark:bg-slate-800 rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 group" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                        
                        <!-- Thumbnail with Image Optimization simulation -->
                        <div class="relative h-60 overflow-hidden">
                            <!-- Skeleton loader placeholder (Lazy load simulation) -->
                            <div class="absolute inset-0 bg-slate-200 dark:bg-slate-700 animate-pulse z-0"></div>
                            
                            <img src="{{ $post->thumbnail }}" class="w-full h-full object-cover relative z-10 transition-transform duration-700 group-hover:scale-110" loading="lazy" alt="{{ $post->title }}">
                            
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent opacity-60"></div>
                            <div class="absolute bottom-4 left-4 z-20">
                                <span class="bg-primary text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-md">Tin tức</span>
                            </div>
                        </div>

                        <div class="p-8">
                            <div class="flex items-center gap-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">
                                <span><i class="fa-regular fa-calendar"></i> {{ \Carbon\Carbon::parse($post->created_at)->format('d/m/Y') }}</span>
                            </div>
                            
                            <h3 class="text-xl font-bold font-heading text-slate-900 dark:text-white mb-4 group-hover:text-primary transition-colors line-clamp-2">
                                {{ $post->title }}
                            </h3>
                            
                            <p class="text-slate-500 text-sm line-clamp-3 mb-6">
                                {{ $post->excerpt }}
                            </p>
                            
                            <a href="javascript:void(0);" class="inline-flex items-center gap-2 font-bold text-primary group-hover:text-blue-600 transition-colors">
                                Đọc tiếp <i class="fa-solid fa-arrow-right transform group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-12 flex justify-center">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
@endsection
