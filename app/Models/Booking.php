<?php

namespace App\Models;

use App\Core\DB;
use PDO;

class Booking
{
    /**
     * Tạo đơn đặt lịch mới trong CSDL
     * @return int ID đặt lịch được chèn
     */
    public static function create(int $userId, int $serviceId, string $date, string $time, string $location, ?string $description): int
    {
        $stmt = DB::pdo()->prepare(
            "INSERT INTO bookings (user_id, service_id, `date`, `time`, location, description, status, created_at, updated_at)
             VALUES (:user_id, :service_id, :date, :time, :location, :description, 'pending', NOW(), NOW())"
        );
        $stmt->execute([
            'user_id' => $userId,
            'service_id' => $serviceId,
            'date' => $date,
            'time' => $time,
            'location' => $location,
            'description' => $description,
        ]);
        return (int)DB::pdo()->lastInsertId();
    }

    /**
     * Lấy tất cả đơn đặt (có tên người dùng/dịch vụ)
     */
    public static function getAll(): array
    {
        $sql = "SELECT b.*, u.name AS user_name, s.name AS service_name
                FROM bookings b
                JOIN users u ON u.id = b.user_id
                JOIN services s ON s.id = b.service_id
                ORDER BY b.created_at DESC";
        $stmt = DB::pdo()->query($sql);
        return $stmt->fetchAll() ?: [];
    }

    /** Lấy đơn đặt theo ID */
    public static function getById(int $id): ?array
    {
        $stmt = DB::pdo()->prepare("SELECT * FROM bookings WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /** Lấy các đơn đặt theo ID người dùng */
    public static function getByUserId(int $userId): array
    {
        $stmt = DB::pdo()->prepare("SELECT * FROM bookings WHERE user_id = :uid ORDER BY created_at DESC");
        $stmt->execute(['uid' => $userId]);
        return $stmt->fetchAll() ?: [];
    }

    /** Cập nhật trạng thái đơn đặt */
    public static function updateStatus(int $id, string $status): bool
    {
        $stmt = DB::pdo()->prepare("UPDATE bookings SET status = :st, updated_at = NOW() WHERE id = :id");
        return $stmt->execute(['st' => $status, 'id' => $id]);
    }

    /** Gắn công nhân vào đơn đặt */
    public static function assignWorker(int $id, int $workerId): bool
    {
        $stmt = DB::pdo()->prepare("UPDATE bookings SET assigned_worker_id = :wid, assigned_at = NOW(), updated_at = NOW() WHERE id = :id");
        return $stmt->execute(['wid' => $workerId, 'id' => $id]);
    }
}
