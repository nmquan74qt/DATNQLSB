@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('header')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="font-heading font-bold text-2xl text-slate-800 dark:text-white leading-tight">
                Tổng Quan Hệ Thống
            </h2>
            <p class="text-sm text-slate-500">Thống kê dữ liệu hoạt động mới nhất</p>
        </div>
        <div>
            <!-- Date range picker or similar could go here -->
            <button class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 px-4 py-2 rounded-xl text-sm font-medium shadow-sm flex items-center gap-2">
                <i class="fa-regular fa-calendar"></i> 7 Ngày Qua
            </button>
        </div>
    </div>
@endsection

@section('content')
    <!-- 4 Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center text-xl">
                    <i class="fa-solid fa-sack-dollar"></i>
                </div>
                <span class="bg-emerald-100 text-emerald-600 px-2 py-1 rounded-md text-xs font-bold">+12%</span>
            </div>
            <p class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-1">Tổng Doanh Thu</p>
            <h3 class="text-2xl font-black text-slate-800 dark:text-white font-heading">
                {{ number_format($totalRevenue) }}đ
            </h3>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-money-bill-trend-up"></i>
                </div>
            </div>
            <p class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-1">Doanh Thu Hôm Nay</p>
            <h3 class="text-2xl font-black text-slate-800 dark:text-white font-heading">
                {{ number_format($todayRevenue) }}đ
            </h3>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center text-xl">
                    <i class="fa-regular fa-calendar-check"></i>
                </div>
            </div>
            <p class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-1">Tổng Lượt Đặt</p>
            <h3 class="text-2xl font-black text-slate-800 dark:text-white font-heading">
                {{ number_format($totalBookings) }}
            </h3>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-500 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <p class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-1">Khách Hàng</p>
            <h3 class="text-2xl font-black text-slate-800 dark:text-white font-heading">
                {{ number_format($totalCustomers) }}
            </h3>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700">
            <h3 class="font-bold text-slate-900 dark:text-white font-heading text-lg mb-6">Biểu Đồ Doanh Thu (7 Ngày)</h3>
            <div class="relative h-72 w-full">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Top Sân -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700">
            <h3 class="font-bold text-slate-900 dark:text-white font-heading text-lg mb-6">Sân Đặt Nhiều Nhất</h3>
            <div class="space-y-4">
                @foreach($topFields as $index => $field)
                    <div class="flex items-center gap-4 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm {{ $index == 0 ? 'bg-amber-100 text-amber-600' : ($index == 1 ? 'bg-slate-200 text-slate-600' : ($index == 2 ? 'bg-orange-100 text-orange-600' : 'bg-slate-100 text-slate-500')) }}">
                            #{{ $index + 1 }}
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-slate-800 dark:text-white text-sm">{{ $field->name }}</h4>
                            <p class="text-xs text-slate-500">{{ $field->type->name ?? 'Sân' }}</p>
                        </div>
                        <div class="text-right">
                            <span class="font-bold text-primary">{{ $field->booking_details_count }}</span>
                            <span class="text-xs text-slate-500 block">Lượt</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Top Khách Hàng -->
        <div class="lg:col-span-3 bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 mt-2">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-slate-900 dark:text-white font-heading text-lg">Khách Hàng Thân Thiết (Top 5)</h3>
                <a href="{{ route('admin.customers.index') }}" class="text-sm text-primary hover:underline font-medium">Xem tất cả</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                @foreach($topCustomers as $index => $customer)
                    <div class="p-4 rounded-2xl border border-slate-100 dark:border-slate-700 text-center hover:shadow-md transition-shadow relative overflow-hidden">
                        @if($index == 0)
                            <div class="absolute top-0 right-0 bg-amber-400 text-white text-[10px] font-bold px-2 py-1 rounded-bl-lg">TOP 1</div>
                        @endif
                        <img src="{{ $customer->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($customer->name).'&background=random' }}" class="w-16 h-16 rounded-full mx-auto mb-3 object-cover border-2 border-primary/20">
                        <h4 class="font-bold text-slate-800 dark:text-white text-sm truncate mb-1">{{ $customer->name }}</h4>
                        <div class="flex justify-center gap-3 text-xs">
                            <span class="text-amber-500 font-bold"><i class="fa-solid fa-star"></i> {{ number_format($customer->points) }}</span>
                            <span class="text-slate-500"><i class="fa-solid fa-calendar-check"></i> {{ $customer->bookings_count }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        // Gradient for line chart
        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.5)'); // primary blue
        gradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($revenueLabels) !!},
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: {!! json_encode($revenueData) !!},
                    borderColor: '#3b82f6',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#3b82f6',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4 // curve
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        titleFont: { size: 13, family: "'Inter', sans-serif" },
                        bodyFont: { size: 14, weight: 'bold', family: "'Inter', sans-serif" },
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false,
                        },
                        ticks: {
                            font: { family: "'Inter', sans-serif", size: 11 },
                            callback: function(value, index, values) {
                                if(value >= 1000000) return (value / 1000000) + 'M';
                                if(value >= 1000) return (value / 1000) + 'K';
                                return value;
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false,
                        },
                        ticks: {
                            font: { family: "'Inter', sans-serif", size: 11 }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
