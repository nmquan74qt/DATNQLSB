<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = \App\Models\Payment::with(['booking.user'])->latest()->paginate(10);
        return view('admin.payments.index', compact('payments'));
    }
    public function export()
    {
        $fileName = 'doanh_thu_' . date('Y-m-d_H-i') . '.csv';
        $payments = \App\Models\Payment::with(['booking.user'])->latest()->get();

        $headers = array(
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = ['Mã Giao Dịch', 'Mã Đơn', 'Khách Hàng', 'Phương Thức', 'Số Tiền (VND)', 'Ngày Thanh Toán'];

        $callback = function() use($payments, $columns) {
            $file = fopen('php://output', 'w');
            // Thêm BOM để Excel đọc được tiếng Việt (UTF-8)
            fputs($file, $bom =(chr(0xEF) . chr(0xBB) . chr(0xBF)));
            fputcsv($file, $columns);

            foreach ($payments as $payment) {
                $row['Mã Giao Dịch']  = $payment->transaction_id;
                $row['Mã Đơn']        = $payment->booking->booking_code ?? 'N/A';
                $row['Khách Hàng']    = $payment->booking->user->name ?? 'Khách lẻ';
                $row['Phương Thức']   = strtoupper($payment->payment_method);
                $row['Số Tiền (VND)'] = $payment->amount;
                $row['Ngày Thanh Toán'] = $payment->created_at->format('d/m/Y H:i');

                fputcsv($file, array($row['Mã Giao Dịch'], $row['Mã Đơn'], $row['Khách Hàng'], $row['Phương Thức'], $row['Số Tiền (VND)'], $row['Ngày Thanh Toán']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
