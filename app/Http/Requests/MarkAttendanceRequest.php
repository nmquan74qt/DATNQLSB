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
            'user_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $user = \App\Models\User::find($value);
                    if ($user && !in_array($user->role, ['staff', 'admin'])) {
                        $fail('Nhân viên không hợp lệ (phải là staff hoặc admin).');
                    }
                }
            ],
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
