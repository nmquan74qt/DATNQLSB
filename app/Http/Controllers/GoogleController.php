<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))->user();
            
            // Tìm user dựa trên google_id
            $user = User::where('google_id', $googleUser->id)->first();
            
            if ($user) {
                // Đăng nhập
                Auth::login($user);
                return redirect()->intended(route('home'))->with('success', 'Đăng nhập thành công!');
            }
            
            // Nếu chưa có google_id, tìm qua email
            $user = User::where('email', $googleUser->email)->first();
            if ($user) {
                // Liên kết tài khoản
                $user->update([
                    'google_id' => $googleUser->id,
                    'avatar' => $user->avatar ?? $googleUser->avatar
                ]);
                Auth::login($user);
                return redirect()->intended(route('home'))->with('success', 'Đăng nhập và liên kết tài khoản thành công!');
            }
            
            // Nếu chưa có tài khoản nào, tạo mới
            $newUser = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'password' => Hash::make(Str::random(24)), // Random password
                'avatar' => $googleUser->avatar,
                'role' => 'customer'
            ]);
            
            Auth::login($newUser);
            return redirect()->route('home')->with('success', 'Đăng nhập bằng Google thành công!');

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Lỗi đăng nhập bằng Google: ' . $e->getMessage());
        }
    }
}
