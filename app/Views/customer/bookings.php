<?php
use App\Core\View;
/** @var array $bookings Danh sách đơn đặt */
/** @var string $csrf */

// Từ điển dịch trạng thái sang Tiếng Việt có kèm icon
$statusLabels = [
    'pending'   => '<span style="color:#f59e0b;font-weight:600;">⏳ Chờ thanh toán</span>',
    'paid'      => '<span style="color:#10b981;font-weight:600;">✅ Đã thanh toán</span>',
    'confirmed' => '<span style="color:#10b981;font-weight:600;">✅ Đã xác nhận (Đã thanh toán)</span>',
    'accepted'  => '<span style="color:#3b82f6;font-weight:600;">👷 Đang thực hiện</span>',
    'completed' => '<span style="color:#8b5cf6;font-weight:600;">🌟 Đã hoàn thành</span>',
    'cancelled' => '<span style="color:#ef4444;font-weight:600;">❌ Đã hủy</span>',
];
?>
<style>
.booking-actions {
  display: flex;
  justify-content: flex-start;
  align-items: center;
  gap: 8px;
  flex-wrap: wrap;
  margin-top: 6px;
}

.booking-actions > * {
  display: inline-flex;
  align-items: center;
}

.booking-actions .home-btn {
  min-height: 42px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  box-sizing: border-box;
  white-space: nowrap;
}

.booking-actions form {
  margin: 0;
}

.booking-actions button.home-btn {
  appearance: none;
  -webkit-appearance: none;
}
</style>
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
          <?php 
            $status = $b['status'] ?? ''; 
            $isCancelled = ($status === 'cancelled');
          ?>
          <div style="<?= $isCancelled ? 'opacity: 0.6; background-color: #f9fafb; border-color: #e5e7eb;' : '' ?>">
            <strong>#<?= View::e($b['id']) ?></strong>
            • <?= View::e($b['date']) ?> <?= View::e($b['time']) ?>
            • <?= View::e($b['location']) ?>
            
            • Trạng thái: <?= $statusLabels[$status] ?? '<span style="color:#6b7280;font-weight:600;">' . View::e($status) . '</span>' ?>
            
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
            
            <div class="booking-actions">
              <a class="home-btn" href="/bookings/<?= (int)$b['id'] ?>">Theo dõi đơn</a>
              
              <?php if ($status === 'pending'): ?>
                <form method="post" action="/bookings/<?= (int)$b['id'] ?>/repay">
                  <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
                  <button type="submit" class="home-btn" style="background-color: #2563eb; border: none; cursor: pointer;">
                    💳 Thanh toán ngay
                  </button>
                </form>
              <?php endif; ?>
              
              <?php if ($status === 'paid' || $status === 'confirmed'): ?>
                <span style="display:inline-flex; align-items:center; padding: 6px 12px; background-color: #d1fae5; color: #065f46; font-weight: 600; border-radius: 8px; border: 1px solid #34d399;">
                  ✅ Đã thanh toán
                </span>
              <?php endif; ?>
              
              <?php if (in_array($status, ['pending', 'confirmed', 'accepted'], true)): ?>
                <form method="post" action="/bookings/<?= (int)$b['id'] ?>/cancel">
                  <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
                  <button type="submit" class="home-btn" style="background-color: transparent; color: #dc2626; border: 2px solid #dc2626; cursor: pointer;" onclick="return confirm('Bạn có chắc muốn hủy đơn này không?');">
                    🗑️ Hủy đơn
                  </button>
                </form>
              <?php endif; ?>
              
              <?php if ($status === 'completed' && empty($b['has_review'])): ?>
                <a class="home-btn home-btn-outline" href="/bookings/<?= (int)$b['id'] ?>/review">
                  ⭐ Đánh giá
                </a>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>
</section>