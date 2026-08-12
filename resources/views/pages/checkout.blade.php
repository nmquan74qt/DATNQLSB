@extends('layouts.app')

@section('title', 'Thanh toán đặt sân - ' . $field->name)

@section('content')
<div class="bg-slate-50 dark:bg-slate-900 pt-32 pb-24 relative z-20 min-h-screen" x-data="checkoutPage()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-6">
            <a href="{{ route('field.detail', $field->slug) }}" class="text-slate-500 hover:text-slate-800 font-medium flex items-center gap-2 transition-colors">
                <i class="fa-solid fa-arrow-left"></i> Quay lại
            </a>
            <h2 class="text-3xl font-heading font-extrabold text-slate-900 mt-4">Thanh toán đặt sân</h2>
        </div>

        <div class="grid grid-cols-12 gap-8">
            <!-- Left: Details -->
            <div class="col-span-12 lg:col-span-8 space-y-6">
                <!-- Thông tin đặt sân (Enterprise Banner Layout) -->
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-6">
                    <!-- Banner Image -->
                    <div class="w-full h-48 bg-slate-100 relative flex items-center justify-center">
                        <i class="fa-solid fa-image text-slate-300 text-5xl absolute z-0"></i>
                        <img src="{{ $field->image_url }}" onerror="this.style.display='none'" class="w-full h-full object-cover relative z-10" alt="{{ $field->name }}">
                        
                        <!-- Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/40 to-transparent z-20"></div>
                        
                        <!-- Text -->
                        <div class="absolute bottom-0 left-0 w-full p-6 z-30">
                            <h3 class="text-xs font-bold text-emerald-400 uppercase tracking-widest mb-1 shadow-sm">Thông tin đặt sân</h3>
                            <h4 class="text-3xl font-extrabold text-white drop-shadow-md">{{ $field->name }}</h4>
                            <p class="text-sm text-slate-200 mt-2 flex items-center gap-2 drop-shadow"><i class="fa-solid fa-location-dot text-emerald-400"></i> Cơ sở Trung Tâm, Hà Nội</p>
                        </div>
                    </div>
                    
                    <!-- Details Row -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 divide-y sm:divide-y-0 sm:divide-x divide-slate-100 bg-white">
                        <div class="p-5 flex items-center gap-4 hover:bg-slate-50 transition-colors">
                            <div class="w-12 h-12 shrink-0 rounded-full bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 shadow-sm">
                                <i class="fa-regular fa-calendar-check text-xl"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-0.5">Ngày đá</p>
                                <p class="font-bold text-slate-800 truncate" x-text="formatDateDisplayWithDay('{{ $date }}')"></p>
                            </div>
                        </div>
                        <div class="p-5 flex items-center gap-4 hover:bg-slate-50 transition-colors">
                            <div class="w-12 h-12 shrink-0 rounded-full bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 shadow-sm">
                                <i class="fa-regular fa-clock text-xl"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-0.5">Khung giờ</p>
                                <p class="font-bold text-slate-800 truncate">{{ substr($timeSlots->first()->start_time, 0, 5) }} - {{ substr($timeSlots->last()->end_time, 0, 5) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thông tin người đặt -->
                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Thông tin người đặt</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="flex items-center gap-2 text-sm font-medium text-slate-700 mb-1.5"><i class="fa-regular fa-user text-slate-400"></i> Họ và tên <span class="text-red-500">*</span></label>
                            <input type="text" x-model="customerName" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-emerald-500 outline-none transition-shadow" placeholder="Nhập họ và tên">
                        </div>
                        <div>
                            <label class="flex items-center gap-2 text-sm font-medium text-slate-700 mb-1.5"><i class="fa-solid fa-phone text-slate-400"></i> Số điện thoại <span class="text-red-500">*</span></label>
                            <input type="text" x-model="customerPhone" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-emerald-500 outline-none transition-shadow" placeholder="Nhập số điện thoại">
                        </div>
                        <div>
                            <label class="flex items-center gap-2 text-sm font-medium text-slate-700 mb-1.5"><i class="fa-regular fa-note-sticky text-slate-400"></i> Ghi chú</label>
                            <textarea x-model="bookingNotes" rows="2" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-emerald-500 outline-none transition-shadow" placeholder="Ghi chú thêm (không bắt buộc)"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Phương thức thanh toán -->
                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Phương thức thanh toán</h3>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-colors" :class="paymentMethod === 'vnpay' ? 'border-emerald-500 bg-emerald-50/30' : 'border-slate-100 hover:border-emerald-300'">
                            <input type="radio" value="vnpay" x-model="paymentMethod" class="text-emerald-600 focus:ring-emerald-500 w-4 h-4 border-slate-300">
                            <img src="https://cdn.haitrieu.com/wp-content/uploads/2022/10/Logo-VNPAY-QR-1.png" class="h-6 w-auto object-contain bg-white rounded border border-slate-200 p-0.5" alt="VNPay">
                            <span class="font-bold" :class="paymentMethod === 'vnpay' ? 'text-slate-700' : 'text-slate-600'">Thanh toán VNPay (Chuyển khoản)</span>
                        </label>

                        <label class="flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-colors" :class="paymentMethod === 'momo' ? 'border-emerald-500 bg-emerald-50/30' : 'border-slate-100 hover:border-emerald-300'">
                            <input type="radio" value="momo" x-model="paymentMethod" class="text-emerald-600 focus:ring-emerald-500 w-4 h-4 border-slate-300">
                            <img src="https://upload.wikimedia.org/wikipedia/vi/f/fe/MoMo_Logo.png" class="h-6 w-auto object-contain bg-white rounded border border-slate-200 p-0.5" alt="MoMo">
                            <span class="font-bold" :class="paymentMethod === 'momo' ? 'text-slate-700' : 'text-slate-600'">Thanh toán Ví MoMo</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Right: Summary -->
            <div class="col-span-12 lg:col-span-4">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 sticky top-28 w-full">
                    <h3 class="text-lg font-bold text-slate-800 mb-5 pb-4 border-b border-slate-100">Tóm tắt đơn hàng</h3>
                    
                    <div class="flex items-start gap-3 mb-6">
                        <div class="w-16 h-16 shrink-0 rounded-xl overflow-hidden shadow-sm border border-slate-100 relative bg-slate-50 flex items-center justify-center">
                            <i class="fa-solid fa-image text-slate-300 absolute z-0 text-lg"></i>
                            <img src="{{ $field->image_url }}" onerror="this.style.display='none'" class="w-full h-full object-cover relative z-10" alt="{{ $field->name }}">
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-bold text-sm text-slate-800 leading-tight truncate">{{ $field->name }}</h4>
                            <p class="text-xs text-slate-500 mt-1 truncate">{{ $field->fieldType?->name ?? 'Sân 5 người' }}</p>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mb-6 text-sm">
                        <span class="text-slate-600 font-medium whitespace-nowrap">{{ substr($timeSlots->first()->start_time, 0, 5) }} - {{ substr($timeSlots->last()->end_time, 0, 5) }}</span>
                        <span class="font-bold text-slate-800 whitespace-nowrap" x-text="formatCurrency(originalPrice)"></span>
                    </div>

                    <!-- Voucher -->
                    <div class="mb-6">
                        <p class="text-sm font-semibold text-slate-700 mb-2"><i class="fa-solid fa-ticket text-emerald-500 mr-2"></i> Mã giảm giá</p>
                        <div class="flex gap-2">
                            <input type="text" x-model="voucherInput" class="flex-1 bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm uppercase focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-shadow" placeholder="NHẬP MÃ GIẢM GIÁ">
                            <button @click="applyVoucher()" :disabled="isProcessingVoucher || !voucherInput" class="bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 text-white px-5 py-2 rounded-lg font-semibold text-sm transition-colors whitespace-nowrap">
                                <span x-show="!isProcessingVoucher">Áp dụng</span>
                                <span x-show="isProcessingVoucher"><i class="fa-solid fa-spinner fa-spin"></i></span>
                            </button>
                        </div>
                        <div x-show="voucherMessage" class="text-xs mt-2 font-medium" :class="voucherSuccess ? 'text-emerald-600' : 'text-red-500'" x-html="voucherMessage"></div>
                    </div>

                    <div class="space-y-3 mb-6 pt-5 border-t border-slate-100">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500 whitespace-nowrap">Tạm tính</span>
                            <span class="font-semibold text-slate-800 whitespace-nowrap" x-text="formatCurrency(originalPrice)"></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500 whitespace-nowrap">Phí dịch vụ</span>
                            <span class="font-medium text-emerald-500 whitespace-nowrap">Miễn phí</span>
                        </div>
                        <div x-show="discountAmount > 0" class="flex justify-between text-sm text-emerald-600 font-medium">
                            <span class="whitespace-nowrap">Giảm giá</span>
                            <span class="whitespace-nowrap" x-text="'-' + formatCurrency(discountAmount)"></span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mb-6 pt-5 border-t border-slate-100">
                        <span class="text-slate-800 font-bold whitespace-nowrap">Tổng cộng</span>
                        <span class="text-2xl font-extrabold text-emerald-600 whitespace-nowrap" x-text="formatCurrency(finalPrice)"></span>
                    </div>

                    @auth
                    <button @click="checkout()" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 rounded-xl shadow-md transition-all flex items-center justify-center gap-2" :class="isProcessing ? 'opacity-70 cursor-wait' : ''">
                        <span x-show="!isProcessing">Xác nhận thanh toán</span>
                        <span x-show="isProcessing"><i class="fa-solid fa-circle-notch fa-spin"></i> Đang xử lý...</span>
                    </button>
                    @endauth
                    @guest
                    <a href="{{ route('login') }}" class="block w-full bg-emerald-600 hover:bg-emerald-700 text-white text-center font-bold py-3.5 rounded-xl shadow-md transition-colors">
                        Đăng nhập để thanh toán
                    </a>
                    @endguest
                    
                    <p class="text-xs text-center text-slate-500 mt-5 leading-relaxed">
                        Bằng việc đặt sân, bạn đồng ý với <a href="#" class="text-emerald-600 font-medium hover:underline">Điều khoản dịch vụ</a> của chúng tôi.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('checkoutPage', () => ({
            fieldId: {{ $field->id }},
            selectedDate: '{{ $date }}',
            timeSlots: @json($timeSlots),
            basePrice: {{ $field->base_price }},
            
            customerName: '{{ auth()->check() ? auth()->user()->name : "" }}',
            customerPhone: '{{ auth()->check() ? (auth()->user()->phone ?? "") : "" }}',
            bookingNotes: '',
            paymentMethod: 'vnpay',
            
            voucherInput: '',
            appliedVoucher: null,
            voucherMessage: '',
            voucherSuccess: false,
            isProcessingVoucher: false,
            isProcessing: false,
            
            formatCurrency(amount) {
                return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
            },
            
            formatDateDisplay(isoDate) {
                if(!isoDate) return '--';
                const parts = isoDate.split('-');
                return parts[2] + '/' + parts[1] + '/' + parts[0];
            },
            
            formatDateDisplayWithDay(isoDate) {
                if(!isoDate) return '--';
                const date = new Date(isoDate);
                const days = ['Chủ nhật', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7'];
                const dayName = days[date.getDay()];
                const parts = isoDate.split('-');
                return dayName + ', ' + parts[2] + '/' + parts[1] + '/' + parts[0];
            },
            
            get originalPrice() {
                if(this.timeSlots.length === 0) return 0;
                let total = 0;
                this.timeSlots.forEach(slot => {
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
            
            checkout() {
                if(!this.selectedDate || this.timeSlots.length === 0) return;
                
                if (!this.customerName || !this.customerName.trim() || !this.customerPhone || !this.customerPhone.trim()) {
                    Swal.fire('Thiếu thông tin!', 'Vui lòng điền đầy đủ <b>Họ và tên</b> và <b>Số điện thoại</b> để tiếp tục.', 'warning');
                    return;
                }
                
                if (this.isProcessing) return;
                this.isProcessing = true;
                
                Swal.fire({
                    title: 'Đang tạo đơn hàng...',
                    html: 'Vui lòng chờ trong giây lát',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });
                
                fetch('{{ route("book") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        field_id: this.fieldId,
                        booking_date: this.selectedDate,
                        slots: this.timeSlots.map(s => ({ start_time: s.start_time, end_time: s.end_time })),
                        total_amount: this.finalPrice,
                        payment_method: this.paymentMethod,
                        voucher_code: this.appliedVoucher ? this.appliedVoucher.code : null,
                        notes: this.bookingNotes,
                        booking_code: ''
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
                            // Xử lý thanh toán tại sân hoặc momo (nếu không có redirect)
                            Swal.fire({
                                title: 'Thành công!',
                                text: data.message || 'Đặt sân thành công!',
                                icon: 'success',
                                confirmButtonColor: '#10B981',
                            }).then(() => {
                                window.location.href = '{{ (auth()->check() && (auth()->user()->role === "admin" || auth()->user()->role === "staff")) ? route("admin.dashboard") : route("customer.dashboard") }}';
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
            }
        }));
    });
</script>
@endpush
