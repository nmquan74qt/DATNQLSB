<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Eloquent\StaffRepository;
use App\Services\PayrollService;
use App\Http\Requests\MarkAttendanceRequest;

class StaffController extends Controller
{
    protected $staffRepo;
    protected $payrollService;

    public function __construct(StaffRepository $staffRepo, PayrollService $payrollService)
    {
        $this->staffRepo = $staffRepo;
        $this->payrollService = $payrollService;
    }

    public function index()
    {
        \Illuminate\Support\Facades\Gate::authorize('manage', \App\Models\User::class);

        $staffs = $this->staffRepo->getAllStaffs();
        $attendances = $this->staffRepo->getTodayAttendances();
        $payrolls = $this->staffRepo->getCurrentMonthPayrolls();

        return view('admin.staff.index', compact('staffs', 'attendances', 'payrolls'));
    }

    public function markAttendance(MarkAttendanceRequest $request)
    {
        $this->staffRepo->markAttendance($request->validated());
        return redirect()->route('admin.staff.index')->with('success', 'Đã cập nhật điểm danh!');
    }

    public function generatePayroll()
    {
        \App\Jobs\GeneratePayrollJob::dispatch();
        return redirect()->route('admin.staff.index')->with('success', 'Đang xử lý tạo bảng lương ngầm (Background Job). Tiến trình sẽ sớm hoàn tất!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,staff',
            'phone' => 'nullable|string|max:20',
        ]);

        \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
        ]);

        return redirect()->route('admin.staff.index')->with('success', 'Đã thêm nhân viên mới!');
    }

    public function update(Request $request, $id)
    {
        $staff = \App\Models\User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            'role' => 'required|in:admin,staff',
            'phone' => 'nullable|string|max:20',
        ]);

        $staff->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
        ]);

        if ($request->filled('password')) {
            $staff->update(['password' => \Illuminate\Support\Facades\Hash::make($request->password)]);
        }

        return redirect()->route('admin.staff.index')->with('success', 'Đã cập nhật thông tin nhân viên!');
    }

    public function destroy($id)
    {
        $staff = \App\Models\User::findOrFail($id);
        if ($staff->id == auth()->id()) {
            return redirect()->route('admin.staff.index')->with('error', 'Không thể tự xóa tài khoản của chính mình!');
        }
        $staff->delete();
        return redirect()->route('admin.staff.index')->with('success', 'Đã xóa nhân viên!');
    }
}
