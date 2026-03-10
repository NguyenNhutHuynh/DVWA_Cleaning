<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\DB;
use PDO;
use PDOException;

/**
 * Model User dùng để quản lý tài khoản và xác thực người dùng.
 * Xử lý tạo mới, truy vấn, cập nhật và luồng duyệt tài khoản.
 */
final class User
{
    // Các cột dữ liệu dùng chung khi truy vấn người dùng
    private const BASE_USER_COLUMNS = 'id, name, email, phone, address, avatar, COALESCE(password_hash, password) AS password_hash, role, approval_status, approved_by, approved_at, reject_reason';

    // Các trạng thái tài khoản người dùng
    public const STATUS_ACTIVE = 'active';
    public const STATUS_PENDING = 'pending';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_LOCKED = 'locked';
    public const STATUS_DELETED = 'deleted';

    // Các vai trò người dùng
    public const ROLE_CUSTOMER = 'customer';
    public const ROLE_WORKER = 'worker';
    public const ROLE_ADMIN = 'admin';

    /**
     * Tìm người dùng theo ID.
     *
     * @param int $id ID người dùng
     * @return array|null Dữ liệu người dùng hoặc null nếu không tìm thấy
     */
    public static function findById(int $id): ?array
    {
        $stmt = DB::pdo()->prepare(
            "SELECT " . self::BASE_USER_COLUMNS . " FROM users WHERE id = :id LIMIT 1"
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Tìm người dùng theo địa chỉ email.
     *
     * @param string $email Email cần tìm
     * @return array|null Dữ liệu người dùng hoặc null nếu không tìm thấy
     */
    public static function findByEmail(string $email): ?array
    {
        $stmt = DB::pdo()->prepare(
            "SELECT " . self::BASE_USER_COLUMNS . " FROM users WHERE email = :email LIMIT 1"
        );
        $stmt->execute(['email' => $email]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Tạo tài khoản người dùng mới.
     *
     * @param string $name Tên hiển thị
     * @param string $email Địa chỉ email
     * @param string $passwordHash Mật khẩu đã băm
     * @param string $role Vai trò (customer, worker, admin)
     * @param string $approvalStatus Trạng thái duyệt ban đầu
     * @return int ID người dùng vừa tạo
     */
    public static function create(
        string $name,
        string $email,
        string $passwordHash,
        string $role = self::ROLE_CUSTOMER,
        string $approvalStatus = self::STATUS_ACTIVE,
        ?string $phone = null,
        ?string $address = null
    ): int {
        $payload = [
            'name' => $name,
            'email' => $email,
            'hash' => $passwordHash,
            'role' => $role,
            'approval_status' => $approvalStatus,
            'phone' => $phone,
            'address' => $address,
        ];

        try {
            $stmt = DB::pdo()->prepare(
                "INSERT INTO users (name, email, phone, address, password_hash, password, role, approval_status)
                 VALUES (:name, :email, :phone, :address, :hash, :hash, :role, :approval_status)"
            );
            $stmt->execute($payload);
        } catch (PDOException $exception) {
            try {
                $stmt = DB::pdo()->prepare(
                    "INSERT INTO users (name, email, phone, address, password_hash, role, approval_status)
                     VALUES (:name, :email, :phone, :address, :hash, :role, :approval_status)"
                );
                $stmt->execute($payload);
            } catch (PDOException $secondaryException) {
                $stmt = DB::pdo()->prepare(
                    "INSERT INTO users (name, email, phone, address, password, role, approval_status)
                     VALUES (:name, :email, :phone, :address, :hash, :role, :approval_status)"
                );
                $stmt->execute($payload);
            }
        }

        return (int)DB::pdo()->lastInsertId();
    }

    /**
     * Cập nhật thông tin cơ bản của người dùng (tên, email, điện thoại, địa chỉ).
     * Đảm bảo email là duy nhất trong hệ thống.
     *
     * @param int $id ID người dùng cần cập nhật
     * @param string $name Tên hiển thị mới
     * @param string $email Email mới
     * @param string|null $phone Số điện thoại mới
     * @param string|null $address Địa chỉ mới
     * @return bool True nếu cập nhật thành công, false nếu email đã tồn tại
     */
    public static function updateInfo(
        int $id,
        string $name,
        string $email,
        ?string $phone,
        ?string $address
    ): bool {
        // Kiểm tra email có đang thuộc về người dùng khác hay không
        $stmt = DB::pdo()->prepare("SELECT id FROM users WHERE email = :email AND id <> :id LIMIT 1");
        $stmt->execute(['email' => $email, 'id' => $id]);
        if ($stmt->fetch()) {
            return false;
        }

        $stmt = DB::pdo()->prepare(
            "UPDATE users SET name = :name, email = :email, phone = :phone, address = :address WHERE id = :id"
        );
        return $stmt->execute([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'id' => $id,
        ]);
    }

    /**
     * Cập nhật toàn bộ trường có thể chỉnh sửa từ trang quản trị.
     *
     * @param int $id ID người dùng
     * @param string $name Tên người dùng
     * @param string $email Email người dùng
     * @param string|null $phone Số điện thoại
     * @param string|null $address Địa chỉ
     * @param string $role Vai trò
     * @param string $approvalStatus Trạng thái tài khoản
     * @param string|null $rejectReason Lý do từ chối/khóa/xóa
     * @return bool True nếu cập nhật thành công, false nếu email đã tồn tại
     */
    public static function updateAdminEditable(
        int $id,
        string $name,
        string $email,
        ?string $phone,
        ?string $address,
        string $role,
        string $approvalStatus,
        ?string $rejectReason
    ): bool {
        $stmt = DB::pdo()->prepare("SELECT id FROM users WHERE email = :email AND id <> :id LIMIT 1");
        $stmt->execute(['email' => $email, 'id' => $id]);
        if ($stmt->fetch()) {
            return false;
        }

        $stmt = DB::pdo()->prepare(
            "UPDATE users
             SET name = :name,
                 email = :email,
                 phone = :phone,
                 address = :address,
                 role = :role,
                 approval_status = :approval_status,
                 reject_reason = :reject_reason
             WHERE id = :id"
        );

        return $stmt->execute([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'role' => $role,
            'approval_status' => $approvalStatus,
            'reject_reason' => $rejectReason,
            'id' => $id,
        ]);
    }

    /**
     * Cập nhật mật khẩu đã băm của người dùng.
     *
     * @param int $id ID người dùng
     * @param string $passwordHash Mật khẩu băm mới
     * @return bool True nếu cập nhật thành công
     */
    public static function updatePassword(int $id, string $passwordHash): bool
    {
        $stmt = DB::pdo()->prepare("UPDATE users SET password_hash = :hash WHERE id = :id");
        return $stmt->execute(['hash' => $passwordHash, 'id' => $id]);
    }

    /**
     * Cập nhật đường dẫn ảnh đại diện của người dùng.
     *
     * @param int $id ID người dùng
     * @param string|null $avatarPath Đường dẫn avatar mới hoặc null để xóa
     * @return bool True nếu cập nhật thành công
     */
    public static function updateAvatar(int $id, ?string $avatarPath): bool
    {
        $stmt = DB::pdo()->prepare("UPDATE users SET avatar = :avatar WHERE id = :id");
        return $stmt->execute(['avatar' => $avatarPath, 'id' => $id]);
    }

    /**
     * Lấy danh sách toàn bộ người dùng (thông tin cơ bản) cho trang quản trị.
     *
     * @return array Danh sách người dùng [id, name, email, role, approval_status]
     */
    public static function listAll(): array
    {
        $stmt = DB::pdo()->query(
            "SELECT id, name, email, role, approval_status FROM users ORDER BY id DESC"
        );
        return $stmt->fetchAll() ?: [];
    }

    /**
     * Lấy toàn bộ người dùng với thông tin đầy đủ (cho trang quản trị).
     *
     * @return array Danh sách người dùng với dữ liệu đầy đủ
     */
    public static function getAllUsers(): array
    {
        $stmt = DB::pdo()->prepare(
            "SELECT id, name, email, role, approval_status, reject_reason, created_at FROM users ORDER BY created_at DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll() ?: [];
    }

    /**
     * Lấy danh sách nhân viên đang chờ duyệt.
     *
     * @return array Danh sách yêu cầu đăng ký worker đang chờ duyệt
     */
    public static function listPendingWorkers(): array
    {
        $stmt = DB::pdo()->prepare(
            "SELECT " . self::BASE_USER_COLUMNS . " FROM users 
             WHERE role = :role AND approval_status = :status 
             ORDER BY id DESC"
        );
        $stmt->execute([
            'role' => self::ROLE_WORKER,
            'status' => self::STATUS_PENDING,
        ]);
        return $stmt->fetchAll() ?: [];
    }

    /**
     * Duyệt tài khoản worker đang chờ xử lý.
     *
     * @param int $userId ID người dùng worker
     * @param int $adminId ID quản trị viên thực hiện duyệt
     * @return bool True nếu duyệt thành công
     */
    public static function approveWorker(int $userId, int $adminId): bool
    {
        $stmt = DB::pdo()->prepare(
            "UPDATE users 
             SET approval_status = :active_status, approved_by = :adminId, approved_at = NOW(), reject_reason = NULL 
             WHERE id = :id AND role = :role AND approval_status = :pending_status"
        );
        return $stmt->execute([
            'active_status' => self::STATUS_ACTIVE,
            'adminId' => $adminId,
            'id' => $userId,
            'role' => self::ROLE_WORKER,
            'pending_status' => self::STATUS_PENDING,
        ]);
    }

    /**
     * Từ chối tài khoản worker đang chờ xử lý.
     *
     * @param int $userId ID người dùng worker
     * @param int $adminId ID quản trị viên thực hiện từ chối
     * @param string|null $reason Lý do từ chối (tùy chọn)
     * @return bool True nếu từ chối thành công
     */
    public static function rejectWorker(int $userId, int $adminId, ?string $reason): bool
    {
        $stmt = DB::pdo()->prepare(
            "UPDATE users 
             SET approval_status = :rejected_status, approved_by = :adminId, approved_at = NOW(), reject_reason = :reason 
             WHERE id = :id AND role = :role AND approval_status IN (:pending_status, :rejected_status)"
        );
        return $stmt->execute([
            'rejected_status' => self::STATUS_REJECTED,
            'adminId' => $adminId,
            'reason' => $reason,
            'id' => $userId,
            'role' => self::ROLE_WORKER,
            'pending_status' => self::STATUS_PENDING,
        ]);
    }

    /**
     * Thiết lập trạng thái duyệt cho người dùng.
     *
     * @param int $userId ID người dùng
     * @param string $status Trạng thái duyệt mới
     * @return bool True nếu cập nhật thành công
     */
    public static function setStatus(int $userId, string $status): bool
    {
        $stmt = DB::pdo()->prepare("UPDATE users SET approval_status = :status WHERE id = :id");
        return $stmt->execute(['status' => $status, 'id' => $userId]);
    }

    /**
     * Thiết lập trạng thái duyệt và lý do cho người dùng.
     *
     * @param int $userId ID người dùng
     * @param string $status Trạng thái duyệt mới
     * @param string|null $reason Lý do thay đổi trạng thái (tùy chọn)
     * @return bool True nếu cập nhật thành công
     */
    public static function setStatusAndReason(int $userId, string $status, ?string $reason): bool
    {
        $stmt = DB::pdo()->prepare(
            "UPDATE users SET approval_status = :status, reject_reason = :reason WHERE id = :id"
        );
        return $stmt->execute(['status' => $status, 'reason' => $reason, 'id' => $userId]);
    }

    /**
     * Khóa tài khoản người dùng với lý do tùy chọn.
     *
     * @param int $id ID người dùng
     * @param string $reason Lý do khóa tài khoản
     * @return bool True nếu khóa thành công
     */
    public static function lockUser(int $id, string $reason): bool
    {
        return self::setStatusAndReason($id, self::STATUS_LOCKED, $reason);
    }

    /**
     * Mở khóa tài khoản người dùng.
     *
     * @param int $id ID người dùng
     * @return bool True nếu mở khóa thành công
     */
    public static function unlockUser(int $id): bool
    {
        return self::setStatusAndReason($id, self::STATUS_ACTIVE, null);
    }

    /**
     * Xóa người dùng khỏi cơ sở dữ liệu.
     *
     * @param int $userId ID người dùng cần xóa
     * @return bool True nếu xóa thành công
     */
    public static function delete(int $userId): bool
    {
        $stmt = DB::pdo()->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute(['id' => $userId]);
    }

    /**
     * Cập nhật đầy đủ hồ sơ người dùng.
     * Đây là hàm tiện ích để cập nhật đồng thời nhiều trường.
     *
     * @param int $id ID người dùng
     * @param string $name Tên hiển thị
     * @param string $email Địa chỉ email
     * @param string $role Vai trò người dùng
     * @param string $approvalStatus Trạng thái duyệt
     * @return bool True nếu cập nhật thành công
     */
    public static function updateUser(
        int $id,
        string $name,
        string $email,
        string $role,
        string $approvalStatus
    ): bool {
        $stmt = DB::pdo()->prepare(
            "UPDATE users SET name = :name, email = :email, role = :role, approval_status = :status WHERE id = :id"
        );
        return $stmt->execute([
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'status' => $approvalStatus,
            'id' => $id,
        ]);
    }
}
