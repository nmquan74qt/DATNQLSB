<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use App\Notifications\SystemNotification;

class CustomerController extends Controller
{
    public function dashboard()
    {
        // For gamification, we need to query user's level
        // But since we just added it and haven't created the Level model, we'll use DB query
        $user = Auth::user();
        
        $level = null;
        if ($user->level_id) {
            $level = DB::table('levels')->where('id', $user->level_id)->first();
        }

        // Get next level for progress bar
        $nextLevel = null;
        if ($level) {
            $nextLevel = DB::table('levels')->where('required_points', '>', $level->required_points)->orderBy('required_points', 'asc')->first();
        } else {
            $nextLevel = DB::table('levels')->orderBy('required_points', 'asc')->first();
        }

        // Query real bookings
        $bookings = Booking::where('user_id', $user->id)->with(['details.field', 'details.timeSlot'])->latest()->take(5)->get();

        // Query wallet transactions
        $walletTransactions = \App\Models\WalletTransaction::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();

        return view('customer.dashboard', compact('user', 'level', 'nextLevel', 'bookings', 'walletTransactions'));
    }
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'dob' => 'nullable|date',
            'bank_name' => 'nullable|string|max:255',
            'bank_account' => 'nullable|string|max:255',
            'bank_account_name' => 'nullable|string|max:255',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        
        if ($request->filled('password')) {
            if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
                return back()->with('error', 'Mật khẩu hiện tại không đúng!');
            }
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->dob = $request->dob;
        $user->bank_name = $request->bank_name;
        $user->bank_account = $request->bank_account;
        $user->bank_account_name = $request->bank_account_name;
        
        // Save avatar if we implement file upload later
        // $user->avatar = ...

        $user->save();

        return redirect()->back()->with('success', 'Đã cập nhật thông tin cá nhân thành công!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Mật khẩu hiện tại không đúng!');
        }

        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Đã đổi mật khẩu thành công!');
    }

    public function cancelBooking(Request $request, $id)
    {
        $booking = Booking::with('details')->where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Không thể hủy đơn ở trạng thái này!');
        }

        // Get the earliest start time of this booking
        $earliestStart = null;
        foreach ($booking->details as $detail) {
            $startTime = $detail->start_time;
            if (!$startTime) {
                // Fallback if old data format
                $timeSlot = \App\Models\TimeSlot::find($detail->time_slot_id);
                $startTime = \Carbon\Carbon::parse($booking->booking_date . ' ' . $timeSlot->start_time);
            } else {
                $startTime = \Carbon\Carbon::parse($startTime);
            }

            if (!$earliestStart || $startTime->lt($earliestStart)) {
                $earliestStart = $startTime;
            }
        }

        if (!$earliestStart) {
            return back()->with('error', 'Lỗi dữ liệu thời gian sân!');
        }

        $hoursDiff = now()->diffInMinutes($earliestStart, false) / 60.0;

        if ($hoursDiff <= 0 && $booking->status === 'confirmed') {
             return back()->with('error', 'Đã qua giờ đá, không thể hủy sân!');
        }

        if ($booking->status === 'pending') {
            // Trường hợp 1: Chưa cọc
            $booking->update([
                'status' => 'cancelled',
                'notes' => ($booking->notes ? $booking->notes . "\n" : '') . "Khách tự hủy đơn (chưa cọc)."
            ]);
            return back()->with('success', 'Đã hủy đơn thành công!');
        }

        // Đã xác nhận (có thể đã thanh toán cọc)
        $paidAmount = \App\Models\Payment::where('booking_id', $booking->id)
                            ->where('status', 'success')
                            ->sum('amount');

        if ($hoursDiff > 4) {
            // Trường hợp 2: Hủy sớm > 4 tiếng => Hoàn cọc vào ví
            if ($paidAmount > 0) {
                $user = auth()->user();
                $user->wallet_balance += $paidAmount;
                $user->save();

                \App\Models\WalletTransaction::create([
                    'user_id' => $user->id,
                    'amount' => $paidAmount,
                    'type' => 'refund',
                    'description' => 'Hoàn tiền cọc hủy đơn đặt sân ' . $booking->booking_code
                ]);

                $booking->update([
                    'status' => 'cancelled',
                    'notes' => ($booking->notes ? $booking->notes . "\n" : '') . "Khách tự hủy trước " . round($hoursDiff, 1) . " tiếng. Đã hoàn " . number_format($paidAmount) . "đ vào ví cá nhân."
                ]);
                $user->notify(new SystemNotification('💰 Hoàn tiền vào ví', "Ví cá nhân của bạn vừa được cộng +" . number_format($paidAmount) . "đ do hủy đơn {$booking->booking_code}.", 'success'));
                return back()->with('success', 'Đã hủy đơn thành công. Tiền cọc đã được hoàn vào Ví Cá Nhân của bạn!');
            } else {
                $booking->update([
                    'status' => 'cancelled',
                    'notes' => ($booking->notes ? $booking->notes . "\n" : '') . "Khách tự hủy trước " . round($hoursDiff, 1) . " tiếng."
                ]);
                $user = auth()->user();
                $user->notify(new SystemNotification('❌ Hủy đơn', "Đơn đặt sân {$booking->booking_code} đã được hủy thành công.", 'warning'));
                return back()->with('success', 'Đã hủy đơn thành công!');
            }
        } else {
            // Trường hợp 3: Hủy trễ < 4 tiếng => Mất cọc
            $booking->update([
                'status' => 'cancelled',
                'notes' => ($booking->notes ? $booking->notes . "\n" : '') . "Khách tự hủy sát giờ (" . round($hoursDiff, 1) . " tiếng). Tịch thu cọc theo quy định."
            ]);
            $user = auth()->user();
            $user->notify(new SystemNotification('❌ Hủy đơn trễ', "Đơn {$booking->booking_code} đã hủy trễ. Không được hoàn cọc theo quy định.", 'warning'));
            return back()->with('error', 'Đã hủy đơn. Rất tiếc, bạn hủy sân trước giờ đá dưới 4 tiếng nên không được hoàn cọc theo quy định.');
        }
    }
}
