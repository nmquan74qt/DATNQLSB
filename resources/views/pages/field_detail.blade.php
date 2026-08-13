@extends('layouts.app')

@section('title', $field->name . ' - Chi Tiết Sân Bóng')

@section('content')
    <div class="bg-white pt-28 pb-10 border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Info -->
            <div class="mb-6" data-aos="fade-up">
                <div class="flex items-center gap-3 mb-3">
                    <span class="bg-primary/10 border border-primary/20 text-primary px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                        {{ $field->fieldType->name }}
                    </span>
                    @if($field->status === 'available')
                        <span class="bg-emerald-50 border border-emerald-200 text-emerald-600 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                            <i class="fa-solid fa-circle-check"></i> Đang trống
                        </span>
                    @endif
                </div>
                <h1 class="text-4xl md:text-5xl font-heading font-extrabold text-slate-900 mb-3">
                    {{ $field->name }}
                </h1>
                <div class="flex items-center gap-6 text-slate-600 font-medium text-sm">
                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-location-dot text-primary"></i> Cơ sở Trung Tâm</span>
                    @php
                        $reviewsCount = \App\Models\Review::where('field_id', $field->id)->where('is_approved', true)->count();
                        $avgRating = $reviewsCount > 0 ? \App\Models\Review::where('field_id', $field->id)->where('is_approved', true)->avg('rating') : 5.0;
                    @endphp
                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-star text-warning"></i> {{ number_format($avgRating, 1) }}/5 ({{ $reviewsCount }} Đánh giá)</span>
                </div>
            </div>

            <!-- Image Gallery (Airbnb Style) -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-2 h-[350px] md:h-[450px] rounded-2xl overflow-hidden shadow-sm" data-aos="fade-up" data-aos-delay="100">
                <!-- Main Large Image -->
                <div class="md:col-span-2 h-full relative group cursor-pointer overflow-hidden">
                    <img src="{{ $field->image_url }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="{{ $field->name }}">
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
                </div>
                <!-- Right Side Images -->
                @php
                    $fallbackImages = [
                        'https://images.pexels.com/photos/47730/the-ball-stadion-football-the-pitch-47730.jpeg?auto=compress&cs=tinysrgb&w=800&h=600&dpr=1',
                        'https://images.pexels.com/photos/46798/the-ball-stadion-football-the-pitch-46798.jpeg?auto=compress&cs=tinysrgb&w=800&h=600&dpr=1',
                        'https://images.pexels.com/photos/114296/pexels-photo-114296.jpeg?auto=compress&cs=tinysrgb&w=800&h=600&dpr=1',
                        'https://images.pexels.com/photos/3628912/pexels-photo-3628912.jpeg?auto=compress&cs=tinysrgb&w=800&h=600&dpr=1'
                    ];
                    $fieldImages = $field->images()->get();
                    $side = [];
                    for ($i = 0; $i < 4; $i++) {
                        if (isset($fieldImages[$i]) && $fieldImages[$i]->image_path != $field->image) {
                            $side[] = asset('storage/' . $fieldImages[$i]->image_path);
                        } else {
                            $side[] = $fallbackImages[$i];
                        }
                    }
                @endphp
                <div class="hidden md:flex flex-col gap-2 h-full md:col-span-1">
                    <div class="h-1/2 relative group cursor-pointer overflow-hidden">
                        <img src="{{ $side[0] }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="Góc sân 1">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
                    </div>
                    <div class="h-1/2 relative group cursor-pointer overflow-hidden">
                        <img src="{{ $side[1] }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="Góc sân 2">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
                    </div>
                </div>
                <div class="hidden md:flex flex-col gap-2 h-full md:col-span-1">
                    <div class="h-1/2 relative group cursor-pointer overflow-hidden">
                        <img src="{{ $side[2] }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="Trang thiết bị 1">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
                    </div>
                    <div class="h-1/2 relative group cursor-pointer overflow-hidden">
                        <img src="{{ $side[3] }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="Trang thiết bị 2">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-slate-50 dark:bg-slate-900 py-12 relative z-20" x-data="bookingWizard()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- STEP 1: Đặt sân -->
            <div x-show="step === 1" x-transition.opacity>
                <div class="grid grid-cols-12 gap-8">
                    
                    <!-- Calendar & Time Selection (Grid 8/12) -->
                    <div class="col-span-12 lg:col-span-8 space-y-8" data-aos="fade-up">
                        <!-- Description -->
                        <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-100 dark:border-slate-700 shadow-sm">
                            <h3 class="text-xl font-heading font-bold text-slate-900 dark:text-white mb-4">Mô tả sân</h3>
                            <div class="prose dark:prose-invert prose-slate max-w-none text-sm leading-relaxed">
                                <p>{{ $field->description ?? 'Đang cập nhật mô tả...' }}</p>
                                <p>Sân bóng cỏ nhân tạo chất lượng cao tại khu vực trung tâm. Sân tiêu chuẩn với hệ thống đèn chiếu sáng hiện đại, bãi đỗ xe rộng rãi.</p>
                            </div>
                        </div>

                        <!-- Reviews Section -->
                        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 md:p-8 border border-slate-100 dark:border-slate-700 shadow-sm">
                            <h3 class="text-xl font-heading font-bold text-slate-900 dark:text-white mb-4">Đánh giá từ khách hàng</h3>
                            
                            @php
                                $reviews = \App\Models\Review::where('field_id', $field->id)->where('is_approved', true)->latest()->get();
                                $avgRating = $reviews->avg('rating') ?? 0;
                            @endphp

                            <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-100 dark:border-slate-700">
                                <div class="text-4xl font-extrabold text-warning">{{ number_format($avgRating, 1) }}</div>
                                <div>
                                    <div class="flex text-warning text-lg">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fa-{{ $i <= round($avgRating) ? 'solid' : 'regular' }} fa-star"></i>
                                        @endfor
                                    </div>
                                    <div class="text-sm text-slate-500 mt-1">{{ $reviews->count() }} lượt đánh giá</div>
                                </div>
                            </div>

                            @if($reviews->isEmpty())
                                <p class="text-slate-500 text-sm text-center py-4">Chưa có đánh giá nào cho sân này.</p>
                            @else
                                <div class="space-y-6">
                                    @foreach($reviews as $review)
                                    <div class="flex gap-4">
                                        <div class="w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center font-bold text-slate-500 shrink-0">
                                            {{ substr($review->user->name ?? 'K', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <h4 class="font-bold text-slate-800 dark:text-white">{{ $review->user->name ?? 'Khách hàng' }}</h4>
                                                <span class="text-xs text-slate-400">&bull; {{ $review->created_at->diffForHumans() }}</span>
                                            </div>
                                            <div class="flex text-warning text-xs mb-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fa-{{ $i <= $review->rating ? 'solid' : 'regular' }} fa-star"></i>
                                                @endfor
                                            </div>
                                            @if($review->comment)
                                            <p class="text-sm text-slate-600 dark:text-slate-300">{{ $review->comment }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Booking Calendar -->
                        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 md:p-8 border border-slate-100 dark:border-slate-700 shadow-sm">
                            <h2 class="text-2xl font-heading font-bold text-slate-900 mb-6">Bảng giá - <span x-text="dates.find(d => d.isoString === selectedDate)?.dayName"></span></h2>
                            
                            <!-- Wizard Step 1: Select Date -->
                            <div class="mb-8">
                                <div class="flex overflow-x-auto pb-4 gap-3 snap-x hide-scroll">
                                    <template x-for="(day, index) in dates" :key="index">
                                        <button @click="selectDate(day.isoString)" 
                                                :class="selectedDate === day.isoString ? 'bg-primary border-primary text-white shadow-md shadow-primary/30 transform scale-105' : 'bg-white dark:bg-slate-700 border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 hover:border-primary/50'"
                                                class="snap-start shrink-0 flex flex-col items-center justify-center w-20 h-24 rounded-2xl border transition-all duration-300 focus:outline-none">
                                            <span class="text-xs font-medium uppercase mb-1" x-text="day.dayName"></span>
                                            <span class="text-2xl font-bold font-heading leading-none mb-1" x-text="day.dayNum"></span>
                                            <span class="text-xs opacity-80" x-text="'Thg ' + day.month"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <!-- Wizard Step 2: Select Time Slot -->
                            <div x-show="selectedDate" x-transition>
                                <template x-if="morningSlots.length > 0">
                                    <div class="mb-6">
                                        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3 flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-amber-400"></div> BUỔI SÁNG</h3>
                                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                            <template x-for="slot in morningSlots" :key="slot.id">
                                                <button @click="selectSlot(slot)"
                                                        :disabled="isBooked(slot.id) || isPast(slot.start_time)"
                                                        :class="[
                                                            isBooked(slot.id) ? 'bg-red-50 border-red-200 cursor-not-allowed opacity-80 text-red-500' : 
                                                            (isPast(slot.start_time) ? 'bg-slate-100 border-slate-200 cursor-not-allowed opacity-50 text-slate-500' :
                                                            (selectedSlots.find(s => s.id === slot.id) ? 'bg-emerald-500 border-emerald-500 text-white shadow-md shadow-emerald-500/30' : 'bg-white border-slate-200 text-slate-700 hover:border-emerald-500/50 hover:text-emerald-500'))
                                                        ]"
                                                        class="flex items-center justify-between px-3 py-3 rounded-xl border transition-all duration-300 focus:outline-none group">
                                                    <div class="flex items-center gap-1.5">
                                                        <i class="fa-regular fa-clock text-xs opacity-70"></i>
                                                        <span class="font-medium text-sm" :class="(isBooked(slot.id) || isPast(slot.start_time)) ? 'line-through' : ''" x-text="formatTime(slot.start_time) + ' - ' + formatTime(slot.end_time)"></span>
                                                    </div>
                                                    
                                                    <template x-if="isBooked(slot.id)">
                                                        <span class="text-xs font-bold bg-red-100/60 px-2 py-0.5 rounded text-red-600">Đã đặt</span>
                                                    </template>
                                                    <template x-if="!isBooked(slot.id)">
                                                        <span class="text-sm font-bold" :class="selectedSlots.find(s => s.id === slot.id) ? 'text-white' : 'text-emerald-500'" x-text="formatCurrencyShort(getSlotPrice(slot))"></span>
                                                    </template>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                                
                                <template x-if="afternoonSlots.length > 0">
                                    <div class="mb-6">
                                        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3 flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-orange-400"></div> BUỔI CHIỀU</h3>
                                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                            <template x-for="slot in afternoonSlots" :key="slot.id">
                                                <button @click="selectSlot(slot)"
                                                        :disabled="isBooked(slot.id) || isPast(slot.start_time)"
                                                        :class="[
                                                            isBooked(slot.id) ? 'bg-red-50 border-red-200 cursor-not-allowed opacity-80 text-red-500' : 
                                                            (isPast(slot.start_time) ? 'bg-slate-100 border-slate-200 cursor-not-allowed opacity-50 text-slate-500' :
                                                            (selectedSlots.find(s => s.id === slot.id) ? 'bg-emerald-500 border-emerald-500 text-white shadow-md shadow-emerald-500/30' : 'bg-white border-slate-200 text-slate-700 hover:border-emerald-500/50 hover:text-emerald-500'))
                                                        ]"
                                                        class="flex items-center justify-between px-3 py-3 rounded-xl border transition-all duration-300 focus:outline-none group">
                                                    <div class="flex items-center gap-1.5">
                                                        <i class="fa-regular fa-clock text-xs opacity-70"></i>
                                                        <span class="font-medium text-sm" :class="(isBooked(slot.id) || isPast(slot.start_time)) ? 'line-through' : ''" x-text="formatTime(slot.start_time) + ' - ' + formatTime(slot.end_time)"></span>
                                                    </div>
                                                    
                                                    <template x-if="isBooked(slot.id)">
                                                        <span class="text-xs font-bold bg-red-100/60 px-2 py-0.5 rounded text-red-600">Đã đặt</span>
                                                    </template>
                                                    <template x-if="!isBooked(slot.id)">
                                                        <span class="text-sm font-bold" :class="selectedSlots.find(s => s.id === slot.id) ? 'text-white' : 'text-emerald-500'" x-text="formatCurrencyShort(getSlotPrice(slot))"></span>
                                                    </template>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                                
                                <template x-if="eveningSlots.length > 0">
                                    <div class="mb-6">
                                        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3 flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-indigo-500"></div> BUỔI TỐI</h3>
                                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                            <template x-for="slot in eveningSlots" :key="slot.id">
                                                <button @click="selectSlot(slot)"
                                                        :disabled="isBooked(slot.id) || isPast(slot.start_time)"
                                                        :class="[
                                                            isBooked(slot.id) ? 'bg-red-50 border-red-200 cursor-not-allowed opacity-80 text-red-500' : 
                                                            (isPast(slot.start_time) ? 'bg-slate-100 border-slate-200 cursor-not-allowed opacity-50 text-slate-500' :
                                                            (selectedSlots.find(s => s.id === slot.id) ? 'bg-emerald-500 border-emerald-500 text-white shadow-md shadow-emerald-500/30' : 'bg-white border-slate-200 text-slate-700 hover:border-emerald-500/50 hover:text-emerald-500'))
                                                        ]"
                                                        class="flex items-center justify-between px-3 py-3 rounded-xl border transition-all duration-300 focus:outline-none group">
                                                    <div class="flex items-center gap-1.5">
                                                        <i class="fa-regular fa-clock text-xs opacity-70"></i>
                                                        <span class="font-medium text-sm" :class="(isBooked(slot.id) || isPast(slot.start_time)) ? 'line-through' : ''" x-text="formatTime(slot.start_time) + ' - ' + formatTime(slot.end_time)"></span>
                                                    </div>
                                                    
                                                    <template x-if="isBooked(slot.id)">
                                                        <span class="text-xs font-bold bg-red-100/60 px-2 py-0.5 rounded text-red-600">Đã đặt</span>
                                                    </template>
                                                    <template x-if="!isBooked(slot.id)">
                                                        <span class="text-sm font-bold" :class="selectedSlots.find(s => s.id === slot.id) ? 'text-white' : 'text-emerald-500'" x-text="formatCurrencyShort(getSlotPrice(slot))"></span>
                                                    </template>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Sticky Summary (Grid 4/12) -->
                    <div class="col-span-12 lg:col-span-4" data-aos="fade-left">
                        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-xl border border-slate-100 dark:border-slate-700 sticky top-28 w-full">
                            <div class="flex items-end gap-1 mb-6 border-b border-slate-100 pb-4">
                                <span class="text-3xl font-heading font-extrabold text-emerald-500">{{ number_format($field->base_price) }}đ</span>
                                <span class="text-sm font-medium text-slate-500 mb-1">/giờ</span>
                                <span class="text-xs text-slate-400 mb-1 ml-1">Giá từ</span>
                            </div>

                            <div class="space-y-4 mb-6">
                                <div>
                                    <div class="flex items-center gap-2 text-sm font-bold text-slate-700 mb-2">
                                        <i class="fa-regular fa-calendar text-slate-400"></i> Ngày đã chọn
                                    </div>
                                    <div class="bg-slate-50 px-4 py-3 rounded-xl text-sm font-medium text-slate-800" x-text="formatDateDisplay(selectedDate) + ' (Thứ ' + (new Date(selectedDate).getDay() === 0 ? 'Chủ nhật' : new Date(selectedDate).getDay() + 1) + ')'"></div>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 text-sm font-bold text-slate-700 mb-2">
                                        <i class="fa-regular fa-clock text-slate-400"></i> Khung giờ đã chọn
                                    </div>
                                    <div class="bg-slate-50 px-4 py-3 rounded-xl min-h-[48px] flex flex-wrap gap-2 items-center">
                                        <template x-if="selectedSlots.length === 0">
                                            <span class="text-sm text-slate-400">Chưa chọn khung giờ nào</span>
                                        </template>
                                        <template x-for="slot in selectedSlots" :key="slot.id">
                                            <span class="bg-white border border-slate-200 text-slate-700 px-2 py-1 rounded text-xs font-medium shadow-sm" x-text="formatTime(slot.start_time) + ' - ' + formatTime(slot.end_time)"></span>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center mb-4 text-sm">
                                <span class="text-slate-600 whitespace-nowrap" x-text="selectedSlots.length > 0 ? (formatTime(selectedSlots[0].start_time) + ' - ' + formatTime(selectedSlots[selectedSlots.length-1].end_time)) : ''"></span>
                                <span class="font-bold text-slate-800 whitespace-nowrap" x-text="formatCurrency(originalPrice)"></span>
                            </div>

                            <!-- Voucher -->
                            <div class="mb-5 pt-5 border-t border-slate-100">
                                <div class="flex items-center gap-2 text-sm font-bold text-slate-700 mb-3">
                                    <i class="fa-solid fa-ticket text-emerald-500"></i> Mã giảm giá
                                </div>
                                <div class="flex gap-2">
                                    <div class="relative flex-1">
                                        <input type="text" x-model="voucherInput" class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-4 pr-10 py-2.5 text-sm font-medium uppercase tracking-wider text-slate-800 placeholder:text-slate-400 placeholder:normal-case focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all outline-none" placeholder="Nhập mã ưu đãi...">
                                        <button x-show="voucherInput" @click="resetVoucher()" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </div>
                                    <button @click="applyVoucher()" :disabled="isProcessingVoucher || !voucherInput || selectedSlots.length === 0" class="bg-slate-800 hover:bg-slate-900 disabled:opacity-50 disabled:cursor-not-allowed text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all shadow-sm whitespace-nowrap">
                                        <span x-show="!isProcessingVoucher">Áp dụng</span>
                                        <span x-show="isProcessingVoucher"><i class="fa-solid fa-circle-notch fa-spin"></i></span>
                                    </button>
                                </div>
                                <div x-show="voucherMessage" x-transition class="text-xs mt-2.5 font-medium flex items-center gap-1.5" :class="voucherSuccess ? 'text-emerald-600' : 'text-red-500'">
                                    <i class="fa-solid" :class="voucherSuccess ? 'fa-check-circle' : 'fa-circle-exclamation'"></i>
                                    <span x-text="voucherMessage"></span>
                                </div>
                            </div>
                            
                            <!-- Discount display if applied -->
                            <div x-show="discountAmount > 0" class="flex justify-between text-sm text-emerald-600 mb-4">
                                <span>Giảm giá</span>
                                <span class="font-bold" x-text="'-' + formatCurrency(discountAmount)"></span>
                            </div>

                            <div class="flex justify-between items-center mb-6 pt-4 border-t border-slate-100">
                                <span class="text-slate-600 font-medium whitespace-nowrap">Tổng cộng</span>
                                <span class="text-2xl font-extrabold text-emerald-600 whitespace-nowrap" x-text="formatCurrency(finalPrice)"></span>
                            </div>

                            <button @click="goToCheckout()" :disabled="selectedSlots.length === 0" class="w-full font-bold py-4 rounded-xl shadow-md transition-all transform" :class="selectedSlots.length > 0 ? 'bg-emerald-500 text-white hover:bg-emerald-600 hover:-translate-y-1' : 'bg-slate-300 text-slate-500 cursor-not-allowed opacity-70'">
                                Đặt sân ngay
                            </button>
                            <p class="text-xs text-center text-slate-400 mt-4">Bạn chưa bị trừ tiền ở bước này</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 2: Checkout -->
            <div x-show="step === 2" x-transition.opacity x-cloak>
                <div class="mb-6">
                    <button @click="step = 1" class="text-slate-500 hover:text-slate-800 font-medium flex items-center gap-2 transition-colors">
                        <i class="fa-solid fa-arrow-left"></i> Quay lại
                    </button>
                    <h2 class="text-3xl font-heading font-extrabold text-slate-900 mt-4">Thanh toán đặt sân</h2>
                </div>

                <div class="grid grid-cols-12 gap-8">
                    <!-- Left: Details -->
                    <div class="col-span-12 lg:col-span-8 space-y-6">
                        <!-- Thông tin đặt sân -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                            <h3 class="text-lg font-bold text-slate-800 mb-4">Thông tin đặt sân</h3>
                            <div class="flex flex-col sm:flex-row gap-4">
                                <div class="w-full sm:w-1/3 h-32 rounded-xl overflow-hidden shadow-sm">
                                    <img src="{{ $field->image_url }}" class="w-full h-full object-cover" alt="{{ $field->name }}">
                                </div>
                                <div class="flex-1 space-y-2">
                                    <h4 class="text-xl font-bold text-slate-900">{{ $field->name }}</h4>
                                    <p class="text-sm text-slate-500"><i class="fa-solid fa-location-dot mr-1 text-slate-400"></i> Cơ sở Trung Tâm, Hà Nội</p>
                                    <div class="flex items-center gap-4 mt-2">
                                        <span class="text-sm font-medium text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-100">
                                            <i class="fa-regular fa-calendar mr-1"></i> <span x-text="formatDateDisplay(selectedDate)"></span>
                                        </span>
                                        <span class="text-sm font-medium text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-100">
                                            <i class="fa-regular fa-clock mr-1"></i> <span x-text="selectedSlots.length > 0 ? (formatTime(selectedSlots[0].start_time) + ' - ' + formatTime(selectedSlots[selectedSlots.length-1].end_time)) : ''"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Thông tin người đặt -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                            <h3 class="text-lg font-bold text-slate-800 mb-4">Thông tin người đặt</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-600 mb-1">Họ và tên <span class="text-red-500">*</span></label>
                                    <input type="text" x-model="customerName" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-emerald-500 outline-none" placeholder="Nhập họ và tên">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-600 mb-1">Số điện thoại <span class="text-red-500">*</span></label>
                                    <input type="text" x-model="customerPhone" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-emerald-500 outline-none" placeholder="Nhập số điện thoại">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-600 mb-1">Ghi chú</label>
                                    <textarea x-model="bookingNotes" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-emerald-500 outline-none" placeholder="Ghi chú thêm (không bắt buộc)"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Phương thức thanh toán -->
                        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                            <h3 class="text-lg font-bold text-slate-800 mb-4">Phương thức thanh toán</h3>
                            <div class="space-y-3">
                                <label class="flex items-center gap-3 p-4 border-2 border-emerald-500 bg-emerald-50/50 rounded-xl cursor-pointer shadow-sm">
                                    <input type="radio" checked class="text-emerald-600 focus:ring-emerald-500 w-4 h-4">
                                    <img src="https://cdn.haitrieu.com/wp-content/uploads/2022/10/Logo-VNPAY-QR-1.png" class="h-6 w-auto object-contain bg-white rounded border border-slate-200 p-0.5" alt="VNPay">
                                    <span class="font-bold text-slate-800">Thanh toán VNPay (Chuyển khoản)</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Summary -->
                    <div class="col-span-12 lg:col-span-4">
                        <div class="bg-white rounded-3xl p-6 shadow-xl border border-slate-100 sticky top-28 w-full">
                            <h3 class="text-lg font-bold text-slate-800 mb-4 pb-4 border-b border-slate-100">Tóm tắt đơn hàng</h3>
                            
                            <div class="flex items-start gap-3 mb-6">
                                <img src="{{ $field->image_url }}" class="w-16 h-12 rounded object-cover shadow-sm">
                                <div>
                                    <h4 class="font-bold text-sm text-slate-800 leading-tight">{{ $field->name }}</h4>
                                    <p class="text-xs text-slate-500">Sân {{ $field->fieldType->name }}</p>
                                </div>
                            </div>

                            <div class="flex justify-between items-center mb-6 text-sm">
                                <span class="text-slate-600" x-text="selectedSlots.length > 0 ? (formatTime(selectedSlots[0].start_time) + ' - ' + formatTime(selectedSlots[selectedSlots.length-1].end_time)) : ''"></span>
                                <span class="font-bold text-slate-800" x-text="formatCurrency(originalPrice)"></span>
                            </div>

                            <!-- Voucher applied (Read only display) -->
                            <div x-show="discountAmount > 0" class="mb-6 bg-emerald-50 border border-emerald-100 p-3 rounded-lg flex items-center justify-between">
                                <div>
                                    <span class="text-sm font-bold text-emerald-700 flex items-center gap-1"><i class="fa-solid fa-ticket"></i> Đã áp dụng mã</span>
                                    <span class="text-xs text-emerald-600 block mt-0.5" x-text="voucherCode"></span>
                                </div>
                                <span class="font-bold text-emerald-600" x-text="'-' + formatCurrency(discountAmount)"></span>
                            </div>

                            <div class="space-y-3 mb-6 pt-4 border-t border-slate-100">
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-500">Tạm tính</span>
                                    <span class="font-bold text-slate-800" x-text="formatCurrency(originalPrice)"></span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-500">Phí dịch vụ</span>
                                    <span class="font-medium text-emerald-500">Miễn phí</span>
                                </div>
                                <div x-show="discountAmount > 0" class="flex justify-between text-sm text-emerald-600">
                                    <span>Giảm giá</span>
                                    <span class="font-bold" x-text="'-' + formatCurrency(discountAmount)"></span>
                                </div>
                            </div>

                            <div class="flex justify-between items-center mb-6 pt-4 border-t border-slate-100">
                                <span class="text-slate-800 font-bold">Tổng cộng</span>
                                <span class="text-2xl font-extrabold text-emerald-600" x-text="formatCurrency(finalPrice)"></span>
                            </div>

                            @auth
                            <button @click="checkout('vnpay')" :disabled="isProcessing || !customerName || !customerPhone" class="w-full bg-emerald-600 hover:bg-emerald-700 disabled:bg-slate-300 disabled:cursor-not-allowed text-white font-bold py-4 rounded-xl shadow-lg transition-all flex items-center justify-center gap-2">
                                <span x-show="!isProcessing">Xác nhận thanh toán</span>
                                <span x-show="isProcessing"><i class="fa-solid fa-circle-notch fa-spin"></i> Đang xử lý...</span>
                            </button>
                            @endauth
                            @guest
                            <a href="{{ route('login') }}" class="block w-full bg-emerald-600 hover:bg-emerald-700 text-white text-center font-bold py-4 rounded-xl shadow-md transition-colors">
                                Đăng nhập để thanh toán
                            </a>
                            @endguest
                            
                            <p class="text-[11px] text-center text-slate-400 mt-4 leading-relaxed">
                                Bằng việc đặt sân, bạn đồng ý với <a href="#" class="text-emerald-500 underline">Điều khoản dịch vụ</a> của chúng tôi.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('bookingWizard', () => ({
            step: 1,
            basePrice: {{ $field->base_price }},
            timeSlots: @json($timeSlots),
            bookedSlotsByDate: @json($bookedSlotsByDate),
            fieldId: {{ $field->id }},
            
            dates: [],
            selectedDate: null,
            selectedSlots: [],
            
            // Voucher State
            voucherInput: '',
            appliedVoucher: null,
            voucherMessage: '',
            voucherSuccess: false,
            isProcessingVoucher: false,
            
            init() {
                // Generate next 14 days
                for(let i=0; i<14; i++) {
                    let d = new Date();
                    d.setDate(d.getDate() + i);
                    
                    const iso = d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
                    
                    this.dates.push({
                        dateObj: d,
                        isoString: iso,
                        dayName: i === 0 ? 'Hôm nay' : (i === 1 ? 'Ngày mai' : d.toLocaleDateString('vi-VN', {weekday: 'short'})),
                        dayNum: String(d.getDate()).padStart(2, '0'),
                        month: String(d.getMonth() + 1).padStart(2, '0')
                    });
                }
                if(this.dates.length > 0) {
                    this.selectedDate = this.dates[0].isoString;
                }
                
                // Watch for changes in date to reset slot
                this.$watch('selectedDate', value => {
                    this.selectedSlots = [];
                    this.resetVoucher();
                });
                
                // Also reset voucher if slots change
                this.$watch('selectedSlots', value => {
                    if (value.length === 0) {
                        this.resetVoucher();
                    }
                });
            },
            
            selectDate(isoDate) {
                this.selectedDate = isoDate;
            },
            
            selectSlot(slot) {
                if(this.isBooked(slot.id) || this.isPast(slot.start_time)) return;
                
                const index = this.selectedSlots.findIndex(s => s.id === slot.id);
                if (index > -1) {
                    this.selectedSlots.splice(index, 1);
                } else {
                    this.selectedSlots.push(slot);
                    // Sort slots by start_time to ensure they are ordered logically
                    this.selectedSlots.sort((a, b) => a.start_time.localeCompare(b.start_time));
                }
                
                this.resetVoucher();
            },
            
            isBooked(slotId) {
                if(!this.selectedDate) return false;
                const bookedIds = this.bookedSlotsByDate[this.selectedDate] || [];
                return bookedIds.includes(slotId);
            },
            
            isPast(timeStr) {
                if(!this.selectedDate || !timeStr) return false;
                
                const today = new Date();
                const todayIso = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0') + '-' + String(today.getDate()).padStart(2, '0');
                
                if (this.selectedDate < todayIso) return true;
                if (this.selectedDate > todayIso) return false;
                
                // If today, compare time
                const [hours, minutes] = timeStr.split(':');
                const slotTime = new Date();
                slotTime.setHours(parseInt(hours), parseInt(minutes), 0, 0);
                
                return slotTime < today;
            },
            
            formatTime(timeStr) {
                if(!timeStr) return '';
                return timeStr.substring(0, 5); // "17:00:00" -> "17:00"
            },
            
            isWeekend() {
                if (!this.selectedDate) return false;
                const d = new Date(this.selectedDate);
                const day = d.getDay();
                return day === 0 || day === 6;
            },
            
            getSlotPrice(slot) {
                let modifier = this.isWeekend() ? (parseFloat(slot.weekend_price_modifier) || 0) : (parseFloat(slot.price_modifier) || 0);
                return this.basePrice + modifier;
            },
            
            formatDateDisplay(isoDate) {
                if(!isoDate) return '--';
                const parts = isoDate.split('-');
                return parts[2] + '/' + parts[1] + '/' + parts[0];
            },
            
            formatCurrency(amount) {
                return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
            },
            
            formatCurrencyShort(amount) {
                return new Intl.NumberFormat('vi-VN').format(amount) + 'đ';
            },

            get morningSlots() {
                return this.timeSlots.filter(s => parseInt(s.start_time.substring(0, 2)) < 12);
            },
            get afternoonSlots() {
                return this.timeSlots.filter(s => {
                    const hour = parseInt(s.start_time.substring(0, 2));
                    return hour >= 12 && hour < 18;
                });
            },
            get eveningSlots() {
                return this.timeSlots.filter(s => parseInt(s.start_time.substring(0, 2)) >= 18);
            },
            
            goToCheckout() {
                if (this.selectedSlots.length > 0) {
                    const slotIds = this.selectedSlots.map(s => s.id).join(',');
                    let url = `{{ route('checkout') }}?field_id={{ $field->id }}&date=${this.selectedDate}&slots=${slotIds}`;
                    if (this.appliedVoucher) {
                        url += `&voucher=${this.appliedVoucher.code}`;
                    }
                    window.location.href = url;
                }
            },
            
            get originalPrice() {
                if(this.selectedSlots.length === 0) return 0;
                let total = 0;
                this.selectedSlots.forEach(slot => {
                    total += this.getSlotPrice(slot);
                });
                return total;
            },
            
            get finalPrice() {
                let price = this.originalPrice - this.discountAmount;
                return price < 0 ? 0 : price;
            },
            
            get discountAmount() {
                if(!this.appliedVoucher) return 0;
                if(this.appliedVoucher.discount_percent) {
                    return this.originalPrice * (this.appliedVoucher.discount_percent / 100);
                } else if(this.appliedVoucher.discount_amount) {
                    return parseFloat(this.appliedVoucher.discount_amount);
                }
                return 0;
            },
            
            resetVoucher() {
                this.appliedVoucher = null;
                this.voucherInput = '';
                this.voucherMessage = '';
                this.voucherSuccess = false;
            },
            
            applyVoucher() {
                if(!this.voucherInput) return;
                
                this.isProcessingVoucher = true;
                this.voucherMessage = '';
                
                fetch('/api/check-voucher', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ code: this.voucherInput })
                })
                .then(res => res.json())
                .then(data => {
                    this.isProcessingVoucher = false;
                    this.voucherSuccess = data.success;
                    this.voucherMessage = data.message || (data.success ? 'Áp dụng mã giảm giá thành công!' : 'Mã không hợp lệ');
                    
                    if(data.success) {
                        this.appliedVoucher = data.voucher;
                    } else {
                        this.appliedVoucher = null;
                    }
                })
                .catch(err => {
                    this.isProcessingVoucher = false;
                    this.voucherSuccess = false;
                    this.voucherMessage = 'Có lỗi xảy ra, vui lòng thử lại';
                });
            },
            
            triggerConfetti() {
                var duration = 3 * 1000;
                var animationEnd = Date.now() + duration;
                var defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 9999 };

                function randomInRange(min, max) {
                    return Math.random() * (max - min) + min;
                }

                var interval = setInterval(function() {
                    var timeLeft = animationEnd - Date.now();
                    if (timeLeft <= 0) {
                        return clearInterval(interval);
                    }
                    var particleCount = 50 * (timeLeft / duration);
                    confetti(Object.assign({}, defaults, { particleCount,
                        origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 }
                    }));
                    confetti(Object.assign({}, defaults, { particleCount,
                        origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 }
                    }));
                }, 250);
            }
        }));
    });
</script>

<!-- Enterprise Payment Gateway Modal -->
<div x-data="{ 
        open: false, method: '', amount: 0, code: '', bankName: '970436', accNum: '1042273294', accName: 'NGUYEN MINH QUAN',
        copied: '',
        timeLeft: 900, // 15 minutes
        timer: null,
        pollTimer: null,
        isChecking: true,
        copyToClipboard(text, field) {
            navigator.clipboard.writeText(text);
            this.copied = field;
            setTimeout(() => this.copied = '', 2000);
        },
        startTimer() {
            this.timeLeft = 900;
            this.isChecking = true;
            clearInterval(this.timer);
            clearInterval(this.pollTimer);
            
            this.timer = setInterval(() => {
                if(this.timeLeft > 0) this.timeLeft--;
                else { 
                    clearInterval(this.timer); 
                    clearInterval(this.pollTimer);
                    this.open = false; 
                    Swal.fire('Hết giờ', 'Đơn hàng đã hết thời gian thanh toán.', 'warning'); 
                }
            }, 1000);

            // Start Polling API every 3 seconds
            this.pollTimer = setInterval(() => {
                this.checkStatus();
            }, 3000);
        },
        checkStatus() {
            if(!this.code) return;
            fetch('/api/booking-status/' + this.code)
                .then(res => res.json())
                .then(data => {
                    if(data.success && (data.status === 'confirmed' || data.status === 'paid')) {
                        clearInterval(this.timer);
                        clearInterval(this.pollTimer);
                        this.open = false;
                        this.showSuccess();
                    }
                });
        },
        simulateWebhook() {
            // Secret button for DEMO purpose
            fetch('/api/webhook/simulate/' + this.code)
                .then(res => res.json())
                .then(data => {
                    console.log('Webhook simulated');
                });
        },
        showSuccess() {
            // Trigger Confetti (Assuming triggerConfetti is globally available or we can just call it from parent)
            if (typeof document.querySelector('[x-data]').__x.$data.triggerConfetti === 'function') {
                document.querySelector('[x-data]').__x.$data.triggerConfetti();
            } else {
                // Inline confetti fallback if needed, but it's on the parent
                window.dispatchEvent(new CustomEvent('trigger-confetti'));
            }
            
            Swal.fire({
                icon: 'success',
                title: 'Thanh toán thành công!',
                text: 'Hệ thống đã nhận được tiền. Mã đơn: ' + this.code + '. Đang chuyển về trang chủ...',
                showConfirmButton: false,
                timer: 3000
            }).then(() => {
                window.location.reload();
            });
        },
        formatTime(seconds) {
            const m = Math.floor(seconds / 60).toString().padStart(2, '0');
            const s = (seconds % 60).toString().padStart(2, '0');
            return `${m}:${s}`;
        }
    }" 
     @open-payment-modal.window="open = true; method = $event.detail.method; amount = $event.detail.amount; code = $event.detail.code; startTimer();"
     @trigger-confetti.window="triggerConfetti()"
     x-show="open" 
     class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 font-sans" 
     x-cloak>
    
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-slate-900/70 backdrop-blur-sm" x-show="open" x-transition.opacity></div>

    <!-- Modal Container -->
    <div class="relative bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[95vh] overflow-hidden flex flex-col md:flex-row"
         x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-8 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 scale-95"
         @click.away="open = false">
        
        <!-- Close Button -->
        <button @click="open = false" class="absolute top-4 right-4 w-8 h-8 z-50 rounded-full bg-slate-100 hover:bg-red-100 text-slate-500 hover:text-red-500 flex items-center justify-center transition-colors">
            <i class="fa-solid fa-times"></i>
        </button>

        <!-- Left Column: Order Invoice -->
        <div class="w-full md:w-5/12 bg-slate-50 p-6 md:p-8 flex flex-col border-r border-slate-200">
            <!-- Brand -->
            <div class="flex items-center gap-3 mb-8 pb-6 border-b border-slate-200">
                <div class="w-10 h-10 bg-primary text-white rounded-lg flex items-center justify-center shadow-md">
                    <i class="fa-solid fa-futbol text-xl"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-800 leading-tight">PitchManage</h2>
                    <p class="text-xs text-slate-500">Hệ thống đặt sân thông minh</p>
                </div>
            </div>

            <!-- Invoice Details -->
            <h3 class="text-base font-bold text-slate-800 mb-4">Thông tin đơn hàng</h3>
            
            <div class="space-y-4 mb-6 flex-1">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-slate-500">Mã đơn hàng:</span>
                    <span class="font-mono font-bold text-slate-800 bg-slate-200 px-2 py-0.5 rounded text-sm" x-text="code"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-slate-500">Khách hàng:</span>
                    <span class="text-sm font-semibold text-slate-800">{{ auth()->user()->name ?? 'Khách lẻ' }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-slate-500">Dịch vụ:</span>
                    <span class="text-sm font-semibold text-slate-800">Thuê sân bóng</span>
                </div>
                
                <div class="border-t border-dashed border-slate-300 my-4"></div>

                <div class="flex flex-col gap-1">
                    <span class="text-sm text-slate-500">Số tiền thanh toán:</span>
                    <span class="text-3xl font-extrabold text-slate-900" x-text="new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount)"></span>
                </div>
            </div>

            <!-- Security Badge -->
            <div class="mt-auto bg-emerald-50 rounded-lg p-3 flex items-start gap-3 border border-emerald-100">
                <i class="fa-solid fa-shield-check text-emerald-500 mt-0.5 text-lg"></i>
                <p class="text-xs text-emerald-800 leading-relaxed">Giao dịch được mã hóa an toàn bằng SSL 256-bit. Vui lòng không đóng trình duyệt khi đang thanh toán.</p>
            </div>
        </div>

        <!-- Right Column: Payment Gateway -->
        <div class="w-full md:w-7/12 bg-white p-6 md:p-10 flex flex-col relative overflow-y-auto">
            
            <!-- Gateway Header -->
            <div class="flex justify-between items-center mb-8">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 p-1.5 rounded-xl border border-slate-200 shadow-sm">
                        <img x-show="method === 'momo'" src="https://cdn.haitrieu.com/wp-content/uploads/2022/10/Logo-MoMo-Square.png" alt="MoMo" class="w-full h-full object-contain rounded-md">
                        <img x-show="method === 'vnpay'" src="https://cdn.haitrieu.com/wp-content/uploads/2022/10/Logo-VNPAY-QR-1.png" alt="VNPay" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-800" x-text="method === 'momo' ? 'Thanh toán qua MoMo' : 'Chuyển khoản VietQR'"></h3>
                        <p class="text-sm font-medium" :class="method === 'momo' ? 'text-[#A50064]' : 'text-blue-600'">Quét mã để thanh toán</p>
                    </div>
                </div>
                
                <!-- Timer -->
                <div class="text-right flex flex-col items-end">
                    <span class="text-xs text-slate-500 font-medium mb-1">Thời gian còn lại</span>
                    <div class="bg-slate-100 px-3 py-1.5 rounded-lg flex items-center gap-2 border border-slate-200">
                        <i class="fa-regular fa-clock text-slate-600 animate-pulse"></i>
                        <span class="font-mono font-bold text-slate-800 text-lg" x-text="formatTime(timeLeft)"></span>
                    </div>
                </div>
            </div>

            <div class="flex flex-col xl:flex-row gap-8 items-center justify-center flex-1">
                
                <!-- QR Scanner Area -->
                <div class="relative flex-shrink-0">
                    <div class="absolute -inset-1 bg-gradient-to-r from-primary to-secondary rounded-3xl blur opacity-20"></div>
                    <div class="relative bg-white p-4 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-slate-100">
                        
                        <div class="relative w-56 h-56 md:w-64 md:h-64 rounded-xl overflow-hidden group border border-slate-100">
                            <!-- MoMo QR -->
                            <img x-show="method === 'momo'" src="/images/momo_qr.jpg" alt="MoMo QR" class="w-full h-full object-contain" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg'">
                            <img x-show="method === 'vnpay'" :src="`https://img.vietqr.io/image/${bankName}-${accNum}-compact2.png?amount=${amount}&addInfo=${encodeURIComponent(code)}&accountName=${encodeURIComponent(accName)}`" alt="VietQR" class="w-full h-full object-contain">
                            
                            <!-- Scanner Animation -->
                            <div class="absolute inset-0 pointer-events-none">
                                <div class="w-full h-[2px] shadow-[0_0_15px_3px_rgba(0,0,0,0.5)] animate-[scan_2s_ease-in-out_infinite]" :class="method === 'momo' ? 'bg-[#ff33a6] shadow-[#ff33a6]' : 'bg-primary shadow-primary'"></div>
                                <div class="w-full h-24 animate-[scan-bg_2s_ease-in-out_infinite] -translate-y-full" :class="method === 'momo' ? 'bg-gradient-to-b from-transparent to-[#ff33a6]/20' : 'bg-gradient-to-b from-transparent to-primary/20'"></div>
                            </div>
                            
                            <!-- Corners -->
                            <div class="absolute top-2 left-2 w-8 h-8 border-t-4 border-l-4 rounded-tl-lg" :class="method === 'momo' ? 'border-[#A50064]' : 'border-blue-600'"></div>
                            <div class="absolute top-2 right-2 w-8 h-8 border-t-4 border-r-4 rounded-tr-lg" :class="method === 'momo' ? 'border-[#A50064]' : 'border-blue-600'"></div>
                            <div class="absolute bottom-2 left-2 w-8 h-8 border-b-4 border-l-4 rounded-bl-lg" :class="method === 'momo' ? 'border-[#A50064]' : 'border-blue-600'"></div>
                            <div class="absolute bottom-2 right-2 w-8 h-8 border-b-4 border-r-4 rounded-br-lg" :class="method === 'momo' ? 'border-[#A50064]' : 'border-blue-600'"></div>
                        </div>
                    </div>
                </div>

                <!-- Manual Transfer Details -->
                <div class="w-full max-w-sm">
                    <p class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2"><i class="fa-solid fa-money-bill-transfer text-slate-400"></i> Hoặc chuyển khoản thủ công</p>
                    
                    <div class="space-y-3">
                        <!-- Account Box -->
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-3" x-show="method === 'vnpay'">
                            <p class="text-[10px] uppercase font-bold text-slate-500 mb-1">Tài khoản nhận</p>
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-bold text-slate-800" x-text="bankName"></p>
                                    <p class="font-mono font-bold text-primary text-lg" x-text="accNum"></p>
                                    <p class="text-xs font-semibold text-slate-600" x-text="accName"></p>
                                </div>
                                <button @click="copyToClipboard(accNum, 'accNum')" class="w-10 h-10 rounded-lg flex items-center justify-center bg-white border border-slate-200 text-slate-600 hover:text-primary hover:border-primary transition-colors shadow-sm">
                                    <i class="fa-regular fa-copy" x-show="copied !== 'accNum'"></i>
                                    <i class="fa-solid fa-check text-emerald-500" x-show="copied === 'accNum'" x-cloak></i>
                                </button>
                            </div>
                        </div>

                        <!-- Content Box -->
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-3">
                            <p class="text-[10px] uppercase font-bold text-amber-700 mb-1">Nội dung chuyển khoản</p>
                            <div class="flex justify-between items-center">
                                <p class="font-mono font-bold text-amber-600 text-lg" x-text="code"></p>
                                <button @click="copyToClipboard(code, 'code')" class="w-10 h-10 rounded-lg flex items-center justify-center bg-white border border-amber-200 text-amber-600 hover:text-amber-700 hover:border-amber-400 transition-colors shadow-sm">
                                    <i class="fa-regular fa-copy" x-show="copied !== 'code'"></i>
                                    <i class="fa-solid fa-check text-emerald-500" x-show="copied === 'code'" x-cloak></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex flex-col gap-2">
                        <button disabled class="w-full bg-slate-100 border border-slate-200 text-slate-500 font-bold py-3.5 rounded-xl transition-all flex items-center justify-center gap-2 cursor-wait">
                            <i class="fa-solid fa-spinner fa-spin text-primary"></i> Hệ thống đang tự động kiểm tra...
                        </button>

                        <!-- Secret button for DEMO -->
                        <button @click="simulateWebhook()" class="w-full bg-emerald-100 border border-emerald-200 text-emerald-700 font-bold py-2 rounded-xl transition-all hover:bg-emerald-200 text-sm flex items-center justify-center gap-2" title="Dành cho lúc test: Giả lập ngân hàng đã chuyển tiền">
                            <i class="fa-solid fa-wand-magic-sparkles"></i> [Dành cho Test] Bấm vào đây để giả lập đã chuyển tiền
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Footer logos -->
            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-center gap-6 opacity-50">
                <img src="https://vnpay.vn/assets/images/logo-icon/logo-primary.svg" class="h-4 grayscale" alt="VNPay">
                <img src="https://upload.wikimedia.org/wikipedia/vi/f/fe/MoMo_Logo.png" class="h-4 grayscale" alt="MoMo">
                <img src="https://napas.com.vn/StaticFiles/Images/logo.svg" class="h-4 grayscale" alt="Napas">
            </div>
        </div>
    </div>
</div>

<style>
    .hide-scroll::-webkit-scrollbar {
        display: none;
    }
    .hide-scroll {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    
    @keyframes scan {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(14rem); }
    }
    @keyframes scan-bg {
        0%, 100% { transform: translateY(-100%); opacity: 0; }
        10%, 40% { opacity: 1; }
        50% { transform: translateY(14rem); opacity: 0; }
        60%, 90% { opacity: 1; }
    }
    @keyframes bounce-slow {
        0%, 100% { transform: translateX(-50%) translateY(0); }
        50% { transform: translateX(-50%) translateY(-25%); }
    }
    .animate-bounce-slow {
        animation: bounce-slow 2s infinite ease-in-out;
    }
</style>
@endpush
