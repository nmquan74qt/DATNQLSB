<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use App\Models\Field;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // General Stats
        $totalRevenue = Payment::where('status', 'success')->sum('amount');
        $todayRevenue = Payment::where('status', 'success')->whereDate('created_at', today())->sum('amount');
        $totalBookings = Booking::count();
        $totalCustomers = User::where('role', 'customer')->count();

        // Revenue Chart (Last 7 Days)
        $revenueLabels = [];
        $revenueData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $revenueLabels[] = $date->format('d/m');
            $revenueData[] = Payment::where('status', 'success')
                                ->whereDate('created_at', $date)
                                ->sum('amount');
        }

        // Top Customers
        $topCustomers = User::where('role', 'customer')
                            ->withCount('bookings')
                            ->orderByDesc('points')
                            ->take(5)
                            ->get();

        // Top Fields
        $topFields = Field::withCount('bookingDetails')
                        ->orderByDesc('booking_details_count')
                        ->take(5)
                        ->get();

        return view('admin.dashboard', compact(
            'totalRevenue', 'todayRevenue', 'totalBookings', 'totalCustomers',
            'revenueLabels', 'revenueData',
            'topCustomers', 'topFields'
        ));
    }
}
