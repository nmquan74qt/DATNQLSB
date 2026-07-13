<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    public function store(Request $request)
    {
        // Simple mock reset
        return redirect()->route('login')->with('status', 'Mật khẩu của bạn đã được cập nhật thành công.');
    }
}
