<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFieldRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'field_type_id' => ['required', 'exists:field_types,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'status' => ['required', 'in:available,maintenance,occupied'],
        ];
    }

    public function messages(): array
    {
        return [
            'field_type_id.required' => 'Vui lòng chọn loại sân.',
            'field_type_id.exists' => 'Loại sân không hợp lệ.',
            'name.required' => 'Vui lòng nhập tên sân.',
            'status.required' => 'Vui lòng chọn trạng thái sân.',
            'image.image' => 'Tệp tải lên phải là hình ảnh.',
            'image.max' => 'Kích thước ảnh tối đa là 2MB.',
        ];
    }
}
