<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // Tự động logout phiên cũ nếu tài khoản bị khóa/đình chỉ
        if (in_array($user->status, ['banned', 'inactive'])) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/login')->with('error', 'Tài khoản của bạn đã bị vô hiệu hóa hoặc cấm truy cập.');
        }

        // Nếu user có một trong các role yêu cầu
        if (!in_array($user->role, $roles)) {
            // Nếu là admin thì được phép truy cập mọi role staff
            if ($user->role === 'admin' && (in_array('staff', $roles))) {
                return $next($request);
            }
            abort(403, 'Bạn không có quyền truy cập khu vực này.');
        }

        return $next($request);
    }
}
