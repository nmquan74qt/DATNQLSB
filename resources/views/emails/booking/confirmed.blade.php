<x-mail::message>
# Xác nhận Đặt Sân Thành Công!

Chào bạn,

Cảm ơn bạn đã đặt sân tại hệ thống của chúng tôi. Dưới đây là thông tin chi tiết đơn đặt sân của bạn:

**Mã đặt sân:** {{ $booking->booking_code }}  
@php
    $firstDetail = $booking->bookingDetails->first();
@endphp
**Sân bóng:** {{ $firstDetail->field->name ?? 'Không rõ' }}  
**Thời gian bắt đầu:** {{ \Carbon\Carbon::parse($firstDetail->start_time)->format('H:i d/m/Y') }}  
**Thời gian kết thúc:** {{ \Carbon\Carbon::parse($booking->bookingDetails->last()->end_time)->format('H:i d/m/Y') }}  
**Tổng thanh toán:** {{ number_format($booking->total_amount) }} VNĐ

<x-mail::button :url="route('home')">
Trang Chủ
</x-mail::button>

Trân trọng,<br>
{{ config('app.name') }}
</x-mail::message>
