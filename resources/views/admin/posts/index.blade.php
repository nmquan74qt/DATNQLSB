@extends('layouts.admin')

@section('header')
<div class="flex justify-between items-center bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 mb-6 mt-16 lg:mt-0">
    <div>
        <h1 class="text-2xl font-bold font-heading text-slate-900 dark:text-white">Quản lý Bài viết</h1>
        <p class="text-sm text-slate-500">Quản lý Blog và Tin tức (SEO)</p>
    </div>
    <a href="{{ route('admin.posts.create') }}" class="bg-primary hover:bg-blue-600 text-white font-bold px-4 py-2 rounded-xl shadow-md flex items-center gap-2 magnetic-btn">
        <i class="fa-solid fa-plus"></i> <span class="btn-text">Viết bài mới</span>
    </a>
</div>
@endsection

@section('content')

@if(session('success'))
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if(typeof showNotification === 'function') {
            showNotification("{{ session('success') }}", "success");
        } else {
            Swal.fire('Thành công', "{{ session('success') }}", 'success');
        }
    });
</script>
@endif

<div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-100 dark:border-slate-800 shadow-sm relative overflow-hidden group">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($posts as $post)
            <div class="bg-slate-50 dark:bg-slate-800 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-shadow border border-slate-100 dark:border-slate-700 flex flex-col">
                <div class="h-48 overflow-hidden relative">
                    @if($post->thumbnail)
                        <img src="{{ asset($post->thumbnail) }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center">
                            <i class="fa-solid fa-image text-4xl text-slate-400"></i>
                        </div>
                    @endif
                    <div class="absolute top-4 right-4">
                        @if($post->status == 'published')
                            <span class="bg-emerald-500 text-white text-xs font-bold px-2 py-1 rounded shadow">Đã xuất bản</span>
                        @else
                            <span class="bg-slate-500 text-white text-xs font-bold px-2 py-1 rounded shadow">Bản nháp</span>
                        @endif
                    </div>
                </div>
                <div class="p-5 flex-1 flex flex-col">
                    <h3 class="font-bold text-lg text-slate-900 dark:text-white mb-2 line-clamp-2">{{ $post->title }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-4 line-clamp-2 flex-1">{{ $post->excerpt }}</p>
                    <div class="flex justify-between items-center mt-auto pt-4 border-t border-slate-200 dark:border-slate-700">
                        <span class="text-xs text-slate-500">{{ $post->created_at->format('d/m/Y') }}</span>
                        <div class="flex gap-2">
                            <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 p-2 rounded-lg transition-colors"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center">
                <div class="w-20 h-20 mx-auto bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center text-3xl text-slate-300 dark:text-slate-600 mb-4">
                    <i class="fa-solid fa-newspaper"></i>
                </div>
                <h4 class="text-lg font-bold text-slate-800 dark:text-white mb-2">Chưa có bài viết nào</h4>
                <p class="text-sm text-slate-500">Hãy viết bài đầu tiên để thu hút khách hàng tìm kiếm trên Google.</p>
            </div>
        @endforelse
    </div>
    <div class="mt-6">
        {{ $posts->links() }}
    </div>
</div>
@endsection
