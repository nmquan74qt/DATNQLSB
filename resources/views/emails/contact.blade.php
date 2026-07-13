<!DOCTYPE html>
<html>
<head>
    <title>Liên hệ mới từ khách hàng</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .header { background-color: #198754; color: white; padding: 10px 20px; border-radius: 5px 5px 0 0; }
        .content { padding: 20px; }
        .item { margin-bottom: 15px; }
        .label { font-weight: bold; color: #555; }
        .value { margin-top: 5px; background: #f9f9f9; padding: 10px; border-left: 3px solid #198754; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>PitchManage - Có Liên Hệ Mới</h2>
        </div>
        <div class="content">
            <p>Xin chào Admin,</p>
            <p>Bạn vừa nhận được một tin nhắn liên hệ mới từ khách hàng thông qua website PitchManage.</p>
            
            <div class="item">
                <div class="label">Họ và tên:</div>
                <div class="value">{{ $data['name'] }}</div>
            </div>
            
            <div class="item">
                <div class="label">Số điện thoại:</div>
                <div class="value">{{ $data['phone'] }}</div>
            </div>

            @if(!empty($data['email']))
            <div class="item">
                <div class="label">Email:</div>
                <div class="value">{{ $data['email'] }}</div>
            </div>
            @endif
            
            <div class="item">
                <div class="label">Nội dung tin nhắn:</div>
                <div class="value">{{ nl2br(e($data['message'])) }}</div>
            </div>
            
            <hr style="margin-top: 30px; border: 0; border-top: 1px solid #eee;">
            <p style="font-size: 12px; color: #888; text-align: center;">Đây là email tự động từ hệ thống PitchManage. Vui lòng không trả lời trực tiếp email này trừ khi bạn muốn phản hồi lại địa chỉ email của khách (nếu có).</p>
        </div>
    </div>
</body>
</html>
