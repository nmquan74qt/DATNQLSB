<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\SystemNotification;

class NotificationController extends Controller
{
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return response()->json(['success' => true]);
    }

    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }

    public function demo()
    {
        $user = Auth::user();
        
        // 1. Thông báo hệ thống
        $user->notify(new SystemNotification(
            '📢 Bảo trì ngày hôm nay',
            'Hệ thống bảo trì máy chủ lúc 10h sáng.',
            'info'
        ));

        // 2. Khuyến mãi
        $user->notify(new SystemNotification(
            '🎁 Giảm 50%',
            'Mã giảm giá: sale50. Áp dụng cho sân 7 người.',
            'promo'
        ));

        // 3. Đặt sân
        $user->notify(new SystemNotification(
            '⚽ Đặt sân thành công',
            'Đơn đặt sân BK-1234 đã được Lễ tân xác nhận!',
            'success'
        ));

        // 4. Nhắc lịch đá
        $user->notify(new SystemNotification(
            '⏰ Nhắc lịch đá',
            'Bạn có lịch đá tại Sân A1 vào lúc 17:00 hôm nay.',
            'warning'
        ));

        return back()->with('success', 'Đã tạo 4 thông báo mẫu thành công!');
    }
}
