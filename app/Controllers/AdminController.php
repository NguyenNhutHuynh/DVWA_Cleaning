<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\View;
use App\Models\Service;
use App\Models\Booking;
use App\Models\Contact;
use App\Models\User;

final class AdminController {
  private function guard(): void {
    if (!Auth::id() || Auth::role() !== 'admin') {
      header("Location: /login");
      exit;
    }
  }

  public function dashboard(): void {
    $this->guard();
    $me = User::findById((int)Auth::id());
    View::render('admin/dashboard', [
      'uid' => Auth::id(),
      'role' => Auth::role(),
      'name' => $me['name'] ?? 'Admin',
    ]);
  }

  public function services(): void {
    $this->guard();
    $services = Service::listAllAdmin();
    View::render('admin/services', [
      'services' => $services,
      'csrf' => \App\Core\Csrf::token(),
    ]);
  }

  public function bookings(): void {
    $this->guard();
    $bookings = Booking::getAll();
    // Liệt kê các công nhân hoạt động để phân công
    $allUsers = User::listAll();
    $workers = array_values(array_filter($allUsers, fn($u) => ($u['role'] ?? '') === 'worker' && ($u['approval_status'] ?? '') === 'active'));
    View::render('admin/bookings', [
      'bookings' => $bookings,
      'workers' => $workers,
      'csrf' => Csrf::token(),
    ]);
  }

  public function moderation(): void {
    $this->guard();
    $contacts = Contact::getAll();
    View::render('admin/moderation', ['contacts' => $contacts]);
  }

  public function users(): void {
    $this->guard();
    $users = User::listAll();
    $pendingWorkers = User::listPendingWorkers();
    View::render('admin/users', [
      'users' => $users,
      'pendingWorkers' => $pendingWorkers,
      'csrf' => Csrf::token(),
    ]);
  }

  public function userDetail(): void {
    $this->guard();
    $id = (int)($_GET['id'] ?? 0);
    if ($id <= 0) { header('Location: /admin/users'); exit; }
    $u = User::findById($id);
    if (!$u) { header('Location: /admin/users'); exit; }
    $approverName = null;
    if (!empty($u['approved_by'])) {
      $ap = User::findById((int)$u['approved_by']);
      if ($ap) $approverName = $ap['name'] ?? null;
    }
    $u['approved_by_name'] = $approverName;
    View::render('admin/user_detail', ['user' => $u, 'csrf' => Csrf::token()]);
  }

  /** Chi tiết JSON nhẹ nhòm cho hiển thị/modal đường dành */
  public function userDetailJson(): void {
    $this->guard();
    header('Content-Type: application/json; charset=utf-8');
    $id = (int)($_GET['id'] ?? 0);
    if ($id <= 0) {
      http_response_code(400);
      echo json_encode(['error' => 'invalid_id'], JSON_UNESCAPED_UNICODE);
      return;
    }
    $u = User::findById($id);
    if (!$u) {
      http_response_code(404);
      echo json_encode(['error' => 'not_found'], JSON_UNESCAPED_UNICODE);
      return;
    }
    // Chỉ kế khai các trường an toàn
    $out = [
      'id' => (int)$u['id'],
      'name' => (string)($u['name'] ?? ''),
      'email' => (string)($u['email'] ?? ''),
      'phone' => $u['phone'] ?? null,
      'address' => $u['address'] ?? null,
      'avatar' => $u['avatar'] ?? null,
      'role' => (string)($u['role'] ?? ''),
      'approval_status' => (string)($u['approval_status'] ?? ''),
      'approved_by' => $u['approved_by'] ?? null,
      'approved_at' => $u['approved_at'] ?? null,
      'reject_reason' => $u['reject_reason'] ?? null,
    ];
    if (!empty($u['approved_by'])) {
      $ap = User::findById((int)$u['approved_by']);
      if ($ap) $out['approved_by_name'] = $ap['name'] ?? null;
    }
    echo json_encode($out, JSON_UNESCAPED_UNICODE);
  }

  public function userUpdate(): void {
    $this->guard();
    if (!Csrf::verify($_POST['_csrf'] ?? null)) { http_response_code(419); echo 'CSRF token mismatch'; exit; }
    $id = (int)($_POST['id'] ?? 0);
    if ($id <= 0) { header('Location: /admin/users'); exit; }
    $name = trim((string)($_POST['name'] ?? ''));
    $email = trim((string)($_POST['email'] ?? ''));
    $phone = trim((string)($_POST['phone'] ?? ''));
    $address = trim((string)($_POST['address'] ?? ''));
    if ($name === '' || $email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
      if (session_status() !== PHP_SESSION_ACTIVE) session_start();
      $_SESSION['error'] = 'Tên và email hợp lệ là bắt buộc.';
      header('Location: /admin/user?id=' . $id);
      exit;
    }
    if ($phone !== '' && !preg_match('/^[0-9+\-\s]{6,20}$/', $phone)) {
      if (session_status() !== PHP_SESSION_ACTIVE) session_start();
      $_SESSION['error'] = 'Số điện thoại không hợp lệ.';
      header('Location: /admin/user?id=' . $id);
      exit;
    }
    // Cập nhật thông tin cơ bản
    $ok = User::updateInfo($id, $name, $email, $phone !== '' ? $phone : null, $address !== '' ? $address : null);
    // Tải lên ảnh đại diện tùy chọn
    $avatarChanged = false;
    if (isset($_FILES['avatar']) && is_array($_FILES['avatar']) && ($_FILES['avatar']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
      $file = $_FILES['avatar'];
      if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $_SESSION['error'] = 'Tải lên ảnh đại diện thất bại.';
        header('Location: /admin/user?id=' . $id);
        exit;
      }
      if (($file['size'] ?? 0) > 2 * 1024 * 1024) {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $_SESSION['error'] = 'Ảnh đại diện vượt quá 2MB.';
        header('Location: /admin/user?id=' . $id);
        exit;
      }
      $allowedExt = ['jpg','jpeg','png','gif','webp'];
      $ext = strtolower(pathinfo((string)$file['name'], PATHINFO_EXTENSION));
      if (!in_array($ext, $allowedExt, true)) {
        $imgInfo = @getimagesize($file['tmp_name']);
        $mime = is_array($imgInfo) && isset($imgInfo['mime']) ? strtolower($imgInfo['mime']) : '';
        $mimeMap = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];
        if (isset($mimeMap[$mime])) { $ext = $mimeMap[$mime]; } else {
          if (session_status() !== PHP_SESSION_ACTIVE) session_start();
          $_SESSION['error'] = 'Ảnh đại diện không hợp lệ (chỉ jpg/png/gif/webp).';
          header('Location: /admin/user?id=' . $id);
          exit;
        }
      }
      $root = dirname(__DIR__, 2);
      $dir = $root . '/public/uploads/avatars';
      if (!is_dir($dir)) { @mkdir($dir, 0777, true); }
      $base = 'u' . $id . '_' . time() . '_' . bin2hex(random_bytes(4));
      $safeFile = $base . '.' . $ext;
      $dest = $dir . '/' . $safeFile;
      if (!@move_uploaded_file($file['tmp_name'], $dest)) {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $_SESSION['error'] = 'Không thể lưu ảnh đại diện.';
        header('Location: /admin/user?id=' . $id);
        exit;
      }
      $webPath = '/uploads/avatars/' . $safeFile;
      $avatarChanged = User::updateAvatar($id, $webPath);
    }
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    if ($ok || $avatarChanged) {
      $_SESSION['success'] = 'Đã cập nhật thông tin người dùng #' . $id . '.';
    } else {
      $_SESSION['error'] = 'Không có thay đổi hoặc cập nhật thất bại.';
    }
    header('Location: /admin/user?id=' . $id);
    exit;
  }

  public function userLock(): void {
    $this->guard();
    if (!Csrf::verify($_POST['_csrf'] ?? null)) { http_response_code(419); echo 'CSRF token mismatch'; exit; }
    $id = (int)($_POST['id'] ?? 0);
    $reason = trim((string)($_POST['reason'] ?? ''));
    if ($id <= 0) { header('Location: /admin/users'); exit; }
    User::setStatusAndReason($id, 'locked', $reason !== '' ? $reason : null);
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    $_SESSION['success'] = 'Đã khóa tài khoản #' . $id . '.';
    header('Location: /admin/user?id=' . $id);
    exit;
  }

  public function userUnlock(): void {
    $this->guard();
    if (!Csrf::verify($_POST['_csrf'] ?? null)) { http_response_code(419); echo 'CSRF token mismatch'; exit; }
    $id = (int)($_POST['id'] ?? 0);
    if ($id <= 0) { header('Location: /admin/users'); exit; }
    User::setStatusAndReason($id, 'active', null);
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    $_SESSION['success'] = 'Đã mở khóa tài khoản #' . $id . '.';
    header('Location: /admin/user?id=' . $id);
    exit;
  }

  public function userDelete(): void {
    $this->guard();
    if (!Csrf::verify($_POST['_csrf'] ?? null)) { http_response_code(419); echo 'CSRF token mismatch'; exit; }
    $id = (int)($_POST['id'] ?? 0);
    $reason = trim((string)($_POST['reason'] ?? ''));
    if ($id <= 0) { header('Location: /admin/users'); exit; }
    // Xóa mềm: đánh dấu là đã xóa và lưu lửa do
    $ok = User::setStatusAndReason($id, 'deleted', $reason !== '' ? $reason : null);
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    if ($ok) {
      $_SESSION['success'] = 'Đã xóa người dùng #' . $id . ' thành công.';
    } else {
      $_SESSION['error'] = 'Không thể xóa người dùng #' . $id . '.';
    }
    header('Location: /admin/users');
    exit;
  }

  public function stats(): void {
    $this->guard();
    $services = Service::all();
    $bookings = Booking::getAll();
    $contacts = Contact::getAll();
    $stats = [
      'service_count' => count($services),
      'booking_count' => count($bookings),
      'contact_count' => count($contacts),
      'confirmed_rate' => round((count(array_filter($bookings, fn($b)=>$b['status']==='confirmed'))/max(count($bookings),1))*100, 1),
      'pending_rate' => round((count(array_filter($bookings, fn($b)=>$b['status']==='pending'))/max(count($bookings),1))*100, 1),
    ];
    View::render('admin/stats', ['stats' => $stats]);
  }

  public function approveWorker(): void {
    $this->guard();
    if (!Csrf::verify($_POST['_csrf'] ?? null)) {
      http_response_code(419);
      echo 'CSRF token mismatch';
      exit;
    }
    $id = (int)($_POST['id'] ?? 0);
    if ($id <= 0) {
      header('Location: /admin/users');
      exit;
    }
    User::approveWorker($id, (int)Auth::id());
    header('Location: /admin/users');
    exit;
  }

  public function rejectWorker(): void {
    $this->guard();
    if (!Csrf::verify($_POST['_csrf'] ?? null)) {
      http_response_code(419);
      echo 'CSRF token mismatch';
      exit;
    }
    $id = (int)($_POST['id'] ?? 0);
    $reason = trim((string)($_POST['reason'] ?? ''));
    if ($id <= 0) {
      header('Location: /admin/users');
      exit;
    }
    User::rejectWorker($id, (int)Auth::id(), $reason !== '' ? $reason : null);
    header('Location: /admin/users');
    exit;
  }

  public function updateService(): void {
    $this->guard();
    if (!Csrf::verify($_POST['_csrf'] ?? null)) {
      http_response_code(419);
      echo 'CSRF token mismatch';
      exit;
    }
    $id = (int)($_POST['id'] ?? 0);
    if ($id <= 0) { header('Location: /admin/services'); exit; }
    $payload = [
      'name' => trim((string)($_POST['name'] ?? '')),
      'description' => trim((string)($_POST['description'] ?? '')),
      'icon' => trim((string)($_POST['icon'] ?? '')),
      'duration' => trim((string)($_POST['duration'] ?? '')),
      'price' => (int)($_POST['price'] ?? 0),
      'unit' => trim((string)($_POST['unit'] ?? '')),
      'minimum' => (int)($_POST['minimum'] ?? 0),
    ];
    // Loại bỏ các giá trị trống để tránh xóa vô tình
    foreach ($payload as $k => $v) {
      if ($k === 'price' || $k === 'minimum') continue; // cho phép số không
      if ($v === '') unset($payload[$k]);
    }
    Service::update($id, $payload);
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    $_SESSION['success'] = 'Cập nhật dịch vụ #' . $id . ' thành công.';
    header('Location: /admin/services');
    exit;
  }

  public function toggleService(): void {
    $this->guard();
    if (!Csrf::verify($_POST['_csrf'] ?? null)) {
      http_response_code(419);
      echo 'CSRF token mismatch';
      exit;
    }
    $id = (int)($_POST['id'] ?? 0);
    if ($id <= 0) { header('Location: /admin/services'); exit; }
    Service::toggleActive($id);
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    $_SESSION['success'] = 'Đã thay đổi trạng thái hiển thị dịch vụ #' . $id . '.';
    header('Location: /admin/services');
    exit;
  }

  public function deleteService(): void {
    $this->guard();
    if (!Csrf::verify($_POST['_csrf'] ?? null)) {
      http_response_code(419);
      echo 'CSRF token mismatch';
      exit;
    }
    $id = (int)($_POST['id'] ?? 0);
    if ($id <= 0) { header('Location: /admin/services'); exit; }
    $ok = Service::delete($id);
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    if ($ok) {
      $_SESSION['success'] = 'Đã xóa dịch vụ #' . $id . ' thành công.';
    } else {
      $_SESSION['error'] = 'Không thể xóa dịch vụ #' . $id . '. Có thể đang được tham chiếu bởi đơn đặt.';
    }
    header('Location: /admin/services');
    exit;
  }

  public function createService(): void {
    $this->guard();
    if (!Csrf::verify($_POST['_csrf'] ?? null)) {
      http_response_code(419);
      echo 'CSRF token mismatch';
      exit;
    }
    $payload = [
      'name' => trim((string)($_POST['name'] ?? '')),
      'description' => trim((string)($_POST['description'] ?? '')),
      'icon' => trim((string)($_POST['icon'] ?? '')),
      'duration' => trim((string)($_POST['duration'] ?? '')),
      'price' => (int)($_POST['price'] ?? 0),
      'unit' => trim((string)($_POST['unit'] ?? '')),
      'minimum' => (int)($_POST['minimum'] ?? 0),
      'is_active' => (int)($_POST['is_active'] ?? 1),
    ];
    if ($payload['name'] === '' || $payload['unit'] === '') {
      if (session_status() !== PHP_SESSION_ACTIVE) session_start();
      $_SESSION['error'] = 'Tên và đơn vị tính là bắt buộc.';
      header('Location: /admin/services');
      exit;
    }
    $id = Service::create($payload);
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    $_SESSION['success'] = 'Đã thêm dịch vụ #' . $id . ' thành công.';
    header('Location: /admin/services');
    exit;
  }

  public function confirmBooking(): void {
    $this->guard();
    if (!Csrf::verify($_POST['_csrf'] ?? null)) { http_response_code(419); echo 'CSRF token mismatch'; exit; }
    $id = (int)($_POST['id'] ?? 0);
    if ($id <= 0) { header('Location: /admin/bookings'); exit; }
    Booking::updateStatus($id, 'confirmed');
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    $_SESSION['success'] = 'Đã xác nhận đơn #' . $id . '.';
    header('Location: /admin/bookings');
    exit;
  }

  public function cancelBooking(): void {
    $this->guard();
    if (!Csrf::verify($_POST['_csrf'] ?? null)) { http_response_code(419); echo 'CSRF token mismatch'; exit; }
    $id = (int)($_POST['id'] ?? 0);
    if ($id <= 0) { header('Location: /admin/bookings'); exit; }
    Booking::updateStatus($id, 'cancelled');
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    $_SESSION['success'] = 'Đã hủy đơn #' . $id . '.';
    header('Location: /admin/bookings');
    exit;
  }

  public function assignBooking(): void {
    $this->guard();
    if (!Csrf::verify($_POST['_csrf'] ?? null)) { http_response_code(419); echo 'CSRF token mismatch'; exit; }
    $id = (int)($_POST['id'] ?? 0);
    $wid = (int)($_POST['worker_id'] ?? 0);
    if ($id <= 0 || $wid <= 0) { header('Location: /admin/bookings'); exit; }
    $worker = User::findById($wid);
    if (!$worker || ($worker['role'] ?? '') !== 'worker' || ($worker['approval_status'] ?? '') !== 'active') {
      if (session_status() !== PHP_SESSION_ACTIVE) session_start();
      $_SESSION['error'] = 'Worker không hợp lệ để gán.';
      header('Location: /admin/bookings');
      exit;
    }
    Booking::assignWorker($id, $wid);
    // Tuy chọn đánh dấu là đã xác nhận khi phân công
    Booking::updateStatus($id, 'confirmed');
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    $_SESSION['success'] = 'Đã gán worker #' . $wid . ' cho đơn #' . $id . '.';
    header('Location: /admin/bookings');
    exit;
  }
}

?>