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

    public static function add(int $bookingId, int $workerId, ?string $report): void
    {
        $stmt = DB::pdo()->prepare(
            "INSERT INTO booking_reports (booking_id, worker_id, difficulties, created_at)
             VALUES (:booking_id, :worker_id, :report, NOW())"
        );
        $stmt->execute([
            'booking_id' => $bookingId,
            'worker_id' => $workerId,
            'report' => $report,
        ]);
    }

    public static function getAll(): array
    {
        $stmt = DB::pdo()->query(
            "SELECT br.*, 
                    b.id AS booking_id,
                    w.name AS worker_name, 
                    w.email AS worker_email,
                    c.name AS customer_name,
                    s.name AS service_name
             FROM booking_reports br
             JOIN bookings b ON b.id = br.booking_id
             JOIN users w ON w.id = br.worker_id
             LEFT JOIN users c ON c.id = b.user_id
             LEFT JOIN services s ON s.id = b.service_id
             ORDER BY br.created_at DESC"
        );
        return $stmt->fetchAll() ?: [];
    }

    public static function getByBookingId(int $bookingId): ?array
    {
        $stmt = DB::pdo()->prepare(
            "SELECT br.*, 
                    b.id AS booking_id,
                    w.name AS worker_name,
                    w.email AS worker_email,
                    c.name AS customer_name,
                    s.name AS service_name
             FROM booking_reports br
             JOIN bookings b ON b.id = br.booking_id
             JOIN users w ON w.id = br.worker_id
             LEFT JOIN users c ON c.id = b.user_id
             LEFT JOIN services s ON s.id = b.service_id
             WHERE br.booking_id = :booking_id
             LIMIT 1"
        );
        $stmt->execute(['booking_id' => $bookingId]);
        return $stmt->fetch() ?: null;
    }
}
