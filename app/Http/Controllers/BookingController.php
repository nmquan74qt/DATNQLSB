<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\TimeSlot;
use App\Models\Payment;
use Illuminate\Support\Str;
use App\Notifications\SystemNotification;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'field_id' => 'required|exists:fields,id',
            'booking_date' => 'required|date',
            'slots' => 'required|array|min:1',
            'slots.*.start_time' => 'required',
            'slots.*.end_time' => 'required',
            'total_amount' => 'required|numeric'
        ]);

        if (auth()->check() && in_array(auth()->user()->status, ['banned', 'inactive'])) {
            return response()->json(['success' => false, 'message' => 'Tài khoản của bạn đã bị vô hiệu hóa hoặc cấm truy cập.'], 403);
        }

        return \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            // Khóa (lock) record của sân này để chống trùng lịch (Race Condition)
            $field = \App\Models\Field::where('id', $request->field_id)->lockForUpdate()->first();
            
            if (!$field) {
                return response()->json(['success' => false, 'message' => 'Sân không tồn tại.']);
            }
            if (!$field->is_active || $field->status === 'maintenance') {
                return response()->json(['success' => false, 'message' => 'Sân đang bảo trì, không thể đặt lịch lúc này.']);
            }

            // Task 3: Chặn Bypass thanh toán
            if (!in_array($request->payment_method, ['vnpay', 'momo'])) {
                return response()->json(['success' => false, 'message' => 'Phương thức thanh toán không hợp lệ!'], 400);
            }

            $timeSlots = [];
            $realTotalAmount = 0; // Tình huống 2: Tính tổng tiền backend
            
            // First loop: Check availability for ALL slots before saving anything
            foreach ($request->slots as $slotData) {
                $startStr = $slotData['start_time'] . (strlen($slotData['start_time']) == 5 ? ':00' : '');
                $endStr = $slotData['end_time'] . (strlen($slotData['end_time']) == 5 ? ':00' : '');
                
                $timeSlot = TimeSlot::firstOrCreate([
                    'start_time' => $startStr,
                    'end_time' => $endStr
                ], [
                    'price_modifier' => 0,
                    'is_active' => true
                ]);

                // Xử lý logic DATETIME và đá xuyên đêm
                $startDatetime = \Carbon\Carbon::parse($request->booking_date . ' ' . $startStr);
                $endDatetime = \Carbon\Carbon::parse($request->booking_date . ' ' . $endStr);

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

                $exists = BookingDetail::whereHas('booking', function($q) {
                    $q->whereIn('status', ['pending', 'confirmed', 'completed']);
                })
                ->whereIn('field_id', $fieldIdsToCheck) // Check tất cả sân liên quan
                ->where(function($q) use ($startDatetime, $endDatetime) {
                    // Logic check trùng lịch tuyệt đối theo DATETIME
                    $q->where('start_time', '<', $endDatetime)
                      ->where('end_time', '>', $startDatetime);
                })
                ->exists();

                if ($exists) {
                    // Cố tình vứt ra Exception để rollback transaction nếu trùng
                    return response()->json(['success' => false, 'message' => 'Một hoặc nhiều khung giờ bạn chọn đã có người đặt. Vui lòng tải lại trang.']);
                }
                
                // Tính tiền cho khoảng thời gian này
                $slotPrice = $this->calculatePrice($field, $startDatetime, $endDatetime);
                $realTotalAmount += $slotPrice;
                
                // Store datetimes temporarily for saving later
                $timeSlot->actual_start_datetime = $startDatetime;
                $timeSlot->actual_end_datetime = $endDatetime;
                $timeSlot->calculated_price = $slotPrice; // Lưu tạm để insert vào BookingDetail
                $timeSlots[] = $timeSlot;
            }

            $notes = 'Đang chờ thanh toán qua ' . $request->payment_method;
            if ($request->voucher_code) {
                $voucher = \App\Models\Voucher::where('code', strtoupper($request->voucher_code))
                    ->lockForUpdate()
                    ->where('is_active', true)
                    ->where('used_count', '<', \Illuminate\Support\Facades\DB::raw('max_uses'))
                    ->where(function($q) {
                        $q->whereNull('valid_from')->orWhere('valid_from', '<=', now());
                    })
                    ->where(function($q) {
                        $q->whereNull('valid_to')->orWhere('valid_to', '>=', now());
                    })
                    ->first();

                if (!$voucher) {
                    return response()->json(['success' => false, 'message' => 'Mã Voucher không hợp lệ, đã hết hạn hoặc hết lượt sử dụng.']);
                }

                // Kiểm tra user đã sử dụng chưa
                $userId = auth()->id() ?? 1;
                $hasUsed = Booking::where('user_id', $userId)
                    ->where('voucher_id', $voucher->id)
                    ->whereIn('status', ['pending', 'confirmed', 'completed'])
                    ->exists();

                if ($hasUsed) {
                    return response()->json(['success' => false, 'message' => 'Bạn đã sử dụng mã Voucher này rồi.']);
                }

                $voucher->increment('used_count');
                $notes .= ' (Có áp dụng mã ' . $voucher->code . ')';
                
                // Trừ tiền Voucher
                if ($voucher->discount_percent) {
                    $realTotalAmount -= $realTotalAmount * ($voucher->discount_percent / 100);
                }
                if ($voucher->discount_amount) {
                    $realTotalAmount -= $voucher->discount_amount;
                }
                if ($realTotalAmount < 0) $realTotalAmount = 0;
            }

            $bookingCode = $request->booking_code ?? ('BK' . strtoupper(Str::random(6)));
            
            $booking = Booking::create([
                'user_id' => auth()->id() ?? 1, // fallback if not logged in
                'booking_code' => $bookingCode,
                'booking_date' => $request->booking_date,
                'total_amount' => $realTotalAmount, // Tình huống 2: backend tính
                'status' => 'pending', 
                'notes' => $notes,
                'voucher_id' => isset($voucher) ? $voucher->id : null // Task 8: Lưu voucher_id
            ]);

            // Second loop: Create booking details
            foreach ($timeSlots as $ts) {
                $booking->details()->create([
                    'field_id' => $request->field_id,
                    'time_slot_id' => $ts->id,
                    'price' => $ts->calculated_price, // Tình huống 2: đúng giá block
                    'start_time' => $ts->actual_start_datetime,
                    'end_time' => $ts->actual_end_datetime,
                ]);
            }

            if ($request->payment_method === 'vnpay') {
                $vnpayService = new \App\Services\Payment\VNPayService();
                $redirectUrl = $vnpayService->createPaymentUrl(
                    $booking->booking_code, 
                    $booking->total_amount, 
                    "Thanh toan dat san " . $booking->booking_code
                );
                
                return response()->json([
                    'success' => true, 
                    'message' => 'Đang chuyển hướng đến VNPay...',
                    'booking_code' => $booking->booking_code,
                    'redirect_url' => $redirectUrl
                ]);
            }

            if ($request->payment_method === 'momo') {
                $momoService = new \App\Services\Payment\MoMoService();
                $redirectUrl = $momoService->createPaymentUrl(
                    $booking->booking_code, 
                    $booking->total_amount, 
                    "Thanh toan dat san " . $booking->booking_code
                );
                
                return response()->json([
                    'success' => true, 
                    'message' => 'Đang chuyển hướng đến MoMo...',
                    'booking_code' => $booking->booking_code,
                    'redirect_url' => $redirectUrl
                ]);
            }

            // Fallback (thực tế không chạy đến đây vì đã bị chặn bởi validator và return ở trên)
            return response()->json([
                'success' => false,
                'message' => 'Phương thức thanh toán không hợp lệ!'
            ], 400);
        });
    }

    public function checkVoucher(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        
        $voucher = \App\Models\Voucher::where('code', strtoupper($request->code))
            ->where('is_active', true)
            ->where('used_count', '<', \Illuminate\Support\Facades\DB::raw('max_uses'))
            ->where(function($q) {
                $q->whereNull('valid_from')->orWhere('valid_from', '<=', now());
            })
            ->where(function($q) {
                $q->whereNull('valid_to')->orWhere('valid_to', '>=', now());
            })
            ->first();

        if (!$voucher) {
            return response()->json(['success' => false, 'message' => 'Mã Voucher không hợp lệ, đã hết hạn hoặc hết lượt sử dụng.']);
        }

        // Kiểm tra user đã sử dụng chưa
        $userId = auth()->id() ?? 1;
        $hasUsed = Booking::where('user_id', $userId)
            ->where('voucher_id', $voucher->id) // Task 8: Dùng voucher_id
            ->whereIn('status', ['pending', 'confirmed', 'completed'])
            ->exists();

        if ($hasUsed) {
            return response()->json(['success' => false, 'message' => 'Bạn đã sử dụng mã Voucher này cho một đơn đặt sân trước đó.']);
        }

        return response()->json([
            'success' => true,
            'voucher' => [
                'code' => $voucher->code,
                'discount_percent' => $voucher->discount_percent,
                'discount_amount' => $voucher->discount_amount
            ]
        ]);
    }

    public function checkPaymentStatus($code)
    {
        $booking = Booking::where('booking_code', $code)->first();
        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Not found']);
        }

        return response()->json([
            'success' => true,
            'status' => $booking->status // 'pending', 'confirmed', etc.
        ]);
    }

    public function simulateWebhook($code)
    {
        // Task 2: Bảo vệ route giả lập webhook bằng secret key
        if (request()->query('secret') !== 'datn2026') {
            return response()->json(['success' => false, 'message' => 'Forbidden: Invalid secret key'], 403);
        }

        $booking = Booking::where('booking_code', $code)->first();
        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking not found']);
        }

        if ($booking->status === 'pending') {
            $booking->update([
                'status' => 'confirmed',
                'notes' => $booking->notes . ' (Đã thanh toán tự động)'
            ]);
            
            // Create payment record
            Payment::create([
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'amount' => $booking->total_amount,
                'payment_method' => str_contains(strtolower($booking->notes), 'momo') ? 'momo' : 'vnpay',
                'status' => 'success',
                'transaction_id' => 'SIMULATED_' . time(),
                'paid_at' => now(),
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Simulated webhook processed']);
    }

    /**
     * Tình huống bảo vệ 2: Tính tiền chia theo block giờ (Giờ thường/Giờ vàng)
     */
    private function calculatePrice($field, $startDatetime, $endDatetime)
    {
        $calculatedTotal = 0;
        $current = clone $startDatetime;
        $basePricePerHour = $field->base_price;

        while ($current < $endDatetime) {
            // Find the next hour boundary
            $next = clone $current;
            $next->startOfHour()->addHour();
            
            if ($next > $endDatetime) {
                $next = clone $endDatetime;
            }
            
            $minutes = $current->diffInMinutes($next);
            $hourFraction = $minutes / 60.0;
            
            $timeString = $current->format('H:i:s');
            
            // Lấy modifier của khung giờ này (Giờ vàng)
            $slot = \App\Models\TimeSlot::where('is_active', true)
                            ->where(function($q) use ($field) {
                                $q->whereNull('field_id')->orWhere('field_id', $field->id);
                            })
                            ->where('start_time', '<=', $timeString)
                            ->where('end_time', '>', $timeString)
                            ->orderBy('field_id', 'desc') // Ưu tiên khung giờ riêng của sân
                            ->first();
                            
            $isWeekend = $current->isWeekend();
            
            $modifier = 0;
            if ($slot) {
                $modifier = $isWeekend ? ($slot->weekend_price_modifier ?? 0) : ($slot->price_modifier ?? 0);
            }
            
            $hourlyRate = $basePricePerHour + $modifier;
            
            $calculatedTotal += $hourlyRate * $hourFraction;
            
            $current = clone $next;
        }

        return $calculatedTotal;
    }
}
