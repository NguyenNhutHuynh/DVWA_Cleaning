# Admin Login - Trang Đăng Nhập Admin Riêng

## 🔐 Tổng Quan
Trang đăng nhập admin riêng biệt được bảo vệ bằng **secret key** để đảm bảo an toàn. Chỉ những người có key chính xác mới có thể truy cập trang đăng nhập này, và chỉ tài khoản admin mới có thể đăng nhập thành công.

## 🛠️ Cấu Hình

### 1. Secret Key (Key Bảo Vệ)
Secret key được lưu trong file cấu hình:
- **Vị trí:** `config/app.php`
- **Mặc định:** `admin-secret-key-2024`
- **Cách tùy chỉnh:**
  - Thay đổi trực tiếp trong `config/app.php` hoặc
  - Đặt biến môi trường `.env`: `ADMIN_LOGIN_KEY=your-custom-key`

**Ví dụ tùy chỉnh trong .env:**
```
ADMIN_LOGIN_KEY=my-super-secret-admin-key-2024
```

## 📍 Truy Cập Trang Đăng Nhập Admin

### Hai Trang Login Riêng Biệt

**Trang Login Thường** (`/login`)
- Dành cho: **Customer** và **Worker**
- Admin **không thể** đăng nhập ở trang này (sẽ bị từ chối)
- Không cần secret key
- Dùng cho khách hàng và công nhân đặt lịch/nhận việc

**Trang Admin Login** (`/admin/login`)  
- Dành cho: **Admin** chỉ
- Yêu cầu secret key trong URL
- Customer và Worker **không thể** đăng nhập ở trang này
- Trang riêng biệt với giao diện khác nhau (màu đỏ, badge bảo mật)
```
https://cleaning.id.vn/admin/login?key=your-secret-key
```

### Ví Dụ Cụ Thể (với key mặc định):
```
https://cleaning.id.vn/admin/login?key=admin-secret-key-2024
```

### Điều kiện truy cập:
- ✅ **Phải có URL parameter `key` chính xác** - Nếu không có key hoặc key sai, sẽ bị redirect về trang chủ
- ✅ **Chỉ tài khoản admin mới có thể đăng nhập** - Nếu tài khoản không phải admin, sẽ hiển thị thông báo lỗi
- ✅ **Session verification window** - Có cửa sổ 5 phút để hoàn tất đăng nhập sau khi xác minh key

## 🔒 Bảo Mật

1. **Secret Key Protection**
   - URL phải chứa key chính xác
   - Key được so sánh an toàn bằng `hash_equals()` để chống timing attack
   - Session verification: Chỉ có 5 phút để hoàn tất đăng nhập sau khi xác thực key

2. **Role Verification**
   - **Trang login thường** (`/login`): Chỉ customer và worker được phép đăng nhập
   - **Trang admin login** (`/admin/login`): Chỉ admin được phép đăng nhập
   - Nếu admin cố đăng nhập trên trang thường sẽ bị từ chối
   - Nếu customer/worker cố đăng nhập trên trang admin sẽ bị từ chối

3. **CSRF Protection**
   - Tất cả POST requests đều được bảo vệ bằng CSRF token
   - Session được xác thực trước khi cho phép đăng nhập

4. **No Direct Access**
   - Không thể truy cập `/admin/login` mà không có key trong URL lần đầu
   - Truy cập mà không có key sẽ tự động redirect về trang chủ
   - Sau khi key được xác thực, session sẽ giữ trạng thái này trong 5 phút

## 📋 Quy Trình Đăng Nhập

### Cho Admin (Trên trang /admin/login)
1. **Nhận URL đặc biệt:**
   ```
   https://cleaning.id.vn/admin/login?key=admin-secret-key-2024
   ```

2. **Hệ thống kiểm tra key:**
   - Nếu key đúng → Hiển thị form đăng nhập admin
   - Nếu key sai/không có → Redirect về trang chủ

3. **Nhập email và mật khẩu admin:**
   - Email phải là email của tài khoản admin
   - Mật khẩu phải chính xác

4. **Hệ thống xác thực:**
   - ✅ Kiểm tra email tồn tại
   - ✅ Kiểm tra mật khẩu chính xác
   - ✅ Kiểm tra **role phải là admin**
   - ✅ Kiểm tra trạng thái tài khoản (active/locked/deleted)

5. **Đăng nhập thành công:**
   - Redirect tới `/admin/dashboard`

### Cho Customer/Worker (Trên trang /login)
1. **Truy cập trang login thường:** `/login`

2. **Nhập email và mật khẩu:**
   - Email phải là email của tài khoản customer hoặc worker
   - Mật khẩu phải chính xác

3. **Hệ thống xác thực:**
   - ✅ Kiểm tra email tồn tại
   - ✅ Kiểm tra mật khẩu chính xác
   - ✅ Kiểm tra **role KHÔNG phải admin** (chặn admin)
   - ✅ Kiểm tra trạng thái tài khoản

4. **Đăng nhập thành công:**
   - Redirect tới dashboard phù hợp (customer hoặc worker)

## 🚀 Sử Dụng Thực Tế

### Tạo link đăng nhập cho admin:
```php
<?php
$config = require 'config/app.php';
$adminKey = $config['admin']['login_key'];
$adminLoginUrl = '/admin/login?key=' . urlencode($adminKey);
// Chia sẻ URL này cho admin
?>
```

### Thay đổi key bảo mật:
Chỉnh sửa file `config/app.php`:
```php
'admin' => [
    'login_key' => 'new-secret-key-2024',
],
```

**HOẶC** tạo `.env` file:
```
ADMIN_LOGIN_KEY=new-secret-key-2024
```

## ⚠️ Lưu Ý Quan Trọng

1. **Giữ key bí mật** - Không chia sẻ key công khai
2. **Thay đổi key định kỳ** - Nên thay đổi key theo định kỳ để tăng bảo mật
3. **Sử dụng HTTPS** - Trong production, luôn sử dụng HTTPS để bảo vệ URL parameter
4. **Chỉ admin mới được** - Trang đăng nhập này chỉ dành cho tài khoản admin
5. **Không thể reset key qua UI** - Key chỉ có thể thay đổi thông qua code

## 🔍 Kiểm Tra Lỗi

| Lỗi | Nguyên nhân | Giải pháp |
|-----|-----------|---------|
| Redirect về trang chủ (khi vào /admin/login) | Không có key hoặc key sai | Kiểm tra URL parameter, đảm bảo key chính xác |
| "Tài khoản admin không thể đăng nhập..." (/login) | Cố đăng nhập admin trên trang login thường | Sử dụng trang `/admin/login?key=...` để đăng nhập admin |
| "Tài khoản này không có quyền truy cập..." (/admin/login) | Cố đăng nhập customer/worker trên trang admin | Chỉ admin có thể đăng nhập ở đây, customer/worker dùng `/login` |
| "Email hoặc mật khẩu không chính xác" | Email không tồn tại hoặc mật khẩu sai | Kiểm tra thông tin đăng nhập |
| "Tài khoản này không thể đăng nhập" | Tài khoản bị khóa hoặc bị xóa | Liên hệ admin để mở khóa |

## 📚 File Liên Quan

- **Controller:** `app/Controllers/AuthController.php` (Methods: `showAdminLogin()`, `adminLogin()`)
- **View:** `app/Views/auth/admin-login.php`
- **Config:** `config/app.php` (Key: `admin.login_key`)
- **Routes:** `public/index.php` (Routes: `/admin/login` GET & POST)

## ✨ Tính Năng

- ✅ **Hai trang login riên biệt** - Admin và Customer/Worker không chia sẻ trang login
- ✅ **Admin bị chặn khỏi login thường** - Tìm admin ở trang `/login` sẽ bị từ chối
- ✅ **Customer/Worker bị chặn khỏi admin login** - Chỉ admin được phép trên `/admin/login`
- ✅ Bảo vệ bằng secret key không thể đoán được
- ✅ Giao diện riên biệt với màu đỏ (khác với login thường)
- ✅ Xác thực role admin bắt buộc
- ✅ CSRF protection
- ✅ Session verification window (5 phút)
- ✅ Không ảnh hưởng tới chức năng đăng nhập bình thường

## 💡 Tại sao phải tách riêng?

1. **Bảo mật cao hơn:** Admin không thể bị tấn công qua trang login bình thường
2. **Phòng chống vô tình:** Admin không vô tình đăng nhập trên trang khác
3. **Secret key:** Chỉ người biết key mới có thể thử đăng nhập admin
4. **Giao diện rõ ràng:** Admin và user thường thấy các trang login khác nhau
5. **Kiểm soát tốt hơn:** Mỗi loại user có trải nghiệm login riêng biệt

---

**Hướng dẫn tạo bởi GitHub Copilot** - Tham khảo thêm tại `config/app.php` và `app/Controllers/AuthController.php`
