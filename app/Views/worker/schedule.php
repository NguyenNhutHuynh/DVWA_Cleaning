<?php
use App\Core\View;
/** @var array $schedule */
?>
<section class="home-container">
  <header class="home-hero">
    <p class="home-kicker">WORKER • LỊCH LÀM VIỆC</p>
    <h1>Lịch làm việc</h1>
    <p>Xem các ca làm việc sắp tới của bạn.</p>
  </header>

  <section class="home-feature">
    <h2>Trong tuần này</h2>
    <div class="review-box">
      <?php foreach ($schedule as $s): ?>
        <div>
          <strong><?= View::e($s['time']) ?></strong>
          <p style="margin:6px 0;">Địa điểm: <?= View::e($s['location']) ?></p>
          <p style="margin:6px 0;">Ghi chú: <?= View::e($s['task']) ?></p>
          <div class="hero-actions" style="justify-content:flex-start;margin-top:6px;">
            <a class="home-btn" href="#">Nhận ca</a>
            <a class="home-btn home-btn-outline" href="#">Hủy</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
</section>