# TÀI LIỆU NGHIỆP VỤ HỆ THỐNG ĐẶT SÂN BÓNG

Tài liệu này mô tả các luồng nghiệp vụ cốt lõi của hệ thống, đặc biệt tập trung vào quy trình đặt sân và thanh toán.

## 1. Quy trình đặt sân và xác nhận (Dành cho khách hàng)

- **Bước 1 (Chọn sân & thời gian):** Khách hàng truy cập trang chi tiết sân, chọn ngày và khung giờ muốn thuê.
- **Bước 2 (Thanh toán & Đặt chỗ):** Khách hàng chọn phương thức thanh toán:
  - **Thanh toán Tiền mặt (Tại sân):** Hệ thống tạo đơn hàng với trạng thái `pending` (Chờ xử lý). Màn hình hiển thị thông báo thành công kèm theo **Mã đơn đặt sân** (Ví dụ: `BK8A9F2`).
  - **Thanh toán VNPay:** Hệ thống chuyển hướng sang cổng thanh toán. Nếu thanh toán thành công, hệ thống tự động đổi trạng thái đơn thành `confirmed` (Đã xác nhận) hoặc `paid` (Đã thanh toán) thông qua Webhook.
- **Bước 3 (Đến sân):** Khách hàng đến sân theo đúng khung giờ đã đặt và cung cấp **Mã đơn đặt sân** (hoặc Số điện thoại) cho nhân viên quản lý tại quầy.

## 2. Quy trình kiểm duyệt và thu tiền (Dành cho Nhân viên / Admin)

- **Kiểm tra thông tin:** Khi khách đến sân, nhân viên mở hệ thống Quản trị (Admin Panel), tìm kiếm đơn hàng dựa trên Mã đơn hoặc Số điện thoại của khách.
- **Xác nhận thanh toán:**
  - **Đối với đơn Tiền mặt (Trạng thái `pending`):** Nhân viên kiểm tra thông tin, thu tiền mặt từ khách. Sau khi nhận đủ tiền, nhân viên nhấn nút "Xác nhận / Đã thanh toán" trên hệ thống để chuyển trạng thái đơn sang `confirmed` (Đã xác nhận) hoặc `completed` (Hoàn thành) và giao sân cho khách.
  - **Đối với đơn VNPay (Trạng thái `confirmed/paid`):** Nhân viên chỉ cần đối chiếu thông tin và giao sân cho khách (không cần thu thêm tiền).

## 3. Quản lý trạng thái và đồng bộ thanh toán

- **Đơn VNPay:** Khi khách thanh toán qua ngân hàng, cổng thanh toán VNPay sẽ tự động gọi API (Webhook) về hệ thống. Hệ thống sẽ **tự động** tạo một bản ghi `Payment` và cập nhật trạng thái đơn mà không cần sự can thiệp của nhân viên.
- **Đơn Tiền mặt:** Hệ thống lưu phương thức thanh toán là `cash`. Trong trang quản trị, đơn này sẽ hiển thị rõ là "Thanh toán tiền mặt" để nhân viên biết đây là đơn chưa thu tiền và cần phải thu tiền trực tiếp tại sân.

## 4. Các ràng buộc hệ thống (Chống bùng sân)

Để hạn chế tình trạng khách đặt sân bằng tiền mặt nhưng không đến, hệ thống cần áp dụng một trong các ràng buộc sau:
1. **Bắt buộc nhập Số điện thoại:** Khi chọn thanh toán tiền mặt, khách hàng bắt buộc phải cung cấp số điện thoại chính xác để nhân viên có thể gọi điện xác nhận trước giờ thi đấu.
2. **Bắt buộc Đăng nhập:** Người dùng phải tạo tài khoản (xác thực qua Email/SĐT) mới được phép đặt sân. Các tài khoản có lịch sử "bùng sân" sẽ bị đưa vào danh sách đen (Blacklist) và bị khóa chức năng đặt sân.
3. **Chính sách đặt cọc (Tùy chọn nâng cao):** Hệ thống có thể yêu cầu thanh toán online một khoản cọc (VD: 30%) qua VNPay, số tiền còn lại (70%) sẽ được thanh toán bằng tiền mặt tại sân.

*(Tài liệu này sẽ được cập nhật liên tục trong quá trình phát triển và hoàn thiện hệ thống)*
