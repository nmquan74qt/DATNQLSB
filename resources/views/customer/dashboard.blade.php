@extends('layouts.app')

@section('title', 'Hồ Sơ Khách Hàng')

@section('content')
    <div class="pt-32 pb-20 bg-slate-50 dark:bg-slate-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-12 gap-8">
                <!-- Sidebar Gamification (Grid 4/12) -->
                <div class="col-span-12 lg:col-span-4 space-y-6" data-aos="fade-right">
                    
                    <!-- Profile Card -->
                    <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-100 dark:border-slate-700 shadow-xl text-center relative overflow-hidden group">
                        <!-- BG Glow -->
                        <div class="absolute top-0 inset-x-0 h-32 opacity-20 transition-all duration-700 group-hover:h-48 group-hover:opacity-40" style="background-color: {{ $level->color_hex ?? '#cd7f32' }}"></div>
                        
                        <div class="relative z-10 pt-4">
                            <div class="w-24 h-24 mx-auto rounded-full p-1 mb-4" style="background: linear-gradient(135deg, {{ $level->color_hex ?? '#cd7f32' }}, transparent);">
                                <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random&color=fff' }}" class="w-full h-full rounded-full border-4 border-white dark:border-slate-800 object-cover" alt="Avatar">
                            </div>
                            <h2 class="text-2xl font-bold font-heading text-slate-900 dark:text-white">{{ $user->name }}</h2>
                            <p class="text-slate-500 mb-4">{{ $user->email }}</p>
                            
                            <!-- Membership Badge -->
                            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border shadow-sm font-bold tracking-wider uppercase text-sm" 
                                 style="color: {{ $level->color_hex ?? '#cd7f32' }}; border-color: {{ $level->color_hex ?? '#cd7f32' }}50; background-color: {{ $level->color_hex ?? '#cd7f32' }}15;">
                                <i class="{{ $level->badge_icon ?? 'fa-solid fa-medal' }}"></i>
                                {{ $level->name ?? 'Thành Viên' }}
                            </div>

                            <!-- Points Progress -->
                            <div class="mt-8 text-left">
                                <div class="flex justify-between items-end mb-2">
                                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Điểm tích lũy</span>
                                    <span class="text-lg font-bold" style="color: {{ $level->color_hex ?? '#cd7f32' }}">{{ number_format($user->points) }} Pts</span>
                                </div>
                                
                                @if($nextLevel)
                                    @php
                                        $required = $nextLevel->required_points;
                                        $progress = $required > 0 ? ($user->points / $required) * 100 : 100;
                                        $progress = $progress > 100 ? 100 : $progress;
                                    @endphp
                                    <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-3 mb-2 overflow-hidden shadow-inner">
                                        <div class="h-3 rounded-full relative overflow-hidden transition-all duration-1000" style="width: {{ $progress }}%; background-color: {{ $level->color_hex ?? '#cd7f32' }}">
                                            <div class="absolute inset-0 bg-white/30 w-full h-full animate-[shimmer_2s_infinite]"></div>
                                        </div>
                                    </div>
                                    @if($nextLevel->required_points - $user->points <= 0)
                                        <p class="text-xs text-emerald-600 font-bold text-center">Bạn đã đủ điều kiện để lên hạng <strong style="color: {{ $nextLevel->color_hex }}">{{ $nextLevel->name }}</strong>!</p>
                                    @else
                                        <p class="text-xs text-slate-500 text-center">Cần <span class="font-bold text-slate-700 dark:text-slate-300">{{ number_format($nextLevel->required_points - $user->points) }} điểm</span> nữa để lên <strong style="color: {{ $nextLevel->color_hex }}">{{ $nextLevel->name }}</strong></p>
                                    @endif
                                @else
                                    <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-3 mb-2 overflow-hidden shadow-inner">
                                        <div class="h-3 rounded-full relative overflow-hidden transition-all duration-1000" style="width: 100%; background-color: {{ $level->color_hex ?? '#cd7f32' }}">
                                            <div class="absolute inset-0 bg-white/30 w-full h-full animate-[shimmer_2s_infinite]"></div>
                                        </div>
                                    </div>
                                    <p class="text-xs text-slate-500 text-center">Bạn đã đạt cấp bậc cao nhất!</p>
                                @endif
                                

                            </div>
                        </div>
                    </div>

                    <!-- Gamification Perks -->
                    <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-100 dark:border-slate-700 shadow-sm">
                        <h3 class="font-heading font-bold text-slate-900 dark:text-white mb-4 border-b border-slate-100 dark:border-slate-700 pb-2">Đặc Quyền Của Bạn</h3>
                        <ul class="space-y-4">
                            <li class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-emerald-50 dark:bg-emerald-900/30 text-emerald-500 flex items-center justify-center">
                                    <i class="fa-solid fa-tags"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800 dark:text-slate-200">Giảm giá {{ floatval($level->discount_percent ?? 0) }}%</p>
                                    <p class="text-xs text-slate-500">Mỗi lượt đặt sân</p>
                                </div>
                            </li>
                            <li class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-900/30 text-blue-500 flex items-center justify-center">
                                    <i class="fa-solid fa-gift"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800 dark:text-slate-200">Tặng nước suối</p>
                                    <p class="text-xs text-slate-500">Cho đội 5 người trở lên</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Main Dashboard Tabs (Grid 8/12) -->
                <div class="col-span-12 lg:col-span-8 space-y-6" data-aos="fade-up" x-data="{ tab: 'profile' }">
                    
                    <!-- Tabs Navigation -->
                    <div class="bg-white dark:bg-slate-800 rounded-2xl p-2 border border-slate-100 dark:border-slate-700 shadow-sm flex flex-col sm:flex-row gap-2">
                        <button @click="tab = 'profile'" :class="tab === 'profile' ? 'bg-primary text-white shadow-md' : 'text-slate-600 hover:bg-slate-50'" class="flex-1 py-3 px-4 rounded-xl font-bold transition-all flex items-center justify-center gap-2">
                            <i class="fa-regular fa-id-badge"></i> Thông Tin Cá Nhân
                        </button>
                        <button @click="tab = 'history'" :class="tab === 'history' ? 'bg-primary text-white shadow-md' : 'text-slate-600 hover:bg-slate-50'" class="flex-1 py-3 px-4 rounded-xl font-bold transition-all flex items-center justify-center gap-2">
                            <i class="fa-solid fa-clock-rotate-left"></i> Lịch Sử Đặt Sân
                        </button>

                    </div>

                    <!-- Profile Tab -->
                    <div x-show="tab === 'profile'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-100 dark:border-slate-700 shadow-sm">
                        <h3 class="text-xl font-heading font-bold text-slate-900 dark:text-white mb-6 border-b pb-4">Cập Nhật Thông Tin</h3>
                        
                        @if(session('success'))
                            <div class="bg-emerald-50 text-emerald-600 p-4 rounded-xl mb-6 font-medium border border-emerald-100 flex items-center gap-3">
                                <i class="fa-solid fa-circle-check text-xl"></i> {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('customer.profile.update') }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Họ Tên -->
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Họ và Tên</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 text-slate-800 focus:ring-2 focus:ring-primary/50 transition-all" required>
                                </div>

                                <!-- Số Điện Thoại -->
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Số Điện Thoại</label>
                                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 text-slate-800 focus:ring-2 focus:ring-primary/50 transition-all">
                                </div>

                                <!-- Ngày Sinh -->
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Ngày Sinh</label>
                                    <input type="date" name="dob" value="{{ old('dob', $user->dob) }}" class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 text-slate-800 focus:ring-2 focus:ring-primary/50 transition-all">
                                </div>

                                <!-- Ngân Hàng -->
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Tên Ngân Hàng</label>
                                    <input type="text" name="bank_name" value="{{ old('bank_name', $user->bank_name) }}" placeholder="VD: Vietcombank" class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 text-slate-800 focus:ring-2 focus:ring-primary/50 transition-all">
                                </div>

                                <!-- Số Tài Khoản -->
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Số Tài Khoản</label>
                                    <input type="text" name="bank_account" value="{{ old('bank_account', $user->bank_account) }}" class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 text-slate-800 focus:ring-2 focus:ring-primary/50 transition-all">
                                </div>

                                <!-- Tên Chủ Thẻ -->
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Tên Chủ Tài Khoản</label>
                                    <input type="text" name="bank_account_name" value="{{ old('bank_account_name', $user->bank_account_name) }}" class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 text-slate-800 focus:ring-2 focus:ring-primary/50 transition-all uppercase">
                                </div>
                            </div>
                            
                            <!-- Đổi Mật Khẩu Section -->
                            <h3 class="text-xl font-heading font-bold text-slate-900 dark:text-white mt-12 mb-6 border-b pb-4">Đổi Mật Khẩu <span class="text-sm font-normal text-slate-500">(Bỏ trống nếu không muốn đổi)</span></h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Mật khẩu hiện tại</label>
                                    <input type="password" name="current_password" class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 text-slate-800 focus:ring-2 focus:ring-primary/50 transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Mật khẩu mới</label>
                                    <input type="password" name="password" class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 text-slate-800 focus:ring-2 focus:ring-primary/50 transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Xác nhận mật khẩu mới</label>
                                    <input type="password" name="password_confirmation" class="w-full bg-slate-50 border-none rounded-xl px-4 py-3 text-slate-800 focus:ring-2 focus:ring-primary/50 transition-all">
                                </div>
                            </div>
                            
                            <div class="pt-8 mt-8 border-t border-slate-100 flex justify-end">
                                <button type="submit" class="bg-primary hover:bg-secondary text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-primary/30 transform hover:-translate-y-1 transition-all">
                                    Lưu Thay Đổi
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- History Tab -->
                    <div x-show="tab === 'history'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="space-y-6">
                        
                        <!-- Quick Stats -->
                        <div class="grid grid-cols-3 gap-4">
                            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700 shadow-sm text-center transform transition-transform hover:-translate-y-1">
                                <i class="fa-solid fa-calendar-check text-3xl text-primary mb-3"></i>
                                <h4 class="text-2xl font-bold text-slate-900 dark:text-white font-heading">{{ $bookings->where('status', '!=', 'cancelled')->count() }}</h4>
                                <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Đã đặt sân</p>
                            </div>
                            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700 shadow-sm text-center transform transition-transform hover:-translate-y-1">
                                <i class="fa-solid fa-ban text-3xl text-red-500 mb-3"></i>
                                <h4 class="text-2xl font-bold text-slate-900 dark:text-white font-heading">{{ $bookings->where('status', 'cancelled')->count() }}</h4>
                                <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Hủy sân</p>
                            </div>
                            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700 shadow-sm text-center transform transition-transform hover:-translate-y-1">
                                <i class="fa-solid fa-star text-3xl text-warning mb-3"></i>
                                <h4 class="text-2xl font-bold text-slate-900 dark:text-white font-heading">5</h4>
                                <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Đánh giá</p>
                            </div>
                        </div>

                        <!-- Bookings History -->
                        <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-100 dark:border-slate-700 shadow-sm">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-xl font-heading font-bold text-slate-900 dark:text-white">Lịch Sử Đặt Sân</h3>
                            </div>
                            
                            @if($bookings->isEmpty())
                                <!-- Empty State Illustration -->
                                <div class="text-center py-12">
                                    <div class="w-32 h-32 mx-auto bg-slate-50 dark:bg-slate-900 rounded-full flex items-center justify-center text-4xl text-slate-300 dark:text-slate-600 mb-4 animate-bounce">
                                        <i class="fa-regular fa-calendar-xmark"></i>
                                    </div>
                                    <h4 class="text-lg font-bold text-slate-800 dark:text-white mb-2">Chưa có lượt đặt sân nào</h4>
                                    <p class="text-sm text-slate-500 mb-6 max-w-sm mx-auto">Hãy bắt đầu trận đấu đầu tiên của bạn để tích lũy điểm thưởng nhé!</p>
                                    <a href="{{ route('fields.index') }}" class="bg-primary text-white px-6 py-3 rounded-xl font-bold shadow-md hover:bg-secondary transition-colors inline-block magnetic-btn"><span class="btn-text">Đặt Sân Ngay</span></a>
                                </div>
                            @else
                                <!-- Custom Booking List -->
                                <div class="space-y-4">
                                    @foreach($bookings as $booking)
                                        @foreach($booking->details as $detail)
                                        <div class="flex flex-col sm:flex-row items-center justify-between p-4 rounded-2xl border border-slate-100 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group">
                                            <div class="flex items-center gap-4 w-full sm:w-auto mb-4 sm:mb-0">
                                                <div class="w-14 h-14 rounded-xl overflow-hidden shadow-sm flex-shrink-0 relative bg-slate-100 flex items-center justify-center text-primary text-2xl">
                                                    <i class="fa-solid fa-futbol"></i>
                                                </div>
                                                <div>
                                                    <h4 class="font-bold text-slate-900 dark:text-white group-hover:text-primary transition-colors">{{ $detail->field->name ?? 'Sân' }}</h4>
                                                    <p class="text-xs text-slate-500 flex items-center gap-2"><i class="fa-regular fa-clock"></i> {{ \Carbon\Carbon::parse($detail->timeSlot->start_time)->format('H:i') ?? '' }} - {{ \Carbon\Carbon::parse($detail->timeSlot->end_time)->format('H:i') ?? '' }} | {{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}</p>
                                                    <p class="text-[10px] font-mono text-slate-400 mt-1">Mã đơn: {{ $booking->booking_code }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center justify-between sm:justify-end gap-6 w-full sm:w-auto">
                                                <div class="text-right">
                                                    <p class="font-bold text-slate-900 dark:text-white">{{ number_format($booking->total_amount) }}đ</p>
                                                    
                                                    @if($booking->status == 'completed')
                                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-600 uppercase tracking-wider">Hoàn thành</span>
                                                    @elseif($booking->status == 'pending')
                                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-600 uppercase tracking-wider">Chờ xác nhận</span>
                                                    @elseif($booking->status == 'confirmed')
                                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-blue-100 text-blue-600 uppercase tracking-wider">Đã xác nhận</span>
                                                    @elseif($booking->status == 'in_progress')
                                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-600 uppercase tracking-wider">Đang đá</span>
                                                    @else
                                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 uppercase tracking-wider">Đã hủy</span>
                                                    @endif
                                                </div>
                                                
                                                @php
                                                    $canCancel = false;
                                                    $hoursDiff = 0;
                                                    if (in_array($booking->status, ['pending', 'confirmed'])) {
                                                        $startTime = $detail->start_time;
                                                        if (!$startTime) {
                                                            $timeSlot = \App\Models\TimeSlot::find($detail->time_slot_id);
                                                            if ($timeSlot) $startTime = \Carbon\Carbon::parse($booking->booking_date . ' ' . $timeSlot->start_time);
                                                        } else {
                                                            $startTime = \Carbon\Carbon::parse($startTime);
                                                        }
                                                        
                                                        if ($startTime && $startTime->isFuture()) {
                                                            $canCancel = true;
                                                            $hoursDiff = now()->diffInMinutes($startTime, false) / 60.0;
                                                        }
                                                    }
                                                    
                                                    // Determine cancellation type
                                                    $cancelType = '';
                                                    if ($booking->status === 'pending') {
                                                        $cancelType = 'no_deposit';
                                                    } elseif ($booking->status === 'confirmed') {
                                                        $cancelType = $hoursDiff > 4 ? 'refund' : 'no_refund';
                                                    }
                                                @endphp
                                                
                                                @if($canCancel)
                                                    <button @click="$dispatch('open-cancel-modal', { action: '{{ route('customer.bookings.cancel', $booking->id) }}', type: '{{ $cancelType }}', amount: {{ \App\Models\Payment::where('booking_id', $booking->id)->where('status', 'success')->sum('amount') }} })" class="ml-4 w-10 h-10 rounded-full bg-red-50 text-red-500 hover:bg-red-500 hover:text-white flex items-center justify-center transition-colors shadow-sm" title="Hủy Sân">
                                                        <i class="fa-solid fa-xmark"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Booking Modal -->
<div x-data="{ show: false, action: '', type: '', cancelAmount: 0 }" 
    @open-cancel-modal.window="show = true; action = $event.detail.action; type = $event.detail.type; cancelAmount = $event.detail.amount"
    x-show="show" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto overflow-x-hidden bg-slate-900/50 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0">
    <div class="relative w-full max-w-md p-4" @click.away="show = false">
        <div class="relative bg-white dark:bg-slate-800 rounded-3xl shadow-2xl border border-slate-100 dark:border-slate-700 overflow-hidden transform transition-all"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-8 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-8 scale-95">
            
            <div class="p-6 text-center">
                <div class="w-16 h-16 rounded-full bg-red-100 text-red-500 flex items-center justify-center text-3xl mx-auto mb-4">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <h3 class="text-xl font-bold font-heading text-slate-900 dark:text-white mb-2">Xác Nhận Hủy Sân</h3>
                
                <div class="text-sm text-slate-500 mb-6 bg-slate-50 dark:bg-slate-900/50 p-4 rounded-xl border border-slate-100 dark:border-slate-700 text-left space-y-2">
                    <div x-show="type === 'no_deposit'">
                        <p><i class="fa-solid fa-info-circle text-primary mr-2"></i> Đơn của bạn đang chờ duyệt và chưa thanh toán cọc. Bạn có thể hủy miễn phí.</p>
                    </div>
                    <div x-show="type === 'refund'">
                        <p class="mb-2"><i class="fa-solid fa-check-circle text-emerald-500 mr-2"></i> Bạn đang hủy sân <strong>trước 4 tiếng</strong> so với giờ đá.</p>
                        <p><i class="fa-solid fa-wallet text-emerald-500 mr-2"></i> Số tiền <strong class="text-emerald-600" x-text="new Intl.NumberFormat('vi-VN').format(cancelAmount) + 'đ'"></strong> sẽ được hoàn tự động vào <strong>Ví Cá Nhân</strong> của bạn.</p>
                    </div>
                    <div x-show="type === 'no_refund'">
                        <p class="mb-2"><i class="fa-solid fa-xmark-circle text-red-500 mr-2"></i> Bạn đang hủy sân <strong>dưới 4 tiếng</strong> so với giờ đá.</p>
                        <p class="text-red-500 font-bold"><i class="fa-solid fa-ban mr-2"></i> Theo quy định, bạn sẽ không được hoàn cọc cho đơn này.</p>
                    </div>
                </div>

                <form :action="action" method="POST" class="flex gap-3 w-full">
                    @csrf
                    @method('PUT')
                    <button type="button" @click="show = false" class="flex-1 py-3 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-colors">
                        Đóng
                    </button>
                    <button type="submit" class="flex-1 py-3 px-4 bg-red-500 hover:bg-red-600 text-white font-bold rounded-xl shadow-lg shadow-red-500/30 transition-all transform hover:-translate-y-1">
                        Đồng Ý Hủy
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<style>
[x-cloak] { display: none !important; }
@keyframes shimmer {
  0% { transform: translateX(-100%); }
  100% { transform: translateX(100%); }
}
</style>
@endpush
