@extends('layouts.admin')

@section('title', 'Quản lý Bảng giá & Khung giờ')

@section('content')
<div class="p-4 lg:p-8 space-y-6" x-data="timeSlotManager()">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white font-heading">Bảng Giá & Khung Giờ</h1>
            <p class="text-slate-500 text-sm mt-1">Quản lý thời gian hoạt động và phụ thu (Giờ vàng)</p>
        </div>
        <button @click="openCreateModal" class="px-6 py-2.5 bg-primary text-white font-bold rounded-xl shadow-md hover:bg-secondary transition-all flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Thêm Khung Giờ
        </button>
    </div>

    <!-- Alert -->
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

    <!-- Danh sách Khung giờ -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 text-sm font-bold uppercase tracking-wider">
                        <th class="px-6 py-4">Giờ Bắt Đầu</th>
                        <th class="px-6 py-4">Giờ Kết Thúc</th>
                        <th class="px-6 py-4">Phụ thu / Giảm giá</th>
                        <th class="px-6 py-4">Trạng thái</th>
                        <th class="px-6 py-4 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($timeSlots as $slot)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-800 dark:text-white">
                            {{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }}
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-800 dark:text-white">
                            {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}
                        </td>
                        <td class="px-6 py-4">
                            @if($slot->price_modifier > 0)
                                <span class="text-red-500 font-bold">+{{ number_format($slot->price_modifier) }}đ</span>
                            @elseif($slot->price_modifier < 0)
                                <span class="text-emerald-500 font-bold">{{ number_format($slot->price_modifier) }}đ</span>
                            @else
                                <span class="text-slate-500">Giữ nguyên</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($slot->is_active)
                                <span class="bg-emerald-100 text-emerald-600 px-3 py-1 rounded-full text-xs font-bold">Hoạt động</span>
                            @else
                                <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-xs font-bold">Tạm ẩn</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button @click="openEditModal({{ $slot->toJson() }})" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 hover:bg-blue-500 hover:text-white transition-colors flex items-center justify-center" title="Sửa">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <form action="{{ route('admin.time-slots.destroy', $slot) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa khung giờ này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-colors flex items-center justify-center" title="Xóa">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                            Chưa có khung giờ nào được thiết lập.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Create / Edit -->
    <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" x-show="isModalOpen" x-transition.opacity></div>

        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="isModalOpen" x-transition.scale.origin.bottom class="relative transform overflow-hidden rounded-3xl bg-white dark:bg-slate-800 text-left shadow-2xl transition-all w-full max-w-lg border border-slate-100 dark:border-slate-700">
                
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/50">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white" id="modal-title" x-text="isEditing ? 'Sửa Khung Giờ' : 'Thêm Khung Giờ Mới'"></h3>
                    <button @click="closeModal()" class="text-slate-400 hover:text-slate-500 focus:outline-none p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                        <i class="fa-solid fa-times text-lg"></i>
                    </button>
                </div>

                <form :action="formAction" method="POST" class="p-6">
                    @csrf
                    <input type="hidden" name="_method" :value="isEditing ? 'PUT' : 'POST'">
                    
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Giờ bắt đầu</label>
                                <input type="time" name="start_time" x-model="formData.start_time" class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-xl px-4 py-3 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-primary/50 transition-all" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Giờ kết thúc</label>
                                <input type="time" name="end_time" x-model="formData.end_time" class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-xl px-4 py-3 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-primary/50 transition-all" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Phụ thu (VND)</label>
                            <input type="number" name="price_modifier" x-model="formData.price_modifier" class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-xl px-4 py-3 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-primary/50 transition-all" required>
                            <p class="text-xs text-slate-500 mt-1">VD: Nhập 20000 để thu thêm 20k vào giờ vàng. Nhập -20000 để giảm giá 20k.</p>
                        </div>

                        <div class="flex items-center gap-3 pt-2">
                            <input type="checkbox" name="is_active" id="is_active" x-model="formData.is_active" value="1" class="w-5 h-5 rounded text-primary focus:ring-primary">
                            <label for="is_active" class="font-medium text-slate-700 dark:text-slate-300">Hoạt động (Cho phép đặt)</label>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end gap-3">
                        <button type="button" @click="closeModal()" class="px-5 py-2.5 rounded-xl font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">Hủy</button>
                        <button type="submit" class="px-6 py-2.5 rounded-xl font-bold text-white bg-primary hover:bg-secondary shadow-sm shadow-primary/30 transition-colors" x-text="isEditing ? 'Cập Nhật' : 'Lưu Lại'"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function timeSlotManager() {
        return {
            isModalOpen: false,
            isEditing: false,
            formAction: '{{ route('admin.time-slots.store') }}',
            formData: {
                start_time: '',
                end_time: '',
                price_modifier: 0,
                is_active: true
            },
            openCreateModal() {
                this.isEditing = false;
                this.formAction = '{{ route('admin.time-slots.store') }}';
                this.formData = { start_time: '', end_time: '', price_modifier: 0, is_active: true };
                this.isModalOpen = true;
            },
            openEditModal(slot) {
                this.isEditing = true;
                this.formAction = '/admin/time-slots/' + slot.id;
                this.formData = {
                    start_time: slot.start_time.substring(0,5),
                    end_time: slot.end_time.substring(0,5),
                    price_modifier: parseFloat(slot.price_modifier),
                    is_active: slot.is_active == 1
                };
                this.isModalOpen = true;
            },
            closeModal() {
                this.isModalOpen = false;
            }
        }
    }
</script>
@endpush
