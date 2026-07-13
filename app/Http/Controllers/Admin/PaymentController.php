<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['booking.user'])->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('booking', function($bq) use ($search) {
                    $bq->where('customer_name', 'like', "%$search%")
                       ->orWhere('customer_phone', 'like', "%$search%");
                })->orWhere('transaction_id', 'like', "%$search%");
            });
        }

        $payments = $query->paginate(10)->withQueryString();
        return view('admin.payments.index', compact('payments'));
    }
}
