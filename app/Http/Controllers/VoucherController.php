<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Eloquent\VoucherRepository;
use App\Http\Requests\StoreVoucherRequest;

class VoucherController extends Controller
{
    protected $voucherRepo;

    public function __construct(VoucherRepository $voucherRepo)
    {
        $this->voucherRepo = $voucherRepo;
    }

    public function index()
    {
        $vouchers = $this->voucherRepo->getAll();
        return view('admin.vouchers.index', compact('vouchers'));
    }

    public function store(StoreVoucherRequest $request)
    {
        $this->voucherRepo->create($request->validated());
        return redirect()->back()->with('success', 'Tạo Voucher thành công!');
    }

    public function autoGenerate()
    {
        $code = 'AUTO' . strtoupper(\Illuminate\Support\Str::random(6));
        $discount = rand(1, 4) * 5; // 5%, 10%, 15%, 20%
        
        $this->voucherRepo->create([
            'code' => $code,
            'name' => 'Voucher Tự Động Giảm ' . $discount . '%',
            'discount_percent' => $discount,
            'max_uses' => 100,
            'valid_from' => now(),
            'valid_to' => now()->addDays(7), // Hết hạn sau 7 ngày
            'is_active' => true,
        ]);
        
        return redirect()->back()->with('success', 'Đã tạo ngẫu nhiên Voucher: ' . $code);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'required|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_to' => 'nullable|date|after_or_equal:valid_from',
            'is_active' => 'boolean'
        ]);

        $this->voucherRepo->update($id, $validated);
        return redirect()->back()->with('success', 'Cập nhật Voucher thành công!');
    }

    public function destroy($id)
    {
        $this->voucherRepo->delete($id);
        return redirect()->back()->with('success', 'Xóa Voucher thành công!');
    }
}
