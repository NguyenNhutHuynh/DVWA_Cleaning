/**
 * Database Migration: Migrate Bookings to New Schema (booking + booking_details)
 * 
 * Script này migrate dữ liệu từ schema cũ (tất cả trong bookings) sang schema mới:
 * - bookings (metadata only: id, user_id, created_at, updated_at)
 * - booking_details (chi tiết công việc: service, date, time, price, status, etc.)
 * 
 * Chạy sau khi tạo bảng booking_details!
 * 
 * mysql> USE cleaning_db;
 * mysql> [copy-paste toàn bộ SQL dưới đây]
 */

-- ============================================================================
-- STEP 1: Thêm cột payment_status vào payment_transactions nếu chưa có booking_id
-- (trong trường hợp dữ liệu cũ không có booking_id)
-- ============================================================================

-- Nếu payment_transactions chưa có booking_id, hãy thêm nó
-- ALTER TABLE payment_transactions ADD COLUMN booking_id BIGINT UNSIGNED NOT NULL AFTER id;

-- ============================================================================
-- STEP 2: Backup dữ liệu cũ (tùy chọn)
-- ============================================================================

-- CREATE TABLE bookings_backup AS SELECT * FROM bookings;

-- ============================================================================
-- STEP 3: Tạo cấu trúc bảng bookings mới (nếu cần reformat)
-- ============================================================================

-- Nếu bảng bookings hiện tại có các cột cũ (date, time, service_id, v.v.),
-- tạo bảng tạm với schema mới, copy dữ liệu, sau đó rename

-- ============================================================================
-- STEP 4: Migrate dữ liệu từ bookings cũ sang booking_details
-- ============================================================================

-- Trường hợp 1: Nếu bookings cũ có tất cả các cột (date, time, location, service_id, etc.)
-- Chạy lệnh này:

INSERT INTO booking_details (
    booking_id,
    service_id,
    work_date,
    work_time,
    location,
    quantity,
    measure_unit,
    unit_price,
    line_total,
    detail_status,
    note,
    assigned_worker_id,
    assigned_at,
    created_at,
    updated_at
)
SELECT
    b.id AS booking_id,
    b.service_id,
    b.date AS work_date,
    b.time AS work_time,
    b.location,
    COALESCE(b.quantity, 1) AS quantity,
    COALESCE(b.measure_unit, '') AS measure_unit,
    COALESCE(b.unit_price, 0) AS unit_price,
    COALESCE(b.line_total, 0) AS line_total,
    COALESCE(b.status, 'pending') AS detail_status,
    b.description AS note,
    b.assigned_worker_id,
    b.assigned_at,
    b.created_at,
    b.updated_at
FROM bookings b
WHERE NOT EXISTS (
    SELECT 1 FROM booking_details bd WHERE bd.booking_id = b.id
)
ON DUPLICATE KEY UPDATE
    booking_id = VALUES(booking_id);

-- ============================================================================
-- STEP 5: Cập nhật payment_transactions.booking_id từ dữ liệu cũ (nếu cần)
-- ============================================================================

-- Nếu payment_transactions chưa có booking_id hoặc có null, hãy cập nhật
-- UPDATE payment_transactions pt
-- SET pt.booking_id = (
--     SELECT b.id FROM bookings b
--     WHERE b.id = pt.booking_id OR pt.order_code = CONCAT('booking_', b.id)
-- )
-- WHERE pt.booking_id IS NULL OR pt.booking_id = 0;

-- ============================================================================
-- STEP 6: Xóa các cột không cần từ bảng bookings cũ (tùy chọn - để lại để backup)
-- ============================================================================

-- Nếu muốn, hãy xóa các cột cũ sau khi đã migrate thành công:
-- ALTER TABLE bookings DROP COLUMN IF EXISTS service_id,
--                        DROP COLUMN IF EXISTS date,
--                        DROP COLUMN IF EXISTS time,
--                        DROP COLUMN IF EXISTS location,
--                        DROP COLUMN IF EXISTS quantity,
--                        DROP COLUMN IF EXISTS measure_unit,
--                        DROP COLUMN IF EXISTS unit_price,
--                        DROP COLUMN IF EXISTS line_total,
--                        DROP COLUMN IF EXISTS status,
--                        DROP COLUMN IF EXISTS description,
--                        DROP COLUMN IF EXISTS assigned_worker_id,
--                        DROP COLUMN IF EXISTS assigned_at;

-- ============================================================================
-- STEP 7: Verify migration
-- ============================================================================

-- Kiểm tra xem migration thành công không:
-- SELECT COUNT(*) as total_booking_details FROM booking_details;
-- SELECT COUNT(*) as total_bookings FROM bookings;
-- SELECT b.id, b.user_id, COUNT(bd.id) as detail_count 
-- FROM bookings b LEFT JOIN booking_details bd ON bd.booking_id = b.id 
-- GROUP BY b.id HAVING detail_count = 0;
