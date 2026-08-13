@extends('layouts.admin')

@section('header')
<div class="flex justify-between items-center bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 mb-6 mt-16 lg:mt-0">
    <div>
        <h1 class="text-2xl font-bold font-heading text-slate-900 dark:text-white">Sửa Bài Viết</h1>
        <p class="text-sm text-slate-500">Tạo nội dung cho Blog</p>
    </div>
    <a href="{{ route('admin.posts.index') }}" class="text-slate-500 hover:text-primary transition-colors flex items-center gap-2">
        <i class="fa-solid fa-arrow-left"></i> Quay lại
    </a>
</div>
@endsection

@section('content')
<div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-100 dark:border-slate-800 shadow-sm relative">
    <form action="{{ route('admin.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Tiêu đề bài viết</label>
                    <input type="text" name="title" value="{{ $post->title }}" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-lg font-bold focus:ring-2 focus:ring-primary" placeholder="Nhập tiêu đề...">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Mô tả ngắn (SEO Excerpt)</label>
                    <textarea name="excerpt" rows="3" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary" placeholder="Đoạn mô tả ngắn hiển thị trên Google và trang chủ...">{{ $post->excerpt }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Nội dung bài viết (HTML)</label>
                    <textarea name="content" rows="15" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 font-mono text-sm focus:ring-2 focus:ring-primary" placeholder="<p>Nhập nội dung HTML ở đây...</p>">{{ $post->content }}</textarea>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-slate-50 dark:bg-slate-800 p-5 rounded-2xl">
                    <h3 class="font-bold text-slate-900 dark:text-white mb-4">Xuất bản</h3>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Trạng thái</label>
                        <select name="status" class="w-full bg-white dark:bg-slate-900 border-none rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary">
                            <option value="published" {{ $post->status == 'published' ? 'selected' : '' }}>Xuất bản ngay</option>
                            <option value="draft" {{ $post->status == 'draft' ? 'selected' : '' }}>Lưu nháp</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-primary hover:bg-blue-600 text-white font-bold py-3 rounded-xl transition-colors shadow-md shadow-primary/30">
                        <i class="fa-solid fa-save mr-2"></i> Lưu Bài Viết
                    </button>
                </div>

                <div class="bg-slate-50 dark:bg-slate-800 p-5 rounded-2xl">
                    <h3 class="font-bold text-slate-900 dark:text-white mb-4">Ảnh đại diện (Thumbnail)</h3>
                    
                    <div class="w-full h-40 bg-white dark:bg-slate-900 rounded-xl border-2 border-dashed border-slate-300 dark:border-slate-700 flex flex-col items-center justify-center text-slate-400 relative overflow-hidden group">
                        <i class="fa-solid fa-cloud-arrow-up text-3xl mb-2"></i>
                        <span class="text-xs">Click hoặc kéo thả ảnh</span>
                        <input type="file" name="thumbnail" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full" id="thumbnail-input" onchange="previewImage(this)">
                        <img id="thumbnail-preview" src="{{ $post->thumbnail ? asset('storage/'.$post->thumbnail) : '' }}" class="absolute inset-0 w-full h-full object-cover {{ $post->thumbnail ? '' : 'hidden' }}">
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('thumbnail-preview');
                img.src = e.target.result;
                img.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
