<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\DB;

final class BookingMessage
{
    public static function add(int $bookingId, int $senderId, string $content): void
    {
        $stmt = DB::pdo()->prepare(
            "INSERT INTO booking_messages (booking_id, sender_id, content, created_at)
             VALUES (:booking_id, :sender_id, :content, NOW())"
        );
        $stmt->execute([
            'booking_id' => $bookingId,
            'sender_id' => $senderId,
            'content' => $content,
        ]);
    }

    public static function byBookingId(int $bookingId): array
    {
        $stmt = DB::pdo()->prepare(
            "SELECT m.*, u.name AS sender_name, u.role AS sender_role
             FROM booking_messages m
             JOIN users u ON u.id = m.sender_id
             WHERE m.booking_id = :booking_id
             ORDER BY m.id ASC"
        );
        $stmt->execute(['booking_id' => $bookingId]);
        return $stmt->fetchAll() ?: [];
    }
}
