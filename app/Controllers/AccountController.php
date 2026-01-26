<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\View;
use App\Models\User;

final class AccountController {
  public function profile(): void {
    $uid = Auth::id();
    if (!$uid) { header('Location: /login'); exit; }
    $user = User::findById((int)$uid);
    View::render('account/profile', ['user' => $user]);
  }

  public function edit(): void {
    $uid = Auth::id();
    if (!$uid) { header('Location: /login'); exit; }
    $user = User::findById((int)$uid);
    View::render('account/edit', ['user' => $user, 'csrf' => Csrf::token()]);
  }

  public function update(): void {
    $uid = Auth::id();
    if (!$uid) { header('Location: /login'); exit; }
    if (!Csrf::verify($_POST['_csrf'] ?? null)) { http_response_code(419); echo 'CSRF token mismatch'; exit; }

    $name = trim((string)($_POST['name'] ?? ''));
    $email = trim((string)($_POST['email'] ?? ''));
    $phone = trim((string)($_POST['phone'] ?? ''));
    $address = trim((string)($_POST['address'] ?? ''));

    if ($name === '' || $email === '') {
      if (session_status() !== PHP_SESSION_ACTIVE) session_start();
      $_SESSION['error'] = 'Vui lòng nhập đầy đủ họ tên và email.';
      header('Location: /account/edit');
      exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      if (session_status() !== PHP_SESSION_ACTIVE) session_start();
      $_SESSION['error'] = 'Email không hợp lệ.';
      header('Location: /account/edit');
      exit;
    }

    // Kiểm tra điện thoại nếu có
    if ($phone !== '' && !preg_match('/^[0-9+\-\s]{6,20}$/', $phone)) {
      if (session_status() !== PHP_SESSION_ACTIVE) session_start();
      $_SESSION['error'] = 'Số điện thoại không hợp lệ.';
      header('Location: /account/edit');
      exit;
    }

    // Cập nhật thông tin cơ bản; ngăn ngừa email trùng lẻ
    $ok = User::updateInfo((int)$uid, $name, $email, $phone !== '' ? $phone : null, $address !== '' ? $address : null);

    // Xử lý tải lên ảnh đại diện nếu có
    $avatarChanged = false;
    if (isset($_FILES['avatar']) && is_array($_FILES['avatar']) && ($_FILES['avatar']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
      $file = $_FILES['avatar'];
      if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $_SESSION['error'] = 'Tải lên ảnh đại diện thất bại.';
        header('Location: /account/edit');
        exit;
      }
      if (($file['size'] ?? 0) > 2 * 1024 * 1024) {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $_SESSION['error'] = 'Ảnh đại diện vượt quá 2MB.';
        header('Location: /account/edit');
        exit;
      }
      $allowedExt = ['jpg','jpeg','png','gif','webp'];
      $ext = strtolower(pathinfo((string)$file['name'], PATHINFO_EXTENSION));
      if (!in_array($ext, $allowedExt, true)) {
        // cố găng phát hiện mime qua getimagesize
        $imgInfo = @getimagesize($file['tmp_name']);
        $mime = is_array($imgInfo) && isset($imgInfo['mime']) ? strtolower($imgInfo['mime']) : '';
        $mimeMap = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];
        if (isset($mimeMap[$mime])) {
          $ext = $mimeMap[$mime];
        } else {
          if (session_status() !== PHP_SESSION_ACTIVE) session_start();
          $_SESSION['error'] = 'Ảnh đại diện không hợp lệ (chỉ jpg/png/gif/webp).';
          header('Location: /account/edit');
          exit;
        }
      }
      $root = dirname(__DIR__, 2);
      $dir = $root . '/public/uploads/avatars';
      if (!is_dir($dir)) { @mkdir($dir, 0777, true); }
      $base = 'u' . ((int)$uid) . '_' . time() . '_' . bin2hex(random_bytes(4));
      $safeFile = $base . '.' . $ext;
      $dest = $dir . '/' . $safeFile;
      if (!@move_uploaded_file($file['tmp_name'], $dest)) {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $_SESSION['error'] = 'Không thể lưu ảnh đại diện.';
        header('Location: /account/edit');
        exit;
      }
      $webPath = '/uploads/avatars/' . $safeFile;
      $avatarChanged = User::updateAvatar((int)$uid, $webPath);
    }

    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    if ($ok || $avatarChanged) {
      $_SESSION['success'] = 'Cập nhật thông tin thành công.';
    } else {
      $_SESSION['error'] = 'Không có thay đổi hoặc cập nhật thất bại.';
    }
    header('Location: /account');
    exit;
  }

  public function changePassword(): void {
    $uid = Auth::id();
    if (!$uid) { header('Location: /login'); exit; }
    $user = User::findById((int)$uid);
    View::render('account/change-password', ['user' => $user, 'csrf' => Csrf::token()]);
  }

  public function updatePassword(): void {
    $uid = Auth::id();
    if (!$uid) { header('Location: /login'); exit; }
    if (!Csrf::verify($_POST['_csrf'] ?? null)) { http_response_code(419); echo 'CSRF token mismatch'; exit; }

    $current = (string)($_POST['current_password'] ?? '');
    $new = (string)($_POST['new_password'] ?? '');
    $confirm = (string)($_POST['confirm_password'] ?? '');

    if ($current === '' || $new === '' || $confirm === '') {
      if (session_status() !== PHP_SESSION_ACTIVE) session_start();
      $_SESSION['error'] = 'Vui lòng nhập đầy đủ thông tin mật khẩu.';
      header('Location: /account/change-password');
      exit;
    }

    $user = User::findById((int)$uid);
    if (!$user || !password_verify($current, $user['password_hash'])) {
      if (session_status() !== PHP_SESSION_ACTIVE) session_start();
      $_SESSION['error'] = 'Mật khẩu hiện tại không đúng.';
      header('Location: /account/change-password');
      exit;
    }

    if (strlen($new) < 6) {
      if (session_status() !== PHP_SESSION_ACTIVE) session_start();
      $_SESSION['error'] = 'Mật khẩu mới tối thiểu 6 ký tự.';
      header('Location: /account/change-password');
      exit;
    }

    if ($new !== $confirm) {
      if (session_status() !== PHP_SESSION_ACTIVE) session_start();
      $_SESSION['error'] = 'Xác nhận mật khẩu không khớp.';
      header('Location: /account/change-password');
      exit;
    }

    $pwdChanged = User::updatePassword((int)$uid, password_hash($new, PASSWORD_DEFAULT));

    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    if ($pwdChanged) {
      $_SESSION['success'] = 'Đổi mật khẩu thành công.';
    } else {
      $_SESSION['error'] = 'Đổi mật khẩu thất bại.';
    }
    header('Location: /account');
    exit;
  }
}
?>