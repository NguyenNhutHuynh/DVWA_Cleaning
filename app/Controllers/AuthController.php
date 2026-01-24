<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\View;
use App\Models\User;

final class AuthController {
  public static function showRegister(): void {
    View::render('auth/register', ['csrf' => Csrf::token(), 'error' => null]);
  }

  public static function register(): void {
    if (!Csrf::verify($_POST['_csrf'] ?? null)) {
      http_response_code(419);
      echo "CSRF token mismatch";
      exit;
    }

    $name = trim((string)($_POST['name'] ?? ''));
    $email = trim((string)($_POST['email'] ?? ''));
    $pass = (string)($_POST['password'] ?? '');
    $role = trim((string)($_POST['role'] ?? 'customer'));

    if ($name === '' || $email === '' || $pass === '') {
      View::render('auth/register', ['csrf' => Csrf::token(), 'error' => 'Vui lòng nhập đầy đủ thông tin.']);
      return;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      View::render('auth/register', ['csrf' => Csrf::token(), 'error' => 'Email không hợp lệ.']);
      return;
    }
    if (strlen($pass) < 6) {
      View::render('auth/register', ['csrf' => Csrf::token(), 'error' => 'Mật khẩu tối thiểu 6 ký tự.']);
      return;
    }
    if (!in_array($role, ['customer', 'worker'])) {
      View::render('auth/register', ['csrf' => Csrf::token(), 'error' => 'Vai trò không hợp lệ.']);
      return;
    }
    if (User::findByEmail($email)) {
      View::render('auth/register', ['csrf' => Csrf::token(), 'error' => 'Email đã tồn tại.']);
      return;
    }

    $hash = password_hash($pass, PASSWORD_DEFAULT);
    // Set approval status based on role
    $approvalStatus = ($role === 'worker') ? 'pending' : 'active';
    $id = User::create($name, $email, $hash, $role, $approvalStatus);
    Auth::login($id, $role);
    // Redirect: pending workers go to awaiting approval page
    if ($role === 'worker' && $approvalStatus === 'pending') {
      header("Location: /worker/pending");
    } else {
      header("Location: /");
    }
    exit;
  }

  public static function showLogin(): void {
    View::render('auth/login', ['csrf' => Csrf::token(), 'error' => null]);
  }

  public static function login(): void {
    if (!Csrf::verify($_POST['_csrf'] ?? null)) {
      http_response_code(419);
      echo "CSRF token mismatch";
      exit;
    }

    $email = trim((string)($_POST['email'] ?? ''));
    $pass = (string)($_POST['password'] ?? '');

    $user = User::findByEmail($email);
    if (!$user) {
      View::render('auth/login', ['csrf' => Csrf::token(), 'error' => 'Email không tồn tại.']);
      return;
    }

    if (!password_verify($pass, $user['password_hash'])) {
      View::render('auth/login', ['csrf' => Csrf::token(), 'error' => 'Mật khẩu không đúng.']);
      return;
    }

    // If worker not active, redirect to pending info
    $role = $user['role'] === 'cleaner' ? 'worker' : $user['role'];
    $status = isset($user['approval_status']) ? (string)$user['approval_status'] : 'active';
    Auth::login((int)$user['id'], (string)$role);
    
    switch ($role) {
      case 'admin':
        header("Location: /admin/dashboard");
        break;
      case 'worker':
        if ($status !== 'active') {
          header("Location: /worker/pending");
        } else {
          header("Location: /worker/dashboard");
        }
        break;
      case 'customer':
      default:
        header("Location: /customer/dashboard");
        break;
    }
    exit;
  }

  public static function logout(): void {
    Auth::logout();
    header("Location: /");
    exit;
  }
}
?>