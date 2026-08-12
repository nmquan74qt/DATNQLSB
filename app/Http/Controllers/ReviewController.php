<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'field_id' => 'required|exists:fields,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $booking = \App\Models\Booking::where('id', $request->booking_id)
            ->where('user_id', auth()->id())
            ->where('status', 'completed')
            ->firstOrFail();

        // Kiểm tra xem đã đánh giá chưa
        if (\App\Models\Review::where('booking_id', $booking->id)->exists()) {
            return back()->with('error', 'Bạn đã đánh giá đơn đặt sân này rồi!');
        }

        \App\Models\Review::create([
            'user_id' => auth()->id(),
            'field_id' => $request->field_id,
            'booking_id' => $booking->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => true // Mặc định auto-approve, hoặc false nếu cần admin duyệt
        ]);

        return back()->with('success', 'Cảm ơn bạn đã gửi đánh giá!');
    }
}
