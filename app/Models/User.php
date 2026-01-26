<?php
namespace App\Models;

use App\Core\DB;

final class User {
  public static function findById(int $id): ?array {
    $stmt = DB::pdo()->prepare("SELECT id, name, email, phone, address, avatar, password_hash, role, approval_status, approved_by, approved_at, reject_reason FROM users WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();
    return $row ?: null;
  }

  public static function findByEmail(string $email): ?array {
    $stmt = DB::pdo()->prepare("SELECT id, name, email, phone, address, avatar, password_hash, role, approval_status, approved_by, approved_at, reject_reason FROM users WHERE email = :email LIMIT 1");
    $stmt->execute(['email' => $email]);
    $row = $stmt->fetch();
    return $row ?: null;
  }

  public static function create(string $name, string $email, string $hash, string $role = 'customer', string $approvalStatus = 'active'): int {
    $stmt = DB::pdo()->prepare("INSERT INTO users(name, email, password_hash, role, approval_status) VALUES(:name, :email, :hash, :role, :approval_status)");
    $stmt->execute(['name' => $name, 'email' => $email, 'hash' => $hash, 'role' => $role, 'approval_status' => $approvalStatus]);
    return (int)DB::pdo()->lastInsertId();
  }

  /** Cập nhật tên hiển thị, email, điện thoại và địa chỉ (đảm bảo email độc nhất) */
  public static function updateInfo(int $id, string $name, string $email, ?string $phone, ?string $address): bool {
    // Kiểm tra xem email có thuộc về người dùng khác không
    $stmt = DB::pdo()->prepare("SELECT id FROM users WHERE email = :email AND id <> :id LIMIT 1");
    $stmt->execute(['email' => $email, 'id' => $id]);
    $exists = $stmt->fetch();
    if ($exists) return false;

    $stmt2 = DB::pdo()->prepare("UPDATE users SET name = :name, email = :email, phone = :phone, address = :address WHERE id = :id");
    return $stmt2->execute(['name' => $name, 'email' => $email, 'phone' => $phone, 'address' => $address, 'id' => $id]);
  }

  /** Cập nhật mã băm mật khẩu */
  public static function updatePassword(int $id, string $hash): bool {
    $stmt = DB::pdo()->prepare("UPDATE users SET password_hash = :hash WHERE id = :id");
    return $stmt->execute(['hash' => $hash, 'id' => $id]);
  }

  /** Cập nhật đường dẫn ảnh đại diện */
  public static function updateAvatar(int $id, ?string $avatar): bool {
    $stmt = DB::pdo()->prepare("UPDATE users SET avatar = :avatar WHERE id = :id");
    return $stmt->execute(['avatar' => $avatar, 'id' => $id]);
  }

  /**
   * Liệt kê tất cả người dùng (các trường cơ bản) cho lịch xem admin
   * @return array<int,array>
   */
  public static function listAll(): array {
    $stmt = DB::pdo()->query("SELECT id, name, email, role, approval_status FROM users ORDER BY id DESC");
    return $stmt->fetchAll() ?: [];
  }

  /**
   * Liệt kê các đơn đăng ký công nhân đang chờ xử lý
   * @return array<int,array>
   */
  public static function listPendingWorkers(): array {
    $stmt = DB::pdo()->prepare("SELECT id, name, email, role, approval_status, approved_by, approved_at, reject_reason FROM users WHERE role = 'worker' AND approval_status = 'pending' ORDER BY id DESC");
    $stmt->execute();
    return $stmt->fetchAll() ?: [];
  }

  /** Phê duyệt đơn đăng ký công nhân */
  public static function approveWorker(int $userId, int $adminId): bool {
    $stmt = DB::pdo()->prepare("UPDATE users SET approval_status='active', approved_by=:adminId, approved_at=NOW(), reject_reason=NULL WHERE id=:id AND role='worker' AND approval_status='pending'");
    return $stmt->execute(['adminId' => $adminId, 'id' => $userId]);
  }

  /** Từ chối đơn đăng ký công nhân */
  public static function rejectWorker(int $userId, int $adminId, ?string $reason): bool {
    $stmt = DB::pdo()->prepare("UPDATE users SET approval_status='rejected', approved_by=:adminId, approved_at=NOW(), reject_reason=:reason WHERE id=:id AND role='worker' AND approval_status IN ('pending','rejected')");
    return $stmt->execute(['adminId' => $adminId, 'id' => $userId, 'reason' => $reason]);
  }

  /** Đặt trạng thái phê duyệt cho người dùng tùy ý (ví dụ: khóa/mở khóa) */
  public static function setStatus(int $userId, string $status): bool {
    $stmt = DB::pdo()->prepare("UPDATE users SET approval_status = :status WHERE id = :id");
    return $stmt->execute(['status' => $status, 'id' => $userId]);
  }

  /** Đặt trạng thái và lý do tùy chọn (lưu trữ trong reject_reason chung) */
  public static function setStatusAndReason(int $userId, string $status, ?string $reason): bool {
    $stmt = DB::pdo()->prepare("UPDATE users SET approval_status = :status, reject_reason = :reason WHERE id = :id");
    return $stmt->execute(['status' => $status, 'reason' => $reason, 'id' => $userId]);
  }

  /** Xóa người dùng */
  public static function delete(int $userId): bool {
    $stmt = DB::pdo()->prepare("DELETE FROM users WHERE id = :id");
    return $stmt->execute(['id' => $userId]);
  }

  public static function getAllUsers(): array {
    $stmt = DB::pdo()->prepare("SELECT id, name, email, role, approval_status, reject_reason, created_at FROM users ORDER BY created_at DESC");
    $stmt->execute();
    return $stmt->fetchAll() ?: [];
  }

  

  public static function updateUser(int $id, string $name, string $email, string $role, string $approvalStatus): bool {
    $stmt = DB::pdo()->prepare("UPDATE users SET name = ?, email = ?, role = ?, approval_status = ? WHERE id = ?");
    return $stmt->execute([$name, $email, $role, $approvalStatus, $id]);
  }

  public static function lockUser(int $id, string $reason): bool {
    $stmt = DB::pdo()->prepare("UPDATE users SET approval_status = 'locked', reject_reason = ? WHERE id = ?");
    return $stmt->execute([$reason, $id]);
  }

  public static function unlockUser(int $id): bool {
    $stmt = DB::pdo()->prepare("UPDATE users SET approval_status = 'active', reject_reason = NULL WHERE id = ?");
    return $stmt->execute([$id]);
  }

  public static function deleteUser(int $id): bool {
    $stmt = DB::pdo()->prepare("DELETE FROM users WHERE id = ?");
    return $stmt->execute([$id]);
  }
}
?>