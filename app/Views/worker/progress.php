<?php
use App\Core\View;
/** @var array $progress Dữ liệu tiến độ công việc */

$bookingStatusMap = [
  'pending' => 'Chờ thanh toán',
  'paid' => 'Đã thanh toán',
  'confirmed' => 'Chờ xác nhận',
  'accepted' => 'Đã nhận',
  'in_progress' => 'Đang thực hiện',
  'completed' => 'Hoàn thành',
  'cancelled' => 'Đã hủy',
];
?>
<section class="home-container">
  <header class="home-hero">
    <p class="home-kicker">WORKER • TIẾN ĐỘ</p>
    <h1>Cập nhật tiến độ</h1>
    <p>Gửi các bước thực hiện để khách hàng theo dõi.</p>
  </header>

  <section class="home-feature">
    <h2>Tiến độ gần đây</h2>
    <div class="review-box">
      <?php if (empty($progress)): ?>
        <p>Chưa có dữ liệu tiến độ.</p>
      <?php endif; ?>
      <?php foreach ($progress as $p): ?>
        <div>
          <strong>Booking #<?= View::e($p['booking_id']) ?></strong> • <?= View::e($p['time']) ?>
          <p style="margin:6px 0;">Bước: <?= View::e($p['step']) ?></p>
          <div class="hero-actions" style="justify-content:flex-start;margin-top:6px;">
            <a class="home-btn" href="/worker/jobs/<?= (int)$p['booking_id'] ?>">Vào cập nhật</a>
            <?php
              $status = (string)($p['status'] ?? '');
              $statusLabel = $bookingStatusMap[$status] ?? 'Không rõ';
            ?>
            <span class="home-btn home-btn-outline" style="cursor:default;">Trạng thái: <?= View::e($statusLabel) ?></span>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
</section>