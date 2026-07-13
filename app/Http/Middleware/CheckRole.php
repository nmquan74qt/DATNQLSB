<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if account is locked
        if ($user->status === 'locked') {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Tài khoản của bạn đã bị khóa bởi quản trị viên.',
            ]);
        }

        // Check if user has one of the required roles
        if ($user->role && in_array($user->role->name, $roles)) {
            return $next($request);
        }

        // Custom redirect based on user's role if unauthorized
        if ($user->isManager() || $user->isStaff()) {
            return redirect()->route('admin.dashboard')->with('error', 'Bạn không có quyền truy cập trang này.');
        }

        return redirect()->route('customer.dashboard')->with('error', 'Bạn không có quyền truy cập trang này.');
    }
}
