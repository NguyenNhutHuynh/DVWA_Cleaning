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
}
