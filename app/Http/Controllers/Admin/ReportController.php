<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Booking;
use App\Models\BookingDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::now()->endOfMonth();

        // Calculate totals in date range
        $totalInvoices = Invoice::where('status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $totalRevenue = Invoice::where('status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('final_amount');

        $totalDiscount = Invoice::where('status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('discount');

        // Revenue by Field Type
        $revenueByFieldType = BookingDetail::select('field_types.name as field_type_name', DB::raw('SUM(booking_details.price) as revenue'), DB::raw('COUNT(*) as count'))
            ->join('football_fields', 'booking_details.football_field_id', '=', 'football_fields.id')
            ->join('field_types', 'football_fields.field_type_id', '=', 'field_types.id')
            ->join('bookings', 'booking_details.booking_id', '=', 'bookings.id')
            ->where('bookings.status', 'completed')
            ->whereBetween('bookings.booking_date', [$startDate, $endDate])
            ->groupBy('field_types.name')
            ->get();

        // Recent paid invoices in date range
        $invoices = Invoice::with(['booking'])
            ->where('status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.reports.index', compact(
            'invoices', 'totalInvoices', 'totalRevenue', 'totalDiscount',
            'revenueByFieldType', 'startDate', 'endDate'
        ));
    }

    public function exportCsv(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::now()->endOfMonth();

        $invoices = Invoice::with(['booking', 'user'])
            ->where('status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'asc')
            ->get();

        $fileName = 'Bao_Cao_Doanh_Thu_' . $startDate->format('Ymd') . '_to_' . $endDate->format('Ymd') . '.csv';

        $headers = array(
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('STT', 'Mã Hóa Đơn', 'Khách Hàng', 'Số Điện Thoại', 'Tổng Tiền Sân & Dịch Vụ', 'Giảm Giá', 'Thực Thu', 'Ngày Thanh Toán', 'Nhân Viên Lập');

        $callback = function() use($invoices, $columns) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility with special characters in Vietnamese
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, $columns);

            $stt = 1;
            foreach ($invoices as $invoice) {
                fputcsv($file, array(
                    $stt++,
                    $invoice->invoice_number,
                    $invoice->customer_name,
                    $invoice->customer_phone,
                    $invoice->total_amount,
                    $invoice->discount,
                    $invoice->final_amount,
                    $invoice->created_at->format('H:i d/m/Y'),
                    $invoice->user ? $invoice->user->name : 'N/A'
                ));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
