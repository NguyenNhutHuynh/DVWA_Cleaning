<?php

namespace App\Models;

use App\Core\DB;
use PDO;

class Contact
{
    /** Lấy tất cả các liên hệ từ CSDL */
    public static function getAll(): array
    {
        $stmt = DB::pdo()->query("SELECT * FROM contacts ORDER BY created_at DESC");
        return $stmt->fetchAll() ?: [];
    }

    /** Tạo tin nhắn liên hệ mới trong CSDL */
    public static function create(string $name, string $email, string $phone, string $subject, string $message): int
    {
        $stmt = DB::pdo()->prepare(
            "INSERT INTO contacts (name, email, phone, subject, message, status, created_at, updated_at)
             VALUES (:name, :email, :phone, :subject, :message, 'pending', NOW(), NOW())"
        );
        $stmt->execute(compact('name','email','phone','subject','message'));
        return (int)DB::pdo()->lastInsertId();
    }

    /** Lấy liên hệ theo ID */
    public static function getById(int $id): ?array
    {
        $stmt = DB::pdo()->prepare("SELECT * FROM contacts WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /** Cập nhật trạng thái liên hệ (kiểm tra xuất admin) */
    public static function updateStatus(int $id, string $status, ?int $adminId = null): bool
    {
        $stmt = DB::pdo()->prepare(
            "UPDATE contacts SET status = :st, processed_by = :aid, processed_at = NOW(), updated_at = NOW() WHERE id = :id"
        );
        return $stmt->execute(['st' => $status, 'aid' => $adminId, 'id' => $id]);
    }
}
