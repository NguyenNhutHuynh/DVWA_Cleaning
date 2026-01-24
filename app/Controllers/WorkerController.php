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
    $schedule = [
      ['time'=>'10:00','location'=>'Quận 1','task'=>'Tổng vệ sinh 80m²'],
      ['time'=>'14:00','location'=>'Quận 7','task'=>'Giặt sofa 3 chỗ'],
    ];
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