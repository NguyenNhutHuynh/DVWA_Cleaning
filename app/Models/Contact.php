<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\DB;

/**
 * Model Contact dùng để quản lý dữ liệu từ biểu mẫu liên hệ.
 * Xử lý tạo mới tin nhắn liên hệ và quản lý trạng thái xử lý.
 */
final class Contact
{
    // Các trạng thái của tin nhắn liên hệ
    public const STATUS_PENDING = 'pending';
    public const STATUS_RESPONDED = 'responded';
    public const STATUS_CLOSED = 'closed';

    /**
     * Lấy toàn bộ tin nhắn liên hệ từ cơ sở dữ liệu.
     *
     * @return array Danh sách toàn bộ tin nhắn liên hệ
     */
    public static function getAll(): array
    {
        $stmt = DB::pdo()->query("SELECT * FROM contacts ORDER BY created_at DESC");
        return $stmt->fetchAll() ?: [];
    }

    /**
     * Tạo tin nhắn liên hệ mới trong cơ sở dữ liệu.
     *
     * @param string $name Tên người gửi
     * @param string $email Email người gửi
     * @param string $phone Số điện thoại người gửi
     * @param string $subject Tiêu đề tin nhắn
     * @param string $message Nội dung tin nhắn
     * @return int ID của tin nhắn liên hệ vừa tạo
     */
    public static function create(
        string $name,
        string $email,
        string $phone,
        string $subject,
        string $message
    ): int {
        $stmt = DB::pdo()->prepare(
            "INSERT INTO contacts (name, email, phone, subject, message, status, created_at, updated_at)
             VALUES (:name, :email, :phone, :subject, :message, :status, NOW(), NOW())"
        );
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'subject' => $subject,
            'message' => $message,
            'status' => self::STATUS_PENDING,
        ]);
        return (int)DB::pdo()->lastInsertId();
    }

    /**
     * Tìm tin nhắn liên hệ theo ID.
     *
     * @param int $id ID tin nhắn liên hệ
     * @return array|null Dữ liệu tin nhắn hoặc null nếu không tìm thấy
     */
    public static function getById(int $id): ?array
    {
        $stmt = DB::pdo()->prepare("SELECT * FROM contacts WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Cập nhật trạng thái tin nhắn liên hệ.
     * Ghi nhận quản trị viên xử lý và thời điểm xử lý.
     *
     * @param int $id ID tin nhắn liên hệ
     * @param string $status Trạng thái mới
     * @param int|null $adminId ID quản trị viên xử lý (tùy chọn)
     * @return bool True nếu cập nhật thành công
     */
    public static function updateStatus(int $id, string $status, ?int $adminId = null): bool
    {
        $stmt = DB::pdo()->prepare(
            "UPDATE contacts SET status = :status, processed_by = :adminId, processed_at = NOW(), updated_at = NOW() WHERE id = :id"
        );
        return $stmt->execute([
            'status' => $status,
            'adminId' => $adminId,
            'id' => $id,
        ]);
    }
}

