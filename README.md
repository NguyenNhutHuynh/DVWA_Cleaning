# 🧹 Hệ Thống Quản Lý Dịch Vụ Dọn Dẹp Nhà Cửa

## 📋 Mục Lục
- [Giới Thiệu](#giới-thiệu)
- [Tính Năng](#tính-năng)
- [Công Nghệ Sử Dụng](#công-nghệ-sử-dụng)
- [Cấu Trúc Dự Án](#cấu-trúc-dự-án)
- [Cài Đặt](#cài-đặt)
- [Cấu Hình](#cấu-hình)
- [Sử Dụng](#sử-dụng)
- [Phân Quyền](#phân-quyền)
- [API Routes](#api-routes)
- [Đóng Góp](#đóng-góp)

## 🎯 Giới Thiệu

Hệ thống quản lý dịch vụ dọn dẹp nhà cửa là một ứng dụng web được xây dựng bằng PHP thuần (không sử dụng framework), cho phép:
- Khách hàng đặt và quản lý các dịch vụ dọn dẹp
- Công nhân nhận việc và cập nhật tiến độ công việc
- Quản trị viên quản lý toàn bộ hệ thống

## ✨ Tính Năng

### 🔐 Xác Thực & Bảo Mật
- Đăng ký tài khoản (Khách hàng/Công nhân)
- Đăng nhập/Đăng xuất
- Bảo vệ CSRF token
- Mã hóa mật khẩu
- Quản lý phiên làm việc

### 👥 Dành Cho Khách Hàng
- Xem danh sách dịch vụ và bảng giá
- Đặt lịch dịch vụ với các thông tin chi tiết
- Theo dõi trạng thái đơn đặt hàng
- Gửi tin nhắn cho công nhân
- Đánh giá dịch vụ sau khi hoàn thành
- Quản lý thông tin cá nhân
- Xem lịch sử đặt dịch vụ
- Hủy đơn đặt hàng

### 👷 Dành Cho Công Nhân
- Xem danh sách công việc khả dụng
- Chấp nhận công việc
- Cập nhật tiến độ làm việc với ảnh minh chứng
- Gửi báo cáo hoàn thành
- Gửi tin nhắn cho khách hàng
- Xem lịch trình công việc
- Dashboard thống kê cá nhân

### 🔧 Dành Cho Quản Trị Viên
- Quản lý người dùng (Khách hàng/Công nhân)
- Phê duyệt/Từ chối đăng ký công nhân
- Khóa/Mở khóa tài khoản
- Quản lý dịch vụ (Thêm/Sửa/Xóa)
- Quản lý đơn đặt hàng
- Xác nhận/Hủy đơn đặt hàng
- Xem thống kê hệ thống
- Dashboard tổng quan

### 📊 Các Module Chính
- **Booking**: Quản lý đơn đặt dịch vụ
- **BookingMessage**: Giao tiếp giữa khách hàng và công nhân
- **BookingPayment**: Quản lý thanh toán
- **BookingProgress**: Theo dõi tiến độ công việc
- **BookingReport**: Báo cáo hoàn thành công việc
- **BookingReview**: Đánh giá dịch vụ
- **Service**: Quản lý các loại dịch vụ
- **Contact**: Quản lý liên hệ
- **User**: Quản lý người dùng

## 🛠️ Công Nghệ Sử Dụng

### Backend
- **PHP 8.1+**: Ngôn ngữ lập trình chính
- **MySQL/MariaDB**: Cơ sở dữ liệu
- **PDO**: Kết nối cơ sở dữ liệu
- **Session**: Quản lý phiên đăng nhập

### Frontend
- **HTML5/CSS3**: Giao diện người dùng
- **JavaScript**: Tương tác động
- **Responsive Design**: Tương thích đa thiết bị

### Architecture Pattern
- **MVC (Model-View-Controller)**: Kiến trúc phân tách rõ ràng
- **Router**: Định tuyến URL thân thiện
- **Autoloader**: Tự động tải class theo namespace

## 📁 Cấu Trúc Dự Án

```
cleaning/
├── app/
│   ├── Controllers/          # Các controller xử lý logic
│   │   ├── AccountController.php    # Quản lý tài khoản cá nhân
│   │   ├── AdminController.php      # Quản lý admin
│   │   ├── AuthController.php       # Xác thực người dùng
│   │   ├── BookingController.php    # Quản lý đặt lịch
│   │   ├── ContactController.php    # Xử lý liên hệ
│   │   ├── CustomerController.php   # Chức năng khách hàng
│   │   ├── PricingController.php    # Bảng giá
│   │   ├── ServicesController.php   # Quản lý dịch vụ
│   │   ├── UserController.php       # Quản lý người dùng
│   │   └── WorkerController.php     # Chức năng công nhân
│   ├── Core/                 # Các class cốt lõi
│   │   ├── Auth.php         # Xác thực và phân quyền
│   │   ├── Csrf.php         # Bảo vệ CSRF
│   │   ├── DB.php           # Kết nối database
│   │   ├── Router.php       # Định tuyến URL
│   │   └── View.php         # Render view
│   ├── Models/              # Các model dữ liệu
│   │   ├── Booking.php           # Model đơn đặt hàng
│   │   ├── BookingMessage.php    # Tin nhắn trong đơn
│   │   ├── BookingPayment.php    # Thanh toán
│   │   ├── BookingProgress.php   # Tiến độ công việc
│   │   ├── BookingReport.php     # Báo cáo hoàn thành
│   │   ├── BookingReview.php     # Đánh giá dịch vụ
│   │   ├── Contact.php           # Liên hệ
│   │   ├── Service.php           # Dịch vụ
│   │   └── User.php              # Người dùng
│   └── Views/               # Giao diện template
│       ├── layout.php       # Layout chính
│       ├── home.php         # Trang chủ
│       ├── auth/            # Trang xác thực
│       ├── customer/        # Trang khách hàng
│       ├── worker/          # Trang công nhân
│       ├── admin/           # Trang quản trị
│       └── account/         # Trang tài khoản
├── config/
│   └── app.php              # Cấu hình ứng dụng
├── public/
│   ├── index.php            # File khởi động
│   ├── assets/              # Tài nguyên tĩnh
│   │   ├── css/            # File CSS
│   │   └── img/            # Hình ảnh
│   └── uploads/             # File upload
│       ├── avatars/        # Ảnh đại diện
│       └── progress/       # Ảnh tiến độ
└── README.md               # File này

```

## 🚀 Cài Đặt

### Yêu Cầu Hệ Thống
- PHP >= 8.1
- MySQL >= 5.7 hoặc MariaDB >= 10.3
- Web Server (Apache/Nginx)
- Composer (không bắt buộc, dự án không sử dụng dependencies)

### Bước 1: Clone/Download Dự Án
```bash
# Clone repository
git clone [repository-url]
cd cleaning
```

### Bước 2: Tạo Cơ Sở Dữ Liệu
```sql
CREATE DATABASE cleaning_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'cleaning_user'@'localhost' IDENTIFIED BY 'cleaning_pass';
GRANT ALL PRIVILEGES ON cleaning_db.* TO 'cleaning_user'@'localhost';
FLUSH PRIVILEGES;
```

### Bước 3: Import Database Schema
```bash
# Import file SQL schema (nếu có)
mysql -u cleaning_user -p cleaning_db < database/schema.sql
```

### Bước 4: Cấu Hình Permissions
```bash
# Trên Linux/Mac
chmod -R 755 public/
chmod -R 777 public/uploads/

# Đảm bảo web server có quyền ghi vào thư mục uploads
```

### Bước 5: Cấu Hình Virtual Host (Optional)

#### Apache (.htaccess đã được cấu hình sẵn)
```apache
<VirtualHost *:80>
    ServerName cleaning.test
    DocumentRoot /path/to/cleaning/public
    
    <Directory /path/to/cleaning/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

#### Nginx
```nginx
server {
    listen 80;
    server_name cleaning.test;
    root /path/to/cleaning/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

## ⚙️ Cấu Hình

### File config/app.php

```php
<?php
return [
    'db' => [
        'host' => '127.0.0.1',        // Host database
        'port' => 3306,                // Port database
        'name' => 'cleaning_db',       // Tên database
        'user' => 'cleaning_user',     // Username database
        'pass' => 'cleaning_pass',     // Password database
        'charset' => 'utf8mb4',        // Charset
    ],
    'app' => [
        'base_url' => 'http://cleaning.test',  // URL gốc của ứng dụng
        'session_name' => 'CLEANINGSESSID',    // Tên session
    ],
];
```

### Cấu Hình .env (Nếu sử dụng)
Bạn có thể tạo file `.env` để quản lý cấu hình một cách linh hoạt hơn.

## 📖 Sử Dụng

### Khởi Động Development Server
```bash
# Sử dụng PHP built-in server
cd public
php -S localhost:8000

# Hoặc sử dụng Laragon/XAMPP/WAMP
# Truy cập: http://localhost/cleaning/public
```

### Tài Khoản Mặc Định (Sau khi seed database)
```
Admin:
- Email: admin@cleaning.test
- Password: admin123

Công nhân:
- Email: worker@cleaning.test
- Password: worker123

Khách hàng:
- Email: customer@cleaning.test
- Password: customer123
```

## 👮 Phân Quyền

### Các Vai Trò (Roles)
1. **admin**: Quản trị viên - Toàn quyền quản lý hệ thống
2. **worker**: Công nhân - Nhận và thực hiện công việc
3. **customer**: Khách hàng - Đặt và theo dõi dịch vụ

### Các Trạng Thái (Status)
- **pending**: Đang chờ phê duyệt (cho công nhân mới)
- **approved**: Đã được phê duyệt
- **rejected**: Bị từ chối
- **locked**: Tài khoản bị khóa

### Trạng Thái Đơn Đặt Hàng
- **pending**: Chờ xác nhận
- **confirmed**: Đã xác nhận
- **accepted**: Đã được công nhân chấp nhận
- **in_progress**: Đang thực hiện
- **completed**: Hoàn thành
- **cancelled**: Đã hủy

## 🛣️ API Routes

### Public Routes
```
GET  /                    - Trang chủ
GET  /services           - Danh sách dịch vụ
GET  /service            - Chi tiết dịch vụ (?id=123)
GET  /pricing            - Bảng giá
GET  /contact            - Liên hệ
POST /contact            - Gửi liên hệ
```

### Authentication Routes
```
GET  /register           - Form đăng ký
POST /register           - Xử lý đăng ký
GET  /login              - Form đăng nhập
POST /login              - Xử lý đăng nhập
GET  /logout             - Đăng xuất
```

### Customer Routes
```
GET  /bookings           - Danh sách đơn đặt
GET  /book               - Form đặt dịch vụ
POST /book               - Tạo đơn đặt mới
GET  /booking/{id}       - Chi tiết đơn đặt
POST /booking/{id}/cancel - Hủy đơn đặt
POST /booking/{id}/message - Gửi tin nhắn
GET  /booking/{id}/review  - Form đánh giá
POST /booking/{id}/review  - Gửi đánh giá
```

### Worker Routes
```
GET  /worker/dashboard    - Dashboard công nhân
GET  /worker/jobs         - Danh sách công việc
POST /worker/job/{id}/accept - Chấp nhận công việc
GET  /worker/job/{id}     - Chi tiết công việc
POST /worker/job/{id}/start - Bắt đầu công việc
POST /worker/job/{id}/progress - Cập nhật tiến độ
GET  /worker/job/{id}/report - Form báo cáo
POST /worker/job/{id}/report - Gửi báo cáo hoàn thành
GET  /worker/progress     - Danh sách tiến độ
GET  /worker/schedule     - Lịch trình công việc
```

### Admin Routes
```
GET  /admin/dashboard     - Dashboard admin
GET  /admin/users         - Quản lý người dùng
GET  /admin/user/{id}     - Chi tiết người dùng
POST /admin/user/{id}     - Cập nhật người dùng
POST /admin/user/{id}/lock - Khóa tài khoản
POST /admin/user/{id}/unlock - Mở khóa tài khoản
POST /admin/user/{id}/delete - Xóa người dùng
GET  /admin/moderation    - Phê duyệt công nhân
POST /admin/worker/{id}/approve - Phê duyệt công nhân
POST /admin/worker/{id}/reject - Từ chối công nhân
GET  /admin/services      - Quản lý dịch vụ
POST /admin/service/create - Tạo dịch vụ mới
POST /admin/service/{id}/update - Cập nhật dịch vụ
POST /admin/service/{id}/toggle - Bật/Tắt dịch vụ
POST /admin/service/{id}/delete - Xóa dịch vụ
GET  /admin/bookings      - Quản lý đơn đặt hàng
POST /admin/booking/{id}/confirm - Xác nhận đơn đặt
POST /admin/booking/{id}/cancel - Hủy đơn đặt
GET  /admin/stats         - Thống kê hệ thống
```

### Account Routes
```
GET  /account/profile     - Trang cá nhân
GET  /account/edit        - Chỉnh sửa thông tin
POST /account/update      - Cập nhật thông tin
GET  /account/change-password - Đổi mật khẩu
POST /account/change-password - Lưu mật khẩu mới
```

## 🗄️ Database Schema

### Bảng users
```sql
- id: INT PRIMARY KEY AUTO_INCREMENT
- name: VARCHAR(255)
- email: VARCHAR(255) UNIQUE
- password: VARCHAR(255)
- role: ENUM('customer', 'worker', 'admin')
- status: ENUM('pending', 'approved', 'rejected', 'locked')
- phone: VARCHAR(20)
- address: TEXT
- avatar: VARCHAR(255)
- created_at: TIMESTAMP
- updated_at: TIMESTAMP
```

### Bảng services
```sql
- id: INT PRIMARY KEY AUTO_INCREMENT
- name: VARCHAR(255)
- description: TEXT
- price: DECIMAL(10,2)
- unit: VARCHAR(50)
- image: VARCHAR(255)
- is_active: BOOLEAN
- created_at: TIMESTAMP
- updated_at: TIMESTAMP
```

### Bảng bookings
```sql
- id: INT PRIMARY KEY AUTO_INCREMENT
- user_id: INT (FK -> users.id)
- service_id: INT (FK -> services.id)
- worker_id: INT (FK -> users.id, nullable)
- date: DATE
- time: TIME
- location: TEXT
- description: TEXT
- quantity: DECIMAL(10,2)
- measure_unit: VARCHAR(50)
- unit_price: DECIMAL(10,2)
- line_total: DECIMAL(10,2)
- status: ENUM('pending', 'confirmed', 'accepted', 'in_progress', 'completed', 'cancelled')
- created_at: TIMESTAMP
- updated_at: TIMESTAMP
```

### Các Bảng Khác
- **booking_messages**: Tin nhắn trong đơn đặt
- **booking_payments**: Thanh toán
- **booking_progress**: Tiến độ công việc
- **booking_reports**: Báo cáo hoàn thành
- **booking_reviews**: Đánh giá dịch vụ
- **contacts**: Liên hệ

## 🔒 Bảo Mật

### Các Biện Pháp Bảo Mật
- ✅ Mã hóa mật khẩu bằng `password_hash()`
- ✅ CSRF Token protection
- ✅ Prepared statements (PDO) chống SQL Injection
- ✅ XSS protection với `htmlspecialchars()`
- ✅ Session management
- ✅ Input validation
- ✅ File upload validation
- ✅ Role-based access control

### Best Practices
- Luôn validate và sanitize input từ người dùng
- Sử dụng HTTPS trong môi trường production
- Giới hạn kích thước file upload
- Kiểm tra MIME type khi upload file
- Không lưu thông tin nhạy cảm trong session

## 🧪 Testing

```bash
# Chạy PHP built-in server để test
cd public
php -S localhost:8000

# Test các chức năng chính:
# 1. Đăng ký/Đăng nhập
# 2. Đặt dịch vụ
# 3. Quản lý đơn hàng
# 4. Admin dashboard
```

## 📝 Development Notes

### Thêm Route Mới
File: `public/index.php`
```php
$router->get('/new-route', [ControllerClass::class, 'method']);
$router->post('/new-route', [ControllerClass::class, 'method']);
```

### Tạo Controller Mới
File: `app/Controllers/NewController.php`
```php
<?php
namespace App\Controllers;

final class NewController
{
    public function index(): void
    {
        // Logic here
    }
}
```

### Tạo Model Mới
File: `app/Models/NewModel.php`
```php
<?php
namespace App\Models;

use App\Core\DB;

final class NewModel
{
    public static function getAll(): array
    {
        $stmt = DB::pdo()->query("SELECT * FROM table_name");
        return $stmt->fetchAll();
    }
}
```

### Tạo View Mới
File: `app/Views/new-view.php`
```php
<?php
// View variables: $data
?>
<h1>New View</h1>
```

## 🐛 Debug & Troubleshooting

### Bật Error Reporting
File: `public/index.php`
```php
error_reporting(E_ALL);
ini_set('display_errors', '1');
```

### Kiểm Tra Database Connection
File: `public/db_test.php`
```php
<?php
require __DIR__ . '/../config/app.php';
// Test connection
```

### Log Errors
```php
error_log("Debug message: " . print_r($data, true));
```

## 📚 Tài Liệu Tham Khảo
- [PHP Documentation](https://www.php.net/docs.php)
- [MySQL Documentation](https://dev.mysql.com/doc/)
- [PDO Documentation](https://www.php.net/manual/en/book.pdo.php)

## 🤝 Đóng Góp

Mọi đóng góp đều được chào đón! Vui lòng:
1. Fork dự án
2. Tạo branch mới (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Mở Pull Request

## 📄 License

Dự án này được phát hành dưới giấy phép [MIT License](LICENSE).

## 👨‍💻 Tác Giả

- **Nhut Huynh Nguyen** - (https://github.com/NguyenNhutHuynh)

## 📞 Liên Hệ

Nếu có bất kỳ câu hỏi nào, vui lòng liên hệ:
- Email: nhuthuynhforwork@gmail.com

## 🎉 Lời Cảm Ơn

Cảm ơn bạn đã sử dụng hệ thống quản lý dịch vụ dọn dẹp nhà cửa!

---

**Made with ❤️ in Vietnam**