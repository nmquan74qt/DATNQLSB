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
