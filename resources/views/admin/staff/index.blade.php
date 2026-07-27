@extends('layouts.admin')

@section('header')
<div class="flex justify-between items-center bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 mb-6 mt-16 lg:mt-0">
    <div>
        <h1 class="text-2xl font-bold font-heading text-slate-900 dark:text-white">Nhân Sự & Lương Thưởng</h1>
        <p class="text-sm text-slate-500">Phân hệ Quản lý ERP Nhân sự (HRM)</p>
    </div>
    <div class="flex gap-3">
        <!-- Notification Center Demo -->
        <button onclick="showNotification('Có 2 nhân viên chưa điểm danh hôm nay', 'warning')" class="relative w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 hover:text-primary transition-colors">
            <i class="fa-regular fa-bell"></i>
            <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full animate-ping"></span>
            <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full"></span>
        </button>

        <form action="{{ route('admin.staff.payroll') }}" method="POST">
            @csrf
            <button type="submit" class="bg-primary hover:bg-blue-600 text-white font-bold px-4 py-2 rounded-xl shadow-md flex items-center gap-2 magnetic-btn">
                <i class="fa-solid fa-file-invoice-dollar"></i> <span class="btn-text">Chốt Bảng Lương</span>
            </button>
        </form>
    </div>
</div>
@endsection

@section('content')

@if(session('success'))
<script>
    document.addEventListener("DOMContentLoaded", function() {
        showNotification("{{ session('success') }}", "success");
    });
</script>
@endif

<div x-data="{ activeTab: 'list', isModalOpen: false, editStaff: null }">
    <!-- Tabs -->
    <div class="flex space-x-1 bg-white dark:bg-slate-800 p-1 rounded-xl shadow-sm border border-slate-100 dark:border-slate-800 mb-6 inline-flex">
        <button @click="activeTab = 'list'" :class="{ 'bg-slate-100 dark:bg-slate-700 text-primary font-bold shadow-sm': activeTab === 'list', 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200': activeTab !== 'list' }" class="px-5 py-2 rounded-lg text-sm transition-all flex items-center gap-2">
            <i class="fa-solid fa-users"></i> Danh Sách Nhân Viên
        </button>
        <button @click="activeTab = 'attendance'" :class="{ 'bg-slate-100 dark:bg-slate-700 text-primary font-bold shadow-sm': activeTab === 'attendance', 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200': activeTab !== 'attendance' }" class="px-5 py-2 rounded-lg text-sm transition-all flex items-center gap-2">
            <i class="fa-solid fa-clipboard-user"></i> Chấm Công & Lương
        </button>
    </div>

    <!-- Tab Danh Sách Nhân Viên -->
    <div x-show="activeTab === 'list'" x-cloak x-transition>
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-100 dark:border-slate-800 shadow-sm relative overflow-hidden group mb-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-slate-900 dark:text-white font-heading text-lg">Quản Lý Danh Sách</h3>
                <button @click="isModalOpen = true; editStaff = null" class="bg-primary hover:bg-blue-600 text-white px-4 py-2 rounded-xl text-sm font-medium transition-colors shadow-sm shadow-primary/30 flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Thêm Nhân Viên
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-slate-500 uppercase bg-slate-50 dark:bg-slate-800/50 rounded-lg">
                        <tr>
                            <th class="px-4 py-3 rounded-l-lg">Họ & Tên</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Vai trò</th>
                            <th class="px-4 py-3 text-right rounded-r-lg">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach($staffs as $staff)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors">
                                <td class="px-4 py-4 font-bold text-slate-900 dark:text-white flex items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($staff->name) }}&background=random" class="w-8 h-8 rounded-full" alt="avatar">
                                    {{ $staff->name }}
                                </td>
                                <td class="px-4 py-4 text-slate-500">{{ $staff->email }}</td>
                                <td class="px-4 py-4">
                                    @if($staff->role == 'admin')
                                        <span class="bg-red-100 text-red-600 px-2 py-1 rounded-md text-xs font-bold uppercase">Admin</span>
                                    @else
                                        <span class="bg-blue-100 text-blue-600 px-2 py-1 rounded-md text-xs font-bold uppercase">Nhân viên</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <button @click="editStaff = {{ json_encode($staff) }}; isModalOpen = true" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 hover:bg-blue-500 hover:text-white transition-colors" title="Sửa"><i class="fa-solid fa-pen"></i></button>
                                    @if($staff->id != auth()->id())
                                    <form action="{{ route('admin.staff.destroy', $staff->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa nhân viên này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-colors" title="Xóa"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tab Chấm Công -->
    <div x-show="activeTab === 'attendance'" x-cloak x-transition>
        <div class="grid grid-cols-12 gap-6">

            <!-- Điểm danh hôm nay (Grid 8/12) -->
            <div class="col-span-12 xl:col-span-8 space-y-6">
                <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-100 dark:border-slate-800 shadow-sm relative overflow-hidden group">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-bold text-slate-900 dark:text-white font-heading text-lg">Điểm Danh Ngày {{ \Carbon\Carbon::today()->format('d/m/Y') }}</h3>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-slate-500 uppercase bg-slate-50 dark:bg-slate-800/50 rounded-lg">
                                <tr>
                                    <th class="px-4 py-3 rounded-l-lg">Nhân viên</th>
                                    <th class="px-4 py-3">Vai trò</th>
                                    <th class="px-4 py-3">Trạng thái</th>
                                    <th class="px-4 py-3 rounded-r-lg text-right">Hành động</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @foreach($staffs as $staff)
                                    @php
                                        $att = collect($attendances)->firstWhere('user_id', $staff->id);
                                    @endphp
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors">
                                        <td class="px-4 py-4 font-bold text-slate-900 dark:text-white flex items-center gap-3">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($staff->name) }}&background=random" class="w-8 h-8 rounded-full" alt="avatar">
                                            {{ $staff->name }}
                                        </td>
                                        <td class="px-4 py-4 text-slate-500 uppercase text-xs font-bold tracking-wider">
                                            {{ $staff->role }}
                                        </td>
                                        <td class="px-4 py-4">
                                            @if($att)
                                                @if($att->status == 'present')
                                                    <span class="bg-emerald-100 text-emerald-600 px-2 py-1 rounded-md text-xs font-bold">Có mặt ({{ \Carbon\Carbon::parse($att->check_in)->format('H:i') }})</span>
                                                @elseif($att->status == 'late')
                                                    <span class="bg-amber-100 text-amber-600 px-2 py-1 rounded-md text-xs font-bold">Đi trễ ({{ \Carbon\Carbon::parse($att->check_in)->format('H:i') }})</span>
                                                @elseif($att->status == 'absent')
                                                    <span class="bg-red-100 text-red-600 px-2 py-1 rounded-md text-xs font-bold">Vắng mặt</span>
                                                @endif
                                            @else
                                                <span class="bg-slate-100 text-slate-500 px-2 py-1 rounded-md text-xs font-bold">Chưa điểm danh</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 text-right">
                                            <form action="{{ route('admin.staff.attendance') }}" method="POST" class="inline-flex gap-2">
                                                @csrf
                                                <input type="hidden" name="user_id" value="{{ $staff->id }}">
                                                <button type="submit" name="status" value="present" class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-500 hover:bg-emerald-500 hover:text-white transition-colors" title="Có mặt"><i class="fa-solid fa-check"></i></button>
                                                <button type="submit" name="status" value="late" class="w-8 h-8 rounded-lg bg-amber-50 text-amber-500 hover:bg-amber-500 hover:text-white transition-colors" title="Đi trễ"><i class="fa-regular fa-clock"></i></button>
                                                <button type="submit" name="status" value="absent" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-colors" title="Vắng"><i class="fa-solid fa-xmark"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Bảng lương tháng này (Grid 4/12) -->
            <div class="col-span-12 xl:col-span-4 space-y-6">
                <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-100 dark:border-slate-800 shadow-sm relative overflow-hidden group">
                    
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-bold text-slate-900 dark:text-white font-heading text-lg">Bảng Lương T{{ \Carbon\Carbon::now()->format('m/Y') }}</h3>
                        <span class="text-xs bg-primary/10 text-primary font-bold px-2 py-1 rounded-md">{{ collect($payrolls)->count() }} NV</span>
                    </div>

                    @if(collect($payrolls)->isEmpty())
                        <div class="text-center py-8">
                            <div class="w-16 h-16 mx-auto bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center text-2xl text-slate-300 dark:text-slate-600 mb-3 animate-pulse">
                                <i class="fa-solid fa-file-invoice-dollar"></i>
                            </div>
                            <p class="text-sm text-slate-500">Chưa chốt lương tháng này.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($payrolls as $pr)
                                <div class="p-4 rounded-xl border border-slate-100 dark:border-slate-800 hover:shadow-md transition-shadow relative overflow-hidden group/item">
                                    <div class="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/40 to-transparent group-hover/item:animate-[shimmer_1s_forwards] z-0 pointer-events-none"></div>
                                    
                                    <div class="relative z-10 flex justify-between items-center mb-2">
                                        <h4 class="font-bold text-sm text-slate-900 dark:text-white">{{ $pr->staff_name }}</h4>
                                        @if($pr->status == 'paid')
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-500"><i class="fa-solid fa-check-circle"></i> Đã thanh toán</span>
                                        @else
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-amber-500"><i class="fa-solid fa-clock"></i> Chờ duyệt</span>
                                        @endif
                                    </div>
                                    <div class="relative z-10 flex justify-between items-end">
                                        <div>
                                            <p class="text-xs text-slate-500">Lương cơ bản: {{ number_format($pr->base_salary) }}</p>
                                            @if($pr->deduction > 0)
                                                <p class="text-xs text-red-500">Phạt trễ: -{{ number_format($pr->deduction) }}</p>
                                            @endif
                                        </div>
                                        <p class="font-bold text-lg text-primary">{{ number_format($pr->total_salary) }}đ</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal CRUD Nhân Viên -->
    <div x-show="isModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm" x-cloak x-transition.opacity>
        <div class="relative w-full max-w-lg p-4 mx-auto transition-all transform" x-show="isModalOpen" x-transition.scale.origin.bottom @click.away="isModalOpen = false">
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl overflow-hidden border border-slate-100 dark:border-slate-700">
                <div class="flex items-center justify-between p-6 border-b border-slate-100 dark:border-slate-700">
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white" x-text="editStaff ? 'Sửa Nhân Viên' : 'Thêm Nhân Viên'"></h3>
                    <button @click="isModalOpen = false" class="text-slate-400 hover:text-red-500 transition-colors w-8 h-8 rounded-full hover:bg-red-50 flex items-center justify-center">
                        <i class="fa-solid fa-times text-lg"></i>
                    </button>
                </div>
                <div class="p-6">
                    <form :action="editStaff ? '/admin/staff/' + editStaff.id : '{{ route('admin.staff.store') }}'" method="POST">
                        @csrf
                        <template x-if="editStaff">
                            <input type="hidden" name="_method" value="PUT">
                        </template>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Họ & Tên</label>
                                <input type="text" name="name" :value="editStaff ? editStaff.name : ''" required class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/50 outline-none transition-all text-slate-800 dark:text-slate-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Email</label>
                                <input type="email" name="email" :value="editStaff ? editStaff.email : ''" required class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/50 outline-none transition-all text-slate-800 dark:text-slate-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Mật khẩu <span x-show="editStaff" class="text-xs text-slate-400 font-normal">(Bỏ trống nếu không đổi)</span></label>
                                <input type="password" name="password" :required="!editStaff" class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/50 outline-none transition-all text-slate-800 dark:text-slate-200">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Vai trò</label>
                                    <select name="role" class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/50 outline-none transition-all text-slate-800 dark:text-slate-200">
                                        <option value="staff" :selected="editStaff && editStaff.role == 'staff'">Nhân viên</option>
                                        <option value="admin" :selected="editStaff && editStaff.role == 'admin'">Admin</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Số Điện Thoại</label>
                                    <input type="text" name="phone" :value="editStaff ? editStaff.phone : ''" class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/50 outline-none transition-all text-slate-800 dark:text-slate-200">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 mt-8">
                            <button type="button" @click="isModalOpen = false" class="px-6 py-2.5 rounded-xl font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600 transition-colors">Hủy</button>
                            <button type="submit" class="px-6 py-2.5 rounded-xl font-medium text-white bg-primary hover:bg-blue-600 shadow-sm shadow-primary/30 transition-colors" x-text="editStaff ? 'Cập Nhật' : 'Lưu'"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Notification Toast Container -->
<div id="toast-container" class="fixed bottom-4 right-4 z-50 flex flex-col gap-3"></div>

@endsection

@push('scripts')
<script>
    // Custom Notification Center (Toast) System using GSAP
    window.showNotification = function(message, type = 'success') {
        const container = document.getElementById('toast-container');
        
        const toast = document.createElement('div');
        toast.className = `p-4 rounded-xl shadow-xl flex items-center gap-3 backdrop-blur-md transform transition-all border`;
        
        let icon = '';
        if (type === 'success') {
            toast.classList.add('bg-emerald-500/90', 'border-emerald-400', 'text-white');
            icon = '<i class="fa-solid fa-circle-check text-xl"></i>';
        } else if (type === 'warning') {
            toast.classList.add('bg-amber-500/90', 'border-amber-400', 'text-white');
            icon = '<i class="fa-solid fa-triangle-exclamation text-xl"></i>';
        } else {
            toast.classList.add('bg-slate-900/90', 'border-slate-700', 'text-white');
            icon = '<i class="fa-solid fa-bell text-xl"></i>';
        }

        toast.innerHTML = `
            ${icon}
            <div class="text-sm font-medium">${message}</div>
            <button onclick="this.parentElement.remove()" class="ml-auto text-white/70 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
        `;
        
        container.appendChild(toast);

        // GSAP animate in
        gsap.fromTo(toast, { x: 100, opacity: 0 }, { x: 0, opacity: 1, duration: 0.4, ease: "back.out(1.5)" });

        // Auto remove
        setTimeout(() => {
            gsap.to(toast, { x: 100, opacity: 0, duration: 0.3, onComplete: () => toast.remove() });
        }, 5000);
    }
</script>
<style>
@keyframes shimmer {
  0% { transform: translateX(-100%); }
  100% { transform: translateX(200%); }
}
</style>
@endpush
