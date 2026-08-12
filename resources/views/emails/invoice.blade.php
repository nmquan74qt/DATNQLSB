<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Hóa Đơn Thanh Toán Đặt Sân</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f8fafc; margin: 0; padding: 20px; color: #334155; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .header { background-color: #10b981; color: white; padding: 30px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { padding: 30px 20px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { padding: 12px 15px; border-bottom: 1px solid #e2e8f0; text-align: left; }
        .table th { background-color: #f8fafc; font-weight: 600; color: #475569; }
        .total-row { font-weight: bold; color: #10b981; font-size: 18px; }
        .footer { background-color: #f1f5f9; padding: 20px; text-align: center; font-size: 12px; color: #64748b; border-top: 1px solid #e2e8f0; }
        .badge { display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; background-color: #d1fae5; color: #059669; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>HÓA ĐƠN ĐẶT SÂN</h1>
            <p>Mã Đơn: {{ $booking->booking_code }}</p>
        </div>
        
        <div class="content">
            <p>Xin chào <strong>{{ $booking->user ? $booking->user->name : 'Quý khách' }}</strong>,</p>
            <p>Cảm ơn bạn đã tin tưởng và sử dụng dịch vụ của PitchManage. Chúng tôi xin gửi thông tin hóa đơn thanh toán cho đơn đặt sân của bạn:</p>
            
            <div style="margin-top: 20px; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px;">
                <p><strong>Ngày Đặt:</strong> {{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}</p>
                <p><strong>Trạng Thái:</strong> <span class="badge">Thanh toán thành công</span></p>
                <p><strong>Hình thức:</strong> {{ strtoupper($payment->payment_method) }}</p>
                <p><strong>Mã Giao Dịch:</strong> {{ $payment->transaction_id }}</p>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>Tên Sân</th>
                        <th>Khung Giờ</th>
                        <th style="text-align: right;">Giá</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($booking->details as $detail)
                    <tr>
                        <td>{{ $detail->field->name ?? 'Sân' }}</td>
                        <td>
                            @php
                                $startTime = '';
                                $endTime = '';
                                if ($detail->time_slot_id) {
                                    $timeSlot = \App\Models\TimeSlot::find($detail->time_slot_id);
                                    if ($timeSlot) {
                                        $startTime = \Carbon\Carbon::parse($timeSlot->start_time)->format('H:i');
                                        $endTime = \Carbon\Carbon::parse($timeSlot->end_time)->format('H:i');
                                    }
                                } else {
                                    $startTime = \Carbon\Carbon::parse($detail->start_time)->format('H:i');
                                    $endTime = \Carbon\Carbon::parse($detail->end_time)->format('H:i');
                                }
                            @endphp
                            {{ $startTime }} - {{ $endTime }}
                        </td>
                        <td style="text-align: right;">{{ number_format($detail->price) }}đ</td>
                    </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="2" style="text-align: right;">Tổng Tiền Đã Thanh Toán:</td>
                        <td style="text-align: right;">{{ number_format($payment->amount) }}đ</td>
                    </tr>
                </tbody>
            </table>

            <p style="margin-top: 30px;">Vui lòng có mặt tại sân trước 10 phút để chuẩn bị khởi động nhé. Chúc bạn có một trận đấu thật bùng nổ!</p>
        </div>

        <div class="footer">
            <p>Đây là email tự động, vui lòng không phản hồi lại email này.</p>
            <p>&copy; 2026 PitchManage. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
