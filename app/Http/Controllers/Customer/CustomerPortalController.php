<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Invoice;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerPortalController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $bookings = Booking::where('user_id', $user->id)
            ->with(['bookingDetails.footballField', 'bookingDetails.timeSlot', 'review'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('customer.dashboard', compact('bookings'));
    }

    public function showBooking(Booking $booking)
    {
        // Protect booking authorization
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Bạn không có quyền xem thông tin này.');
        }

        $booking->load(['bookingDetails.footballField.fieldType', 'bookingDetails.timeSlot', 'serviceOrders.service', 'payment', 'invoice', 'review']);
        return view('customer.bookings.show', compact('booking'));
    }

    public function editProfile()
    {
        $user = auth()->user();
        return view('customer.profile', compact('user'));
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = auth()->user();
        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $avatarPath;
        }

        $user->update($data);

        return redirect()->route('customer.profile.edit')->with('success', 'Hồ sơ của bạn đã được cập nhật thành công.');
    }

    public function storeReview(Request $request, Booking $booking)
    {
        // Protect authorization
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Bạn không có quyền đánh giá đơn này.');
        }

        // Only completed bookings can be reviewed
        if ($booking->status !== 'completed') {
            return back()->with('error', 'Chỉ có thể đánh giá sân sau khi đã đá xong.');
        }

        // Check if already reviewed
        if ($booking->review()->exists()) {
            return back()->with('error', 'Bạn đã đánh giá đơn đặt sân này rồi.');
        }

        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $booking->review()->create([
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Cảm ơn bạn đã gửi đánh giá! Ý kiến của bạn sẽ giúp chúng tôi cải thiện chất lượng dịch vụ.');
    }
}
