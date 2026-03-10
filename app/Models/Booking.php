<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\DB;
use PDOException;

/**
 * Model Booking dùng để quản lý các lịch đặt dịch vụ.
 * Xử lý tạo mới, truy vấn và cập nhật trạng thái đơn đặt của khách hàng.
 */
final class Booking
{
    private static ?array $statusEnumCache = null;
    private static ?array $bookingColumnsCache = null;

    // Các trạng thái của đơn đặt lịch
    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    /**
     * Tạo một đơn đặt lịch mới trong cơ sở dữ liệu.
     *
     * @param int $userId ID người dùng (khách hàng)
     * @param int $serviceId ID dịch vụ được đặt
     * @param string $date Ngày đặt lịch (YYYY-MM-DD)
     * @param string $time Giờ đặt lịch (HH:MM)
     * @param string $location Địa điểm thực hiện dịch vụ
     * @param string|null $description Ghi chú bổ sung (tùy chọn)
     * @return int ID của đơn đặt vừa tạo
     */
    public static function create(
        int $userId,
        int $serviceId,
        string $date,
        string $time,
        string $location,
        ?string $description,
        float $quantity = 1.0,
        ?string $measureUnit = null,
        ?float $unitPrice = null,
        ?float $lineTotal = null
    ): int {
        self::ensurePricingColumnsIfNeeded();

        if (self::supportsPricingColumns()) {
            $stmt = DB::pdo()->prepare(
                "INSERT INTO bookings (
                    user_id, service_id, quantity, measure_unit, unit_price, line_total,
                    date, time, location, description, status, created_at, updated_at
                ) VALUES (
                    :user_id, :service_id, :quantity, :measure_unit, :unit_price, :line_total,
                    :date, :time, :location, :description, :status, NOW(), NOW()
                )"
            );
            $stmt->execute([
                'user_id' => $userId,
                'service_id' => $serviceId,
                'quantity' => $quantity,
                'measure_unit' => $measureUnit ?? '',
                'unit_price' => $unitPrice ?? 0,
                'line_total' => $lineTotal ?? 0,
                'date' => $date,
                'time' => $time,
                'location' => $location,
                'description' => $description,
                'status' => self::STATUS_PENDING,
            ]);
        } else {
            $stmt = DB::pdo()->prepare(
                "INSERT INTO bookings (user_id, service_id, date, time, location, description, status, created_at, updated_at)
                 VALUES (:user_id, :service_id, :date, :time, :location, :description, :status, NOW(), NOW())"
            );
            $stmt->execute([
                'user_id' => $userId,
                'service_id' => $serviceId,
                'date' => $date,
                'time' => $time,
                'location' => $location,
                'description' => $description,
                'status' => self::STATUS_PENDING,
            ]);
        }

        return (int)DB::pdo()->lastInsertId();
    }

    /**
     * Lấy toàn bộ đơn đặt kèm thông tin người dùng và dịch vụ liên quan.
     *
     * @return array Danh sách toàn bộ đơn đặt và thông tin chi tiết
     */
    public static function getAll(): array
    {
        $servicePriceExpr = self::supportsPricingColumns()
            ? 'COALESCE(NULLIF(b.line_total, 0), s.price)'
            : 's.price';

        $sql = "SELECT b.*, u.name AS user_name, s.name AS service_name, {$servicePriceExpr} AS service_price
                FROM bookings b
                JOIN users u ON u.id = b.user_id
                JOIN services s ON s.id = b.service_id
                ORDER BY b.created_at DESC";
        $stmt = DB::pdo()->query($sql);
        return $stmt->fetchAll() ?: [];
    }

    /**
     * Tìm đơn đặt theo ID.
     *
     * @param int $id ID đơn đặt
     * @return array|null Dữ liệu đơn đặt hoặc null nếu không tìm thấy
     */
    public static function getById(int $id): ?array
    {
        $stmt = DB::pdo()->prepare("SELECT * FROM bookings WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Lấy chi tiết một đơn đặt (kèm khách hàng, worker, dịch vụ).
     */
    public static function getDetailById(int $id): ?array
    {
        $servicePriceExpr = self::supportsPricingColumns()
            ? 'COALESCE(NULLIF(b.line_total, 0), s.price)'
            : 's.price';

        $stmt = DB::pdo()->prepare(
            "SELECT
                b.*,
                u.name AS user_name,
                u.phone AS user_phone,
                u.address AS user_address,
                s.name AS service_name,
                {$servicePriceExpr} AS service_price,
                s.unit AS service_unit,
                w.name AS worker_name,
                w.phone AS worker_phone,
                w.address AS worker_address
             FROM bookings b
             JOIN users u ON u.id = b.user_id
             JOIN services s ON s.id = b.service_id
             LEFT JOIN users w ON w.id = b.assigned_worker_id
             WHERE b.id = :id
             LIMIT 1"
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Lấy toàn bộ đơn đặt của một người dùng cụ thể.
     *
     * @param int $userId ID người dùng (khách hàng)
     * @return array Danh sách đơn đặt của khách hàng
     */
    public static function getByUserId(int $userId): array
    {
        $servicePriceExpr = self::supportsPricingColumns()
            ? 'COALESCE(NULLIF(b.line_total, 0), s.price)'
            : 's.price';

        $stmt = DB::pdo()->prepare(
            "SELECT b.*, s.name AS service_name, {$servicePriceExpr} AS service_price, w.name AS worker_name
             FROM bookings b
             JOIN services s ON s.id = b.service_id
             LEFT JOIN users w ON w.id = b.assigned_worker_id
             WHERE b.user_id = :uid
             ORDER BY b.created_at DESC"
        );
        $stmt->execute(['uid' => $userId]);
        return $stmt->fetchAll() ?: [];
    }

    /**
     * Lấy danh sách đơn đã gán cho worker.
     */
    public static function getByWorkerId(int $workerId): array
    {
        $servicePriceExpr = self::supportsPricingColumns()
            ? 'COALESCE(NULLIF(b.line_total, 0), s.price)'
            : 's.price';

        $stmt = DB::pdo()->prepare(
            "SELECT b.*, u.name AS user_name, u.phone AS user_phone, s.name AS service_name, {$servicePriceExpr} AS service_price
             FROM bookings b
             JOIN users u ON u.id = b.user_id
             JOIN services s ON s.id = b.service_id
             WHERE b.assigned_worker_id = :wid
             ORDER BY b.date ASC, b.time ASC"
        );
        $stmt->execute(['wid' => $workerId]);
        return $stmt->fetchAll() ?: [];
    }

    /**
     * Cập nhật trạng thái của đơn đặt.
     *
     * @param int $id ID đơn đặt
     * @param string $status Trạng thái mới
     * @return bool True nếu cập nhật thành công
     */
    public static function updateStatus(int $id, string $status): bool
    {
        $safeStatus = self::resolveStatusForDatabase($status);

        $stmt = DB::pdo()->prepare(
            "UPDATE bookings SET status = :status, updated_at = NOW() WHERE id = :id"
        );
        return $stmt->execute(['status' => $safeStatus, 'id' => $id]);
    }

    /**
     * Phân công nhân viên cho một đơn đặt.
     *
     * @param int $id ID đơn đặt
     * @param int $workerId ID nhân viên (người dùng)
     * @return bool True nếu phân công thành công
     */
    public static function assignWorker(int $id, int $workerId): bool
    {
        $stmt = DB::pdo()->prepare(
            "UPDATE bookings SET assigned_worker_id = :wid, assigned_at = NOW(), updated_at = NOW() WHERE id = :id"
        );
        return $stmt->execute(['wid' => $workerId, 'id' => $id]);
    }

    /**
     * Chuyển trạng thái ứng dụng sang trạng thái hợp lệ theo enum hiện có trong DB.
     * Giúp tương thích với schema cũ chưa có các trạng thái mở rộng.
     */
    private static function resolveStatusForDatabase(string $status): string
    {
        $supported = self::getSupportedStatuses();
        if (in_array($status, $supported, true)) {
            return $status;
        }

        $fallbacks = [
            self::STATUS_ACCEPTED => self::STATUS_CONFIRMED,
            self::STATUS_IN_PROGRESS => self::STATUS_CONFIRMED,
            'on_the_way' => self::STATUS_CONFIRMED,
            'arrived' => self::STATUS_CONFIRMED,
            'before_cleaning' => self::STATUS_CONFIRMED,
            'after_cleaning' => self::STATUS_CONFIRMED,
            'before_cleaning_photo' => self::STATUS_CONFIRMED,
            'after_cleaning_photo' => self::STATUS_CONFIRMED,
        ];

        $candidate = $fallbacks[$status] ?? self::STATUS_PENDING;
        if (in_array($candidate, $supported, true)) {
            return $candidate;
        }

        return $supported[0] ?? self::STATUS_PENDING;
    }

    /**
     * Lấy danh sách giá trị enum cột bookings.status từ DB.
     */
    private static function getSupportedStatuses(): array
    {
        if (self::$statusEnumCache !== null) {
            return self::$statusEnumCache;
        }

        try {
            $stmt = DB::pdo()->query("SHOW COLUMNS FROM bookings LIKE 'status'");
            $column = $stmt->fetch();
            $type = (string)($column['Type'] ?? '');

            if (preg_match('/^enum\\((.*)\\)$/i', $type, $matches) === 1) {
                $values = str_getcsv($matches[1], ',', "'");
                self::$statusEnumCache = array_values(array_filter(array_map('trim', $values)));
            } else {
                self::$statusEnumCache = [
                    self::STATUS_PENDING,
                    self::STATUS_CONFIRMED,
                    self::STATUS_COMPLETED,
                    self::STATUS_CANCELLED,
                ];
            }
        } catch (PDOException $exception) {
            self::$statusEnumCache = [
                self::STATUS_PENDING,
                self::STATUS_CONFIRMED,
                self::STATUS_COMPLETED,
                self::STATUS_CANCELLED,
            ];
        }

        return self::$statusEnumCache;
    }

    private static function supportsPricingColumns(): bool
    {
        return self::hasBookingColumn('quantity')
            && self::hasBookingColumn('measure_unit')
            && self::hasBookingColumn('unit_price')
            && self::hasBookingColumn('line_total');
    }

    private static function hasBookingColumn(string $name): bool
    {
        $columns = self::getBookingColumns();
        return in_array($name, $columns, true);
    }

    private static function getBookingColumns(): array
    {
        if (self::$bookingColumnsCache !== null) {
            return self::$bookingColumnsCache;
        }

        try {
            $stmt = DB::pdo()->query('SHOW COLUMNS FROM bookings');
            $rows = $stmt->fetchAll() ?: [];
            self::$bookingColumnsCache = array_values(array_map(
                static fn(array $row): string => (string)($row['Field'] ?? ''),
                $rows
            ));
        } catch (PDOException $exception) {
            self::$bookingColumnsCache = [];
        }

        return self::$bookingColumnsCache;
    }

    private static function ensurePricingColumnsIfNeeded(): void
    {
        if (self::supportsPricingColumns()) {
            return;
        }

        $alterStatements = [
            "ALTER TABLE bookings ADD COLUMN quantity DECIMAL(10,2) NOT NULL DEFAULT 1.00 AFTER service_id",
            "ALTER TABLE bookings ADD COLUMN measure_unit VARCHAR(32) NOT NULL DEFAULT '' AFTER quantity",
            "ALTER TABLE bookings ADD COLUMN unit_price DECIMAL(15,2) NOT NULL DEFAULT 0.00 AFTER measure_unit",
            "ALTER TABLE bookings ADD COLUMN line_total DECIMAL(15,2) NOT NULL DEFAULT 0.00 AFTER unit_price",
        ];

        foreach ($alterStatements as $sql) {
            try {
                DB::pdo()->exec($sql);
            } catch (PDOException $exception) {
                // Cột có thể đã tồn tại hoặc không có quyền ALTER TABLE.
            }
        }

        self::$bookingColumnsCache = null;
    }
}
