<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Models\Booking;

final class CleanerController {
  private function guard(): void {
    if (!Auth::id() || Auth::role() !== 'cleaner') {
      header("Location: /login");
      exit;
    }
  }

  public function dashboard(): void {
    $this->guard();
    $me = \App\Models\User::findById((int)Auth::id());
    View::render('cleaner/dashboard', [
      'uid' => Auth::id(),
      'role' => Auth::role(),
      'name' => $me['name'] ?? 'Nhân viên',
    ]);
  }

  public function jobs(): void {
    $this->guard();
    // Hiển thị các đơn chờ xử lý làm các công việc có sẵn (demo)
    $jobs = array_filter(Booking::getAll(), fn($b)=>$b['status']==='pending');
    View::render('cleaner/jobs', ['jobs' => $jobs]);
  }

  public function progress(): void {
    $this->guard();
    // Giẳ lậ các mục tiến độ
    $progress = [
      ['booking_id'=>2,'step'=>'Đang di chuyển','time'=>'2026-01-24 13:30'],
      ['booking_id'=>2,'step'=>'Bắt đầu công việc','time'=>'2026-01-24 14:05'],
    ];
    View::render('cleaner/progress', ['progress' => $progress]);
  }

  public function schedule(): void {
    $this->guard();
    // Giẳ lậ lịch hôm nay
    $schedule = [
      ['time'=>'10:00','location'=>'Quận 1','task'=>'Tổng vệ sinh 80m²'],
      ['time'=>'14:00','location'=>'Quận 7','task'=>'Giặt sofa 3 chỗ'],
    ];
    View::render('cleaner/schedule', ['schedule' => $schedule]);
  }
}

?>