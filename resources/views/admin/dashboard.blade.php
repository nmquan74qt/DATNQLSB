@extends('layouts.admin')

@section('title', 'Admin Dashboard - PitchManage')
@section('page_title', 'Hệ Thống Thống Kê Tổng Quan')

@section('content')
<!-- Metric Cards -->
<div class="row g-4 mb-4">
    <!-- Revenue Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card bg-white border h-100 p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-secondary fw-semibold text-uppercase mb-2 small">Tổng Doanh Thu</h6>
                    <h3 class="fw-bold text-success mb-0">{{ number_format($totalRevenue) }}đ</h3>
                </div>
                <div class="bg-success-subtle text-success rounded-3 p-3">
                    <i class="fa-solid fa-file-invoice-dollar fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- Bookings Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card bg-white border h-100 p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-secondary fw-semibold text-uppercase mb-2 small">Lượt Đặt Sân</h6>
                    <h3 class="fw-bold text-dark mb-0">{{ $totalBookings }}</h3>
                </div>
                <div class="bg-primary-subtle text-primary rounded-3 p-3">
                    <i class="fa-solid fa-calendar-check fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- Customers Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card bg-white border h-100 p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-secondary fw-semibold text-uppercase mb-2 small">Khách Hàng</h6>
                    <h3 class="fw-bold text-dark mb-0">{{ $totalCustomers }}</h3>
                </div>
                <div class="bg-info-subtle text-info rounded-3 p-3">
                    <i class="fa-solid fa-users fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- Staffs Card -->
    <div class="col-xl-3 col-md-6">
        <div class="card bg-white border h-100 p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-secondary fw-semibold text-uppercase mb-2 small">Nhân Viên</h6>
                    <h3 class="fw-bold text-dark mb-0">{{ $totalStaff }}</h3>
                </div>
                <div class="bg-warning-subtle text-warning rounded-3 p-3">
                    <i class="fa-solid fa-user-shield fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Occupancy Stats Card -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-white border">
            <div class="card-body p-4">
                <div class="row align-items-center text-center text-md-start">
                    <div class="col-md-4 mb-3 mb-md-0 border-end">
                        <h6 class="text-secondary fw-semibold mb-1">Hiện trạng sân cỏ hôm nay</h6>
                        <span class="small text-muted"><i class="fa-solid fa-clock"></i> Khung giờ hiện tại</span>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0 border-end text-center">
                        <div class="display-6 fw-bold text-success">{{ $availableCount }}</div>
                        <span class="text-secondary small fw-semibold"><i class="fa-solid fa-circle-check text-success me-1"></i>Sân còn trống</span>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="display-6 fw-bold text-danger">{{ $occupiedCount }}</div>
                        <span class="text-secondary small fw-semibold"><i class="fa-solid fa-circle text-danger me-1"></i>Sân đang sử dụng</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row g-4 mb-4">
    <!-- Revenue Chart -->
    <div class="col-lg-6">
        <div class="card bg-white border h-100">
            <div class="card-header py-3 bg-transparent border-bottom">
                <h5 class="fw-bold m-0"><i class="fa-solid fa-chart-line text-success me-2"></i>Doanh Thu Theo Tháng (VND)</h5>
            </div>
            <div class="card-body p-4">
                <canvas id="revenueChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>
    <!-- Bookings Chart -->
    <div class="col-lg-6">
        <div class="card bg-white border h-100">
            <div class="card-header py-3 bg-transparent border-bottom">
                <h5 class="fw-bold m-0"><i class="fa-solid fa-chart-bar text-success me-2"></i>Lượt Đặt Sân Hàng Tháng</h5>
            </div>
            <div class="card-body p-4">
                <canvas id="bookingsChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Top Fields Chart -->
    <div class="col-lg-4">
        <div class="card bg-white border h-100">
            <div class="card-header py-3 bg-transparent border-bottom">
                <h5 class="fw-bold m-0"><i class="fa-solid fa-chart-pie text-success me-2"></i>Sân Được Đặt Nhiều Nhất</h5>
            </div>
            <div class="card-body p-4 d-flex align-items-center justify-content-center">
                <div style="width: 100%; max-width: 250px;">
                    <canvas id="topFieldsChart" style="height: 250px;"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Bookings Table -->
    <div class="col-lg-8">
        <div class="card bg-white border h-100">
            <div class="card-header py-3 bg-transparent border-bottom d-flex justify-content-between align-items-center">
                <h5 class="fw-bold m-0"><i class="fa-solid fa-bell text-success me-2"></i>Đơn Đặt Sân Mới Nhất</h5>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-outline-success">Xem tất cả</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Khách Hàng</th>
                                <th>Ngày Đá</th>
                                <th>Tổng Tiền</th>
                                <th>Trạng Thái</th>
                                <th class="pe-4 text-end">Chi Tiết</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentBookings as $booking)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold">{{ $booking->customer_name }}</div>
                                        <small class="text-secondary">{{ $booking->customer_phone }}</small>
                                    </td>
                                    <td>{{ $booking->booking_date->format('d/m/Y') }}</td>
                                    <td class="fw-bold text-success">{{ number_format($booking->total_amount) }}đ</td>
                                    <td>
                                        @if($booking->status === 'pending')
                                            <span class="badge bg-warning text-dark">Chờ duyệt</span>
                                        @elseif($booking->status === 'confirmed')
                                            <span class="badge bg-primary">Đã duyệt</span>
                                        @elseif($booking->status === 'completed')
                                            <span class="badge bg-success">Hoàn thành</span>
                                        @else
                                            <span class="badge bg-danger">Đã hủy</span>
                                        @endif
                                    </td>
                                    <td class="pe-4 text-end">
                                        <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-sm btn-success py-1 px-2.5">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-secondary">Không có đơn đặt sân mới.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Load Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Monthly Revenue Chart (Line)
        const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctxRevenue, {
            type: 'line',
            data: {
                labels: @json($revenueLabels),
                datasets: [{
                    label: 'Doanh Thu',
                    data: @json($revenueValues),
                    backgroundColor: 'rgba(25, 135, 84, 0.1)',
                    borderColor: 'rgba(25, 135, 84, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: 'rgba(25, 135, 84, 1)',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('vi-VN') + 'đ';
                            }
                        }
                    }
                }
            }
        });

        // 2. Monthly Bookings Chart (Bar)
        const ctxBookings = document.getElementById('bookingsChart').getContext('2d');
        new Chart(ctxBookings, {
            type: 'bar',
            data: {
                labels: @json($bookingLabels),
                datasets: [{
                    label: 'Lượt Đặt',
                    data: @json($bookingValues),
                    backgroundColor: 'rgba(25, 135, 84, 0.8)',
                    borderColor: 'rgba(25, 135, 84, 1)',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });

        // 3. Top booked fields (Pie / Doughnut)
        const ctxTop = document.getElementById('topFieldsChart').getContext('2d');
        const topFieldLabels = @json($topFieldLabels);
        
        if(topFieldLabels.length > 0) {
            new Chart(ctxTop, {
                type: 'doughnut',
                data: {
                    labels: topFieldLabels,
                    datasets: [{
                        data: @json($topFieldValues),
                        backgroundColor: [
                            '#198754',
                            '#0f5132',
                            '#20c997',
                            '#0dcaf0',
                            '#ffc107'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 12 } }
                    }
                }
            });
        } else {
            // Render text placeholder inside canvas parent if no data
            document.getElementById('topFieldsChart').parentElement.innerHTML = '<span class="text-secondary small">Chưa có dữ liệu đặt sân</span>';
        }
    });
</script>
@endsection
