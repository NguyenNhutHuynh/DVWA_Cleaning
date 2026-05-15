# 🔧 Hướng Dẫn Fix Database Migration (Booking → Booking + Booking_Details)

## 📋 Tóm Tắt Vấn Đề

Code đã được update để sử dụng schema mới:
- **bookings table**: Chỉ chứa metadata (id, user_id, created_at, updated_at)
- **booking_details table**: Chứa chi tiết công việc (service, date, time, price, worker, status, etc.)

Nhưng database cũ có thể:
1. Chưa có bảng `booking_details`
2. Dữ liệu trong `bookings` chưa được migrate sang `booking_details`
3. `payment_transactions` có thể không có `booking_id` (dữ liệu cũ)

## ✅ Cách Fix

### Option 1: Chạy Auto Setup Script (Khuyến Nghị)

**Bước 1:** Truy cập URL này trong browser:
```
http://localhost:8000/database_setup.php
```

Script này sẽ tự động:
- ✅ Tạo bảng `booking_details` (nếu chưa có)
- ✅ Migrate dữ liệu từ `bookings` cũ sang `booking_details`
- ✅ Kiểm tra và báo cáo trạng thái migration
- ✅ Cho phép bạn xem chi tiết lỗi (nếu có)

**Output sẽ hiển thị:**
```
✅ Tạo bảng booking_details thành công!
✅ Migrate 5 records từ bookings sang booking_details
✅ Tất cả bookings đều có chi tiết
✅ Database setup hoàn tất!
```

### Option 2: Chạy Manual SQL Commands

Nếu muốn chạy thủ công:

**Bước 1:** Tạo bảng booking_details
```sql
mysql> USE cleaning_db;
mysql> [copy-paste từ database_migration_booking_details.sql]
```

**Bước 2:** Migrate dữ liệu
```sql
INSERT INTO booking_details (
    booking_id, service_id, work_date, work_time, location,
    quantity, measure_unit, unit_price, line_total,
    detail_status, note, assigned_worker_id, assigned_at,
    created_at, updated_at
)
SELECT
    b.id, b.service_id, b.date, b.time, b.location,
    COALESCE(b.quantity, 1), COALESCE(b.measure_unit, ''),
    COALESCE(b.unit_price, 0), COALESCE(b.line_total, 0),
    COALESCE(b.status, 'pending'), b.description,
    b.assigned_worker_id, b.assigned_at,
    b.created_at, b.updated_at
FROM bookings b
WHERE NOT EXISTS (SELECT 1 FROM booking_details bd WHERE bd.booking_id = b.id);
```

**Bước 3 (Tùy chọn):** Backup và xóa cột cũ
```sql
-- Backup
CREATE TABLE bookings_backup AS SELECT * FROM bookings;

-- Xóa cột cũ (sau khi verify migration)
ALTER TABLE bookings 
    DROP COLUMN IF EXISTS service_id,
    DROP COLUMN IF EXISTS date,
    DROP COLUMN IF EXISTS time,
    DROP COLUMN IF EXISTS location,
    DROP COLUMN IF EXISTS quantity,
    DROP COLUMN IF EXISTS measure_unit,
    DROP COLUMN IF EXISTS unit_price,
    DROP COLUMN IF EXISTS line_total,
    DROP COLUMN IF EXISTS status,
    DROP COLUMN IF EXISTS description,
    DROP COLUMN IF EXISTS assigned_worker_id,
    DROP COLUMN IF EXISTS assigned_at;
```

## 🔍 Xác Minh Migration

Sau khi chạy setup, kiểm tra:

```sql
-- Check booking_details table exists
DESCRIBE booking_details;

-- Check data migrated
SELECT COUNT(*) FROM booking_details; -- Nên = COUNT(*) FROM bookings

-- Check orphaned bookings (không có detail)
SELECT b.id FROM bookings b 
LEFT JOIN booking_details bd ON bd.booking_id = b.id 
WHERE bd.id IS NULL;

-- Check payment_transactions
SELECT COUNT(*) FROM payment_transactions;
SELECT COUNT(*) FROM payment_transactions WHERE status = 'paid';
```

## 🐛 Xử Lý Lỗi

### "Không tìm thấy đơn đặt" khi theo dõi/chi tiết
**Nguyên nhân:** Booking không có record trong booking_details

**Fix:**
1. Chạy auto setup script: `http://localhost:8000/database_setup.php`
2. Hoặc migrate dữ liệu manually (xem Option 2 trên)

### "Khách hàng chưa thanh toán" khi gán worker
**Nguyên nhân:** Payment_transactions không có booking_id

**Fix:**
Code đã được update để xử lý fallback:
- Nếu không tìm thấy payment bằng booking_id, sẽ tìm qua user_id
- Lỗi này sẽ fix tự động sau khi migrate data

### Payment_transactions.booking_id = NULL/0
**Fix:**
```sql
-- Cập nhật booking_id cho payment cũ (nếu có)
UPDATE payment_transactions pt
SET pt.booking_id = (
    SELECT b.id FROM bookings b 
    WHERE b.id = pt.booking_id OR b.user_id = (
        SELECT user_id FROM bookings WHERE id = pt.booking_id
    ) LIMIT 1
)
WHERE pt.booking_id IS NULL OR pt.booking_id = 0;
```

## 📄 Files Liên Quan

- `public/database_setup.php` - Auto setup script
- `database_migration_booking_details.sql` - Schema tạo booking_details
- `database_migration_bookings_to_details.sql` - Data migration script
- `app/Models/Booking.php` - Updated với LEFT JOIN fallback
- `app/Models/PaymentTransaction.php` - Updated với fallback logic

## 🚀 Bước Tiếp Theo

Sau khi fix database:

1. ✅ Test tạo booking mới - phải có record trong booking_details
2. ✅ Test theo dõi booking - phải hiển thị chi tiết
3. ✅ Test gán worker - phải kiểm tra payment đúng
4. ✅ Test quản lý bookings - phải hiển thị danh sách

## 💡 Lưu Ý

- Auto setup script là **idempotent** - chạy nhiều lần cũng được
- Code được thiết kế để compatible với cả schema cũ và mới
- Fallback logic được thêm vào để xử lý dữ liệu cũ
- **Không xóa data** - chỉ migrate từ một bảng sang bảng khác

## ❓ Câu Hỏi Thường Gặp

**Q: Chạy setup script có xóa data không?**
A: Không! Script chỉ tạo bảng mới và copy data, không xóa gì cả.

**Q: Cần backup database trước không?**
A: Luôn nên backup (CREATE TABLE bookings_backup AS SELECT * FROM bookings).

**Q: Migration mất bao lâu?**
A: Phụ thuộc vào số lượng data. Thường < 1 giây cho < 10k bookings.

**Q: Có thể rollback không?**
A: Có! DROP TABLE booking_details và data sẽ quay lại bookings cũ (nếu backup).
