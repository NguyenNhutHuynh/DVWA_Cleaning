<?php
use App\Core\View;
/** @var array $progress Dữ liệu tiến độ công việc */
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
      <?php foreach ($progress as $p): ?>
        <div>
          <strong>Booking #<?= View::e($p['booking_id']) ?></strong> • <?= View::e($p['time']) ?>
          <p style="margin:6px 0;">Bước: <?= View::e($p['step']) ?></p>
          <div class="hero-actions" style="justify-content:flex-start;margin-top:6px;">
            <a class="home-btn" href="#">Thêm bước</a>
            <a class="home-btn home-btn-outline" href="#">Hoàn thành</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
</section>