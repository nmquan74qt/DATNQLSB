<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\SystemNotification;

class NotificationController extends Controller
{
    /**
     * Hiển thị giao diện Quản lý thông báo
     */
    public function index()
    {
        return view('admin.notifications.index');
    }

    /**
     * Gửi thông báo đến tất cả người dùng
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:info,promo,warning,success', // Thể loại từ mockup: info(hệ thống), promo(khuyến mãi)
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // Có thể tối ưu bằng chunk() nếu số lượng user lớn, 
        // nhưng với dự án hiện tại, get() và vòng lặp là đủ và nhanh chóng.
        $users = User::all();

        $count = 0;
        foreach ($users as $user) {
            $user->notify(new SystemNotification(
                $request->title,
                $request->content,
                $request->type
            ));
            $count++;
        }

        return back()->with('success', "Đã gửi thông báo thành công đến {$count} người dùng!");
    }
}
