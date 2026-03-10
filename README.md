# Cleaning Service Management

## 1. Tổng quan
Đây là hệ thống quản lý dịch vụ dọn dẹp theo mô hình nhiều vai trò, xây dựng bằng PHP thuần theo kiến trúc MVC.

Hệ thống hỗ trợ:
- Khách hàng: đặt lịch, theo dõi tiến độ, nhắn tin, đánh giá dịch vụ.
- Worker: nhận việc, cập nhật tiến độ kèm ảnh, gửi báo cáo hoàn thành.
- Admin: quản lý người dùng, dịch vụ, đơn đặt, kiểm duyệt phản hồi và theo dõi thống kê.

## 2. Công nghệ và kiến trúc
- Backend: PHP 8.1+, PDO, Session.
- Cơ sở dữ liệu: MySQL/MariaDB.
- Frontend: HTML/CSS/JavaScript.
- Kiến trúc: MVC tự xây dựng (`Controllers`, `Models`, `Views`).
- Router nội bộ hỗ trợ route tĩnh và route động (`/bookings/{id}`).

## 3. Tính năng chính
### Khách hàng
- Đăng ký, đăng nhập, quản lý hồ sơ.
- Xem danh sách dịch vụ và chi tiết dịch vụ.
- Đặt lịch dịch vụ.
- Theo dõi trạng thái đơn và tiến độ worker.
- Nhắn tin theo từng đơn.
- Đánh giá sau khi hoàn thành, xem lại đánh giá đã gửi.

### Worker
- Xem danh sách việc được phân công.
- Chấp nhận việc, bắt đầu việc, cập nhật tiến độ theo bước.
- Upload ảnh tiến độ.
- Gửi báo cáo hoàn thành.

### Admin
- Quản lý người dùng và phê duyệt worker.
- Quản lý dịch vụ (thêm/sửa/xóa/bật tắt).
- Quản lý đơn đặt (gán worker, xác nhận, hủy).
- Xem chi tiết đơn đầy đủ: thông tin đơn, tiến độ, tin nhắn, báo cáo, đánh giá.
- Kiểm duyệt nội dung: liên hệ, đánh giá, khiếu nại, báo cáo worker.
- Theo dõi thống kê hệ thống.

## 4. Cấu trúc thư mục
```text
cleaning/
	app/
		Controllers/
		Core/
		Models/
		Views/
	config/
		app.php
	public/
		index.php
		assets/
		uploads/
	README.md
```

## 5. Yêu cầu môi trường
- PHP >= 8.1
- MySQL >= 5.7 hoặc MariaDB >= 10.3
- Apache/Nginx hoặc PHP built-in server

## 6. Cài đặt nhanh
### Bước 1: chuẩn bị mã nguồn
```bash
cd e:\laragon\www\cleaning
```

### Bước 2: tạo database
```sql
CREATE DATABASE cleaning_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'cleaning_user'@'localhost' IDENTIFIED BY 'cleaning_pass';
GRANT ALL PRIVILEGES ON cleaning_db.* TO 'cleaning_user'@'localhost';
FLUSH PRIVILEGES;
```

### Bước 3: import schema
```bash
mysql -u cleaning_user -p cleaning_db < database/schema.sql
```

### Bước 4: cấu hình ứng dụng
Chỉnh file `config/app.php`:
```php
<?php
return [
	'db' => [
		'host' => '127.0.0.1',
		'port' => 3306,
		'name' => 'cleaning_db',
		'user' => 'cleaning_user',
		'pass' => 'cleaning_pass',
		'charset' => 'utf8mb4',
	],
	'app' => [
		'base_url' => 'http://localhost/cleaning/public',
		'session_name' => 'CLEANINGSESSID',
	],
];
```

## 7. Chạy dự án
### Cách 1: PHP built-in server
```bash
cd public
php -S localhost:8000
```
Truy cập: `http://localhost:8000`

### Cách 2: Laragon/Apache
Truy cập: `http://localhost/cleaning/public`

## 8. Các route chính
### Public
- `GET /`
- `GET /services`
- `GET /service?id={id}`
- `GET /pricing`
- `GET|POST /contact`

### Auth
- `GET|POST /register`
- `GET|POST /login`
- `GET /logout`

### Customer
- `GET /book`
- `POST /book`
- `GET /bookings`
- `GET /bookings/{id}`
- `POST /bookings/{id}/message`
- `GET|POST /bookings/{id}/review`

### Worker
- `GET /worker/jobs`
- `GET /worker/jobs/{id}`
- `POST /worker/jobs/{id}/accept`
- `POST /worker/jobs/{id}/start`
- `POST /worker/jobs/{id}/progress`
- `GET|POST /worker/jobs/{id}/report`

### Admin
- `GET /admin/dashboard`
- `GET /admin/bookings`
- `GET /admin/bookings/{id}`
- `POST /admin/bookings/assign|confirm|cancel`
- `GET /admin/services`
- `POST /admin/services/create|update|toggle|delete`
- `GET /admin/moderation`
- `GET /admin/users`
- `POST /admin/users/approve|reject`

## 9. Cơ sở dữ liệu (bảng trọng tâm)
- `users`
- `services`
- `bookings`
- `booking_progress`
- `booking_progress_photos`
- `booking_messages`
- `booking_reports`
- `booking_reviews`
- `booking_payments`
- `contacts`

## 10. Bảo mật
- Mã hóa mật khẩu với `password_hash`.
- CSRF token cho form POST.
- Prepared statements qua PDO.
- Escape output khi render (`View::e`).
- Kiểm tra quyền theo vai trò tại controller.

## 11. Kiểm thử nhanh sau cài đặt
1. Đăng ký/đăng nhập theo từng vai trò.
2. Tạo đơn đặt từ khách hàng.
3. Gán worker và xác nhận đơn ở admin.
4. Worker cập nhật tiến độ và gửi báo cáo.
5. Khách hàng đánh giá hoàn thành.
6. Admin kiểm tra dữ liệu tại `Admin > Moderation` và `Admin > Booking Detail`.

## 12. Vận hành và xử lý lỗi
- File bootstrap: `public/index.php`
- Kết nối DB: `app/Core/DB.php`
- Route dispatcher: `app/Core/Router.php`
- Nếu lỗi trắng trang, bật debug tạm thời:
```php
error_reporting(E_ALL);
ini_set('display_errors', '1');
```

## 13. Đóng góp
Quy trình đề xuất:
1. Tạo branch mới theo chức năng.
2. Commit rõ phạm vi thay đổi.
3. Tạo Pull Request với mô tả ngắn gọn: mục tiêu, thay đổi chính, cách test.

## 14. License
Dự án phát hành theo giấy phép MIT (nếu có file `LICENSE` trong repository).
