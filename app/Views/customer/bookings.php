<?php
use App\Core\View;
/** @var array $bookings Danh sách đơn đặt */
/** @var string $csrf */
?>
<section class="home-container">
  <header class="home-hero">
    <p class="home-kicker">TÀI KHOẢN • KHÁCH HÀNG</p>
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
            <?php if (isset($b['quantity']) && (float)$b['quantity'] > 0): ?>
              <div style="margin-top:4px;color:#455a64;">Khối lượng: <?= View::e((string)$b['quantity']) ?> <?= View::e((string)($b['measure_unit'] ?? '')) ?></div>
            <?php endif; ?>
            <div style="margin-top:4px;color:#1f2d3d;font-weight:600;">Thành tiền tạm tính: <?= number_format((float)($b['service_price'] ?? 0), 0, ',', '.') ?>đ</div>
            <?php if (!empty($b['description'])): ?>
              <p style="margin:6px 0;"><?= View::e($b['description']) ?></p>
            <?php endif; ?>
            <div class="hero-actions" style="justify-content:flex-start;margin-top:6px;gap:8px;">
              <a class="home-btn" href="/bookings/<?= (int)$b['id'] ?>">Theo dõi đơn</a>
              <?php if (in_array(($b['status'] ?? ''), ['pending', 'confirmed', 'accepted'], true)): ?>
                <form method="post" action="/bookings/<?= (int)$b['id'] ?>/cancel" style="display:inline;">
                  <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
                  <button
                    type="submit"
                    class="home-btn home-btn-outline"
                    style="border-color:#dc2626;color:#dc2626;"
                    onclick="return confirm('Bạn có chắc muốn hủy đơn này không?');"
                  >
                    Hủy đơn
                  </button>
                </form>
              <?php endif; ?>
              <?php if (($b['status'] ?? '') === 'completed' && empty($b['has_review'])): ?>
                <a class="home-btn home-btn-outline" href="/bookings/<?= (int)$b['id'] ?>/review">Đánh giá</a>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>
</section>
