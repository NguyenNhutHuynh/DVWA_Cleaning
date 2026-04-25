<?php
use App\Core\View;
use App\Models\BookingProgress;
/** @var array $booking */
/** @var array $progress */
/** @var array $messages */
/** @var array|null $payment */
/** @var bool $hasReview */
/** @var string $csrf */
?>
<section class="home-container">
  <header class="home-hero">
    <p class="home-kicker">KHÁCH HÀNG • THEO DÕI ĐƠN</p>
    <h1>Đơn #<?= View::e($booking['id'] ?? '') ?></h1>
    <p>Theo dõi tiến độ làm việc, ảnh cập nhật và trao đổi trực tiếp với worker.</p>
  </header>

  <section class="home-feature">
    <h2>Thông tin đơn</h2>
    <div class="review-box">
      <p><strong>Dịch vụ:</strong> <?= View::e($booking['service_name'] ?? '') ?></p>
      <?php if (isset($booking['quantity']) && (float)$booking['quantity'] > 0): ?>
        <p><strong>Khối lượng:</strong> <?= View::e((string)$booking['quantity']) ?> <?= View::e((string)($booking['measure_unit'] ?? '')) ?></p>
      <?php endif; ?>
      <?php if (isset($booking['unit_price']) && (float)$booking['unit_price'] > 0): ?>
        <p><strong>Đơn giá:</strong> <?= number_format((float)$booking['unit_price'], 0, ',', '.') ?>đ/<?= View::e((string)($booking['measure_unit'] ?? '')) ?></p>
      <?php endif; ?>
      <p><strong>Địa chỉ:</strong> <span id="customerAddress"><?= View::e($booking['location'] ?? '') ?></span></p>
      <p><strong>Thời gian:</strong> <?= View::e(($booking['date'] ?? '') . ' ' . ($booking['time'] ?? '')) ?></p>
      <p><strong>Worker:</strong> <?= View::e($booking['worker_name'] ?? 'Chưa gán') ?></p>
      <p><strong>SĐT worker:</strong> <?= View::e($booking['worker_phone'] ?? '') ?></p>
      <p><strong>Trạng thái:</strong> <span style="color:#2eaf7d;font-weight:700;"><?= View::e($booking['status'] ?? '') ?></span></p>

      <?php if ($payment !== null): ?>
        <hr style="margin:12px 0;">
        <p><strong>Khách thanh toán:</strong> <?= number_format((float)($payment['service_price'] ?? 0), 0, ',', '.') ?>đ</p>
      <?php else: ?>
        <hr style="margin:12px 0;">
        <p><strong>Khách thanh toán:</strong> <?= number_format((float)($booking['service_price'] ?? 0), 0, ',', '.') ?>đ</p>
      <?php endif; ?>
    </div>
  </section>

  <section class="home-feature">
    <h2>Tiến độ công việc & ảnh cập nhật</h2>
    <div class="review-box">
      <?php if (empty($progress)): ?>
        <p>Worker chưa cập nhật tiến độ.</p>
      <?php endif; ?>
      <?php foreach ($progress as $item): ?>
        <div style="margin-bottom:14px;padding:10px;border:1px solid #e9ecef;border-radius:8px;">
          <p><strong><?= View::e(BookingProgress::stepLabel((string)($item['step'] ?? ''))) ?></strong> • <?= View::e($item['created_at'] ?? '') ?></p>
          <?php if (!empty($item['note'])): ?>
            <p><?= View::e($item['note']) ?></p>
          <?php endif; ?>
          <?php if (!empty($item['photos'])): ?>
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
              <?php foreach ($item['photos'] as $photo): ?>
                <a href="<?= View::e($photo) ?>" target="_blank" rel="noopener">
                  <img src="<?= View::e($photo) ?>" alt="progress" style="width:110px;height:110px;object-fit:cover;border-radius:8px;">
                </a>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <?php if (!empty($booking['assigned_worker_id']) && in_array($booking['status'] ?? '', ['accepted', 'confirmed', 'in_progress'], true)): ?>
    <section class="home-feature">
      <h2>⏱️ Thời gian ước tính đến</h2>
      <div class="review-box">
        <?php if (!empty($booking['estimated_arrival_time'])): ?>
          <p><strong>Worker sẽ đến lúc:</strong> <span style="color:#2eaf7d;font-weight:700;"><?= View::e($booking['estimated_arrival_time']) ?></span></p>
          <p style="font-size:14px;color:#666;margin:8px 0 0;">Worker đã cập nhật thời gian dự kiến đến</p>
        <?php else: ?>
          <p><strong>Thời gian ước tính:</strong> Chưa có</p>
          <p style="font-size:14px;color:#666;margin:8px 0 0;">Worker chưa cập nhật thời gian dự kiến đến.</p>
        <?php endif; ?>
      </div>
    </section>
  <?php endif; ?>

  <section class="home-feature">
    <h2>Nhắn tin với worker</h2>
    <div class="review-box">
      <?php if (empty($messages)): ?>
        <p>Chưa có tin nhắn.</p>
      <?php endif; ?>
      <?php foreach ($messages as $message): ?>
        <div style="margin-bottom:10px;padding:8px;border:1px solid #e9ecef;border-radius:8px;">
          <p style="margin:0 0 4px;"><strong><?= View::e($message['sender_name'] ?? '') ?></strong> (<?= View::e($message['sender_role'] ?? '') ?>)</p>
          <p style="margin:0 0 4px;"><?= View::e($message['content'] ?? '') ?></p>
          <small><?= View::e($message['created_at'] ?? '') ?></small>
        </div>
      <?php endforeach; ?>

      <form method="post" action="/bookings/<?= (int)$booking['id'] ?>/message">
        <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
        <textarea name="content" rows="2" required style="width:100%;padding:10px;border-radius:8px;margin:8px 0 12px;" placeholder="Nhập tin nhắn..."></textarea>
        <button type="submit" class="home-btn">Gửi tin nhắn</button>
      </form>
    </div>
  </section>

  <?php if (($booking['status'] ?? '') === 'completed' && !empty($review)): ?>
    <section class="home-feature">
      <h2>✨ Đánh giá của bạn</h2>
      <div class="review-box">
        <div style="margin-bottom:12px;">
          <p style="margin:0 0 8px;"><strong>Điểm đánh giá:</strong></p>
          <div style="font-size:1.5rem;color:#ffc107;">
            <?= str_repeat('⭐', (int)($review['rating'] ?? 0)) ?>
            <span style="color:#546e7a;font-size:1rem;margin-left:8px;">(<?= (int)($review['rating'] ?? 0) ?>/5)</span>
          </div>
        </div>
        <?php if (!empty($review['comment'])): ?>
          <p style="margin:0 0 4px;"><strong>Bình luận của bạn:</strong></p>
          <div style="background:#f8f9fa;padding:12px;border-radius:8px;border-left:4px solid #2eaf7d;margin-bottom:12px;">
            <p style="margin:0;font-style:italic;color:#546e7a;">"<?= View::e($review['comment']) ?>"</p>
          </div>
        <?php endif; ?>
        <p style="margin:0;"><small style="color:#78909c;">Đánh giá vào: <?= View::e($review['created_at'] ?? '') ?></small></p>
      </div>
    </section>
  <?php endif; ?>

  <?php if (($booking['status'] ?? '') === 'completed' && !$hasReview): ?>
    <section class="home-feature">
      <h2>Đánh giá sau khi hoàn thành</h2>
      <div class="review-box">
        <p>Đơn đã hoàn thành. Vui lòng đánh giá worker và để lại bình luận.</p>
        <a class="home-btn" href="/bookings/<?= (int)$booking['id'] ?>/review">Đi tới trang đánh giá</a>
      </div>
    </section>
    <script>
      setTimeout(function () {
        window.location.href = '/bookings/<?= (int)$booking['id'] ?>/review';
      }, 2200);
    </script>
  <?php endif; ?>
</section>

