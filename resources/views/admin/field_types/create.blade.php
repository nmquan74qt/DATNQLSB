@extends('layouts.admin')

@section('title', 'Thêm Loại Sân Mới')

@section('content')
<div class="p-4 lg:p-8 space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.field-types.index') }}" class="text-slate-500 hover:text-primary transition-colors">
            <i class="fa-solid fa-arrow-left text-xl"></i>
        </a>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white font-heading">Thêm Loại Sân Mới</h1>
    </div>

    @if($errors->any())
        <div class="bg-red-50 text-red-600 p-4 rounded-xl font-medium">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 p-6 max-w-2xl">
        <form action="{{ route('admin.field-types.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Tên loại sân (VD: Sân 5 người)</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary transition-all">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Sức chứa (Số người)</label>
                <input type="number" name="capacity" value="{{ old('capacity', 5) }}" required min="1" class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary transition-all">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Mô tả chi tiết</label>
                <textarea name="description" rows="4" class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary transition-all">{{ old('description') }}</textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-700">
                <a href="{{ route('admin.field-types.index') }}" class="px-6 py-2.5 rounded-xl font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">Hủy</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl font-bold text-white bg-primary hover:bg-secondary shadow-sm transition-colors">Lưu Lại</button>
            </div>
        </form>
    </div>
</div>
@endsection
