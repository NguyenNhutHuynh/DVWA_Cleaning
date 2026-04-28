<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\DB;

final class AdminUserMessage
{
    public static function add(int $userId, int $senderId, string $senderRole, string $content): void
    {
        $stmt = DB::pdo()->prepare(
            "INSERT INTO admin_user_messages (user_id, sender_id, sender_role, content, created_at)
             VALUES (:user_id, :sender_id, :sender_role, :content, NOW())"
        );
        $stmt->execute([
            'user_id' => $userId,
            'sender_id' => $senderId,
            'sender_role' => $senderRole,
            'content' => $content,
        ]);
    }

    public static function byUserId(int $userId): array
    {
        $stmt = DB::pdo()->prepare(
            "SELECT m.*, u.name AS sender_name
             FROM admin_user_messages m
             JOIN users u ON u.id = m.sender_id
             WHERE m.user_id = :user_id
             ORDER BY m.id ASC"
        );
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll() ?: [];
    }
}
