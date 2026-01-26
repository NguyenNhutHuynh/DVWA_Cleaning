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
          • Dịch vụ: <?= View::e($b['service_name'] ?? '') ?>
          • Trạng thái: <span style="color:#2eaf7d;font-weight:600;"><?= View::e($b['status']) ?></span>
          <?php if (!empty($b['assigned_worker_id'])): ?>
            <span>• Đã gán cho Worker #<?= View::e($b['assigned_worker_id']) ?></span>
          <?php endif; ?>
          <div class="hero-actions" style="justify-content:flex-start;margin-top:8px; gap:8px; flex-wrap: wrap;">
            <form method="POST" action="/admin/bookings/confirm" style="display:inline-block;">
              <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
              <input type="hidden" name="id" value="<?= (int)$b['id'] ?>">
              <button class="home-btn" type="submit">Xác nhận</button>
            </form>
            <form method="POST" action="/admin/bookings/cancel" style="display:inline-block;">
              <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
              <input type="hidden" name="id" value="<?= (int)$b['id'] ?>">
              <button class="home-btn home-btn-outline" type="submit">Hủy</button>
            </form>
            <form method="POST" action="/admin/bookings/assign" style="display:flex; align-items:center; gap:8px;">
              <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
              <input type="hidden" name="id" value="<?= (int)$b['id'] ?>">
              <select name="worker_id" required style="padding:6px 10px; border:1px solid #e0f2e9; border-radius:8px;">
                <option value="">-- Chọn worker --</option>
                <?php foreach (($workers ?? []) as $w): ?>
                  <option value="<?= (int)$w['id'] ?>">Worker #<?= (int)$w['id'] ?> · <?= View::e($w['name']) ?></option>
                <?php endforeach; ?>
              </select>
              <button class="home-btn home-btn-outline" type="submit">Gán worker</button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
</section>