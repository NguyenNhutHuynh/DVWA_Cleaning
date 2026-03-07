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
        ?string $description
    ): int {
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
        return (int)DB::pdo()->lastInsertId();
    }

    /**
     * Lấy toàn bộ đơn đặt kèm thông tin người dùng và dịch vụ liên quan.
     *
     * @return array Danh sách toàn bộ đơn đặt và thông tin chi tiết
     */
    public static function getAll(): array
    {
        $sql = "SELECT b.*, u.name AS user_name, s.name AS service_name, s.price AS service_price
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
     * Lấy toàn bộ đơn đặt của một người dùng cụ thể.
     *
     * @param int $userId ID người dùng (khách hàng)
     * @return array Danh sách đơn đặt của khách hàng
     */
    public static function getByUserId(int $userId): array
    {
        $stmt = DB::pdo()->prepare(
            "SELECT * FROM bookings WHERE user_id = :uid ORDER BY created_at DESC"
        );
        $stmt->execute(['uid' => $userId]);
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
        $stmt = DB::pdo()->prepare(
            "UPDATE bookings SET status = :status, updated_at = NOW() WHERE id = :id"
        );
        return $stmt->execute(['status' => $status, 'id' => $id]);
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
}
