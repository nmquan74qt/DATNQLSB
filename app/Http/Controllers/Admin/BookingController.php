<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Notifications\SystemNotification;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = \App\Models\Booking::with(['user', 'details.timeSlot', 'details.field'])->latest()->paginate(10);
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
                    
                    // Prefer the exact DATETIME if available (supports cross-midnight)
                    if ($detail->start_time && $detail->end_time) {
                        $startISO = $detail->start_time->toIso8601String();
                        $endISO = $detail->end_time->toIso8601String();
                    } else {
                        // Fallback for old bookings
                        $startTime = $detail->timeSlot->start_time;
                        $endTime = $detail->timeSlot->end_time;
                        $startISO = $date . 'T' . $startTime;
                        $endISO = $date . 'T' . $endTime;
                    }
                    
                    $color = '#3b82f6'; // blue
                    if ($booking->status == 'pending') $color = '#f59e0b'; // amber
                    if ($booking->status == 'in_progress') $color = '#ef4444'; // red
                    if ($booking->status == 'completed') $color = '#10b981'; // emerald
                    if ($booking->status == 'cancelled') $color = '#64748b'; // slate

                    $events[] = [
                        'id' => $booking->id,
                        'title' => ($booking->user->name ?? 'Khách lẻ') . ' - ' . ($detail->field->name ?? 'Sân'),
                        'start' => $startISO,
                        'end' => $endISO,
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

        return \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            // Lock field for update to prevent race conditions
            $field = \App\Models\Field::where('id', $request->field_id)->lockForUpdate()->first();
            
            if (!$field) {
                return back()->with('error', 'Sân không tồn tại!');
            }

            $timeSlot = \App\Models\TimeSlot::find($request->time_slot_id);
            
            $startDatetime = \Carbon\Carbon::parse($request->booking_date . ' ' . $timeSlot->start_time);
            $endDatetime = \Carbon\Carbon::parse($request->booking_date . ' ' . $timeSlot->end_time);

            if ($endDatetime->lt($startDatetime)) {
                $endDatetime->addDay(); // Qua ngày hôm sau
            }

            // Tình huống 4: Logic Gộp/Chia Sân (Sân Cha - Sân Con)
            $fieldIdsToCheck = [$request->field_id];
            if ($field->parent_id) {
                $fieldIdsToCheck[] = $field->parent_id; // Check sân cha
            }
            foreach ($field->children as $child) {
                $fieldIdsToCheck[] = $child->id; // Check các sân con
            }

            // Check if already booked
            $exists = \App\Models\BookingDetail::whereHas('booking', function($q) {
                $q->whereIn('status', ['pending', 'confirmed', 'in_progress', 'completed']);
            })
            ->whereIn('field_id', $fieldIdsToCheck)
            ->where(function($q) use ($startDatetime, $endDatetime) {
                $q->where('start_time', '<', $endDatetime)
                  ->where('end_time', '>', $startDatetime);
            })
            ->exists();

            if ($exists) {
                return back()->with('error', 'Sân trong khung giờ này đã được đặt!');
            }

            // Tính tổng tiền theo block (Tình huống 2)
            $calculatedTotal = 0;
            $current = clone $startDatetime;
            $basePricePerHour = $field->base_price;

            while ($current < $endDatetime) {
                $next = clone $current;
                $next->startOfHour()->addHour();
                if ($next > $endDatetime) $next = clone $endDatetime;
                
                $minutes = $current->diffInMinutes($next);
                $hourFraction = $minutes / 60.0;
                
                $timeString = $current->format('H:i:s');
                $slot = \App\Models\TimeSlot::where('start_time', '<=', $timeString)
                                ->where('end_time', '>', $timeString)
                                ->first();
                                
                $modifier = $slot ? $slot->price_modifier : 0;
                $calculatedTotal += ($basePricePerHour + $modifier) * $hourFraction;
                
                $current = clone $next;
            }

            $booking = \App\Models\Booking::create([
                'user_id' => $request->user_id ?? auth()->id(), // default to logged in admin if walk-in
                'booking_code' => 'BK' . strtoupper(uniqid()),
                'booking_date' => $request->booking_date,
                'total_amount' => $calculatedTotal,
                'status' => 'confirmed', // Admin booking auto confirmed
                'notes' => $request->notes,
            ]);

            $booking->details()->create([
                'field_id' => $request->field_id,
                'time_slot_id' => $request->time_slot_id,
                'price' => $calculatedTotal,
                'start_time' => $startDatetime,
                'end_time' => $endDatetime,
            ]);

            // Task 6: Tự động tạo Payment (POS) khi Admin đặt sân hộ
            \App\Models\Payment::create([
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'amount' => $calculatedTotal,
                'payment_method' => 'pos', // Admin thu tại quầy
                'status' => 'success',
                'transaction_id' => 'POS_' . time() . '_' . $booking->id,
                'paid_at' => now(),
            ]);

            return back()->with('success', 'Đã tạo lịch đặt sân thành công!');
        });
    }

    public function batchStore(Request $request)
    {
        $request->validate([
            'field_id' => 'required|exists:fields,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'days_of_week' => 'required|array', // e.g. [0, 6] for Sun, Sat (0 is Sunday)
            'time_slot_id' => 'required|exists:time_slots,id',
        ]);

        return \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            $field = \App\Models\Field::where('id', $request->field_id)->lockForUpdate()->first();
            $timeSlot = \App\Models\TimeSlot::find($request->time_slot_id);
            
            $startDate = \Carbon\Carbon::parse($request->start_date);
            $endDate = \Carbon\Carbon::parse($request->end_date);
            
            $fieldIdsToCheck = [$request->field_id];
            if ($field->parent_id) $fieldIdsToCheck[] = $field->parent_id;
            foreach ($field->children as $child) $fieldIdsToCheck[] = $child->id;

            $datesToBook = [];
            
            // Loop through all dates in range
            for ($date = clone $startDate; $date->lte($endDate); $date->addDay()) {
                if (in_array($date->dayOfWeek, $request->days_of_week)) {
                    $datesToBook[] = clone $date;
                }
            }
            
            if (empty($datesToBook)) {
                return back()->with('error', 'Không tìm thấy ngày nào phù hợp trong khoảng thời gian này!');
            }

            // Tình huống 1: Check conflicts for ALL dates first (Dừng lại báo lỗi cụ thể)
            foreach ($datesToBook as $date) {
                $startDatetime = \Carbon\Carbon::parse($date->format('Y-m-d') . ' ' . $timeSlot->start_time);
                $endDatetime = \Carbon\Carbon::parse($date->format('Y-m-d') . ' ' . $timeSlot->end_time);
                if ($endDatetime->lt($startDatetime)) $endDatetime->addDay();

                $exists = \App\Models\BookingDetail::whereHas('booking', function($q) {
                    $q->whereIn('status', ['pending', 'confirmed', 'completed']);
                })
                ->whereIn('field_id', $fieldIdsToCheck)
                ->where(function($q) use ($startDatetime, $endDatetime) {
                    $q->where('start_time', '<', $endDatetime)
                      ->where('end_time', '>', $startDatetime);
                })
                ->exists();

                if ($exists) {
                    return back()->with('error', 'Lịch đặt bị trùng vào ngày ' . $date->format('d/m/Y') . ' lúc ' . $timeSlot->start_time . '! Chuỗi lịch đã bị hủy.');
                }
            }

            // All clear, create bookings
            $successCount = 0;
            foreach ($datesToBook as $date) {
                $startDatetime = \Carbon\Carbon::parse($date->format('Y-m-d') . ' ' . $timeSlot->start_time);
                $endDatetime = \Carbon\Carbon::parse($date->format('Y-m-d') . ' ' . $timeSlot->end_time);
                if ($endDatetime->lt($startDatetime)) $endDatetime->addDay();

                $calculatedTotal = 0;
                $current = clone $startDatetime;
                $basePricePerHour = $field->base_price;

                while ($current < $endDatetime) {
                    $next = clone $current;
                    $next->startOfHour()->addHour();
                    if ($next > $endDatetime) $next = clone $endDatetime;
                    $minutes = $current->diffInMinutes($next);
                    $hourFraction = $minutes / 60.0;
                    $timeString = $current->format('H:i:s');
                    $slot = \App\Models\TimeSlot::where('start_time', '<=', $timeString)
                                    ->where('end_time', '>', $timeString)
                                    ->first();
                    $modifier = $slot ? $slot->price_modifier : 0;
                    $calculatedTotal += ($basePricePerHour + $modifier) * $hourFraction;
                    $current = clone $next;
                }

                $booking = \App\Models\Booking::create([
                    'user_id' => $request->user_id ?? auth()->id(),
                    'booking_code' => 'BATCH' . strtoupper(uniqid()),
                    'booking_date' => $date->format('Y-m-d'),
                    'total_amount' => $calculatedTotal,
                    'status' => 'confirmed', 
                    'notes' => ($request->notes ?? '') . ' (Đặt lịch Chuỗi / Giải đấu)',
                ]);

                $booking->details()->create([
                    'field_id' => $request->field_id,
                    'time_slot_id' => $request->time_slot_id,
                    'price' => $calculatedTotal,
                    'start_time' => $startDatetime,
                    'end_time' => $endDatetime,
                ]);

                // Task 6: Tự động tạo Payment (POS) khi Admin đặt sân hộ
                \App\Models\Payment::create([
                    'booking_id' => $booking->id,
                    'user_id' => $booking->user_id,
                    'amount' => $calculatedTotal,
                    'payment_method' => 'pos',
                    'status' => 'success',
                    'transaction_id' => 'POS_' . time() . '_' . $booking->id,
                    'paid_at' => now(),
                ]);
                
                $successCount++;
            }

            return back()->with('success', "Đã tạo thành công chuỗi $successCount lịch đặt sân liên tiếp!");
        });
    }

    public function updateStatus(Request $request, $id)
    {
        $booking = \App\Models\Booking::with('details.field')->findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:pending,confirmed,in_progress,completed,cancelled'
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();

        try {

        if ($request->status === 'in_progress' && $booking->status !== 'in_progress') {
            foreach ($booking->details as $detail) {
                $detail->update(['actual_start_time' => now()]);
            }
        }

        // Tình huống 5: Phạt quá giờ khi check-out (completed)
        if ($request->status === 'completed' && $booking->status !== 'completed') {
            $customEndTime = $request->actual_end_time ? \Carbon\Carbon::parse($request->actual_end_time) : null;

            foreach ($booking->details as $detail) {
                $actualEndTime = $customEndTime ?? $detail->end_time ?? now();
                
                $detail->update([
                    'actual_end_time' => $actualEndTime,
                    'overtime_fee' => 0
                ]);
            }

            // Reward points
            if ($booking->user && $booking->user->role === 'customer') {
                $pointsEarned = floor($booking->total_amount / 1000); // 1 point per 1000 VND
                $booking->user->increment('points', $pointsEarned);
            }
            
            // Task 11: Tính phí ngoài giờ (Overtime Fee) nếu Admin có nhập
            $overtimeFee = (int)$request->input('overtime_fee', 0);
            if ($overtimeFee > 0) {
                $booking->update([
                    'total_amount' => $booking->total_amount + $overtimeFee
                ]);
                $booking->details()->first()->update(['overtime_fee' => $overtimeFee]);
                
                // Thu khoản overtime này luôn dưới dạng cash tại quầy để tránh bị báo thiếu tiền
                \App\Models\Payment::create([
                    'booking_id' => $booking->id,
                    'amount' => $overtimeFee,
                    'payment_method' => 'cash', // Admin thu POS/Tiền mặt
                    'status' => 'success',
                    'transaction_id' => 'POS_OT_' . time() . '_' . $booking->id,
                    'paid_at' => now(),
                ]);
            }
            
            // Task 5: Chặn chuyển completed nếu chưa thanh toán đủ
            $paidAmount = \App\Models\Payment::where('booking_id', $booking->id)
                            ->where('status', 'success')
                            ->sum('amount');
            
            $unpaidAmount = $booking->total_amount - $paidAmount;
            
            if ($unpaidAmount > 0) {
                \Illuminate\Support\Facades\DB::rollBack();
                return back()->with('error', 'Không thể hoàn thành đơn! Khách hàng còn nợ ' . number_format($unpaidAmount) . 'đ. Vui lòng yêu cầu khách thanh toán đủ trước.');
            }

            // Task 10: Gamification - Nâng hạng (Level) cho user động từ bảng levels
            if ($booking->user && $booking->user->role === 'customer') {
                $totalPoints = $booking->user->points;
                
                $levels = \App\Models\Level::orderBy('required_points', 'desc')->get();
                $newLevelId = $booking->user->level_id; // Default keep current
                
                foreach ($levels as $level) {
                    if ($totalPoints >= $level->required_points) {
                        $newLevelId = $level->id;
                        break;
                    }
                }

                if ($booking->user->level_id !== $newLevelId) {
                    $booking->user->update(['level_id' => $newLevelId]);
                }
            }
        }
        
        // Tình huống 6: Hoàn tiền / Bảo lưu cọc nếu Hủy
        if ($request->status === 'cancelled' && $booking->status !== 'cancelled') {
            $paidAmount = \App\Models\Payment::where('booking_id', $booking->id)->where('status', 'success')->sum('amount');
            
            if ($paidAmount > 0 && $booking->user) {
                // Bảo lưu cọc (Hoàn tiền vào ví dư)
                $booking->user->wallet_balance += $paidAmount;
                $booking->user->save();
                
                \App\Models\WalletTransaction::create([
                    'user_id' => $booking->user_id,
                    'amount' => $paidAmount,
                    'type' => 'refund',
                    'description' => 'Hoàn tiền hủy đơn đặt sân ' . $booking->booking_code
                ]);
            }
        }
        
        $booking->update(['status' => $request->status]);

        // Gửi thông báo thực tế cho khách hàng
        if ($booking->user) {
            $statusMessages = [
                'confirmed' => ['title' => '✅ Đơn đặt sân đã xác nhận', 'message' => "Đơn đặt sân {$booking->booking_code} đã được Lễ tân xác nhận.", 'type' => 'success'],
                'in_progress' => ['title' => '⚽ Đang sử dụng sân', 'message' => "Đơn đặt sân {$booking->booking_code} đã bắt đầu.", 'type' => 'info'],
                'completed' => ['title' => '🎉 Hoàn thành', 'message' => "Đơn đặt sân {$booking->booking_code} đã hoàn thành. Cảm ơn quý khách!", 'type' => 'success'],
                'cancelled' => ['title' => '❌ Hủy đơn', 'message' => "Đơn đặt sân {$booking->booking_code} đã bị hủy bởi hệ thống.", 'type' => 'warning'],
            ];
            if (isset($statusMessages[$request->status])) {
                $msg = $statusMessages[$request->status];
                $booking->user->notify(new SystemNotification($msg['title'], $msg['message'], $msg['type']));
            }
        }

        \Illuminate\Support\Facades\DB::commit();
        return back()->with('success', 'Đã cập nhật trạng thái đơn đặt sân!');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
