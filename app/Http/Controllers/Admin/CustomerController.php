<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = \App\Models\User::where('role', 'customer')
            ->with(['bookings' => function($q) {
                $q->where('status', 'completed');
            }])
            ->withCount(['bookings as completed_bookings_count' => function($q) {
                $q->where('status', 'completed');
            }])
            ->latest()
            ->paginate(10);
            
        return view('admin.customers.index', compact('customers'));
    }

    public function update(Request $request, $id)
    {
        $customer = \App\Models\User::where('role', 'customer')->findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'points' => 'nullable|integer|min:0',
            'status' => 'nullable|in:active,inactive,banned' // Task 9: Cho phép khóa tài khoản
        ]);

        $customer->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'points' => $request->points ?? $customer->points,
            'status' => $request->status ?? $customer->status
        ]);

        if ($request->filled('password')) {
            $customer->update(['password' => \Illuminate\Support\Facades\Hash::make($request->password)]);
        }

        return redirect()->route('admin.customers.index')->with('success', 'Đã cập nhật thông tin khách hàng!');
    }

    public function destroy($id)
    {
        $customer = \App\Models\User::where('role', 'customer')->findOrFail($id);
        
        // Prevent deleting if they have bookings
        if ($customer->bookings()->exists()) {
            return redirect()->route('admin.customers.index')->with('error', 'Không thể xóa khách hàng đã có lịch sử đặt sân!');
        }

        $customer->delete();
        return redirect()->route('admin.customers.index')->with('success', 'Đã xóa khách hàng thành công!');
    }
}
