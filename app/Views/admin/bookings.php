<?php
use App\Core\View;
/** @var array $bookings */
?>
<section class="home-container">
  <header class="home-hero">
    <p class="home-kicker">ADMIN • ĐƠN ĐẶT</p>
    <h1>Quản lý đơn đặt</h1>
    <p>Xác nhận, hủy, cập nhật trạng thái.</p>
  </header>

  <section class="home-feature">
    <h2>Danh sách đơn đặt</h2>
    <div class="review-box">
      <?php foreach ($bookings as $b): ?>
        <div>
          <strong>#<?= View::e($b['id']) ?></strong>
          • <?= View::e($b['date']) ?> <?= View::e($b['time']) ?>
          • <?= View::e($b['location']) ?>
          • Trạng thái: <span style="color:#2eaf7d;font-weight:600;"><?= View::e($b['status']) ?></span>
          <div class="hero-actions" style="justify-content:flex-start;margin-top:6px;">
            <a class="home-btn" href="#">Xác nhận</a>
            <a class="home-btn home-btn-outline" href="#">Hủy</a>
            <a class="home-btn home-btn-outline" href="#">Giao cho cleaner</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
</section>