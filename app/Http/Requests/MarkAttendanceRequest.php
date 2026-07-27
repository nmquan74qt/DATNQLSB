<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MarkAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:present,absent,late,leave',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.exists' => 'Nhân viên không tồn tại.',
            'status.in' => 'Trạng thái điểm danh không hợp lệ.',
        ];
    }
}
