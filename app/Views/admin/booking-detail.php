<?php
use App\Core\View;
use App\Models\BookingProgress;
/** @var array $booking */
/** @var array $progress */
/** @var array $messages */
/** @var array|null $payment */
/** @var array|null $report */
/** @var array|null $review */
?>

<section class="home-container" style="max-width:1180px; margin:0 auto 70px; padding:0 16px; display:grid; gap:24px;">
  <header class="home-hero" style="background: linear-gradient(135deg, #2eaf7d 0%, #43c59e 50%, #8cdf94 100%); color:#fff; border-radius:20px; box-shadow:0 10px 40px rgba(46,175,125,.3);">
    <p class="home-kicker" style="color:#fff;">ADMIN • CHI TIET DON</p>
    <h1>Don #<?= (int)($booking['id'] ?? 0) ?></h1>
    <p>Theo doi toan bo qua trinh worker thuc hien cong viec.</p>
    <div style="margin-top:12px;">
      <a class="home-btn home-btn-outline" href="/admin/bookings" style="background:#fff;">Quay lai danh sach don</a>
    </div>
  </header>

  <section class="home-feature">
    <h2>Thong tin tong quan</h2>
    <div class="review-box">
      <p><strong>Dich vu:</strong> <?= View::e((string)($booking['service_name'] ?? '')) ?></p>
      <p><strong>Khach hang:</strong> <?= View::e((string)($booking['user_name'] ?? '')) ?> (<?= View::e((string)($booking['user_phone'] ?? '')) ?>)</p>
      <p><strong>Dia chi:</strong> <?= View::e((string)($booking['location'] ?? '')) ?></p>
      <p><strong>Lich lam:</strong> <?= View::e((string)($booking['date'] ?? '')) ?> <?= View::e((string)($booking['time'] ?? '')) ?></p>
      <p><strong>Worker:</strong> <?= View::e((string)($booking['worker_name'] ?? 'Chua phan cong')) ?> (<?= View::e((string)($booking['worker_phone'] ?? '')) ?>)</p>
      <p><strong>Trang thai:</strong> <span style="font-weight:700;color:#2eaf7d;"><?= View::e((string)($booking['status'] ?? '')) ?></span></p>
      <?php if ($payment !== null): ?>
        <hr style="margin:12px 0;">
        <p><strong>Thanh toan:</strong> <?= number_format((float)($payment['service_price'] ?? 0), 0, ',', '.') ?>d</p>
      <?php else: ?>
        <hr style="margin:12px 0;">
        <p><strong>Thanh toan:</strong> <?= number_format((float)($booking['service_price'] ?? 0), 0, ',', '.') ?>d</p>
      <?php endif; ?>
    </div>
  </section>

  <section class="home-feature">
    <h2>Worker da lam gi (Tien do)</h2>
    <div class="review-box">
      <?php if (empty($progress)): ?>
        <p>Worker chua cap nhat tien do.</p>
      <?php else: ?>
        <?php foreach ($progress as $item): ?>
          <article style="margin-bottom:14px;padding:12px;border:1px solid #e9ecef;border-radius:10px;">
            <p><strong><?= View::e(BookingProgress::stepLabel((string)($item['step'] ?? ''))) ?></strong> • <?= View::e((string)($item['created_at'] ?? '')) ?></p>
            <?php if (!empty($item['note'])): ?>
              <p><?= View::e((string)$item['note']) ?></p>
            <?php endif; ?>
            <?php if (!empty($item['photos'])): ?>
              <div style="display:flex; gap:8px; flex-wrap:wrap;">
                <?php foreach ($item['photos'] as $photo): ?>
                  <a href="<?= View::e((string)$photo) ?>" target="_blank" rel="noopener">
                    <img src="<?= View::e((string)$photo) ?>" alt="progress" style="width:110px;height:110px;object-fit:cover;border-radius:8px;">
                  </a>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>

  <section class="home-feature">
    <h2>Bao cao hoan thanh</h2>
    <div class="review-box">
      <?php if ($report === null): ?>
        <p>Worker chua gui bao cao hoan thanh.</p>
      <?php else: ?>
        <?php if (!empty($report['difficulties'])): ?>
          <div style="background:#fff3cd;padding:12px;border-radius:8px;border-left:4px solid #ffc107;">
            <p style="margin:0;white-space:pre-wrap;"><?= View::e((string)$report['difficulties']) ?></p>
          </div>
        <?php endif; ?>
        <?php if (!empty($report['summary'])): ?>
          <p><strong>Tom tat:</strong> <?= View::e((string)$report['summary']) ?></p>
        <?php endif; ?>
        <p><small>Gui luc: <?= View::e((string)($report['created_at'] ?? '')) ?></small></p>
      <?php endif; ?>
    </div>
  </section>

  <section class="home-feature">
    <h2>Danh gia cua khach hang</h2>
    <div class="review-box">
      <?php if ($review === null): ?>
        <p>Khach hang chua gui danh gia.</p>
      <?php else: ?>
        <p><strong>Diem:</strong> <?= str_repeat('⭐', (int)($review['rating'] ?? 0)) ?> (<?= (int)($review['rating'] ?? 0) ?>/5)</p>
        <?php if (!empty($review['comment'])): ?>
          <p><strong>Noi dung:</strong> "<?= View::e((string)$review['comment']) ?>"</p>
        <?php endif; ?>
        <p><small>Danh gia luc: <?= View::e((string)($review['created_at'] ?? '')) ?></small></p>
      <?php endif; ?>
    </div>
  </section>

  <section class="home-feature">
    <h2>Trao doi trong don</h2>
    <div class="review-box">
      <?php if (empty($messages)): ?>
        <p>Chua co tin nhan trong don nay.</p>
      <?php else: ?>
        <?php foreach ($messages as $message): ?>
          <article style="margin-bottom:10px;padding:10px;border:1px solid #e9ecef;border-radius:8px;">
            <p style="margin:0 0 4px;"><strong><?= View::e((string)($message['sender_name'] ?? '')) ?></strong> (<?= View::e((string)($message['sender_role'] ?? '')) ?>)</p>
            <p style="margin:0 0 4px;"><?= View::e((string)($message['content'] ?? '')) ?></p>
            <small><?= View::e((string)($message['created_at'] ?? '')) ?></small>
          </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>
</section>
