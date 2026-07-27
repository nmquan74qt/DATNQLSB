@extends('layouts.admin')

@section('title', 'Quản Lý Khách Hàng')

@section('header')
    <h2 class="font-heading font-bold text-2xl text-slate-800 dark:text-white leading-tight">
        Quản Lý Khách Hàng
    </h2>
@endsection

@section('content')
    <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 text-sm">
                        <th class="py-4 px-4 font-bold rounded-l-xl">Khách Hàng</th>
                        <th class="py-4 px-4 font-bold">Liên Hệ</th>
                        <th class="py-4 px-4 font-bold">Thống Kê</th>
                        <th class="py-4 px-4 font-bold">Điểm & Hạng</th>
                        <th class="py-4 px-4 font-bold rounded-r-xl"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($customers as $customer)
                        @php
                            $totalSpent = $customer->bookings->sum('total_amount');
                        @endphp
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                            <td class="py-4 px-4 flex items-center gap-3">
                                <img src="{{ $customer->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($customer->name).'&background=random' }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover border border-slate-200">
                                <div>
                                    <span class="font-bold text-slate-800 dark:text-white block">{{ $customer->name }}</span>
                                    <span class="text-xs text-slate-500">Tham gia: {{ $customer->created_at->format('m/Y') }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <div class="text-sm text-slate-600 dark:text-slate-300"><i class="fa-solid fa-envelope w-4 text-slate-400"></i> {{ $customer->email }}</div>
                                <div class="text-sm text-slate-600 dark:text-slate-300 mt-1"><i class="fa-solid fa-phone w-4 text-slate-400"></i> {{ $customer->phone ?? 'Chưa cập nhật' }}</div>
                            </td>
                            <td class="py-4 px-4">
                                <div class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ $customer->completed_bookings_count }} <span class="text-xs font-normal text-slate-500">lần đặt</span></div>
                                <div class="text-sm font-bold text-primary mt-1">{{ number_format($totalSpent) }}đ <span class="text-xs font-normal text-slate-500">chi tiêu</span></div>
                            </td>
                            <td class="py-4 px-4">
                                <div class="font-bold text-amber-500"><i class="fa-solid fa-star text-xs"></i> {{ number_format($customer->points) }}</div>
                                <div class="mt-1">
                                    <span class="bg-slate-100 dark:bg-slate-700 px-2 py-0.5 rounded text-xs font-mono text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-600">{{ $customer->membership_code ?? '---' }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-4 text-right flex justify-end gap-2">
                                <button onclick="showCustomerHistory({{ json_encode($customer->bookings->take(5)) }}, '{{ $customer->name }}')" class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-colors text-sm font-medium" title="Xem lịch sử">
                                    <i class="fa-solid fa-clock-rotate-left mr-1"></i> Lịch sử
                                </button>
                                <button onclick="editCustomer({{ $customer }})" class="px-3 py-1.5 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white transition-colors text-sm font-medium" title="Sửa">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa khách hàng này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-colors text-sm font-medium" title="Xóa">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-500">
                                <div class="w-16 h-16 mx-auto bg-slate-100 dark:bg-slate-700 text-slate-400 rounded-full flex items-center justify-center text-2xl mb-4">
                                    <i class="fa-solid fa-user-xmark"></i>
                                </div>
                                <p>Chưa có khách hàng nào.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-6">
            {{ $customers->links() }}
        </div>
    </div>

    <!-- History Modal -->
    <div x-data="{ open: false, history: [], customerName: '' }" 
         x-on:show-history.window="history = $event.detail.history; customerName = $event.detail.name; open = true">
        
        <div x-show="open" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm" x-cloak x-transition.opacity>
            <div class="relative w-full max-w-2xl p-4 mx-auto transition-all transform" x-show="open" x-transition.scale.origin.bottom @click.away="open = false">
                <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl overflow-hidden border border-slate-100 dark:border-slate-700">
                    <div class="flex items-center justify-between p-6 border-b border-slate-100 dark:border-slate-700">
                        <h3 class="text-xl font-bold text-slate-800 dark:text-white">5 Lịch sử đặt sân gần nhất - <span class="text-primary" x-text="customerName"></span></h3>
                        <button @click="open = false" class="text-slate-400 hover:text-red-500 transition-colors w-8 h-8 rounded-full hover:bg-red-50 flex items-center justify-center">
                            <i class="fa-solid fa-times text-lg"></i>
                        </button>
                    </div>
                    <div class="p-6">
                        <template x-if="history.length === 0">
                            <div class="text-center py-8 text-slate-500">
                                Khách hàng này chưa có lịch sử đặt sân nào.
                            </div>
                        </template>
                        <template x-if="history.length > 0">
                            <div class="space-y-4">
                                <template x-for="booking in history" :key="booking.id">
                                    <div class="p-4 border border-slate-100 dark:border-slate-700 rounded-xl flex justify-between items-center bg-slate-50 dark:bg-slate-800/50">
                                        <div>
                                            <div class="font-bold text-slate-800 dark:text-white" x-text="'Mã đơn: ' + booking.booking_code"></div>
                                            <div class="text-sm text-slate-500 mt-1">Ngày đặt: <span class="font-medium" x-text="new Date(booking.booking_date).toLocaleDateString('vi-VN')"></span></div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-bold text-primary" x-text="new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(booking.total_amount)"></div>
                                            <div class="mt-1">
                                                <span class="bg-emerald-100 text-emerald-600 px-2 py-0.5 rounded text-xs font-bold">Hoàn thành</span>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    <!-- Edit Modal -->
    <div x-data="{ openEdit: false, customer: {} }" 
         x-on:edit-customer.window="customer = $event.detail.customer; openEdit = true">
        <div x-show="openEdit" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm" x-cloak x-transition.opacity>
            <div class="relative w-full max-w-lg p-4 mx-auto transition-all transform" x-show="openEdit" x-transition.scale.origin.bottom @click.away="openEdit = false">
                <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl overflow-hidden border border-slate-100 dark:border-slate-700">
                    <div class="flex items-center justify-between p-6 border-b border-slate-100 dark:border-slate-700">
                        <h3 class="text-xl font-bold text-slate-800 dark:text-white">Cập Nhật Khách Hàng</h3>
                        <button @click="openEdit = false" class="text-slate-400 hover:text-red-500 transition-colors w-8 h-8 rounded-full hover:bg-red-50 flex items-center justify-center">
                            <i class="fa-solid fa-times text-lg"></i>
                        </button>
                    </div>
                    <form :action="`{{ url('admin/customers') }}/${customer.id}`" method="POST" class="p-6">
                        @csrf
                        @method('PUT')
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Họ Tên</label>
                                <input type="text" name="name" :value="customer.name" required class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-primary/50">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Số Điện Thoại</label>
                                <input type="text" name="phone" :value="customer.phone" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-primary/50">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Điểm Tích Lũy (Pts)</label>
                                <input type="number" name="points" :value="customer.points" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-primary/50">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Mật Khẩu Mới (Bỏ trống nếu không đổi)</label>
                                <input type="password" name="password" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-primary/50">
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" @click="openEdit = false" class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 font-bold hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">Hủy</button>
                            <button type="submit" class="bg-primary hover:bg-blue-600 text-white px-5 py-2.5 rounded-xl font-bold shadow-md transition-colors">Cập Nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function showCustomerHistory(history, name) {
        window.dispatchEvent(new CustomEvent('show-history', {
            detail: {
                history: history,
                name: name
            }
        }));
    }

    function editCustomer(customer) {
        window.dispatchEvent(new CustomEvent('edit-customer', {
            detail: { customer: customer }
        }));
    }
</script>
@endpush
