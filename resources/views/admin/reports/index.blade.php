@extends('layouts.admin')

@section('title', 'Báo cáo Doanh thu & Tỷ lệ lấp đầy')

@section('content')
<div class="p-4 lg:p-8 space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white font-heading">Báo Cáo & Thống Kê</h1>
            <p class="text-slate-500 text-sm mt-1">Doanh thu và tỷ lệ hoạt động của hệ thống</p>
        </div>
        
        <!-- Filter Form -->
        <form action="{{ route('admin.reports.index') }}" method="GET" class="flex items-center gap-2">
            <input type="month" name="month" value="{{ $month }}" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-primary/50 text-slate-700 dark:text-slate-200" onchange="this.form.submit()">
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Doanh thu -->
        <div class="bg-gradient-to-br from-primary to-blue-600 rounded-3xl p-6 text-white shadow-lg shadow-primary/30 relative overflow-hidden">
            <div class="absolute right-0 top-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -mr-10 -mt-10"></div>
            <div class="relative z-10">
                <p class="text-white/80 text-sm font-bold uppercase tracking-wider mb-2">Tổng Doanh Thu ({{ \Carbon\Carbon::parse($month)->format('m/Y') }})</p>
                <h3 class="text-4xl font-bold font-heading">{{ number_format($totalRevenue) }}đ</h3>
            </div>
        </div>

        <!-- Đơn thành công -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-100 dark:border-slate-700 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-slate-500 text-sm font-bold uppercase tracking-wider mb-2">Lượt Thuê Hoàn Thành</p>
                <h3 class="text-3xl font-bold text-slate-900 dark:text-white font-heading">{{ number_format($completedBookings) }}</h3>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-500 flex items-center justify-center text-2xl">
                <i class="fa-solid fa-check-to-slot"></i>
            </div>
        </div>

        <!-- Đơn hủy -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-100 dark:border-slate-700 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-slate-500 text-sm font-bold uppercase tracking-wider mb-2">Lượt Thuê Bị Hủy</p>
                <h3 class="text-3xl font-bold text-slate-900 dark:text-white font-heading">{{ number_format($cancelledBookings) }}</h3>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-red-50 dark:bg-red-900/30 text-red-500 flex items-center justify-center text-2xl">
                <i class="fa-solid fa-ban"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-100 dark:border-slate-700 shadow-sm">
            <h3 class="font-bold text-slate-800 dark:text-white mb-6 font-heading">Biểu Đồ Doanh Thu Theo Ngày</h3>
            <div class="relative h-72">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Top Sân -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-100 dark:border-slate-700 shadow-sm">
            <h3 class="font-bold text-slate-800 dark:text-white mb-6 font-heading">Top Sân Đặt Nhiều Nhất</h3>
            <div class="space-y-4">
                @forelse($topFields as $index => $tf)
                    <div class="flex items-center gap-4 p-3 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold {{ $index === 0 ? 'bg-amber-100 text-amber-600' : ($index === 1 ? 'bg-slate-200 text-slate-600' : 'bg-orange-100 text-orange-600') }}">
                            #{{ $index + 1 }}
                        </div>
                        <div class="flex-grow">
                            <h4 class="font-bold text-slate-900 dark:text-white text-sm">{{ $tf->name }}</h4>
                            <p class="text-xs text-slate-500">{{ $tf->total_bookings }} lượt thuê</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-primary text-sm">{{ number_format($tf->total_revenue) }}đ</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-slate-500 py-8">
                        Chưa có dữ liệu thống kê
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        // Gradient cho Bar chart
        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.8)'); // Blue-500
        gradient.addColorStop(1, 'rgba(59, 130, 246, 0.2)');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: {!! json_encode($chartData) !!},
                    backgroundColor: gradient,
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return new Intl.NumberFormat('vi-VN').format(context.parsed.y) + 'đ';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [5, 5], color: '#e2e8f0' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>
@endpush
