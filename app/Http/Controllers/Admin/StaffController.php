<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    public function index()
    {
        // Lấy danh sách nhân viên và admin (ngoại trừ customer)
        $staffs = User::whereIn('role', ['admin', 'staff'])->latest()->get();
        return view('admin.staff.index', compact('staffs'));
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
