@extends('layouts.admin')

@section('title', 'Quản lý Loại Sân')

@section('content')
<div class="p-4 lg:p-8 space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white font-heading">Quản Lý Loại Sân</h1>
            <p class="text-slate-500 text-sm mt-1">Danh sách các loại sân bóng có trong hệ thống</p>
        </div>
        <a href="{{ route('admin.field-types.create') }}" class="px-6 py-2.5 bg-primary text-white font-bold rounded-xl shadow-md hover:bg-secondary transition-all flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Thêm Loại Sân
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-600 p-4 rounded-xl font-medium flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-xl"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 text-red-600 p-4 rounded-xl font-medium flex items-center gap-3">
            <i class="fa-solid fa-circle-exclamation text-xl"></i> {{ session('error') }}
        </div>
    @endif

    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 text-sm font-bold uppercase tracking-wider">
                        <th class="px-6 py-4">Tên loại sân</th>
                        <th class="px-6 py-4">Sức chứa</th>
                        <th class="px-6 py-4">Mô tả</th>
                        <th class="px-6 py-4 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($fieldTypes as $type)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">{{ $type->name }}</td>
                        <td class="px-6 py-4">{{ $type->capacity }} người</td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ Str::limit($type->description, 50) }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.field-types.edit', $type) }}" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 hover:bg-blue-500 hover:text-white transition-colors flex items-center justify-center">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form action="{{ route('admin.field-types.destroy', $type) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-colors flex items-center justify-center">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-500">Chưa có dữ liệu loại sân.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
