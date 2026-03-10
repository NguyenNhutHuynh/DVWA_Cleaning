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

    public static function getComplaintsForModeration(): array
    {
        $stmt = DB::pdo()->query(
            "SELECT bm.*, 
                    b.id AS booking_id,
                    u.name AS sender_name, 
                    u.email AS sender_email,
                    u.role AS sender_role,
                    s.name AS service_name
             FROM booking_messages bm
             JOIN bookings b ON b.id = bm.booking_id
             JOIN users u ON u.id = bm.sender_id
             LEFT JOIN services s ON s.id = b.service_id
             WHERE LOWER(bm.content) LIKE '%khiếu nại%'
                OR LOWER(bm.content) LIKE '%complaint%'
                OR LOWER(bm.content) LIKE '%khieu nai%'
                OR LOWER(bm.content) LIKE '%phàn nàn%'
                OR LOWER(bm.content) LIKE '%phan nan%'
             ORDER BY bm.created_at DESC"
        );
        return $stmt->fetchAll() ?: [];
    }
}
