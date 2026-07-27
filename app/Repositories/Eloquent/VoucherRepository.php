<?php

namespace App\Repositories\Eloquent;

use App\Models\Voucher;

class VoucherRepository
{
    public function getAll()
    {
        return Voucher::latest()->get();
    }

    public function create(array $data)
    {
        $data['is_active'] = $data['is_active'] ?? true;
        return Voucher::create($data);
    }

    public function update($id, array $data)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->update($data);
        return $voucher;
    }

    public function delete($id)
    {
        $voucher = Voucher::findOrFail($id);
        return $voucher->delete();
    }
}
