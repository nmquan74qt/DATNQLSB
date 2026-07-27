<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVoucherRequest extends FormRequest
{
    public function authorize(): bool
    {
        // For now, return true (we'll enforce via Policies later)
        return true; 
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string|unique:vouchers,code',
            'name' => 'required|string|max:255',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'required|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_to' => 'nullable|date|after_or_equal:valid_from',
        ];
    }

    public function messages(): array
    {
        return [
            'code.unique' => 'Mã Voucher này đã tồn tại.',
            'max_uses.min' => 'Số lượt dùng phải lớn hơn 0.',
            'valid_to.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
        ];
    }
}
