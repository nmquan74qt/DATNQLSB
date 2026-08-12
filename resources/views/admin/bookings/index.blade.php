@extends('layouts.admin')

@section('title', 'Quản Lý Lịch Đặt Sân')

@section('header')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h2 class="font-heading font-bold text-2xl text-slate-800 dark:text-white leading-tight">
            Quản Lý Lịch Đặt Sân
        </h2>
        <button @click="$dispatch('open-booking-modal')" class="bg-primary hover:bg-blue-600 text-white px-5 py-2.5 rounded-xl font-medium transition-colors shadow-sm shadow-primary/30 flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Tạo Lịch Đặt
        </button>
    </div>
@endsection

@section('content')
    <div x-data="bookingManager()">
        <!-- Tabs -->
        <div class="flex space-x-1 bg-white dark:bg-slate-800 p-1 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 mb-6 inline-flex">
            <button @click="activeTab = 'calendar'" :class="{ 'bg-slate-100 dark:bg-slate-700 text-primary font-bold shadow-sm': activeTab === 'calendar', 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200': activeTab !== 'calendar' }" class="px-5 py-2 rounded-lg text-sm transition-all flex items-center gap-2">
                <i class="fa-regular fa-calendar"></i> Lịch Biểu
            </button>
            <button @click="activeTab = 'list'" :class="{ 'bg-slate-100 dark:bg-slate-700 text-primary font-bold shadow-sm': activeTab === 'list', 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200': activeTab !== 'list' }" class="px-5 py-2 rounded-lg text-sm transition-all flex items-center gap-2">
                <i class="fa-solid fa-list"></i> Danh Sách
            </button>
        </div>

        <!-- Calendar Tab -->
        <div x-show="activeTab === 'calendar'" x-transition x-cloak>
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-100 dark:border-slate-700 shadow-sm">
                <div id="booking-calendar" class="h-[600px]"></div>
            </div>
        </div>

        <!-- List Tab -->
        <div x-show="activeTab === 'list'" x-transition x-cloak>
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 text-sm">
                                <th class="py-4 px-4 font-bold rounded-l-xl">Mã Đặt</th>
                                <th class="py-4 px-4 font-bold">Khách Hàng</th>
                                <th class="py-4 px-4 font-bold">Ngày Đặt</th>
                                <th class="py-4 px-4 font-bold text-right">Tổng Tiền</th>
                                <th class="py-4 px-4 font-bold">Trạng Thái</th>
                                <th class="py-4 px-4 font-bold rounded-r-xl"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            @forelse($bookings as $booking)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                    <td class="py-4 px-4">
                                        <span class="font-bold text-slate-800 dark:text-white">{{ $booking->booking_code }}</span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="font-medium text-slate-800 dark:text-slate-200">{{ $booking->user->name ?? 'Khách lẻ' }}</div>
                                        <div class="text-xs text-slate-500">{{ $booking->user->phone ?? '' }}</div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="text-sm text-slate-600 dark:text-slate-300">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}</span>
                                    </td>
                                    <td class="py-4 px-4 text-right">
                                        <span class="font-bold text-primary">{{ number_format($booking->total_amount, 0, ',', '.') }}đ</span>
                                    </td>
                                    <td class="py-4 px-4">
                                        @if($booking->status == 'pending')
                                            <span class="bg-amber-100 text-amber-600 px-3 py-1 rounded-full text-xs font-bold">Chờ duyệt</span>
                                        @elseif($booking->status == 'confirmed')
                                            <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-xs font-bold">Đã xác nhận</span>
                                        @elseif($booking->status == 'in_progress')
                                            <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs font-bold">Đang đá</span>
                                        @elseif($booking->status == 'completed')
                                            <span class="bg-emerald-100 text-emerald-600 px-3 py-1 rounded-full text-xs font-bold">Hoàn thành</span>
                                        @else
                                            <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-xs font-bold">Đã hủy</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            @if($booking->status == 'pending')
                                                <button @click="submitStatus('confirmed', {{ $booking->id }})" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 hover:bg-blue-500 hover:text-white transition-colors flex items-center justify-center" title="Duyệt (Khóa sân)">
                                                    <i class="fa-solid fa-check"></i>
                                                </button>
                                            @elseif($booking->status == 'confirmed')
                                                <button @click="if(confirm('Khách đã đến? Bắt đầu Check-in?')) submitStatus('in_progress', {{ $booking->id }})" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-colors flex items-center justify-center" title="Bắt đầu đá (Check-in)">
                                                    <i class="fa-solid fa-play"></i>
                                                </button>
                                            @elseif($booking->status == 'in_progress')
                                                <button @click="if(confirm('Khách đã trả sân? Check-out hoàn tất?')) submitStatus('completed', {{ $booking->id }})" class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-500 hover:bg-emerald-500 hover:text-white transition-colors flex items-center justify-center" title="Kết thúc (Check-out)">
                                                    <i class="fa-solid fa-check-to-slot"></i>
                                                </button>
                                            @endif
                                            <button @click="openDetails({{ $booking->id }})" class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-primary hover:text-white text-slate-500 transition-colors flex items-center justify-center" title="Chi tiết">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-slate-500">
                                        <div class="w-16 h-16 mx-auto bg-slate-100 dark:bg-slate-700 text-slate-400 rounded-full flex items-center justify-center text-2xl mb-4">
                                            <i class="fa-solid fa-inbox"></i>
                                        </div>
                                        <p>Chưa có lượt đặt sân nào.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-6">
                    {{ $bookings->links() }}
                </div>
            </div>
        </div>

    <!-- Modal Tạo Đặt Sân -->
    <div x-data="{ isModalOpen: false }" @open-booking-modal.window="isModalOpen = true">
        <!-- Button is now linked to this via an event or we can just wrap the button -->
        <!-- Actually, I will adjust the header button to dispatch event: @click="$dispatch('open-booking-modal')" -->
        
        <div x-show="isModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto overflow-x-hidden bg-slate-900/50 backdrop-blur-sm" x-cloak x-transition.opacity>
            <div class="relative w-full max-w-2xl p-4 md:p-6 mx-auto transition-all transform" x-show="isModalOpen" x-transition.scale.origin.bottom @click.away="isModalOpen = false">
                <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl border border-slate-100 dark:border-slate-700 overflow-hidden">
                    <div class="flex items-center justify-between p-6 border-b border-slate-100 dark:border-slate-700">
                        <h3 class="text-xl font-bold text-slate-800 dark:text-white">Tạo Lịch Đặt Sân Mới</h3>
                        <button @click="isModalOpen = false" class="text-slate-400 hover:text-red-500 transition-colors w-8 h-8 rounded-full hover:bg-red-50 flex items-center justify-center">
                            <i class="fa-solid fa-times text-lg"></i>
                        </button>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('admin.bookings.store') }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Khách Hàng</label>
                                    <select name="user_id" class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/50 outline-none transition-all text-slate-800 dark:text-slate-200">
                                        <option value="">-- Khách Vãng Lai --</option>
                                        @foreach(\App\Models\User::where('role', 'customer')->get() as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->phone }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Chọn Sân</label>
                                    <select name="field_id" required class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/50 outline-none transition-all text-slate-800 dark:text-slate-200">
                                        <option value="">-- Chọn Sân --</option>
                                        @foreach(\App\Models\Field::where('status', 'available')->get() as $field)
                                            <option value="{{ $field->id }}">{{ $field->name }} ({{ $field->fieldType->name ?? 'N/A' }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Ngày Đặt</label>
                                    <input type="date" name="booking_date" required class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/50 outline-none transition-all text-slate-800 dark:text-slate-200">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Khung Giờ</label>
                                    <select name="time_slot_id" required class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/50 outline-none transition-all text-slate-800 dark:text-slate-200">
                                        <option value="">-- Chọn Giờ --</option>
                                        @foreach(\App\Models\TimeSlot::all() as $slot)
                                            <option value="{{ $slot->id }}">{{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }} ({{ number_format($slot->price, 0, ',', '.') }}đ)</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Ghi chú</label>
                                <textarea name="notes" rows="3" class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/50 outline-none transition-all text-slate-800 dark:text-slate-200"></textarea>
                            </div>
                            <div class="flex justify-end gap-3">
                                <button type="button" @click="isModalOpen = false" class="px-6 py-2.5 rounded-xl font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600 transition-colors">Hủy</button>
                                <button type="submit" class="px-6 py-2.5 rounded-xl font-medium text-white bg-primary hover:bg-blue-600 shadow-sm shadow-primary/30 transition-colors">Tạo Đặt Sân</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Chi Tiết Đặt Sân -->
    <div x-show="isDetailsModalOpen" class="fixed inset-0 z-[105] flex items-center justify-center overflow-y-auto overflow-x-hidden bg-slate-900/50 backdrop-blur-sm" x-cloak x-transition.opacity>
        <div class="relative w-full max-w-4xl p-4 md:p-6 mx-auto transition-all transform" x-show="isDetailsModalOpen" x-transition.scale.origin.bottom @click.away="isDetailsModalOpen = false">
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl border border-slate-100 dark:border-slate-700 overflow-hidden flex flex-col max-h-[90vh]">
                <div class="flex items-center justify-between p-6 border-b border-slate-100 dark:border-slate-700 shrink-0">
                    <div>
                        <h3 class="text-xl font-bold text-slate-800 dark:text-white">Chi Tiết Lịch Đặt Sân <span x-text="selectedBooking?.booking_code" class="text-primary ml-2"></span></h3>
                        <p class="text-sm text-slate-500 mt-1">Ngày đặt: <span x-text="selectedBooking ? new Date(selectedBooking.booking_date).toLocaleDateString('vi-VN') : ''"></span></p>
                    </div>
                    <button @click="isDetailsModalOpen = false" class="text-slate-400 hover:text-red-500 transition-colors w-8 h-8 rounded-full hover:bg-red-50 flex items-center justify-center">
                        <i class="fa-solid fa-times text-lg"></i>
                    </button>
                </div>
                <div class="p-6 overflow-y-auto grow">
                    <template x-if="selectedBooking">
                        <div>
                            <!-- Thông tin khách hàng -->
                            <div class="mb-6 bg-slate-50 dark:bg-slate-700/50 p-4 rounded-xl border border-slate-100 dark:border-slate-600">
                                <h4 class="font-bold text-slate-700 dark:text-slate-300 mb-3"><i class="fa-solid fa-user text-primary mr-2"></i>Thông tin Khách Hàng</h4>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div><span class="text-slate-500">Tên:</span> <strong class="text-slate-800 dark:text-slate-200" x-text="selectedBooking.user ? selectedBooking.user.name : 'Khách lẻ'"></strong></div>
                                    <div><span class="text-slate-500">SĐT:</span> <strong class="text-slate-800 dark:text-slate-200" x-text="selectedBooking.user ? selectedBooking.user.phone : 'N/A'"></strong></div>
                                    <div><span class="text-slate-500">Tổng tiền:</span> <strong class="text-primary text-base" x-text="new Intl.NumberFormat('vi-VN').format(selectedBooking.total_amount) + 'đ'"></strong></div>
                                    <div><span class="text-slate-500">Trạng thái:</span> 
                                        <span x-show="selectedBooking.status === 'pending'" class="bg-amber-100 text-amber-600 px-2 py-0.5 rounded text-xs font-bold">Chờ duyệt</span>
                                        <span x-show="selectedBooking.status === 'confirmed'" class="bg-blue-100 text-blue-600 px-2 py-0.5 rounded text-xs font-bold">Đã xác nhận</span>
                                        <span x-show="selectedBooking.status === 'in_progress'" class="bg-red-100 text-red-600 px-2 py-0.5 rounded text-xs font-bold">Đang đá</span>
                                        <span x-show="selectedBooking.status === 'completed'" class="bg-emerald-100 text-emerald-600 px-2 py-0.5 rounded text-xs font-bold">Hoàn thành</span>
                                        <span x-show="selectedBooking.status === 'cancelled'" class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded text-xs font-bold">Đã hủy</span>
                                    </div>
                                    <div class="col-span-2"><span class="text-slate-500">Ghi chú:</span> <span class="text-slate-800 dark:text-slate-200 italic" x-text="selectedBooking.notes || 'Không có ghi chú'"></span></div>
                                </div>
                            </div>
                            
                            <!-- Chi tiết sân -->
                            <h4 class="font-bold text-slate-700 dark:text-slate-300 mb-3"><i class="fa-solid fa-futbol text-primary mr-2"></i>Chi Tiết Thuê Sân</h4>
                            <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-700 mb-6">
                                <table class="w-full text-left text-sm">
                                    <thead class="bg-slate-50 dark:bg-slate-700 text-slate-600 dark:text-slate-300">
                                        <tr>
                                            <th class="px-4 py-3 font-semibold">Tên Sân</th>
                                            <th class="px-4 py-3 font-semibold">Khung Giờ</th>
                                            <th class="px-4 py-3 font-semibold text-right">Giá</th>
                                            <th class="px-4 py-3 font-semibold text-right">Phụ thu (Quá giờ)</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                        <template x-for="detail in selectedBooking.details" :key="detail.id">
                                            <tr>
                                                <td class="px-4 py-3 text-slate-800 dark:text-slate-200 font-medium" x-text="detail.field ? detail.field.name : 'N/A'"></td>
                                                <td class="px-4 py-3 text-slate-600 dark:text-slate-400">
                                                    <span x-text="detail.time_slot ? detail.time_slot.start_time.substring(0,5) + ' - ' + detail.time_slot.end_time.substring(0,5) : ''"></span>
                                                    <div x-show="detail.actual_start_time" class="text-xs text-blue-500 mt-1">
                                                        Vào sân: <span x-text="new Date(detail.actual_start_time).toLocaleTimeString('vi-VN', {hour: '2-digit', minute:'2-digit'})"></span>
                                                    </div>
                                                    <div x-show="detail.actual_end_time" class="text-xs text-red-500 mt-1">
                                                        Ra sân: <span x-text="new Date(detail.actual_end_time).toLocaleTimeString('vi-VN', {hour: '2-digit', minute:'2-digit'})"></span>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 text-right font-medium text-slate-800 dark:text-slate-200" x-text="new Intl.NumberFormat('vi-VN').format(detail.price) + 'đ'"></td>
                                                <td class="px-4 py-3 text-right text-red-500 font-medium" x-text="detail.overtime_fee > 0 ? '+' + new Intl.NumberFormat('vi-VN').format(detail.overtime_fee) + 'đ' : '-'"></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Actions Form -->
                            <form :action="'/admin/bookings/' + selectedBooking.id + '/status'" method="POST" id="statusForm">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" id="status_input" value="">
                                
                                <!-- Hành động: Chờ duyệt -> Duyệt / Hủy -->
                                <div x-show="selectedBooking.status === 'pending'" class="flex justify-end gap-3 mt-6 pt-4 border-t border-slate-100 dark:border-slate-700">
                                    <button type="button" @click="submitStatus('cancelled')" class="px-5 py-2.5 rounded-xl font-medium text-red-600 bg-red-50 hover:bg-red-100 transition-colors">Hủy Đơn</button>
                                    <button type="button" @click="submitStatus('confirmed')" class="px-5 py-2.5 rounded-xl font-medium text-white bg-primary hover:bg-blue-600 shadow-sm shadow-primary/30 transition-colors">Duyệt Đơn</button>
                                </div>
                                
                                <!-- Hành động: Đã xác nhận -> Check-in -->
                                <div x-show="selectedBooking.status === 'confirmed'" class="bg-red-50/50 dark:bg-slate-700/30 p-4 rounded-xl border border-red-100 dark:border-slate-600 mt-6 text-center">
                                    <h4 class="font-bold text-slate-700 dark:text-slate-300 mb-2"><i class="fa-solid fa-play text-red-500 mr-2"></i>Khách đến sân</h4>
                                    <p class="text-sm text-slate-500 mb-4">Bấm nút Check-in để bắt đầu tính giờ sử dụng sân thực tế.</p>
                                    <div class="flex justify-center gap-3">
                                        <button type="button" @click="submitStatus('in_progress')" class="px-8 py-3 rounded-xl font-bold text-white bg-red-500 hover:bg-red-600 shadow-sm shadow-red-500/30 transition-colors">
                                            Bắt đầu Check-in
                                        </button>
                                    </div>
                                    <div class="mt-4">
                                        <button type="button" @click="if(confirm('Bạn có chắc chắn muốn hủy đơn này? Hệ thống sẽ hoàn tiền cọc nếu có.')) submitStatus('cancelled')" class="text-sm font-medium text-red-500 hover:text-red-700 transition-colors underline">Hủy Đơn Này</button>
                                    </div>
                                </div>
                                
                                <!-- Hành động: Đang đá -> Check-out -->
                                <div x-show="selectedBooking.status === 'in_progress'" class="bg-blue-50/50 dark:bg-slate-700/30 p-4 rounded-xl border border-blue-100 dark:border-slate-600 mt-6">
                                    <h4 class="font-bold text-slate-700 dark:text-slate-300 mb-3"><i class="fa-solid fa-clock-rotate-left text-blue-500 mr-2"></i>Thủ tục Check-out</h4>
                                    <div class="flex flex-col sm:flex-row gap-4 items-end">
                                        <div class="grow w-full">
                                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Giờ kết thúc thực tế (Để trống nếu đúng giờ)</label>
                                            <input type="datetime-local" name="actual_end_time" class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-primary/50 outline-none transition-all text-slate-800 dark:text-slate-200">
                                            <p class="text-xs text-slate-500 mt-1">Phụ thu: <10p: Miễn phí | 10-30p: 50% giá sân/giờ | >30p: 100% giá sân/giờ.</p>
                                        </div>
                                        <button type="button" @click="submitStatus('completed')" class="shrink-0 px-6 py-2.5 rounded-xl font-bold text-white bg-emerald-500 hover:bg-emerald-600 shadow-sm shadow-emerald-500/30 transition-colors h-11 flex items-center gap-2">
                                            <i class="fa-solid fa-check-to-slot"></i> Check-out
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Completed / Cancelled: No actions -->
                                <div x-show="['completed', 'cancelled'].includes(selectedBooking.status)" class="text-center py-4 mt-4 border-t border-slate-100 dark:border-slate-700">
                                    <p class="text-slate-500 text-sm">Đơn đặt sân này đã kết thúc và không thể thay đổi trạng thái.</p>
                                </div>
                            </form>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- Global Hidden Form for List View Actions -->
    <form id="globalStatusForm" method="POST" class="hidden">
        @csrf
        @method('PUT')
        <input type="hidden" name="status" id="global_status_input">
    </form>
</div>
@endsection

@push('scripts')
<script>
    window.bookingsData = {!! $bookings->getCollection()->toJson() !!};
    
    document.addEventListener('alpine:init', () => {
        Alpine.data('bookingManager', () => ({
            activeTab: sessionStorage.getItem('adminBookingTab') || 'calendar',
            isDetailsModalOpen: false,
            selectedBooking: null,
            bookings: window.bookingsData,
            
            init() {
                this.$watch('activeTab', (val) => sessionStorage.setItem('adminBookingTab', val));
            },
            
            openDetails(id) {
                this.selectedBooking = this.bookings.find(b => b.id === id);
                this.isDetailsModalOpen = true;
            },
            
            submitStatus(status, id = null) {
                if (id) {
                    let form = document.getElementById('globalStatusForm');
                    form.action = '/admin/bookings/' + id + '/status';
                    document.getElementById('global_status_input').value = status;
                    form.submit();
                } else {
                    document.getElementById('status_input').value = status;
                    document.getElementById('statusForm').submit();
                }
            }
        }));
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('booking-calendar');
        if(calendarEl) {
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                locale: 'vi',
                slotMinTime: '06:00:00',
                slotMaxTime: '23:00:00',
                allDaySlot: false,
                slotDuration: '01:00:00',
                expandRows: true,
                nowIndicator: true,
                events: '/admin/bookings/calendar-data',
                eventClick: function(info) {
                    // Custom sweetalert or modal would be better, but basic alert for now
                    alert('Chi tiết đặt sân:\n' + info.event.title + '\nTrạng thái: ' + (info.event.extendedProps.status === 'pending' ? 'Chờ duyệt' : 'Đã duyệt'));
                },
                eventContent: function(arg) {
                    return {
                        html: `<div class="p-1 h-full flex flex-col justify-center">
                            <div class="font-bold text-xs truncate leading-tight">${arg.timeText}</div>
                            <div class="font-medium text-xs truncate opacity-90 leading-tight">${arg.event.title}</div>
                        </div>`
                    };
                }
            });
            calendar.render();
        }
    });
</script>
<style>
    /* FullCalendar Premium Tailwind Override */
    .fc { font-family: 'Inter', sans-serif; }
    .fc .fc-toolbar-title { font-family: 'Outfit', sans-serif; font-size: 1.5rem !important; font-weight: 800; color: #1e293b; letter-spacing: -0.025em; }
    .dark .fc .fc-toolbar-title { color: #f8fafc; }
    .fc-theme-standard td, .fc-theme-standard th { border-color: #e2e8f0; }
    .dark .fc-theme-standard td, .dark .fc-theme-standard th { border-color: #334155; }
    
    .fc .fc-button-primary { background-color: #2563EB; border-color: #2563EB; border-radius: 0.75rem; font-weight: 600; text-transform: capitalize; padding: 0.5rem 1rem; transition: all 0.2s; box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2); }
    .fc .fc-button-primary:not(:disabled):hover { background-color: #1d4ed8; border-color: #1d4ed8; transform: translateY(-1px); }
    .fc .fc-button-primary:not(:disabled):active, .fc .fc-button-primary:not(:disabled).fc-button-active { background-color: #1e40af; border-color: #1e40af; }
    .fc .fc-button-primary:disabled { background-color: #94a3b8; border-color: #94a3b8; box-shadow: none; }
    
    .fc-theme-standard .fc-scrollgrid { border-radius: 1rem; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.5); }
    .dark .fc-theme-standard .fc-scrollgrid { border-color: #334155; box-shadow: none; }
    
    .fc .fc-col-header-cell-cushion { padding: 1rem 0.5rem; color: #64748b; font-weight: 700; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; }
    .dark .fc .fc-col-header-cell-cushion { color: #cbd5e1; }
    
    .fc-v-event { border-radius: 0.5rem; border: none; box-shadow: 0 4px 10px -2px rgba(0, 0, 0, 0.2); transition: transform 0.2s; }
    .fc-v-event:hover { transform: scale(1.02); z-index: 50 !important; cursor: pointer; }
    
    .fc-timegrid-slot { height: 4em !important; }
    .fc .fc-timegrid-slot-label-cushion { font-size: 0.8rem; font-weight: 600; color: #94a3b8; }
    .dark .fc .fc-timegrid-slot-label-cushion { color: #64748b; }
    
    /* Current time indicator */
    .fc-timegrid-now-indicator-line { border-color: #EF4444; border-width: 2px; }
    .fc-timegrid-now-indicator-arrow { border-color: #EF4444; background: #EF4444; border-width: 6px; }
</style>
@endpush
