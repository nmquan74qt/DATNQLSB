<p align="center"><a href="#" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Pitch Admin Logo"></a></p>

# Hệ thống Quản lý Sân Bóng (Pitch Admin)

Đây là hệ thống quản lý sân bóng mini (Pitch Admin) dành cho Đồ án Tốt nghiệp (DATN), được phát triển bằng Laravel 13. Hệ thống cho phép quản trị viên (Admin) quản lý sân bóng, lịch đặt sân, hóa đơn, khách hàng, nhân sự và cung cấp giao diện đặt sân trực tuyến cho khách hàng.

## 🚀 Tính năng nổi bật

### Khách hàng (User)
- Xem danh sách và chi tiết sân bóng (kèm đánh giá, nhiều ảnh minh họa).
- Đặt sân trực tuyến theo các khung giờ (có kiểm tra trùng lặp).
- Thanh toán trực tuyến (tích hợp mock VNPay/MoMo).
- Đăng nhập/Đăng ký qua form hoặc qua Google (Socialite).
- Xem lịch sử đặt sân, viết đánh giá (Review).

### Quản trị viên (Admin)
- **Quản lý Sân Bóng**: Thêm/sửa/xóa sân, hỗ trợ upload nhiều ảnh, cấu hình loại sân, khung giờ, bảo trì.
- **Quản lý Đặt Sân & Hóa Đơn**: Duyệt lịch, tạo lịch trực tiếp (đặt hộ), thay đổi trạng thái thanh toán, áp dụng mã giảm giá (Voucher) và phụ thu.
- **Thống kê Doanh Thu**: Bảng điều khiển (Dashboard) xem biểu đồ doanh thu, tỷ lệ lấp đầy sân theo ngày/tháng/năm.
- **Quản lý Nhân Sự**: Thêm, sửa, xóa tài khoản Admin/Nhân viên, điểm danh (Attendance) và tính lương (Payroll).
- **Tính năng mở rộng**: Quản lý Voucher, Tin tức (Blog), Thông báo (Notification).
- **Hệ thống**: Chức năng tự động Backup Database (hỗ trợ SQLite/MySQL).

## 🛠 Tech Stack
- **Framework**: Laravel 13 (PHP ^8.3|^8.4)
- **Frontend**: Blade Template, Tailwind CSS, Alpine.js, FontAwesome
- **Database**: SQLite (Mặc định) hoặc MySQL
- **Tooling**: Vite (cho biên dịch tài sản CSS/JS)

## 📋 Hướng dẫn cài đặt

1. **Clone repository:**
   ```bash
   git clone https://github.com/nmquan74qt/DATNQLSB.git
   cd DATNQLSB
   ```

2. **Cài đặt các gói phụ thuộc PHP (Composer):**
   ```bash
   composer install
   ```

3. **Cài đặt các gói phụ thuộc Frontend (NPM):**
   ```bash
   npm install
   npm run build
   ```

4. **Cấu hình môi trường (.env):**
   Copy file `.env.example` thành `.env`
   ```bash
   cp .env.example .env
   ```
   *Lưu ý: Mặc định dự án sử dụng `DB_CONNECTION=sqlite`. Đảm bảo extension `pdo_sqlite` trong `php.ini` đã được bật.*

5. **Tạo application key:**
   ```bash
   php artisan key:generate
   ```

6. **Chạy Migration và Seeder:**
   *(Lệnh này sẽ tạo cấu trúc bảng và chèn dữ liệu mẫu, bao gồm cả tài khoản admin)*
   ```bash
   php artisan migrate:fresh --seed
   ```

7. **Link Storage (để hiện thị ảnh upload):**
   ```bash
   php artisan storage:link
   ```

8. **Chạy server phát triển:**
   ```bash
   php artisan serve
   ```
   Truy cập vào `http://localhost:8000`.

## 👤 Tài khoản mặc định (từ Seeder)
- **Admin**: `admin@pitch.com` / `password`
- **Khách hàng**: `customer@pitch.com` / `password`

## 🧪 Chạy Unit Test
Hệ thống đi kèm một số bài kiểm tra (Feature Test) để đảm bảo độ tin cậy. Bạn có thể chạy:
```bash
php artisan test
```

## 📝 Giấy phép (License)
Dự án phục vụ mục đích đồ án học tập, áp dụng theo giấy phép [MIT license](https://opensource.org/licenses/MIT).
