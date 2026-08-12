@extends('layouts.admin')

@section('header')
<div class="flex justify-between items-center bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 mb-6 mt-16 lg:mt-0">
    <div>
        <h1 class="text-2xl font-bold font-heading text-slate-900 dark:text-white">Chiến Dịch Khuyến Mãi</h1>
        <p class="text-sm text-slate-500">Quản lý Voucher, Coupon và Flash Sale</p>
    </div>
    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
        <form action="{{ route('admin.vouchers.auto') }}" method="POST" class="m-0">
            @csrf
            <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-bold px-4 py-2 rounded-xl shadow-md shadow-amber-500/20 flex items-center gap-2 transition-transform hover:-translate-y-0.5">
                <i class="fa-solid fa-wand-magic-sparkles"></i> <span>Tạo Tự Động</span>
            </button>
        </form>
        <button onclick="document.getElementById('addVoucherModal').classList.remove('hidden')" class="bg-primary hover:bg-blue-600 text-white font-bold px-4 py-2 rounded-xl shadow-md shadow-primary/20 flex items-center gap-2 magnetic-btn transition-transform hover:-translate-y-0.5">
            <i class="fa-solid fa-plus"></i> <span class="btn-text">Tạo Voucher Mới</span>
        </button>
    </div>
</div>
@endsection

@section('content')

@if(session('success'))
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if(typeof showNotification === 'function') {
            showNotification("{{ session('success') }}", "success");
        } else {
            Swal.fire('Thành công', "{{ session('success') }}", 'success');
        }
    });
</script>
@endif

<div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-100 dark:border-slate-800 shadow-sm relative overflow-hidden group">
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($vouchers as $voucher)
            <!-- Voucher Ticket Design -->
            <div class="relative bg-gradient-to-r from-primary to-blue-500 p-1 rounded-2xl shadow-lg hover:-translate-y-1 transition-transform group/card">
                <div class="bg-white dark:bg-slate-800 rounded-xl p-5 h-full flex flex-col relative overflow-hidden">
                    <!-- Ticket cutouts -->
                    <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-slate-50 dark:bg-slate-950 rounded-full"></div>
                    <div class="absolute -right-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-slate-50 dark:bg-slate-950 rounded-full"></div>
                    
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <span class="bg-primary/10 text-primary font-bold px-2 py-1 rounded-md text-xs uppercase tracking-wider">{{ $voucher->code }}</span>
                        </div>
                        <div class="text-right">
                            @if($voucher->discount_percent)
                                <p class="text-2xl font-bold font-heading text-slate-900 dark:text-white">-{{ floatval($voucher->discount_percent) }}%</p>
                            @else
                                <p class="text-2xl font-bold font-heading text-slate-900 dark:text-white">-{{ number_format($voucher->discount_amount) }}đ</p>
                            @endif
                        </div>
                    </div>
                    
                    <h3 class="font-bold text-slate-800 dark:text-slate-200 mb-2">{{ $voucher->name }}</h3>
                    <p class="text-xs text-slate-500 mb-4 line-clamp-2 flex-grow">{{ $voucher->description ?? 'Không có mô tả' }}</p>
                    
                    <div class="pt-4 border-t border-dashed border-slate-200 dark:border-slate-700 flex justify-between items-center text-xs relative">
                        <span class="text-slate-500">Đã dùng: <strong class="text-slate-700 dark:text-slate-300">{{ $voucher->used_count }}/{{ $voucher->max_uses }}</strong></span>
                        
                        <div class="group-hover/card:opacity-0 transition-opacity absolute right-0 pointer-events-none">
                            @if($voucher->is_active)
                                <span class="text-emerald-500 font-bold"><i class="fa-solid fa-circle text-[8px]"></i> Đang chạy</span>
                            @else
                                <span class="text-slate-400 font-bold"><i class="fa-solid fa-circle text-[8px]"></i> Hết hạn</span>
                            @endif
                        </div>

                        <div class="opacity-0 group-hover/card:opacity-100 transition-opacity flex gap-2 absolute right-0 z-20">
                            <button type="button" 
                                data-id="{{ $voucher->id }}"
                                data-code="{{ $voucher->code }}"
                                data-name="{{ $voucher->name }}"
                                data-discount-percent="{{ $voucher->discount_percent }}"
                                data-discount-amount="{{ $voucher->discount_amount }}"
                                data-max-uses="{{ $voucher->max_uses }}"
                                data-valid-from="{{ $voucher->valid_from }}"
                                data-valid-to="{{ $voucher->valid_to }}"
                                data-is-active="{{ $voucher->is_active }}"
                                onclick="editVoucher(this)" class="w-6 h-6 rounded bg-amber-50 text-amber-500 flex items-center justify-center hover:bg-amber-500 hover:text-white transition-colors" title="Sửa">
                                <i class="fa-solid fa-pen text-[10px] pointer-events-none"></i>
                            </button>
                            <form action="{{ route('admin.vouchers.destroy', $voucher->id) }}" method="POST" class="m-0 p-0" onsubmit="return confirm('Chắc chắn xóa Voucher này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-6 h-6 rounded bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-colors" title="Xóa"><i class="fa-solid fa-trash text-[10px]"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center">
                <div class="w-20 h-20 mx-auto bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center text-3xl text-slate-300 dark:text-slate-600 mb-4">
                    <i class="fa-solid fa-ticket"></i>
                </div>
                <h4 class="text-lg font-bold text-slate-800 dark:text-white mb-2">Chưa có Voucher nào</h4>
                <p class="text-sm text-slate-500">Bắt đầu tạo các chiến dịch khuyến mãi để thu hút khách hàng.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Modal Create Voucher -->
<div id="addVoucherModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm">
    <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold font-heading text-slate-900 dark:text-white">Tạo Voucher Mới</h3>
            <button onclick="document.getElementById('addVoucherModal').classList.add('hidden')" class="text-slate-400 hover:text-red-500 transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        
        <form action="{{ route('admin.vouchers.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Mã Voucher (Code)</label>
                    <div class="relative">
                        <input type="text" id="voucher_code_input" name="code" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 pr-24 text-sm focus:ring-2 focus:ring-primary uppercase" placeholder="VD: SUMMER2026">
                        <button type="button" onclick="document.getElementById('voucher_code_input').value = 'SALE' + Math.random().toString(36).substring(2, 8).toUpperCase()" class="absolute right-2 top-1/2 -translate-y-1/2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold px-3 py-1.5 rounded-lg transition-colors">
                            Tạo tự động
                        </button>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Tên chiến dịch</label>
                    <input type="text" name="name" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary" placeholder="Khuyến mãi hè sôi động">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">% Giảm Giá</label>
                        <input type="number" name="discount_percent" min="0" max="100" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary" placeholder="VD: 10">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Hoặc Giảm Tiền (VNĐ)</label>
                        <input type="number" name="discount_amount" min="0" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary" placeholder="VD: 50000">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Giới hạn số lượt dùng</label>
                    <input type="number" name="max_uses" value="100" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Ngày bắt đầu</label>
                        <input type="datetime-local" name="valid_from" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Ngày kết thúc</label>
                        <input type="datetime-local" name="valid_to" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary">
                    </div>
                </div>
                
                <button type="submit" class="w-full bg-primary hover:bg-blue-600 text-white font-bold py-3 rounded-xl mt-4 transition-colors">Tạo Voucher Phát Hành Ngay</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Voucher -->
<div id="editVoucherModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm">
    <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold font-heading text-slate-900 dark:text-white">Cập Nhật Voucher</h3>
            <button onclick="document.getElementById('editVoucherModal').classList.add('hidden')" class="text-slate-400 hover:text-red-500 transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        
        <form id="editVoucherForm" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Mã Voucher (Code)</label>
                    <input type="text" id="edit_code" name="code" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary uppercase">
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Tên chiến dịch</label>
                    <input type="text" id="edit_name" name="name" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">% Giảm Giá</label>
                        <input type="number" id="edit_discount_percent" name="discount_percent" min="0" max="100" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Hoặc Giảm Tiền (VNĐ)</label>
                        <input type="number" id="edit_discount_amount" name="discount_amount" min="0" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Giới hạn số lượt dùng</label>
                    <input type="number" id="edit_max_uses" name="max_uses" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Ngày bắt đầu</label>
                        <input type="datetime-local" id="edit_valid_from" name="valid_from" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Ngày kết thúc</label>
                        <input type="datetime-local" id="edit_valid_to" name="valid_to" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary">
                    </div>
                </div>

                <div class="flex items-center gap-2 mt-4">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" id="edit_is_active" name="is_active" value="1" class="w-4 h-4 text-primary bg-slate-100 border-slate-300 rounded focus:ring-primary dark:focus:ring-primary dark:ring-offset-slate-800 focus:ring-2 dark:bg-slate-700 dark:border-slate-600">
                    <label for="edit_is_active" class="text-sm font-bold text-slate-700 dark:text-slate-300">Đang hoạt động</label>
                </div>
                
                <button type="submit" class="w-full bg-primary hover:bg-blue-600 text-white font-bold py-3 rounded-xl mt-4 transition-colors">Lưu Thay Đổi</button>
            </div>
        </form>
<script>
    function editVoucher(btn) {
        // Test nếu hàm được gọi
        console.log("Button clicked!", btn);
        try {
            const d = btn.dataset;
            
            if (!d.id) {
                alert('Lỗi: Nút bấm không có ID Voucher.');
                return;
            }

            document.getElementById('editVoucherForm').action = `{{ url('admin/vouchers') }}/${d.id}`;
            document.getElementById('edit_code').value = d.code || '';
            document.getElementById('edit_name').value = d.name || '';
            document.getElementById('edit_discount_percent').value = d.discountPercent || '';
            document.getElementById('edit_discount_amount').value = d.discountAmount || '';
            document.getElementById('edit_max_uses').value = d.maxUses || 100;
            
            const formatDateTime = (dateStr) => {
                if (!dateStr || dateStr === 'null' || dateStr === 'undefined') return '';
                return String(dateStr).replace(' ', 'T').slice(0, 16);
            };

            document.getElementById('edit_valid_from').value = formatDateTime(d.validFrom);
            document.getElementById('edit_valid_to').value = formatDateTime(d.validTo);
            document.getElementById('edit_is_active').checked = d.isActive == '1';
            
            document.getElementById('editVoucherModal').classList.remove('hidden');
        } catch (error) {
            console.error('Lỗi khi mở modal sửa:', error);
            alert('Lỗi: ' + error.message);
        }
    }
</script>

@endsection
