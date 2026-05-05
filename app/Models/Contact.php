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
    public const STATUS_RESPONDED = 'replied';
    public const STATUS_CLOSED = 'closed';

    private static ?bool $hasUserIdColumn = null;

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
     * Lấy các tin nhắn liên hệ theo người dùng.
     *
     * @param int $userId ID người dùng
     * @return array Danh sách tin nhắn liên hệ của người dùng
     */
    public static function getByUserId(int $userId, ?string $email = null): array
    {
        if (self::hasUserIdColumn()) {
            $stmt = DB::pdo()->prepare("SELECT * FROM contacts WHERE user_id = :user_id ORDER BY created_at DESC");
            $stmt->execute(['user_id' => $userId]);

            return $stmt->fetchAll() ?: [];
        }

        if ($email === null || $email === '') {
            return [];
        }

        $stmt = DB::pdo()->prepare("SELECT * FROM contacts WHERE email = :email ORDER BY created_at DESC");
        $stmt->execute(['email' => $email]);

        return $stmt->fetchAll() ?: [];
    }

    /**
     * Tạo tin nhắn liên hệ mới trong cơ sở dữ liệu.
     *
     * @param int $userId ID người dùng gửi tin nhắn
     * @param string $name Tên người gửi
     * @param string $email Email người gửi
     * @param string $phone Số điện thoại người gửi
     * @param string $subject Tiêu đề tin nhắn
     * @param string $message Nội dung tin nhắn
     * @return int ID của tin nhắn liên hệ vừa tạo
     */
    public static function create(
        int $userId,
        string $name,
        string $email,
        string $phone,
        string $subject,
        string $message
    ): int {
        $pdo = DB::pdo();
        if (self::hasUserIdColumn()) {
            $stmt = $pdo->prepare(
                "INSERT INTO contacts (user_id, name, email, phone, subject, message, status, created_at, updated_at)
                 VALUES (:user_id, :name, :email, :phone, :subject, :message, :status, NOW(), NOW())"
            );
            $stmt->execute([
                'user_id' => $userId,
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'subject' => $subject,
                'message' => $message,
                'status' => self::STATUS_PENDING,
            ]);
        } else {
            $stmt = $pdo->prepare(
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
        }
        
        return (int)$pdo->lastInsertId();
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

    /**
     * Thêm phản hồi từ admin cho tin nhắn liên hệ.
     *
     * @param int $id ID tin nhắn liên hệ
     * @param string $reply Nội dung phản hồi
     * @param int $adminId ID admin trả lời
     * @return bool True nếu cập nhật thành công
     */
    public static function addReply(int $id, string $reply, int $adminId): bool
    {
        $stmt = DB::pdo()->prepare(
            "UPDATE contacts SET reply = :reply, replied_by = :adminId, replied_at = :repliedAt, status = :status WHERE id = :id"
        );
        return $stmt->execute([
            'reply' => $reply,
            'adminId' => $adminId,
            'repliedAt' => date('Y-m-d H:i:s'),
            'id' => $id,
            'status' => self::STATUS_RESPONDED,
        ]);
    }

    /**
     * Kiểm tra bảng contacts có cột user_id hay chưa.
     */
    private static function hasUserIdColumn(): bool
    {
        if (self::$hasUserIdColumn !== null) {
            return self::$hasUserIdColumn;
        }

        $stmt = DB::pdo()->prepare(
            "SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME = 'contacts'
               AND COLUMN_NAME = 'user_id'"
        );
        $stmt->execute();

        self::$hasUserIdColumn = ((int)$stmt->fetchColumn() > 0);

        return self::$hasUserIdColumn;
    }
}

