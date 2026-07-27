@extends('layouts.admin')

@section('title', 'Quản Lý Hóa Đơn & Thanh Toán')

@section('header')
    <div class="flex justify-between items-center bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 mb-6 mt-16 lg:mt-0">
        <div>
            <h1 class="text-2xl font-bold font-heading text-slate-900 dark:text-white">Quản Lý Hóa Đơn</h1>
            <p class="text-sm text-slate-500">Kiểm soát các giao dịch thanh toán</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.payments.export') }}" class="bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold px-4 py-2 rounded-xl transition-colors flex items-center gap-2">
                <i class="fa-solid fa-file-export"></i> Xuất Excel / CSV
            </a>
        </div>
    </div>
@endsection

@section('content')
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-700">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-1">Tổng Doanh Thu</p>
                    <h3 class="text-2xl font-black text-slate-800 dark:text-white font-heading">
                        {{ number_format(\App\Models\Payment::where('status', 'success')->sum('amount')) }}đ
                    </h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-sack-dollar"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-700">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-1">Thanh Toán VNPay</p>
                    <h3 class="text-2xl font-black text-slate-800 dark:text-white font-heading">
                        {{ number_format(\App\Models\Payment::where('status', 'success')->where('payment_method', 'vnpay')->sum('amount')) }}đ
                    </h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-credit-card"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-700">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-1">Thanh Toán Tiền Mặt</p>
                    <h3 class="text-2xl font-black text-slate-800 dark:text-white font-heading">
                        {{ number_format(\App\Models\Payment::where('status', 'success')->where('payment_method', 'cash')->sum('amount')) }}đ
                    </h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-money-bill-wave"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-100 dark:border-slate-800 shadow-sm relative overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wider">
                        <th class="py-3 px-4 font-bold rounded-l-lg">Mã Giao Dịch</th>
                        <th class="py-3 px-4 font-bold">Khách Hàng / Hóa Đơn</th>
                        <th class="py-3 px-4 font-bold">Phương thức</th>
                        <th class="py-3 px-4 font-bold">Số Tiền</th>
                        <th class="py-3 px-4 font-bold">Trạng Thái</th>
                        <th class="py-3 px-4 font-bold rounded-r-lg">Ngày</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                            <td class="py-4 px-4">
                                <span class="font-mono text-slate-800 dark:text-white font-bold">{{ $payment->transaction_id ?? '---' }}</span>
                            </td>
                            <td class="py-4 px-4">
                                <div class="font-bold text-slate-800 dark:text-white">{{ $payment->booking->user->name ?? 'Khách vãng lai' }}</div>
                                <div class="text-xs text-primary mt-1">Đơn: {{ $payment->booking->booking_code ?? 'N/A' }}</div>
                            </td>
                            <td class="py-4 px-4">
                                @if($payment->payment_method == 'vnpay')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-blue-50 text-blue-600 border border-blue-100">
                                        <i class="fa-solid fa-credit-card"></i> VNPay
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                        <i class="fa-solid fa-money-bill"></i> Tiền mặt
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                <span class="font-bold text-slate-800 dark:text-white">{{ number_format($payment->amount) }}đ</span>
                            </td>
                            <td class="py-4 px-4">
                                @if($payment->status == 'success')
                                    <span class="bg-emerald-100 text-emerald-600 px-2.5 py-1 rounded-md text-xs font-bold"><i class="fa-solid fa-check mr-1"></i> Thành công</span>
                                @elseif($payment->status == 'pending')
                                    <span class="bg-amber-100 text-amber-600 px-2.5 py-1 rounded-md text-xs font-bold"><i class="fa-solid fa-clock mr-1"></i> Đang chờ</span>
                                @else
                                    <span class="bg-red-100 text-red-600 px-2.5 py-1 rounded-md text-xs font-bold"><i class="fa-solid fa-xmark mr-1"></i> Thất bại</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-slate-500">
                                {{ $payment->created_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-500">
                                <div class="w-16 h-16 mx-auto bg-slate-100 dark:bg-slate-800 text-slate-400 rounded-full flex items-center justify-center text-2xl mb-4">
                                    <i class="fa-solid fa-file-invoice-dollar"></i>
                                </div>
                                <p>Chưa có giao dịch thanh toán nào.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-6">
            {{ $payments->links() }}
        </div>
    </div>
@endsection
