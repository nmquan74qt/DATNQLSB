@extends('layouts.admin')

@section('title', 'Nhân viên & Phân quyền')

@section('content')
<div class="p-4 lg:p-8 space-y-6" x-data="staffManager()">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white font-heading">Nhân Viên & Phân Quyền</h1>
            <p class="text-slate-500 text-sm mt-1">Quản lý tài khoản quản trị và nhân viên hệ thống</p>
        </div>
        <button @click="openCreateModal" class="px-6 py-2.5 bg-primary text-white font-bold rounded-xl shadow-md hover:bg-secondary transition-all flex items-center gap-2">
            <i class="fa-solid fa-user-plus"></i> Thêm Nhân Viên
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
    @if($errors->any())
        <div class="bg-red-50 text-red-600 p-4 rounded-xl font-medium">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Danh sách Nhân viên -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 text-sm font-bold uppercase tracking-wider">
                        <th class="px-6 py-4">Nhân viên</th>
                        <th class="px-6 py-4">Liên hệ</th>
                        <th class="px-6 py-4">Vai trò</th>
                        <th class="px-6 py-4">Trạng thái</th>
                        <th class="px-6 py-4 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($staffs as $staff)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center font-bold text-slate-600">
                                    {{ substr($staff->name, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 dark:text-white">{{ $staff->name }}</h4>
                                    <p class="text-xs text-slate-500">ID: #{{ $staff->id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-slate-800 dark:text-slate-200 font-medium">{{ $staff->email }}</p>
                            <p class="text-slate-500 text-sm">{{ $staff->phone ?? 'Chưa cập nhật' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @if($staff->role == 'admin')
                                <span class="bg-purple-100 text-purple-600 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">Quản trị viên</span>
                            @else
                                <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">Nhân viên</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($staff->status == 'active')
                                <span class="bg-emerald-100 text-emerald-600 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">Hoạt động</span>
                            @elseif($staff->status == 'inactive')
                                <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">Tạm khóa</span>
                            @else
                                <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">Banned</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button @click="openEditModal({{ $staff->toJson() }})" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 hover:bg-blue-500 hover:text-white transition-colors flex items-center justify-center" title="Sửa">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                @if(auth()->id() !== $staff->id)
                                <form action="{{ route('admin.staff.destroy', $staff) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa nhân viên này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-colors flex items-center justify-center" title="Xóa">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                            Không có dữ liệu nhân viên.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Attendance and Payroll Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
        <!-- Attendance -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden p-6">
            <div class="mb-5 pb-4 border-b border-slate-100 dark:border-slate-700">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white font-heading">Chấm công hôm nay</h3>
                <p class="text-sm text-slate-500 mt-1">Ghi nhận trạng thái làm việc của nhân viên trong ngày</p>
            </div>
            
            <form action="{{ route('admin.staff.attendance') }}" method="POST" class="flex flex-col sm:flex-row gap-3 mb-6 bg-slate-50 dark:bg-slate-700/30 p-4 rounded-2xl border border-slate-100 dark:border-slate-700">
                @csrf
                <div class="flex-1">
                    <select name="user_id" required class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all text-slate-800 dark:text-slate-200 text-sm font-medium shadow-sm cursor-pointer">
                        <option value="">-- Chọn nhân viên --</option>
                        @foreach($staffs as $staff)
                            <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:w-40">
                    <select name="status" required class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all text-slate-800 dark:text-slate-200 text-sm font-medium shadow-sm cursor-pointer">
                        <option value="present">Có mặt</option>
                        <option value="absent">Vắng mặt</option>
                        <option value="late">Đi trễ</option>
                        <option value="leave">Nghỉ phép</option>
                    </select>
                </div>
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl shadow-md shadow-emerald-500/20 text-sm font-bold transition-all whitespace-nowrap active:scale-95 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-clock"></i> Điểm danh
                </button>
            </form>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 text-xs font-bold uppercase tracking-wider">
                            <th class="px-4 py-2">Nhân viên</th>
                            <th class="px-4 py-2">Ngày</th>
                            <th class="px-4 py-2">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @forelse($attendances ?? [] as $attendance)
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium">{{ $attendance->user->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm text-slate-500">{{ $attendance->date }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="bg-emerald-100 text-emerald-600 px-2 py-1 rounded-full text-xs font-bold">{{ $attendance->status }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-4 py-6 text-center text-slate-500 text-sm">Chưa có dữ liệu chấm công.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payroll -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden p-6">
            <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4 mb-4">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white font-heading whitespace-nowrap">Bảng lương tháng này</h3>
                <form action="{{ route('admin.staff.payroll') }}" method="POST" class="w-full xl:w-auto text-right">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl shadow-sm text-sm font-bold transition-colors">
                        <i class="fa-solid fa-file-invoice-dollar mr-1"></i> Tính lương
                    </button>
                </form>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 text-xs font-bold uppercase tracking-wider">
                            <th class="px-4 py-2">Nhân viên</th>
                            <th class="px-4 py-2">Kỳ lương</th>
                            <th class="px-4 py-2 text-right">Tổng lương</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @forelse($payrolls ?? [] as $payroll)
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium">{{ $payroll->user->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm text-slate-500">{{ $payroll->month }}/{{ $payroll->year }}</td>
                            <td class="px-4 py-3 text-sm text-right font-bold text-emerald-600">{{ number_format($payroll->total_salary) }} đ</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-4 py-6 text-center text-slate-500 text-sm">Chưa có bảng lương.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Create / Edit -->
    <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" x-show="isModalOpen" x-transition.opacity></div>

        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="isModalOpen" x-transition.scale.origin.bottom class="relative transform overflow-hidden rounded-3xl bg-white dark:bg-slate-800 text-left shadow-2xl transition-all w-full max-w-lg border border-slate-100 dark:border-slate-700">
                
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/50">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white" id="modal-title" x-text="isEditing ? 'Sửa Nhân Viên' : 'Thêm Nhân Viên Mới'"></h3>
                    <button @click="closeModal()" class="text-slate-400 hover:text-slate-500 focus:outline-none p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                        <i class="fa-solid fa-times text-lg"></i>
                    </button>
                </div>

                <form :action="formAction" method="POST" class="p-6">
                    @csrf
                    <input type="hidden" name="_method" :value="isEditing ? 'PUT' : 'POST'">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Họ & Tên</label>
                            <input type="text" name="name" x-model="formData.name" class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-xl px-4 py-3 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-primary/50 transition-all" required>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Email</label>
                                <input type="email" name="email" x-model="formData.email" class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-xl px-4 py-3 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-primary/50 transition-all" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Số điện thoại</label>
                                <input type="text" name="phone" x-model="formData.phone" class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-xl px-4 py-3 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-primary/50 transition-all">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Mật khẩu</label>
                                <input type="password" name="password" class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-xl px-4 py-3 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-primary/50 transition-all" :required="!isEditing">
                                <p class="text-xs text-slate-500 mt-1" x-show="isEditing">Để trống nếu không muốn đổi mật khẩu</p>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Nhập lại mật khẩu</label>
                                <input type="password" name="password_confirmation" class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-xl px-4 py-3 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-primary/50 transition-all" :required="!isEditing">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Vai trò</label>
                                <select name="role" x-model="formData.role" class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-xl px-4 py-3 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-primary/50 transition-all">
                                    <option value="staff">Nhân viên</option>
                                    <option value="admin">Quản trị viên</option>
                                </select>
                            </div>
                            <div x-show="isEditing">
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Trạng thái</label>
                                <select name="status" x-model="formData.status" class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-xl px-4 py-3 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-primary/50 transition-all">
                                    <option value="active">Hoạt động</option>
                                    <option value="inactive">Tạm khóa</option>
                                    <option value="banned">Banned</option>
                                </select>
                            </div>
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
    function staffManager() {
        return {
            isModalOpen: false,
            isEditing: false,
            formAction: '{{ route('admin.staff.store') }}',
            formData: {
                name: '',
                email: '',
                phone: '',
                role: 'staff',
                status: 'active'
            },
            openCreateModal() {
                this.isEditing = false;
                this.formAction = '{{ route('admin.staff.store') }}';
                this.formData = { name: '', email: '', phone: '', role: 'staff', status: 'active' };
                this.isModalOpen = true;
            },
            openEditModal(staff) {
                this.isEditing = true;
                this.formAction = '/admin/staff/' + staff.id;
                this.formData = {
                    name: staff.name,
                    email: staff.email,
                    phone: staff.phone || '',
                    role: staff.role,
                    status: staff.status
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
