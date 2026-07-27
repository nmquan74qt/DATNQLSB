<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = \App\Models\Booking::with(['user', 'details.timeSlot'])->latest()->paginate(10);
        return view('admin.bookings.index', compact('bookings'));
    }

    public function calendarData(Request $request)
    {
        $start = $request->query('start'); // ISO8601 string
        $end = $request->query('end');

        $query = \App\Models\Booking::with(['user', 'details.timeSlot', 'details.field']);
        
        if ($start) {
            $query->where('booking_date', '>=', date('Y-m-d', strtotime($start)));
        }
        if ($end) {
            $query->where('booking_date', '<=', date('Y-m-d', strtotime($end)));
        }

        $bookings = $query->get();
        $events = [];

        foreach ($bookings as $booking) {
            foreach ($booking->details as $detail) {
                if ($detail->timeSlot) {
                    $date = $booking->booking_date;
                    $startTime = $detail->timeSlot->start_time;
                    $endTime = $detail->timeSlot->end_time;
                    
                    $color = '#3b82f6'; // blue
                    if ($booking->status == 'pending') $color = '#f59e0b'; // amber
                    if ($booking->status == 'completed') $color = '#10b981'; // emerald
                    if ($booking->status == 'cancelled') $color = '#64748b'; // slate

                    $events[] = [
                        'id' => $booking->id,
                        'title' => ($booking->user->name ?? 'Khách lẻ') . ' - ' . ($detail->field->name ?? 'Sân'),
                        'start' => $date . 'T' . $startTime,
                        'end' => $date . 'T' . $endTime,
                        'backgroundColor' => $color,
                        'borderColor' => $color,
                        'extendedProps' => [
                            'status' => $booking->status,
                            'code' => $booking->booking_code
                        ]
                    ];
                }
            }
        }

        return response()->json($events);
    }

    public function store(Request $request)
    {
        $request->validate([
            'field_id' => 'required|exists:fields,id',
            'booking_date' => 'required|date',
            'time_slot_id' => 'required|exists:time_slots,id',
        ]);

        $timeSlot = \App\Models\TimeSlot::find($request->time_slot_id);
        
        // Check if already booked
        $exists = \App\Models\BookingDetail::whereHas('booking', function($q) use ($request) {
            $q->where('booking_date', $request->booking_date)
              ->whereIn('status', ['pending', 'confirmed']);
        })
        ->where('field_id', $request->field_id)
        ->where('time_slot_id', $request->time_slot_id)
        ->exists();

        if ($exists) {
            return back()->with('error', 'Sân trong khung giờ này đã được đặt!');
        }

        $booking = \App\Models\Booking::create([
            'user_id' => $request->user_id ?? auth()->id(), // default to logged in admin if walk-in
            'booking_code' => 'BK' . strtoupper(uniqid()),
            'booking_date' => $request->booking_date,
            'total_amount' => $timeSlot->price,
            'status' => 'confirmed', // Admin booking auto confirmed
            'notes' => $request->notes,
        ]);

        $booking->details()->create([
            'field_id' => $request->field_id,
            'time_slot_id' => $request->time_slot_id,
            'price' => $timeSlot->price,
        ]);

        return back()->with('success', 'Đã tạo lịch đặt sân thành công!');
    }

    public function updateStatus(Request $request, $id)
    {
        $booking = \App\Models\Booking::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled'
        ]);

        // Reward points if completed
        if ($request->status === 'completed' && $booking->status !== 'completed') {
            if ($booking->user && $booking->user->role === 'customer') {
                $pointsEarned = floor($booking->total_amount / 1000); // 1 point per 1000 VND
                $booking->user->increment('points', $pointsEarned);
            }
        }
        
        $booking->update(['status' => $request->status]);

        return back()->with('success', 'Đã cập nhật trạng thái đơn đặt sân!');
    }
}
