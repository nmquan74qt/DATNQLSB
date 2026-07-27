<?php

namespace App\Services;

use App\Models\User;
use App\Models\Payroll;
use App\Models\Attendance;
use Carbon\Carbon;

class PayrollService
{
    public function generateMonthlyPayroll()
    {
        $staffs = User::whereIn('role', ['staff'])->get();
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;

        foreach ($staffs as $staff) {
            $existing = Payroll::where('user_id', $staff->id)
                ->where('month', $month)
                ->where('year', $year)
                ->first();

            if (!$existing) {
                // Business Logic for Salary
                $baseSalary = 7000000;
                
                $lates = Attendance::where('user_id', $staff->id)
                    ->whereMonth('date', $month)
                    ->whereYear('date', $year)
                    ->where('status', 'late')
                    ->count();
                
                $deduction = $lates * 100000; // Phạt 100k/ngày đi trễ
                $totalSalary = $baseSalary - $deduction;

                $payroll = Payroll::create([
                    'user_id' => $staff->id,
                    'month' => $month,
                    'year' => $year,
                    'base_salary' => $baseSalary,
                    'deduction' => $deduction,
                    'total_salary' => $totalSalary,
                    'status' => 'pending',
                ]);

                // Send Email to Staff in background
                \Illuminate\Support\Facades\Mail::to($staff->email)->queue(new \App\Mail\PayrollSlip($payroll));
            }
        }
    }
}
