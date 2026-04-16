/**
 * Database Migration: Create payment_transactions Table
 * 
 * Chạy lệnh này trong MySQL để tạo bảng payment_transactions:
 * 
 * mysql> USE cleaning_db;
 * mysql> [copy-paste toàn bộ SQL dưới đây]
 */

-- ============================================================================
-- TẠO BẢNG PAYMENT_TRANSACTIONS
-- ============================================================================

CREATE TABLE IF NOT EXISTS payment_transactions (
    id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'ID giao dịch',
    
    booking_id BIGINT UNSIGNED NOT NULL COMMENT 'ID booking liên kết',
    order_code VARCHAR(255) UNIQUE NOT NULL COMMENT 'Mã đơn hàng từ PayOS',
    amount DECIMAL(10, 2) NOT NULL COMMENT 'Số tiền thanh toán (VND)',
    
    status VARCHAR(50) NOT NULL DEFAULT 'pending' 
        COMMENT 'Trạng thái: pending|paid|failed|cancelled',
    payment_method VARCHAR(50) COMMENT 'Phương thức thanh toán (e.g., bank_transfer)',
    
    transaction_id VARCHAR(255) COMMENT 'Transaction ID từ PayOS',
    payer_account_number VARCHAR(50) COMMENT 'Số tài khoản người chuyển tiền',
    payer_name VARCHAR(255) COMMENT 'Tên người chuyển tiền',
    
    webhook_raw_data LONGTEXT COMMENT 'Raw JSON data từ webhook PayOS',
    webhook_signature VARCHAR(255) COMMENT 'Signature HMAC_SHA256 từ PayOS',
    webhook_received_at DATETIME COMMENT 'Thời điểm nhận webhook từ PayOS',
    
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'Lúc tạo giao dịch',
    paid_at DATETIME COMMENT 'Thời điểm thanh toán thành công',
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP 
        COMMENT 'Cập nhật cuối cùng',
    
    -- ========================================================================
    -- INDEXES
    -- ========================================================================
    
    KEY idx_booking_id (booking_id) COMMENT 'Tìm nhanh theo booking_id',
    KEY idx_order_code (order_code) COMMENT 'Tìm nhanh theo order_code',
    KEY idx_status (status) COMMENT 'Tìm nhanh theo status',
    KEY idx_created_at (created_at) COMMENT 'Tìm nhanh theo thời gian tạo',
    
    -- ========================================================================
    -- FOREIGN KEY
    -- ========================================================================
    
    CONSTRAINT fk_payment_booking FOREIGN KEY (booking_id) REFERENCES bookings(id) 
        ON DELETE CASCADE COMMENT 'Xóa giao dịch khi xóa booking'
        
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='Lưu trữ thông tin giao dịch thanh toán từ PayOS';

-- ============================================================================
-- VERIFY TABLE CREATED
-- ============================================================================

-- Chạy lệnh này để xác nhận bảng đã được tạo:
-- DESCRIBE payment_transactions;
