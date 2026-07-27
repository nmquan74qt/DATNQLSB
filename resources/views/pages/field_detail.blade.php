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
                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-star text-warning"></i> 4.9/5 (128 Đánh giá)</span>
                </div>
            </div>

            <!-- Image Gallery (Airbnb Style) -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-2 h-[350px] md:h-[450px] rounded-2xl overflow-hidden shadow-sm" data-aos="fade-up" data-aos-delay="100">
                <!-- Main Large Image -->
                <div class="md:col-span-2 h-full relative group cursor-pointer overflow-hidden">
                    <img src="{{ $field->image ?? 'https://images.unsplash.com/photo-1551958219-acbc608c6477?auto=format&fit=crop&w=2070&q=80' }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="{{ $field->name }}">
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
                </div>
                <!-- Right Side Images -->
                <div class="hidden md:flex flex-col gap-2 h-full md:col-span-1">
                    <div class="h-1/2 relative group cursor-pointer overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1518609878373-06d740f60d8b?auto=format&fit=crop&w=1000&q=80" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="Góc sân 1">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
                    </div>
                    <div class="h-1/2 relative group cursor-pointer overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1579952363873-27f3bade9f55?auto=format&fit=crop&w=1000&q=80" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="Góc sân 2">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
                    </div>
                </div>
                <div class="hidden md:flex flex-col gap-2 h-full md:col-span-1">
                    <div class="h-1/2 relative group cursor-pointer overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1431324155629-1a6fc1ac5e52?auto=format&fit=crop&w=1000&q=80" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="Trang thiết bị 1">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
                    </div>
                    <div class="h-1/2 relative group cursor-pointer overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1508344928928-7165b67de128?auto=format&fit=crop&w=1000&q=80" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="Trang thiết bị 2">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-slate-50 dark:bg-slate-900 py-12 relative z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-12 gap-8">
                
                <!-- Info Section (Grid 4/12) -->
                <div class="col-span-12 lg:col-span-4 space-y-8" data-aos="fade-right">
                    
                    <!-- Pricing Card with Animated Border -->
                    <div class="relative bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-xl overflow-hidden group">
                        <!-- Animated Border Effect (via pseudo element in CSS or simple div) -->
                        <div class="absolute inset-0 bg-gradient-to-r from-primary via-secondary to-primary opacity-20 blur-xl group-hover:opacity-40 transition-opacity duration-500"></div>
                        
                        <div class="relative z-10">
                            <h3 class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Giá thuê sân</h3>
                            <div class="flex items-end gap-2 mb-6">
                                <span class="text-4xl font-heading font-extrabold text-slate-900 dark:text-white">{{ number_format($field->base_price) }}đ</span>
                                <span class="text-lg font-medium text-slate-500 dark:text-slate-400 mb-1">/ giờ</span>
                            </div>

                            <ul class="space-y-4 mb-8">
                                <li class="flex items-center gap-3 text-sm text-slate-700 dark:text-slate-300">
                                    <i class="fa-solid fa-check text-emerald-500"></i> Miễn phí nước suối & bóng đá
                                </li>
                                <li class="flex items-center gap-3 text-sm text-slate-700 dark:text-slate-300">
                                    <i class="fa-solid fa-check text-emerald-500"></i> Hỗ trợ áo pitch (bib) miễn phí
                                </li>
                                <li class="flex items-center gap-3 text-sm text-slate-700 dark:text-slate-300">
                                    <i class="fa-solid fa-check text-emerald-500"></i> Đèn LED chuẩn FIFA chiếu sáng
                                </li>
                            </ul>

                            <button onclick="scrollToCalendar()" class="w-full bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold py-4 rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1 magnetic-btn relative overflow-hidden">
                                <span class="relative z-10 btn-text">Chọn Giờ Đặt Sân</span>
                            </button>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-100 dark:border-slate-700 shadow-sm">
                        <h3 class="text-xl font-heading font-bold text-slate-900 dark:text-white mb-4">Về Sân Bóng Này</h3>
                        <div class="prose dark:prose-invert prose-slate max-w-none text-sm leading-relaxed">
                            <p>{{ $field->description ?? 'Đang cập nhật mô tả...' }}</p>
                            <p>Sân được bảo trì thường xuyên, đảm bảo mặt cỏ luôn ở trạng thái tốt nhất. Phù hợp cho các giải đấu phong trào hoặc luyện tập hàng tuần.</p>
                        </div>
                    </div>

                </div>

                <!-- Booking Calendar (Grid 8/12) -->
                <div class="col-span-12 lg:col-span-8" id="booking-section" data-aos="fade-up" x-data="bookingWizard()">
                    <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 md:p-8 border border-slate-100 dark:border-slate-700 shadow-xl relative overflow-hidden">
                        
                        <!-- Confetti Canvas (Hidden by default) -->
                        <canvas id="confetti-canvas" class="absolute inset-0 w-full h-full pointer-events-none z-50"></canvas>

                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4 border-b border-slate-100 pb-6">
                            <div>
                                <h2 class="text-2xl font-heading font-bold text-slate-900">Đặt Sân Bóng</h2>
                                <p class="text-sm text-slate-500 mt-1">Chọn thời gian để giữ chỗ ngay!</p>
                            </div>
                            <!-- Thêm ảnh sân bóng vào mục đặt sân như yêu cầu -->
                            <div class="w-24 h-16 sm:w-32 sm:h-24 rounded-lg overflow-hidden border-2 border-primary/20 shadow-sm flex-shrink-0">
                                <img src="{{ $field->image ?? 'https://images.unsplash.com/photo-1518605363189-9854359db5a3?auto=format&fit=crop&w=300&q=80' }}" class="w-full h-full object-cover" alt="{{ $field->name }}">
                            </div>
                        </div>

                        <!-- Wizard Step 1: Select Date -->
                        <div class="mb-8">
                            <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-4"><i class="fa-regular fa-calendar mr-2"></i> 1. Chọn ngày</h3>
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
                        <div class="mb-8" x-show="selectedDate" x-transition>
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider"><i class="fa-regular fa-clock mr-2"></i> 2. Chọn khung giờ</h3>
                                <button @click="selectAllSlots()" class="text-xs bg-primary/10 text-primary border border-primary/20 hover:bg-primary hover:text-white px-3 py-1.5 rounded-lg font-bold transition-all shadow-sm">
                                    <i class="fa-solid fa-check-double mr-1"></i> Thuê Trọn Ngày
                                </button>
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                                <template x-for="slot in timeSlots" :key="slot.id">
                                    <button @click="selectSlot(slot)"
                                            :disabled="isBooked(slot.id)"
                                            :class="[
                                                isBooked(slot.id) ? 'bg-slate-100 border-transparent cursor-not-allowed opacity-60' : 
                                                (selectedSlots.find(s => s.id === slot.id) ? 'bg-emerald-500 border-emerald-500 text-white shadow-md shadow-emerald-500/30' : 'bg-white border-slate-200 text-slate-700 hover:border-emerald-500/50 hover:text-emerald-500')
                                            ]"
                                            class="flex flex-col items-center justify-center py-3 rounded-xl border transition-all duration-300 focus:outline-none relative overflow-hidden group">
                                        <span class="font-bold text-lg leading-none mb-1" x-text="formatTime(slot.start_time) + ' - ' + formatTime(slot.end_time)"></span>
                                        
                                        <!-- Price Modifier Badge if any -->
                                        <template x-if="slot.price_modifier > 0 && !isBooked(slot.id)">
                                            <span class="text-[10px] font-bold bg-amber-100 text-amber-600 px-2 py-0.5 rounded-full absolute top-1 right-1">+Giờ vàng</span>
                                        </template>

                                        <!-- Booked Status -->
                                        <template x-if="isBooked(slot.id)">
                                            <span class="text-xs font-bold mt-1 text-slate-400"><i class="fa-solid fa-lock text-[10px]"></i> Đã đặt</span>
                                        </template>
                                        <template x-if="!isBooked(slot.id)">
                                            <span class="text-xs font-medium mt-1 opacity-80" x-text="formatCurrency(basePrice + parseFloat(slot.price_modifier))"></span>
                                        </template>
                                    </button>
                                </template>
                            </div>
                            <template x-if="timeSlots.length === 0">
                                <div class="text-center py-8 text-slate-500">Không có khung giờ nào được cấu hình cho sân này.</div>
                            </template>
                        </div>

                        <!-- Wizard Step 3: Checkout -->
                        <div x-show="selectedSlots.length > 0" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="p-6 bg-slate-50 rounded-3xl border border-primary/20 shadow-inner">
                            <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-4"><i class="fa-solid fa-check-to-slot mr-2"></i> 3. Xác nhận & Thanh toán</h3>
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                                <div>
                                    <p class="text-xs text-slate-500 uppercase tracking-wider font-bold mb-1">Ngày</p>
                                    <p class="font-medium text-slate-800" x-text="formatDateDisplay(selectedDate)"></p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 uppercase tracking-wider font-bold mb-1">Bắt đầu</p>
                                    <p class="font-medium text-slate-800" x-text="selectedSlots.length > 0 ? formatTime(selectedSlots[0].start_time) : '--'"></p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 uppercase tracking-wider font-bold mb-1">Kết thúc</p>
                                    <p class="font-medium text-slate-800" x-text="selectedSlots.length > 0 ? formatTime(selectedSlots[selectedSlots.length - 1].end_time) : '--'"></p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 uppercase tracking-wider font-bold mb-1">Tổng tiền</p>
                                    <p class="font-bold text-slate-400 text-sm line-through" x-show="discountAmount > 0" x-text="formatCurrency(originalPrice)"></p>
                                    <p class="font-bold text-primary text-lg" x-text="formatCurrency(finalPrice)"></p>
                                </div>
                            </div>
                            
                            <!-- Voucher Section -->
                            <div class="mb-6 bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 flex gap-2">
                                <div class="flex-1 relative">
                                    <i class="fa-solid fa-ticket absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="text" x-model="voucherInput" class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-lg pl-10 pr-4 py-3 text-sm focus:ring-2 focus:ring-primary uppercase font-bold text-slate-700 dark:text-slate-200" placeholder="Nhập mã khuyến mãi">
                                </div>
                                <button @click="applyVoucher()" :disabled="isProcessingVoucher || !voucherInput" class="bg-slate-900 dark:bg-slate-700 hover:bg-slate-800 disabled:opacity-50 text-white px-6 py-3 rounded-lg font-bold transition-colors whitespace-nowrap">
                                    <span x-show="!isProcessingVoucher">Áp dụng</span>
                                    <span x-show="isProcessingVoucher"><i class="fa-solid fa-spinner fa-spin"></i></span>
                                </button>
                            </div>
                            <div x-show="voucherMessage" class="text-sm mb-4 font-bold" :class="voucherSuccess ? 'text-emerald-500' : 'text-red-500'" x-html="voucherMessage"></div>
                            
                            <div class="flex flex-col sm:flex-row gap-4">
                                <button @click="checkout('vnpay')" :disabled="isProcessing" class="flex-1 bg-primary hover:bg-secondary text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-primary/30 transform hover:-translate-y-1 transition-all flex items-center justify-center gap-2 disabled:opacity-70 disabled:hover:translate-y-0">
                                    <span x-show="!isProcessing" class="flex items-center"><img src="https://cdn.haitrieu.com/wp-content/uploads/2022/10/Logo-VNPAY-QR-1.png" class="h-5 w-auto object-contain inline-block mr-2 bg-white rounded-sm p-0.5" alt="VNPay"> Thanh toán VNPay</span>
                                    <span x-show="isProcessing"><i class="fa-solid fa-circle-notch fa-spin"></i> Đang xử lý...</span>
                                </button>
                                <button @click="checkout('momo')" :disabled="isProcessing" class="flex-1 bg-[#A50064] hover:bg-[#8A0053] text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-[#A50064]/30 transform hover:-translate-y-1 transition-all flex items-center justify-center gap-2 disabled:opacity-70 disabled:hover:translate-y-0">
                                    <span x-show="!isProcessing" class="flex items-center"><img src="https://cdn.haitrieu.com/wp-content/uploads/2022/10/Logo-MoMo-Square.png" class="h-5 w-5 object-contain inline-block mr-2 rounded-sm" alt="MoMo"> Thanh toán MoMo</span>
                                    <span x-show="isProcessing"><i class="fa-solid fa-circle-notch fa-spin"></i> Đang xử lý...</span>
                                </button>
                            </div>
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
            basePrice: {{ $field->base_price }},
            timeSlots: @json($timeSlots),
            bookedSlotsByDate: @json($bookedSlotsByDate),
            fieldId: {{ $field->id }},
            
            dates: [],
            selectedDate: null,
            selectedSlots: [],
            
            voucherInput: '',
            appliedVoucher: null,
            voucherMessage: '',
            voucherSuccess: false,
            isProcessingVoucher: false,
            isProcessing: false,
            
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
            },
            
            selectDate(isoDate) {
                this.selectedDate = isoDate;
            },
            
            selectSlot(slot) {
                if(this.isBooked(slot.id)) return;
                
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
            
            selectAllSlots() {
                this.selectedSlots = [];
                this.timeSlots.forEach(slot => {
                    if (!this.isBooked(slot.id)) {
                        this.selectedSlots.push(slot);
                    }
                });
                this.selectedSlots.sort((a, b) => a.start_time.localeCompare(b.start_time));
                this.resetVoucher();
            },
            
            isBooked(slotId) {
                if(!this.selectedDate) return false;
                const bookedIds = this.bookedSlotsByDate[this.selectedDate] || [];
                return bookedIds.includes(slotId);
            },
            
            formatTime(timeStr) {
                if(!timeStr) return '';
                return timeStr.substring(0, 5); // "17:00:00" -> "17:00"
            },
            
            formatDateDisplay(isoDate) {
                if(!isoDate) return '--';
                const parts = isoDate.split('-');
                return parts[2] + '/' + parts[1] + '/' + parts[0];
            },
            
            formatCurrency(amount) {
                return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
            },
            
            get originalPrice() {
                if(this.selectedSlots.length === 0) return 0;
                let total = 0;
                this.selectedSlots.forEach(slot => {
                    total += this.basePrice + parseFloat(slot.price_modifier);
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
                
                fetch('{{ route("voucher.check") }}', {
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
                    
                    if (data.success) {
                        this.appliedVoucher = data.voucher;
                        this.voucherSuccess = true;
                        this.voucherMessage = `<i class="fa-solid fa-check-circle"></i> Áp dụng thành công! Đã giảm ${this.formatCurrency(this.discountAmount)}`;
                    } else {
                        this.resetVoucher();
                        this.voucherSuccess = false;
                        this.voucherMessage = `<i class="fa-solid fa-circle-exclamation"></i> ${data.message}`;
                    }
                })
                .catch(err => {
                    this.isProcessingVoucher = false;
                    this.voucherSuccess = false;
                    this.voucherMessage = `<i class="fa-solid fa-circle-exclamation"></i> Có lỗi xảy ra.`;
                });
            },
            
            checkout(paymentMethod) {
                if(!this.selectedDate || this.selectedSlots.length === 0) return;
                
                this.isProcessing = true;
                this.paymentMethod = paymentMethod;
                
                Swal.fire({
                    title: 'Đang tạo đơn hàng...',
                    html: 'Vui lòng chờ trong giây lát',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });
                
                // Create booking with status pending
                fetch('{{ route("book") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        field_id: this.fieldId,
                        booking_date: this.selectedDate,
                        slots: this.selectedSlots.map(s => ({ start_time: s.start_time, end_time: s.end_time })),
                        total_amount: this.finalPrice,
                        payment_method: this.paymentMethod,
                        voucher_code: this.appliedVoucher ? this.appliedVoucher.code : null,
                        booking_code: '' // Let backend generate it
                    })
                })
                .then(res => res.json())
                .then(data => {
                    this.isProcessing = false;
                    if (data.success) {
                        if (data.redirect_url) {
                            Swal.fire({
                                title: 'Đang chuyển hướng...',
                                text: 'Vui lòng chờ trong giây lát',
                                icon: 'info',
                                showConfirmButton: false,
                                timer: 2000
                            });
                            window.location.href = data.redirect_url;
                        } else {
                            Swal.close();
                            // Open QR Modal for MoMo
                            this.$dispatch('open-payment-modal', {
                                method: paymentMethod,
                                amount: this.finalPrice,
                                code: data.booking_code
                            });
                        }
                    } else {
                        Swal.close();
                        Swal.fire('Lỗi!', data.message, 'error');
                    }
                })
                .catch(err => {
                    this.isProcessing = false;
                    Swal.fire('Lỗi!', 'Có lỗi xảy ra, vui lòng thử lại.', 'error');
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
