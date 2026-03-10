<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\DB;
use PDOException;

final class BookingProgress
{
    private static ?bool $hasWorkerIdColumn = null;

    public const ON_THE_WAY = 'on_the_way';
    public const ARRIVED = 'arrived';
    public const BEFORE_PHOTO = 'before_photo';
    public const AFTER_PHOTO = 'after_photo';
    public const COMPLETED = 'completed';

    public static function add(int $bookingId, string $step, ?string $note = null, ?int $workerId = null): int
    {
        if (self::hasWorkerIdColumn()) {
            $resolvedWorkerId = $workerId ?? self::resolveWorkerIdFromBooking($bookingId);
            if ($resolvedWorkerId === null) {
                throw new PDOException('Cannot insert booking_progress: worker_id is required but could not be resolved.');
            }

            $stmt = DB::pdo()->prepare(
                "INSERT INTO booking_progress (booking_id, worker_id, step, note, created_at)
                 VALUES (:booking_id, :worker_id, :step, :note, NOW())"
            );
            $stmt->execute([
                'booking_id' => $bookingId,
                'worker_id' => $resolvedWorkerId,
                'step' => $step,
                'note' => $note,
            ]);
        } else {
            $stmt = DB::pdo()->prepare(
                "INSERT INTO booking_progress (booking_id, step, note, created_at)
                 VALUES (:booking_id, :step, :note, NOW())"
            );
            $stmt->execute([
                'booking_id' => $bookingId,
                'step' => $step,
                'note' => $note,
            ]);
        }

        return (int)DB::pdo()->lastInsertId();
    }

    private static function hasWorkerIdColumn(): bool
    {
        if (self::$hasWorkerIdColumn !== null) {
            return self::$hasWorkerIdColumn;
        }

        $stmt = DB::pdo()->query("SHOW COLUMNS FROM booking_progress LIKE 'worker_id'");
        self::$hasWorkerIdColumn = (bool)$stmt->fetch();
        return self::$hasWorkerIdColumn;
    }

    private static function resolveWorkerIdFromBooking(int $bookingId): ?int
    {
        $stmt = DB::pdo()->prepare("SELECT assigned_worker_id FROM bookings WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $bookingId]);
        $row = $stmt->fetch();
        $workerId = isset($row['assigned_worker_id']) ? (int)$row['assigned_worker_id'] : 0;
        return $workerId > 0 ? $workerId : null;
    }

    public static function addPhoto(int $progressId, string $path): void
    {
        $stmt = DB::pdo()->prepare(
            "INSERT INTO booking_progress_photos (progress_id, photo_path, created_at)
             VALUES (:progress_id, :photo_path, NOW())"
        );
        $stmt->execute([
            'progress_id' => $progressId,
            'photo_path' => $path,
        ]);
    }

    public static function byBookingId(int $bookingId): array
    {
        $stmt = DB::pdo()->prepare(
            "SELECT * FROM booking_progress WHERE booking_id = :booking_id ORDER BY created_at ASC"
        );
        $stmt->execute(['booking_id' => $bookingId]);
        $rows = $stmt->fetchAll() ?: [];

        foreach ($rows as &$row) {
            $row['photos'] = self::photosByProgressId((int)$row['id']);
        }

        return $rows;
    }

    public static function latestStep(int $bookingId): ?string
    {
        $stmt = DB::pdo()->prepare(
            "SELECT step FROM booking_progress WHERE booking_id = :booking_id ORDER BY id DESC LIMIT 1"
        );
        $stmt->execute(['booking_id' => $bookingId]);
        $row = $stmt->fetch();
        return $row['step'] ?? null;
    }

    private static function photosByProgressId(int $progressId): array
    {
        $stmt = DB::pdo()->prepare(
            "SELECT photo_path FROM booking_progress_photos WHERE progress_id = :progress_id ORDER BY id ASC"
        );
        $stmt->execute(['progress_id' => $progressId]);
        $rows = $stmt->fetchAll() ?: [];
        return array_map(static fn(array $item): string => (string)$item['photo_path'], $rows);
    }

    public static function stepLabel(string $step): string
    {
        return match ($step) {
            self::ON_THE_WAY => 'Trên đường đến',
            self::ARRIVED => 'Đã đến',
            self::BEFORE_PHOTO => 'Ảnh trước dọn dẹp',
            self::AFTER_PHOTO => 'Ảnh sau dọn dẹp',
            self::COMPLETED => 'Hoàn thành',
            default => $step,
        };
    }
}
