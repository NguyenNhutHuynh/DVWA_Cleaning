<?php
namespace App\Models;

use App\Core\DB;

final class User {
  public static function findById(int $id): ?array {
    $stmt = DB::pdo()->prepare("SELECT id, name, email, password_hash, role, approval_status, approved_by, approved_at, reject_reason FROM users WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();
    return $row ?: null;
  }

  public static function findByEmail(string $email): ?array {
    $stmt = DB::pdo()->prepare("SELECT id, name, email, password_hash, role, approval_status, approved_by, approved_at, reject_reason FROM users WHERE email = :email LIMIT 1");
    $stmt->execute(['email' => $email]);
    $row = $stmt->fetch();
    return $row ?: null;
  }

  public static function create(string $name, string $email, string $hash, string $role = 'customer', string $approvalStatus = 'active'): int {
    $stmt = DB::pdo()->prepare("INSERT INTO users(name, email, password_hash, role, approval_status) VALUES(:name, :email, :hash, :role, :approval_status)");
    $stmt->execute(['name' => $name, 'email' => $email, 'hash' => $hash, 'role' => $role, 'approval_status' => $approvalStatus]);
    return (int)DB::pdo()->lastInsertId();
  }

  /**
   * List all users (basic fields) for admin view
   * @return array<int,array>
   */
  public static function listAll(): array {
    $stmt = DB::pdo()->query("SELECT id, name, email, role, approval_status FROM users ORDER BY id DESC");
    return $stmt->fetchAll() ?: [];
  }

  /**
   * List pending worker applications
   * @return array<int,array>
   */
  public static function listPendingWorkers(): array {
    $stmt = DB::pdo()->prepare("SELECT id, name, email, role, approval_status, approved_by, approved_at, reject_reason FROM users WHERE role = 'worker' AND approval_status = 'pending' ORDER BY id DESC");
    $stmt->execute();
    return $stmt->fetchAll() ?: [];
  }

  /** Approve a worker application */
  public static function approveWorker(int $userId, int $adminId): bool {
    $stmt = DB::pdo()->prepare("UPDATE users SET approval_status='active', approved_by=:adminId, approved_at=NOW(), reject_reason=NULL WHERE id=:id AND role='worker' AND approval_status='pending'");
    return $stmt->execute(['adminId' => $adminId, 'id' => $userId]);
  }

  /** Reject a worker application */
  public static function rejectWorker(int $userId, int $adminId, ?string $reason): bool {
    $stmt = DB::pdo()->prepare("UPDATE users SET approval_status='rejected', approved_by=:adminId, approved_at=NOW(), reject_reason=:reason WHERE id=:id AND role='worker' AND approval_status IN ('pending','rejected')");
    return $stmt->execute(['adminId' => $adminId, 'id' => $userId, 'reason' => $reason]);
  }
}
?>