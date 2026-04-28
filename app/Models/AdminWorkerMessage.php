<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\DB;

final class AdminWorkerMessage
{
    public static function add(
        int $bookingId,
        int $workerId,
        int $senderId,
        string $senderRole,
        string $content
    ): void {
        $stmt = DB::pdo()->prepare(
            "INSERT INTO admin_worker_messages (booking_id, worker_id, sender_id, sender_role, content, created_at)
             VALUES (:booking_id, :worker_id, :sender_id, :sender_role, :content, NOW())"
        );
        $stmt->execute([
            'booking_id' => $bookingId,
            'worker_id' => $workerId,
            'sender_id' => $senderId,
            'sender_role' => $senderRole,
            'content' => $content,
        ]);
    }

    public static function byBookingId(int $bookingId): array
    {
        $stmt = DB::pdo()->prepare(
            "SELECT m.*, u.name AS sender_name
             FROM admin_worker_messages m
             JOIN users u ON u.id = m.sender_id
             WHERE m.booking_id = :booking_id
             ORDER BY m.id ASC"
        );
        $stmt->execute(['booking_id' => $bookingId]);
        return $stmt->fetchAll() ?: [];
    }

    public static function byWorkerId(int $workerId): array
    {
        $stmt = DB::pdo()->prepare(
            "SELECT m.*, b.id AS booking_id, s.name AS service_name, c.name AS customer_name, u.name AS sender_name
             FROM admin_worker_messages m
             JOIN bookings b ON b.id = m.booking_id
             JOIN services s ON s.id = b.service_id
             JOIN users c ON c.id = b.user_id
             JOIN users u ON u.id = m.sender_id
             WHERE m.worker_id = :worker_id
             ORDER BY m.booking_id DESC, m.id ASC"
        );
        $stmt->execute(['worker_id' => $workerId]);
        return $stmt->fetchAll() ?: [];
    }
}
