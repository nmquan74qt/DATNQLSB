<x-mail::message>
# Phiếu Lương Tháng {{ $payroll->month }}/{{ $payroll->year }}

Xin chào **{{ $payroll->user->name }}**,

Hệ thống ERP PitchManage xin gửi đến bạn thông tin bảng lương tháng {{ $payroll->month }}.

<x-mail::panel>
**Chi tiết lương:**
- Lương cơ bản: {{ number_format($payroll->base_salary) }} VNĐ
- Phạt đi trễ: -{{ number_format($payroll->deduction) }} VNĐ
- **Tổng thực nhận: {{ number_format($payroll->total_salary) }} VNĐ**
</x-mail::panel>

<x-mail::button :url="url('/admin/staff')">
Xem chi tiết trên ERP
</x-mail::button>

Cảm ơn bạn đã cống hiến hết mình,<br>
**{{ config('app.name') }} HR Team**
</x-mail::message>
