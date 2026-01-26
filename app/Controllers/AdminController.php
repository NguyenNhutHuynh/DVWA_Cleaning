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
    // List active workers for assignment
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
    // Remove empty values to avoid wiping unintentionally
    foreach ($payload as $k => $v) {
      if ($k === 'price' || $k === 'minimum') continue; // allow zero
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
    // Optionally mark as confirmed upon assignment
    Booking::updateStatus($id, 'confirmed');
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    $_SESSION['success'] = 'Đã gán worker #' . $wid . ' cho đơn #' . $id . '.';
    header('Location: /admin/bookings');
    exit;
  }
}

?>