@extends('layouts.admin')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-heading font-bold text-slate-800 dark:text-white">Quản Lý Sân Bóng</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Xem, thêm, sửa, xóa thông tin sân bóng</p>
        </div>
        <a href="{{ route('admin.fields.create') }}" class="bg-primary text-white px-4 py-2.5 rounded-xl text-sm font-bold shadow-md shadow-primary/30 hover:shadow-primary/50 transition-all hover:-translate-y-0.5 flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Thêm Sân Mới
        </a>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-100 dark:border-slate-700 shadow-sm mb-6 flex flex-col md:flex-row gap-4 justify-between items-center">
        <div class="flex flex-wrap gap-2 w-full md:w-auto">
            <select class="bg-slate-50 dark:bg-slate-700 border-none rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-primary/50 text-slate-700 dark:text-slate-200">
                <option value="">Tất cả loại sân</option>
                <option value="5">Sân 5 người</option>
                <option value="7">Sân 7 người</option>
                <option value="11">Sân 11 người</option>
            </select>
            <select class="bg-slate-50 dark:bg-slate-700 border-none rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-primary/50 text-slate-700 dark:text-slate-200">
                <option value="">Trạng thái</option>
                <option value="available">Trống</option>
                <option value="booked">Đã đặt</option>
                <option value="maintenance">Bảo trì</option>
            </select>
        </div>
        <div class="relative w-full md:w-64">
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input type="text" placeholder="Tìm tên sân..." class="w-full bg-slate-50 dark:bg-slate-700 border-none rounded-xl pl-10 pr-4 py-2 text-sm focus:ring-2 focus:ring-primary/50 text-slate-700 dark:text-slate-200">
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-700/50 border-b border-slate-100 dark:border-slate-700">
                        <th class="py-4 px-6 font-bold text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400">Tên Sân</th>
                        <th class="py-4 px-6 font-bold text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400">Loại</th>
                        <th class="py-4 px-6 font-bold text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400">Giá Cơ Bản (h)</th>
                        <th class="py-4 px-6 font-bold text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400">Trạng Thái</th>
                        <th class="py-4 px-6 font-bold text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($fields as $field)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 flex items-center justify-center flex-shrink-0">
                                        <i class="fa-solid fa-layer-group"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 dark:text-white">{{ $field->name }}</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate max-w-[200px]">{{ $field->description ?? 'Không có mô tả' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-bold px-3 py-1 rounded-full">
                                    {{ $field->fieldType->name }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-bold text-primary">{{ number_format($field->base_price) }}đ</span>
                            </td>
                            <td class="py-4 px-6">
                                @if($field->status == 'available')
                                    <span class="bg-emerald-100 text-emerald-600 text-xs font-bold px-3 py-1 rounded-full"><i class="fa-solid fa-check mr-1"></i> Trống</span>
                                @elseif($field->status == 'booked')
                                    <span class="bg-amber-100 text-amber-600 text-xs font-bold px-3 py-1 rounded-full"><i class="fa-solid fa-calendar mr-1"></i> Đã Đặt</span>
                                @elseif($field->status == 'in_use')
                                    <span class="bg-blue-100 text-blue-600 text-xs font-bold px-3 py-1 rounded-full"><i class="fa-solid fa-play mr-1"></i> Đang Đá</span>
                                @else
                                    <span class="bg-red-100 text-red-600 text-xs font-bold px-3 py-1 rounded-full"><i class="fa-solid fa-wrench mr-1"></i> Bảo Trì</span>
                                @endif
                                
                                @if(!$field->is_active)
                                    <span class="bg-slate-200 text-slate-600 text-xs font-bold px-3 py-1 rounded-full ml-1">Đã Khóa</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-100 md:opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.fields.edit', $field->id) }}" class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-500 flex items-center justify-center hover:bg-blue-500 hover:text-white transition-colors" title="Sửa">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <form action="{{ route('admin.fields.destroy', $field->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sân này?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 dark:bg-red-900/30 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-colors" title="Xóa">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-500 dark:text-slate-400">
                                <div class="text-4xl mb-4 text-slate-300 dark:text-slate-600"><i class="fa-solid fa-box-open"></i></div>
                                <p>Chưa có dữ liệu sân bóng nào.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        @if($fields->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700">
            {{ $fields->links('pagination::tailwind') }}
        </div>
        @endif
    </div>
@endsection
