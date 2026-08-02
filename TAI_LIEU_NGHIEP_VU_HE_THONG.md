# TÀI LIỆU LUỒNG NGHIỆP VỤ HỆ THỐNG PITCHMANAGE (QUẢN LÝ ĐẶT SÂN BÓNG)

Dưới đây là chi tiết tất cả các luồng hoạt động (User Flows) và nghiệp vụ kinh doanh (Business Logic) cốt lõi của hệ thống, giúp bạn dễ dàng thuyết trình hoặc viết báo cáo đồ án.

## 1. PHÂN QUYỀN NGƯỜI DÙNG (ROLES)
Hệ thống được chia làm 3 vai trò (Roles) chính:
- **Khách hàng (Customer):** Đặt sân, thanh toán, đánh giá.
- **Nhân viên (Staff):** Quét mã QR nhận sân, check-in, check-out, hỗ trợ tại sân.
- **Quản trị viên (Admin):** Toàn quyền quản lý hệ thống, thống kê doanh thu, quản lý nhân viên và khách hàng.

## 2. LUỒNG NGHIỆP VỤ DÀNH CHO KHÁCH HÀNG (CUSTOMER FLOW)

### 2.1. Đăng ký & Đăng nhập
- **Nghiệp vụ:** Khách hàng tạo tài khoản bằng Email, SĐT, Mật khẩu. Dữ liệu mật khẩu được mã hóa an toàn (Bcrypt).
- **Gamification (Tích điểm):** Hệ thống có thể gắn sẵn cơ chế Cấp độ (Level) cho người mới để tạo động lực.

### 2.2. Tìm kiếm & Xem chi tiết sân
- **Trang chủ (Home):** Hiển thị các sân bóng nổi bật, banner quảng cáo.
- **Danh sách sân (Fields):** Khách hàng có thể lọc sân theo:
  - Loại sân (Sân 5 người, 7 người, 11 người).
  - Khoảng giá (Từ thấp đến cao).
- **Chi tiết sân (Field Detail):** Xem hình ảnh, mô tả chi tiết, giá tiền và các đánh giá (Review) từ những người đã thuê trước đó.

### 2.3. Nghiệp vụ Đặt Sân (Booking Logic)
Đây là nghiệp vụ quan trọng nhất của hệ thống:
- **Chọn thời gian:** Khách chọn Ngày và Giờ muốn thuê (Time Slots).
- **Kiểm tra trùng lặp:** Hệ thống (Database) sẽ tự động kiểm tra xem khung giờ đó trong ngày đó sân đã có ai đặt hay chưa. Nếu đã có người đặt, sẽ chặn không cho đặt.
- **Tính giá tiền:** Hệ thống tính toán Tổng tiền = (Giá sân theo khung giờ) x (Số lượng giờ).
- **Tạo Đơn (Order):** Mã đơn hàng (Mã Booking) được sinh ngẫu nhiên để tiện tra cứu. Trạng thái ban đầu là Chờ xác nhận (Pending).

### 2.4. Nghiệp vụ Thanh Toán (Tích hợp VNPay)
Khách hàng có 2 tùy chọn thanh toán:
- **Tiền mặt tại sân:** Đơn hàng ghi nhận Chưa thanh toán, chờ khách đến sân đưa tiền mặt cho nhân viên.
- **Thanh toán Online qua VNPay:**
  - Dữ liệu đơn hàng được mã hóa chuẩn hóa urlencode tạo thành một chữ ký số (vnp_SecureHash) an toàn tuyệt đối.
  - Khách hàng được chuyển hướng sang cổng thanh toán VNPay để nhập thẻ (Sandbox Test).
  - Sau khi trừ tiền, VNPay sẽ gọi lại (Callback) về hệ thống qua Return URL.
  - Hệ thống giải mã chữ ký, kiểm tra tính hợp lệ. Nếu mã lỗi vnp_ResponseCode == '00', hệ thống tự động cập nhật Đơn hàng thành Đã thanh toán và gửi email hóa đơn thành công.

### 2.5. Lịch sử Đặt sân & Đánh giá (Review)
- **Lịch sử:** Khách hàng vào Dashboard để xem lại danh sách sân đã đặt, kiểm tra xem Admin đã duyệt đơn chưa.
- **Đánh giá:** Khi đơn hàng chuyển sang trạng thái Hoàn thành (Đã đá xong), khách hàng mới được quyền viết Review và chấm sao (1-5 sao) cho sân đó để chống SPAM.

## 3. LUỒNG NGHIỆP VỤ DÀNH CHO ADMIN / CHỦ SÂN

### 3.1. Quản lý Cơ Sở Vật Chất (Sân & Loại Sân)
- **Quản lý Loại sân:** Admin định nghĩa các loại sân (Sân 5, Sân 7).
- **Quản lý Sân bóng:**
  - Thêm, sửa, xóa sân bóng.
  - Đăng tải nhiều hình ảnh cho một sân (Lưu trữ ảnh trong thư mục `public/uploads/fields`).
  - Khóa sân (Đưa vào trạng thái Bảo trì) để khách không thể đặt.

### 3.2. Quản lý Đơn Đặt Sân (Booking Management)
Admin sẽ nhìn thấy luồng đơn hàng đổ về từ khách hàng.
- **Duyệt đơn:** Cập nhật trạng thái từ Pending (Chờ duyệt) -> Confirmed (Đã xác nhận).
- **Hủy đơn:** Nếu có sự cố trùng lịch hoặc sân hỏng đột xuất, Admin có quyền Hủy đơn và nhập Lý do.
- **Check-in:** Khi khách đến, Admin hoặc Staff cập nhật trạng thái đơn thành Hoàn thành.

### 3.3. Thống Kê & Báo Cáo Doanh Thu (Dashboard)
- **Biểu đồ doanh thu:** Thống kê doanh thu theo Ngày, Tuần, Tháng (Dựa trên những đơn hàng đã thanh toán thành công).
- **Thống kê sân:** Hiển thị Sân bóng nào được đặt nhiều nhất, tần suất lấp đầy sân.
- **Khách hàng thân thiết:** Phân tích dữ liệu khách hàng đặt nhiều nhất để lên chương trình Voucher tri ân.

## 4. BẢO MẬT VÀ TOÀN VẸN DỮ LIỆU (SYSTEM SECURITY)
- **Chữ ký điện tử VNPay:** Ngăn chặn tình trạng giả mạo (Fake URL) để hack trạng thái thanh toán. Dù user có cố ý đổi URL để báo thành công, hệ thống vẫn check mã hash_hmac để phát hiện giả mạo.
- **Middleware Phân Quyền:** Khách hàng không thể truy cập vào đường dẫn của Admin (`/admin/*`) vì có chốt chặn Middleware.
- **Chống trùng lặp (Concurrency Control):** Code được thiết kế để không cho phép 2 khách hàng đặt cùng 1 sân tại cùng 1 giờ.

## 5. NGHIỆP VỤ NÂNG CAO (ADVANCED BUSINESS LOGIC)
Ngoài các luồng cốt lõi về Đặt sân và Thanh toán, hệ thống còn bao gồm các Module nghiệp vụ phức tạp sau:

### 5.1. Quản lý Nhân sự & Chấm công (Staff & Attendance)
- **Lên lịch làm việc (Staff Schedule):** Admin phân ca làm việc (Sáng/Chiều/Tối) cho từng nhân viên.
- **Điểm danh (Attendance):** Nhân viên Check-in / Check-out ca làm việc. Hệ thống tự động ghi nhận thời gian làm việc thực tế, đánh dấu đi trễ, về sớm.
- **Tính lương tự động (Payroll):** Cuối tháng, hệ thống tự động tổng hợp số giờ làm, số ca, và tính toán tổng lương cho từng nhân viên (Base Salary + Bonus - Phạt). Sinh phiếu lương (Payroll Slip) gửi qua email cho nhân viên.

### 5.2. Chăm sóc khách hàng & Khuyến mãi (Voucher)
- **Mã giảm giá (Vouchers):** Admin tạo các mã giảm giá (Theo % hoặc số tiền cố định), thiết lập số lượng giới hạn và hạn sử dụng.
- **Áp dụng Voucher:** Khi Khách hàng đặt sân, họ có thể nhập mã Voucher. Hệ thống (Database) sẽ kiểm tra mã đó còn hạn không, còn lượt dùng không, có đáp ứng điều kiện tối thiểu không, sau đó mới trừ tiền tổng hóa đơn.

### 5.3. Hạng Thành Viên & Thành Tựu (Gamification)
- **Tích điểm thăng hạng:** Mỗi khi khách hàng đá xong 1 trận hoặc thanh toán thành công, hệ thống tự động cộng điểm kinh nghiệm (EXP).
- **Hệ thống cấp độ (Level):** Khi đủ điểm, khách sẽ lên hạng (Đồng, Bạc, Vàng, Kim Cương). Hạng càng cao có thể nhận được ưu đãi (Giảm % vĩnh viễn) khi đặt sân.
- **Thành tựu (Achievements):** Cấp các huy hiệu (Badge) như "Vua Đặt Sân", "Chuyên Gia Đá Đêm" để tạo sự thú vị và giữ chân người dùng (Retention).

### 5.4. Hệ thống Tin Tức (Blog / Posts)
- **Quản lý nội dung (CMS):** Admin đăng tải các bản tin giải đấu, luật thi đấu sân 5, thông báo bảo trì sân...
- **SEO & Tương tác:** Cập nhật tin tức giúp website tối ưu hóa công cụ tìm kiếm và tăng tương tác với cộng đồng thể thao.

> **TÓM LẠI:** Quy trình dòng chảy chuẩn của hệ thống là: Đăng nhập -> Tìm sân -> Chọn giờ -> Áp mã Voucher -> Đặt sân -> Thanh toán VNPay -> Trải nghiệm -> Tích điểm thăng hạng -> Đánh giá sân. Với đầy đủ Tất tần tật các nghiệp vụ (Từ Core đến Advanced) này, dự án hoàn toàn đạt quy mô của một hệ thống quản lý chuẩn SaaS thực tế!
