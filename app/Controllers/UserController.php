<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Models\User;

final class UserController {
  
  public static function adminUsers(): void {
    // Chỉ các quản trị viên mới có thể truy cập
    if (!Auth::isLoggedIn() || Auth::role() !== 'admin') {
      http_response_code(403);
      echo "Access denied";
      exit;
    }

    $users = User::getAllUsers();
    View::render('admin/users', ['users' => $users]);
  }

  public static function updateUser(): void {
    if (!Auth::isLoggedIn() || Auth::role() !== 'admin') {
      http_response_code(403);
      echo "Access denied";
      exit;
    }

    $id = (int)($_POST['id'] ?? 0);
    $name = trim((string)($_POST['name'] ?? ''));
    $email = trim((string)($_POST['email'] ?? ''));
    $role = trim((string)($_POST['role'] ?? ''));
    $approvalStatus = trim((string)($_POST['approval_status'] ?? ''));

    if ($id <= 0 || $name === '' || $email === '') {
      http_response_code(400);
      echo "Invalid input";
      exit;
    }

    $user = User::findById($id);
    if (!$user) {
      http_response_code(404);
      echo "User not found";
      exit;
    }

    User::updateUser($id, $name, $email, $role, $approvalStatus);
    
    header("Location: /admin/users");
    exit;
  }

  public static function lockUser(): void {
    if (!Auth::isLoggedIn() || Auth::role() !== 'admin') {
      http_response_code(403);
      echo "Access denied";
      exit;
    }

    $id = (int)($_POST['id'] ?? 0);
    $reason = trim((string)($_POST['reason'] ?? 'Tài khoản bị khóa bởi quản trị viên'));

    if ($id <= 0) {
      http_response_code(400);
      echo "Invalid user ID";
      exit;
    }

    User::lockUser($id, $reason);
    
    header("Location: /admin/users");
    exit;
  }

  public static function unlockUser(): void {
    if (!Auth::isLoggedIn() || Auth::role() !== 'admin') {
      http_response_code(403);
      echo "Access denied";
      exit;
    }

    $id = (int)($_POST['id'] ?? 0);

    if ($id <= 0) {
      http_response_code(400);
      echo "Invalid user ID";
      exit;
    }

    User::unlockUser($id);
    
    header("Location: /admin/users");
    exit;
  }

  public static function deleteUser(): void {
    if (!Auth::isLoggedIn() || Auth::role() !== 'admin') {
      http_response_code(403);
      echo "Access denied";
      exit;
    }

    $id = (int)($_POST['id'] ?? 0);

    if ($id <= 0) {
      http_response_code(400);
      echo "Invalid user ID";
      exit;
    }

    User::deleteUser($id);
    
    header("Location: /admin/users");
    exit;
  }
}
?>
