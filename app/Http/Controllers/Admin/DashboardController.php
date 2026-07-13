<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\FootballField;
use App\Models\Invoice;
use App\Models\Role;
use App\Models\User;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $nowTime = Carbon::now()->toTimeString();

        // Basic counts
        $totalFields = FootballField::count();
        $totalCustomers = User::whereHas('role', function ($query) {
            $query->where('name', 'customer');
        })->count();
        $totalStaff = User::whereHas('role', function ($query) {
            $query->where('name', 'staff');
        })->count();
        $totalBookings = Booking::count();
        $totalRevenue = Invoice::where('status', 'paid')->sum('final_amount');

        // Dynamic occupied fields check (for current timeslot today)
        $currentSlot = TimeSlot::where('start_time', '<=', $nowTime)
            ->where('end_time', '>=', $nowTime)
            ->first();

        $occupiedCount = 0;
        if ($currentSlot) {
            $occupiedCount = BookingDetail::where('booking_date', $today)
                ->where('time_slot_id', $currentSlot->id)
                ->whereHas('booking', function ($q) {
                    $q->whereIn('status', ['confirmed', 'completed']);
                })
                ->count();
        }
        $availableCount = max(0, $totalFields - $occupiedCount);

        // Chart 1: Revenue by month (last 6 months)
        $revenueData = DB::table('invoices')
            ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"), DB::raw('SUM(final_amount) as total'))
            ->where('status', 'paid')
            ->where('created_at', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        $revenueLabels = [];
        $revenueValues = [];
        for ($i = 5; $i >= 0; $i--) {
            $m = Carbon::now()->subMonths($i)->format('Y-m');
            $label = Carbon::now()->subMonths($i)->format('m/Y');
            $revenueLabels[] = $label;
            
            $found = $revenueData->firstWhere('month', $m);
            $revenueValues[] = $found ? (float)$found->total : 0;
        }

        // Chart 2: Bookings by month (last 6 months)
        $bookingData = DB::table('bookings')
            ->select(DB::raw("DATE_FORMAT(booking_date, '%Y-%m') as month"), DB::raw('COUNT(*) as total'))
            ->where('booking_date', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        $bookingLabels = [];
        $bookingValues = [];
        for ($i = 5; $i >= 0; $i--) {
            $m = Carbon::now()->subMonths($i)->format('Y-m');
            $label = Carbon::now()->subMonths($i)->format('m/Y');
            $bookingLabels[] = $label;
            
            $found = $bookingData->firstWhere('month', $m);
            $bookingValues[] = $found ? (int)$found->total : 0;
        }

        // Chart 3: Top fields booked (count)
        $topFieldsData = BookingDetail::select('football_field_id', DB::raw('count(*) as count'))
            ->groupBy('football_field_id')
            ->orderBy('count', 'desc')
            ->with('footballField')
            ->take(5)
            ->get();

        $topFieldLabels = [];
        $topFieldValues = [];
        foreach ($topFieldsData as $detail) {
            $topFieldLabels[] = $detail->footballField ? $detail->footballField->name : 'N/A';
            $topFieldValues[] = $detail->count;
        }

        // Recent bookings (limit 5)
        $recentBookings = Booking::orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact(
            'totalFields', 'totalCustomers', 'totalStaff', 'totalBookings', 'totalRevenue',
            'occupiedCount', 'availableCount',
            'revenueLabels', 'revenueValues',
            'bookingLabels', 'bookingValues',
            'topFieldLabels', 'topFieldValues',
            'recentBookings'
        ));
    }
}
