<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customerRole = Role::where('name', 'customer')->first();
        $query = User::where('role_id', $customerRole->id);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%");
            });
        }

        $customers = $query->paginate(10)->withQueryString();
        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        $roles = Role::where('name', 'customer')->get();
        return view('admin.customers.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('admin.customers.index')->with('success', 'Tài khoản khách hàng mới đã được tạo.');
    }

    public function edit(User $customer)
    {
        $roles = Role::where('name', 'customer')->get();
        return view('admin.customers.edit', compact('customer', 'roles'));
    }

    public function update(StoreUserRequest $request, User $customer)
    {
        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $customer->update($data);

        return redirect()->route('admin.customers.index')->with('success', 'Thông tin khách hàng đã được cập nhật.');
    }

    public function toggleStatus(User $customer)
    {
        $customer->status = $customer->status === 'active' ? 'locked' : 'active';
        $customer->save();

        $msg = $customer->status === 'active' ? 'Đã mở khóa tài khoản khách hàng.' : 'Đã khóa tài khoản khách hàng.';
        return redirect()->route('admin.customers.index')->with('success', $msg);
    }
}
