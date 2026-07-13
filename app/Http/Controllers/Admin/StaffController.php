<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        $staffRole = Role::where('name', 'staff')->first();
        $staffs = User::where('role_id', $staffRole->id)->paginate(10);
        return view('admin.staffs.index', compact('staffs'));
    }

    public function create()
    {
        $roles = Role::whereIn('name', ['staff', 'manager'])->get();
        return view('admin.staffs.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('admin.staffs.index')->with('success', 'Tài khoản nhân viên mới đã được tạo.');
    }

    public function edit(User $staff)
    {
        // Don't allow editing other managers unless authorized
        $roles = Role::whereIn('name', ['staff', 'manager'])->get();
        return view('admin.staffs.edit', compact('staff', 'roles'));
    }

    public function update(StoreUserRequest $request, User $staff)
    {
        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $staff->update($data);

        return redirect()->route('admin.staffs.index')->with('success', 'Thông tin nhân viên đã được cập nhật.');
    }

    public function destroy(User $staff)
    {
        // Prevent self delete
        if (auth()->id() === $staff->id) {
            return redirect()->route('admin.staffs.index')->with('error', 'Bạn không thể tự xóa tài khoản của chính mình.');
        }

        $staff->delete();
        return redirect()->route('admin.staffs.index')->with('success', 'Tài khoản nhân viên đã được xóa.');
    }

    public function toggleStatus(User $staff)
    {
        if (auth()->id() === $staff->id) {
            return redirect()->route('admin.staffs.index')->with('error', 'Bạn không thể tự khóa tài khoản của chính mình.');
        }

        $staff->status = $staff->status === 'active' ? 'locked' : 'active';
        $staff->save();

        $msg = $staff->status === 'active' ? 'Đã mở khóa tài khoản thành công.' : 'Đã khóa tài khoản thành công.';
        return redirect()->route('admin.staffs.index')->with('success', $msg);
    }
}
