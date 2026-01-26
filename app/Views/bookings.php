<?php
use App\Core\View;
/** @var array $bookings */
?>
<section class="home-container">
  <header class="home-hero">
    <p class="home-kicker">TÀI KHOẢN • ĐƠN ĐẶT</p>
    <h1>Đơn đặt của bạn</h1>
    <p>Xem trạng thái và chi tiết các lịch đã đặt.</p>
    <div class="hero-actions">
      <a class="home-btn" href="/book">Đặt lịch mới</a>
    </div>
  </header>

  <section class="home-feature">
    <h2>Danh sách đơn đặt</h2>
    <div class="review-box">
      <?php if (empty($bookings)): ?>
        <p>Bạn chưa có đơn đặt nào. <a href="/book">Đặt lịch ngay</a>.</p>
      <?php else: ?>
        <?php foreach ($bookings as $b): ?>
          <div>
            <strong>#<?= View::e($b['id']) ?></strong>
            • <?= View::e($b['date']) ?> <?= View::e($b['time']) ?>
            • <?= View::e($b['location']) ?>
            • Trạng thái: <span style="color:#2eaf7d;font-weight:600;"><?= View::e($b['status']) ?></span>
            <?php if (!empty($b['service_name'])): ?>
              <div style="margin-top:4px;color:#455a64;">Dịch vụ: <?= View::e($b['service_name']) ?></div>
            <?php endif; ?>
            <?php if (!empty($b['description'])): ?>
              <p style="margin:6px 0;"><?= View::e($b['description']) ?></p>
            <?php endif; ?>
            <div class="hero-actions" style="justify-content:flex-start;margin-top:6px;gap:8px;">
              <!-- Hành động huỷ có thể được bổ sung sau qua route POST -->
              <!-- <form method="post" action="/bookings/cancel" style="display:inline-block;"> -->
              <!--   <input type="hidden" name="id" value="<?= View::e($b['id']) ?>"> -->
              <!--   <button class="home-btn home-btn-outline" type="submit">Huỷ</button> -->
              <!-- </form> -->
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>
</section>
