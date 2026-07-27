<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Models\Attendance;
use App\Models\Payroll;
use Carbon\Carbon;

class StaffRepository
{
    public function getAllStaffs()
    {
        return User::whereIn('role', ['staff'])->get();
    }

    public function getTodayAttendances()
    {
        return Attendance::with('user')
            ->whereDate('date', Carbon::today())
            ->get();
    }

    public function getCurrentMonthPayrolls()
    {
        return Payroll::with('user')
            ->where('month', Carbon::now()->month)
            ->where('year', Carbon::now()->year)
            ->get();
    }

    public function markAttendance(array $data)
    {
        return Attendance::updateOrCreate(
            [
                'user_id' => $data['user_id'],
                'date' => Carbon::today()
            ],
            [
                'status' => $data['status'],
                'check_in' => in_array($data['status'], ['present', 'late']) ? Carbon::now() : null
            ]
        );
    }
}
