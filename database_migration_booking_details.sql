/**
 * Database Migration: Create booking_details Table
 * 
 * Chạy lệnh này trong MySQL để tạo bảng booking_details:
 * 
 * mysql> USE cleaning_db;
 * mysql> [copy-paste toàn bộ SQL dưới đây]
 */

-- ============================================================================
-- TẠO BẢNG BOOKING_DETAILS (tách từ bảng bookings cũ)
-- ============================================================================

CREATE TABLE IF NOT EXISTS booking_details (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    
    booking_id BIGINT UNSIGNED NOT NULL COMMENT 'ID booking liên kết',
    service_id BIGINT UNSIGNED NOT NULL COMMENT 'ID dịch vụ',
    assigned_worker_id BIGINT UNSIGNED COMMENT 'ID worker được gán',
    
    -- Thông tin công việc
    work_date DATE NOT NULL COMMENT 'Ngày thực hiện công việc',
    work_time VARCHAR(5) NOT NULL COMMENT 'Giờ thực hiện công việc (HH:MM)',
    location VARCHAR(255) COMMENT 'Địa điểm thực hiện dịch vụ',
    
    -- Thông tin số lượng & giá
    quantity DECIMAL(10, 2) NOT NULL DEFAULT 1 COMMENT 'Số lượng',
    measure_unit VARCHAR(50) COMMENT 'Đơn vị tính',
    unit_price DECIMAL(10, 2) NOT NULL DEFAULT 0 COMMENT 'Giá đơn vị',
    line_total DECIMAL(10, 2) NOT NULL DEFAULT 0 COMMENT 'Tổng giá (quantity * unit_price)',
    
    -- Trạng thái công việc
    detail_status ENUM(
        'pending',
        'assigned',
        'confirmed',
        'accepted',
        'on_the_way',
        'arrived',
        'in_progress',
        'before_cleaning',
        'after_cleaning',
        'completed',
        'cancelled'
    ) NOT NULL DEFAULT 'pending' COMMENT 'Trạng thái chi tiết công việc',
    
    -- Thêm thông tin
    note LONGTEXT COMMENT 'Ghi chú / mô tả công việc',
    estimated_arrival_time DATETIME COMMENT 'Thời gian ước tính worker sẽ đến',
    assigned_at DATETIME COMMENT 'Thời điểm được gán cho worker',
    
    -- Timestamps
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'Lúc tạo',
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Cập nhật cuối',
    
    -- ========================================================================
    -- PRIMARY KEY
    -- ========================================================================
    
    PRIMARY KEY (id),
    
    -- ========================================================================
    -- INDEXES
    -- ========================================================================
    
    KEY idx_booking_details_booking (booking_id) COMMENT 'Tìm nhanh theo booking_id',
    KEY idx_booking_details_service (service_id) COMMENT 'Tìm nhanh theo service_id',
    KEY idx_booking_details_worker (assigned_worker_id) COMMENT 'Tìm nhanh theo worker_id',
    KEY idx_booking_details_status (detail_status) COMMENT 'Tìm nhanh theo status',
    KEY idx_booking_details_date (work_date) COMMENT 'Tìm nhanh theo ngày',
    
    -- ========================================================================
    -- FOREIGN KEYS
    -- ========================================================================
    
    CONSTRAINT fk_booking_details_booking FOREIGN KEY (booking_id) REFERENCES bookings(id) 
        ON DELETE CASCADE COMMENT 'Xóa chi tiết khi xóa booking',
    
    CONSTRAINT fk_booking_details_service FOREIGN KEY (service_id) REFERENCES services(id) 
        ON DELETE RESTRICT COMMENT 'Không xóa service nếu có chi tiết liên kết',
    
    CONSTRAINT fk_booking_details_worker FOREIGN KEY (assigned_worker_id) REFERENCES users(id) 
        ON DELETE SET NULL COMMENT 'Xóa gán worker khi xóa user'
        
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='Chi tiết công việc của từng booking sau khi tách ra từ bảng bookings';

-- ============================================================================
-- VERIFY TABLE CREATED
-- ============================================================================

-- Chạy lệnh này để xác nhận bảng đã được tạo:
-- DESCRIBE booking_details;
