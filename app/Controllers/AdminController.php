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
    $services = Service::all();
    View::render('admin/services', ['services' => $services]);
  }

  public function bookings(): void {
    $this->guard();
    $bookings = Booking::getAll();
    View::render('admin/bookings', ['bookings' => $bookings]);
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
}

?>