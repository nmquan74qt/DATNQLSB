<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\Field;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->query('month', date('Y-m')); // Format YYYY-MM
        $startDate = Carbon::parse($month . '-01')->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        // 1. Tổng doanh thu (chỉ tính đơn hoàn thành)
        $totalRevenue = Booking::where('status', 'completed')
            ->whereBetween('booking_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->sum('total_amount');

        // 2. Số liệu các loại đơn (Thành công vs Hủy)
        $completedBookings = Booking::where('status', 'completed')
            ->whereBetween('booking_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->count();
            
        $cancelledBookings = Booking::where('status', 'cancelled')
            ->whereBetween('booking_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->count();

        // 3. Top Sân được đặt nhiều nhất
        $topFields = DB::table('booking_details')
            ->join('bookings', 'booking_details.booking_id', '=', 'bookings.id')
            ->join('fields', 'booking_details.field_id', '=', 'fields.id')
            ->where('bookings.status', 'completed')
            ->whereBetween('bookings.booking_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->select('fields.name', DB::raw('COUNT(booking_details.id) as total_bookings'), DB::raw('SUM(booking_details.price + IFNULL(booking_details.overtime_fee, 0)) as total_revenue'))
            ->groupBy('fields.id', 'fields.name')
            ->orderByDesc('total_bookings')
            ->limit(5)
            ->get();

        // 4. Doanh thu theo ngày trong tháng
        $revenueByDay = Booking::where('status', 'completed')
            ->whereBetween('booking_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->select(DB::raw('DATE(booking_date) as date'), DB::raw('SUM(total_amount) as revenue'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Prepare chart data for all days in month
        $chartLabels = [];
        $chartData = [];
        for ($i = 1; $i <= $endDate->day; $i++) {
            $dateString = $startDate->copy()->addDays($i - 1)->format('Y-m-d');
            $chartLabels[] = $i;
            $chartData[] = isset($revenueByDay[$dateString]) ? $revenueByDay[$dateString]->revenue : 0;
        }

        return view('admin.reports.index', compact(
            'month', 
            'totalRevenue', 
            'completedBookings', 
            'cancelledBookings', 
            'topFields',
            'chartLabels',
            'chartData'
        ));
    }
}
