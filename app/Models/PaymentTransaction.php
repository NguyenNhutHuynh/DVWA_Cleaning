<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\DB;
use PDOException;

/**
 * Model PaymentTransaction - Quản lý giao dịch thanh toán PayOS
 * 
 * MIGRATION SQL (Chạy trước khi sử dụng):
 * 
 * CREATE TABLE IF NOT EXISTS payment_transactions (
 *     id INT PRIMARY KEY AUTO_INCREMENT,
 *     booking_id INT NOT NULL,
 *     order_code VARCHAR(255) UNIQUE NOT NULL,
 *     amount DECIMAL(10, 2) NOT NULL,
 *     status VARCHAR(50) NOT NULL DEFAULT 'pending',
 *     payment_method VARCHAR(50),
 *     transaction_id VARCHAR(255),
 *     payer_account_number VARCHAR(50),
 *     payer_name VARCHAR(255),
 *     webhook_raw_data LONGTEXT,
 *     webhook_signature VARCHAR(255),
 *     webhook_received_at DATETIME,
 *     created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
 *     paid_at DATETIME,
 *     updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 *     KEY idx_booking_id (booking_id),
 *     KEY idx_order_code (order_code),
 *     KEY idx_status (status),
 *     CONSTRAINT fk_payment_booking FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
 */
final class PaymentTransaction
{
    public const METHOD_CUSTOMER_PAYMENT = 'customer_payment';
    public const METHOD_WORKER_PAYOUT = 'worker_payout';

    public static function create(int $bookingId, int $orderCode, float $amount, ?string $status = null, ?string $description = null, ?string $paymentMethod = null): bool
    {
        try {
            $stmt = DB::pdo()->prepare("
                INSERT INTO payment_transactions 
                (booking_id, order_code, amount, status, payment_method, created_at)
                VALUES (:booking_id, :order_code, :amount, :status, :payment_method, NOW())
            ");
            
            return $stmt->execute([
                ':booking_id' => $bookingId,
                ':order_code' => (string)$orderCode,
                ':amount' => $amount,
                ':status' => $status ?? 'pending',
                ':payment_method' => $paymentMethod ?? self::METHOD_CUSTOMER_PAYMENT,
            ]);
        } catch (PDOException $e) {
            error_log('PaymentTransaction::create error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy thông tin giao dịch theo Order Code
     */
    public static function getByOrderCode(string $orderCode): ?array
    {
        try {
            $stmt = DB::pdo()->prepare("
                SELECT * FROM payment_transactions 
                WHERE order_code = :order_code
                LIMIT 1
            ");
            
            $stmt->execute([':order_code' => $orderCode]);
            $result = $stmt->fetch();
            
            return $result ?: null;
        } catch (PDOException $e) {
            error_log('PaymentTransaction::getByOrderCode error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Lấy thông tin giao dịch theo Booking ID
     */
    public static function getByBookingId(int $bookingId): ?array
    {
        try {
            $stmt = DB::pdo()->prepare("
                SELECT * FROM payment_transactions 
                WHERE booking_id = :booking_id
                ORDER BY created_at DESC
                LIMIT 1
            ");
            
            $stmt->execute([':booking_id' => $bookingId]);
            $result = $stmt->fetch();
            
            return $result ?: null;
        } catch (PDOException $e) {
            error_log('PaymentTransaction::getByBookingId error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Lấy giao dịch thanh toán của khách hàng theo booking.
     */
    public static function getLatestCustomerByBookingId(int $bookingId): ?array
    {
        try {
            $stmt = DB::pdo()->prepare(
                "SELECT * FROM payment_transactions
                 WHERE booking_id = :booking_id
                   AND (
                        payment_method IS NULL
                        OR payment_method IN (:customer_method, :legacy_method)
                   )
                 ORDER BY created_at DESC
                 LIMIT 1"
            );

            $stmt->execute([
                ':booking_id' => $bookingId,
                ':customer_method' => self::METHOD_CUSTOMER_PAYMENT,
                ':legacy_method' => 'bank_transfer',
            ]);

            $result = $stmt->fetch();
            return $result ?: null;
        } catch (PDOException $e) {
            error_log('PaymentTransaction::getLatestCustomerByBookingId error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Kiểm tra booking đã có giao dịch khách thanh toán thành công chưa.
     */
    public static function hasSuccessfulCustomerPayment(int $bookingId): bool
    {
        try {
            $stmt = DB::pdo()->prepare(
                "SELECT COUNT(*) AS total
                 FROM payment_transactions
                 WHERE booking_id = :booking_id
                   AND status = 'paid'
                   AND (
                        payment_method IS NULL
                        OR payment_method IN (:customer_method, :legacy_method)
                   )"
            );

            $stmt->execute([
                ':booking_id' => $bookingId,
                ':customer_method' => self::METHOD_CUSTOMER_PAYMENT,
                ':legacy_method' => 'bank_transfer',
            ]);

            $result = $stmt->fetch();
            return (int)($result['total'] ?? 0) > 0;
        } catch (PDOException $e) {
            error_log('PaymentTransaction::hasSuccessfulCustomerPayment error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Tổng tiền khách đã thanh toán thành công.
     */
    public static function getTotalPaidCustomerRevenue(): float
    {
        try {
            $stmt = DB::pdo()->prepare(
                "SELECT COALESCE(SUM(amount), 0) AS total
                 FROM payment_transactions
                 WHERE status = 'paid'
                   AND (
                        payment_method IS NULL
                        OR payment_method IN (:customer_method, :legacy_method)
                   )"
            );

            $stmt->execute([
                ':customer_method' => self::METHOD_CUSTOMER_PAYMENT,
                ':legacy_method' => 'bank_transfer',
            ]);

            $result = $stmt->fetch();
            return (float)($result['total'] ?? 0);
        } catch (PDOException $e) {
            error_log('PaymentTransaction::getTotalPaidCustomerRevenue error: ' . $e->getMessage());
            return 0.0;
        }
    }

    /**
     * Doanh thu khách thanh toán theo từng tháng gần nhất.
     */
    public static function getPaidCustomerRevenueByMonth(int $monthCount = 6): array
    {
        $monthCount = max(1, $monthCount);
        $monthlyData = [];

        for ($i = $monthCount - 1; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-{$i} months"));
            $monthlyData[$month] = 0.0;
        }

        try {
            $stmt = DB::pdo()->prepare(
                "SELECT DATE_FORMAT(paid_at, '%Y-%m') AS month_key, COALESCE(SUM(amount), 0) AS month_total
                 FROM payment_transactions
                 WHERE status = 'paid'
                   AND paid_at IS NOT NULL
                   AND (
                        payment_method IS NULL
                        OR payment_method IN (:customer_method, :legacy_method)
                   )
                 GROUP BY month_key"
            );

            $stmt->execute([
                ':customer_method' => self::METHOD_CUSTOMER_PAYMENT,
                ':legacy_method' => 'bank_transfer',
            ]);

            $rows = $stmt->fetchAll() ?: [];
            foreach ($rows as $row) {
                $monthKey = (string)($row['month_key'] ?? '');
                if (isset($monthlyData[$monthKey])) {
                    $monthlyData[$monthKey] = (float)($row['month_total'] ?? 0);
                }
            }
        } catch (PDOException $e) {
            error_log('PaymentTransaction::getPaidCustomerRevenueByMonth error: ' . $e->getMessage());
        }

        return $monthlyData;
    }

    /**
     * Cập nhật trạng thái thanh toán
     * 
     * @param string $orderCode Mã đơn hàng
     * @param string $status Trạng thái mới: paid|failed|cancelled
     * @param array $data Dữ liệu bổ sung (transaction_id, payer_account_number, payer_name, webhook_raw_data, webhook_signature, webhook_received_at)
     */
    public static function updateStatus(string $orderCode, string $status, array $data = []): bool
    {
        try {
            $updates = ['status = :status'];
            $params = [':status' => $status, ':order_code' => $orderCode];
            
            if (isset($data['transaction_id'])) {
                $updates[] = 'transaction_id = :transaction_id';
                $params[':transaction_id'] = $data['transaction_id'];
            }
            
            if (isset($data['payer_account_number'])) {
                $updates[] = 'payer_account_number = :payer_account_number';
                $params[':payer_account_number'] = $data['payer_account_number'];
            }
            
            if (isset($data['payer_name'])) {
                $updates[] = 'payer_name = :payer_name';
                $params[':payer_name'] = $data['payer_name'];
            }
            
            if (isset($data['webhook_raw_data'])) {
                $updates[] = 'webhook_raw_data = :webhook_raw_data';
                $params[':webhook_raw_data'] = $data['webhook_raw_data'];
            }
            
            if (isset($data['webhook_signature'])) {
                $updates[] = 'webhook_signature = :webhook_signature';
                $params[':webhook_signature'] = $data['webhook_signature'];
            }
            
            if (isset($data['webhook_received_at'])) {
                $updates[] = 'webhook_received_at = :webhook_received_at';
                $params[':webhook_received_at'] = $data['webhook_received_at'];
            }
            
            if ($status === 'paid') {
                $updates[] = 'paid_at = NOW()';
            }
            
            $sql = "UPDATE payment_transactions SET " . implode(', ', $updates) . " WHERE order_code = :order_code";
            
            $stmt = DB::pdo()->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log('PaymentTransaction::updateStatus error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Kiểm tra xem giao dịch đã được thanh toán hay chưa
     */
    public static function isPaid(string $orderCode): bool
    {
        $transaction = self::getByOrderCode($orderCode);
        return $transaction !== null && $transaction['status'] === 'paid';
    }

    /**
     * Lấy danh sách tất cả giao dịch với phân trang
     */
    public static function getAll(int $limit = 50, int $offset = 0): array
    {
        try {
            $stmt = DB::pdo()->prepare("
                SELECT * FROM payment_transactions 
                ORDER BY created_at DESC
                LIMIT :limit OFFSET :offset
            ");
            
            $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll() ?: [];
        } catch (PDOException $e) {
            error_log('PaymentTransaction::getAll error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Đếm tổng số giao dịch theo trạng thái
     */
    public static function countByStatus(string $status): int
    {
        try {
            $stmt = DB::pdo()->prepare("
                SELECT COUNT(*) as total FROM payment_transactions 
                WHERE status = :status
            ");
            
            $stmt->execute([':status' => $status]);
            $result = $stmt->fetch();
            
            return (int)($result['total'] ?? 0);
        } catch (PDOException $e) {
            error_log('PaymentTransaction::countByStatus error: ' . $e->getMessage());
            return 0;
        }
    }
}
