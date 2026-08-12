<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cache the maintenance mode setting for 5 minutes to avoid DB queries on every single request
        $isMaintenance = Cache::remember('maintenance_mode_status', 300, function () {
            if (Schema::hasTable('settings')) {
                return Setting::where('key', 'maintenance_mode')->value('value') == '1';
            }
            return false;
        });

        if ($isMaintenance) {
            // Allow admin to bypass maintenance mode completely (checks auth if available)
            if (auth()->check() && auth()->user()->role === 'admin') {
                return $next($request);
            }

            // Allow access to login/logout routes so admin can authenticate, and allow admin panel routes
            if ($request->is('admin*') || $request->is('login') || $request->is('logout')) {
                return $next($request);
            }

            // Return a simple maintenance mode view
            return response()->view('errors.503', [], 503);
        }

        return $next($request);
    }
}
