<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Repositories\Eloquent\StaffRepository;
use App\Services\PayrollService;
use App\Http\Requests\MarkAttendanceRequest;

class StaffController extends Controller
{
    protected $staffRepo;
    protected $payrollService;

    public function __construct(StaffRepository $staffRepo = null, PayrollService $payrollService = null)
    {
        $this->staffRepo = $staffRepo;
        $this->payrollService = $payrollService;
    }

    public function index()
    {
        // Lấy danh sách nhân viên và admin (ngoại trừ customer)
        $staffs = User::whereIn('role', ['admin', 'staff'])->latest()->get();
        
        $attendances = $this->staffRepo ? $this->staffRepo->getTodayAttendances() : collect();
        $payrolls = $this->staffRepo ? $this->staffRepo->getCurrentMonthPayrolls() : collect();
        
        return view('admin.staff.index', compact('staffs', 'attendances', 'payrolls'));
    }

    public function markAttendance(MarkAttendanceRequest $request)
    {
        if ($this->staffRepo) {
            $this->staffRepo->markAttendance($request->validated());
        }
        return back()->with('success', 'Đã cập nhật điểm danh!');
    }

    public function selfAttendance(Request $request)
    {
        if ($this->staffRepo) {
            $this->staffRepo->markAttendance([
                'user_id' => auth()->id(),
                'status' => 'present',
                'date' => now()->format('Y-m-d')
            ]);
        }
        return back()->with('success', 'Bạn đã chấm công (Có mặt) hôm nay thành công!');
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
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,staff',
            'phone' => 'nullable|string|max:20'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'status' => 'active'
        ]);

        return back()->with('success', 'Đã thêm nhân viên mới thành công!');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:admin,staff',
            'status' => 'required|in:active,inactive,banned',
            'phone' => 'nullable|string|max:20'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'status' => $request->status,
            'phone' => $request->phone,
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Đã cập nhật thông tin nhân viên!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Không cho phép admin tự xóa chính mình
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Không thể xóa tài khoản đang đăng nhập!');
        }

        $user->delete();
        return back()->with('success', 'Đã xóa nhân viên!');
    }
}
