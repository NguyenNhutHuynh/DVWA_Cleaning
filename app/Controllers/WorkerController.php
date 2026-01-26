<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Models\Booking;
use App\Models\User;

final class WorkerController {
  private function guard(): void {
    if (!Auth::id() || Auth::role() !== 'worker') {
      header("Location: /login");
      exit;
    }
    // Ensure worker is approved (active)
    $me = User::findById((int)Auth::id());
    if (!$me || ($me['approval_status'] ?? 'active') !== 'active') {
      header("Location: /worker/pending");
      exit;
    }
  }

  public function dashboard(): void {
    $this->guard();
    $me = User::findById((int)Auth::id());
    View::render('worker/dashboard', [
      'uid' => Auth::id(),
      'role' => Auth::role(),
      'name' => $me['name'] ?? 'Worker',
    ]);
  }

  public function jobs(): void {
    $this->guard();
    $jobs = array_filter(Booking::getAll(), fn($b)=>$b['status']==='pending');
    View::render('worker/jobs', ['jobs' => $jobs]);
  }

  public function progress(): void {
    $this->guard();
    $progress = [
      ['booking_id'=>2,'step'=>'Đang di chuyển','time'=>'2026-01-24 13:30'],
      ['booking_id'=>2,'step'=>'Bắt đầu công việc','time'=>'2026-01-24 14:05'],
    ];
    View::render('worker/progress', ['progress' => $progress]);
  }

  public function schedule(): void {
    $this->guard();
    $all = Booking::getAll();
    $mine = array_values(array_filter($all, fn($b) => (int)($b['assigned_worker_id'] ?? 0) === (int)Auth::id() && ($b['status'] ?? '') !== 'cancelled'));
    $schedule = array_map(function($b){
      return [
        'time' => ($b['date'] ?? '') . ' ' . ($b['time'] ?? ''),
        'location' => $b['location'] ?? '',
        'task' => ($b['service_name'] ?? 'Công việc') . ' • Trạng thái: ' . ($b['status'] ?? ''),
      ];
    }, $mine);
    View::render('worker/schedule', ['schedule' => $schedule]);
  }

  public function pending(): void {
    if (!Auth::id() || Auth::role() !== 'worker') {
      header("Location: /login");
      exit;
    }
    View::render('worker/pending', []);
  }
}

?>