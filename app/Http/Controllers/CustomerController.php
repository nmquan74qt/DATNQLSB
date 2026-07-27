<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;

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

        return view('customer.dashboard', compact('user', 'level', 'nextLevel', 'bookings'));
    }
}
