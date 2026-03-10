<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\DB;

final class BookingReport
{
    public static function exists(int $bookingId): bool
    {
        $stmt = DB::pdo()->prepare("SELECT id FROM booking_reports WHERE booking_id = :booking_id LIMIT 1");
        $stmt->execute(['booking_id' => $bookingId]);
        return (bool)$stmt->fetch();
    }

    public static function add(int $bookingId, int $workerId, ?string $difficulties, ?string $note): void
    {
        $stmt = DB::pdo()->prepare(
            "INSERT INTO booking_reports (booking_id, worker_id, difficulties, note, created_at)
             VALUES (:booking_id, :worker_id, :difficulties, :note, NOW())"
        );
        $stmt->execute([
            'booking_id' => $bookingId,
            'worker_id' => $workerId,
            'difficulties' => $difficulties,
            'note' => $note,
        ]);
    }
}
