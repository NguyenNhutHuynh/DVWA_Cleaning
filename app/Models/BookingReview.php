<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\DB;

final class BookingReview
{
    public static function exists(int $bookingId): bool
    {
        $stmt = DB::pdo()->prepare("SELECT id FROM booking_reviews WHERE booking_id = :booking_id LIMIT 1");
        $stmt->execute(['booking_id' => $bookingId]);
        return (bool)$stmt->fetch();
    }

    public static function add(int $bookingId, int $customerId, int $workerId, int $rating, ?string $comment): void
    {
        $stmt = DB::pdo()->prepare(
            "INSERT INTO booking_reviews (booking_id, customer_id, worker_id, rating, comment, created_at)
             VALUES (:booking_id, :customer_id, :worker_id, :rating, :comment, NOW())"
        );
        $stmt->execute([
            'booking_id' => $bookingId,
            'customer_id' => $customerId,
            'worker_id' => $workerId,
            'rating' => $rating,
            'comment' => $comment,
        ]);
    }

    public static function getByBookingId(int $bookingId): ?array
    {
        $stmt = DB::pdo()->prepare(
            "SELECT br.*, u.name AS customer_name, w.name AS worker_name
             FROM booking_reviews br
             LEFT JOIN users u ON u.id = br.customer_id
             LEFT JOIN users w ON w.id = br.worker_id
             WHERE br.booking_id = :booking_id
             LIMIT 1"
        );
        $stmt->execute(['booking_id' => $bookingId]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public static function getAll(): array
    {
        $stmt = DB::pdo()->query(
            "SELECT br.*, 
                    b.id AS booking_id,
                    c.name AS customer_name, 
                    c.email AS customer_email,
                    w.name AS worker_name,
                    s.name AS service_name
             FROM booking_reviews br
             JOIN bookings b ON b.id = br.booking_id
             JOIN users c ON c.id = br.customer_id
             LEFT JOIN users w ON w.id = br.worker_id
             LEFT JOIN services s ON s.id = b.service_id
             ORDER BY br.created_at DESC"
        );
        return $stmt->fetchAll() ?: [];
    }

    public static function getByServiceId(int $serviceId): array
    {
        $stmt = DB::pdo()->prepare(
            "SELECT
                br.*,
                c.name AS customer_name,
                s.name AS service_name
             FROM booking_reviews br
             JOIN bookings b ON b.id = br.booking_id
             JOIN users c ON c.id = br.customer_id
             JOIN services s ON s.id = b.service_id
             WHERE b.service_id = :service_id
             ORDER BY br.created_at DESC"
        );
        $stmt->execute(['service_id' => $serviceId]);
        return $stmt->fetchAll() ?: [];
    }
}
