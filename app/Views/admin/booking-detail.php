<?php
use App\Core\View;
use App\Models\BookingProgress;
/** @var array $booking */
/** @var array $progress */
/** @var array $messages */
/** @var array|null $payment */
/** @var array|null $customerPayment */
/** @var array|null $report */
/** @var array|null $review */
/** @var array $workers */

$isCustomerPaid = $customerPayment !== null && (($customerPayment['status'] ?? '') === 'paid');
$assignedWorkerId = (int)($booking['assigned_worker_id'] ?? 0);
?>

<style>
.admin-booking-detail {
  max-width: 1200px;
  margin: 0 auto 70px;
  padding: 0 16px;
  display: grid;
  gap: 24px;
}

.booking-detail-hero {
  background: linear-gradient(135deg, #2eaf7d 0%, #43c59e 50%, #8cdf94 100%);
  border-radius: 20px;
  padding: 48px 38px;
  color: #fff;
  box-shadow: 0 10px 40px rgba(46, 175, 125, 0.3);
  position: relative;
  overflow: hidden;
  animation: slideInDown 0.6s ease-out;
}

.booking-detail-hero::before {
  content: '';
  position: absolute;
  top: -50%;
  right: -50%;
  width: 200%;
  height: 200%;
  background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
  animation: rotate 20s linear infinite;
}

.booking-detail-hero .home-kicker,
.booking-detail-hero h1,
.booking-detail-hero p,
.booking-detail-hero .hero-back {
  position: relative;
  z-index: 1;
}

.booking-detail-hero .home-kicker {
  color: #fff;
}

.booking-detail-hero h1 {
  margin: 0 0 10px;
  font-size: 2.4rem;
}

.booking-detail-page .home-feature {
  border: 1px solid #e7f3ed;
  background: #fff;
  box-shadow: 0 6px 20px rgba(44,62,80,0.06);
  animation: fadeInUp 0.8s ease-out 0.2s both;
}

.booking-detail-page .review-box {
  border: 1px solid #dff1e8;
  background: #fff;
  display: grid;
  gap: 10px;
}

.meta-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  gap: 10px 16px;
}

.status-chip {
  display: inline-block;
  color: #2eaf7d;
  font-weight: 700;
  text-transform: capitalize;
}

.timeline-item,
.message-item {
  margin-bottom: 12px;
  padding: 12px;
  border: 1px solid #e9ecef;
  border-radius: 10px;
  background: #fcfffd;
}

.photo-list {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
  margin-top: 8px;
}

.photo-list img {
  width: 110px;
  height: 110px;
  object-fit: cover;
  border-radius: 8px;
}

.report-box {
  background: #fff3cd;
  padding: 12px;
  border-radius: 8px;
  border-left: 4px solid #ffc107;
}

.review-stars {
  color: #ffb400;
  font-size: 1.1rem;
}

@keyframes slideInDown {
  from { opacity: 0; transform: translateY(-30px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes rotate {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

@media (max-width: 768px) {
  .booking-detail-hero {
    padding: 34px 24px;
  }

  .booking-detail-hero h1 {
    font-size: 2rem;
  }
}
</style>

<section class="home-container admin-booking-detail booking-detail-page">
  <header class="home-hero booking-detail-hero">
    <p class="home-kicker">ADMIN • CHI TIẾT ĐƠN</p>
    <h1>Đơn #<?= (int)($booking['id'] ?? 0) ?></h1>
    <p>Theo dõi toàn bộ quá trình worker thực hiện công việc.</p>
    <div class="hero-back" style="margin-top: 12px;">
      <a class="home-btn home-btn-outline" href="/admin/bookings" style="background:#fff;">Quay lại danh sách đơn</a>
    </div>
  </header>

  <section class="home-feature">
    <h2>Thông tin tổng quan</h2>
    <div class="review-box">
      <div class="meta-grid">
        <p><strong>Dịch vụ:</strong> <?= View::e((string)($booking['service_name'] ?? '')) ?></p>
        <p><strong>Khách hàng:</strong> <?= View::e((string)($booking['user_name'] ?? '')) ?> (<?= View::e((string)($booking['user_phone'] ?? '')) ?>)</p>
        <p><strong>Địa chỉ:</strong> <?= View::e((string)($booking['location'] ?? '')) ?></p>
        <p><strong>Lịch làm:</strong> <?= View::e((string)($booking['date'] ?? '')) ?> <?= View::e((string)($booking['time'] ?? '')) ?></p>
        <p><strong>Worker:</strong> <?= View::e((string)($booking['worker_name'] ?? 'Chưa phân công')) ?> (<?= View::e((string)($booking['worker_phone'] ?? '')) ?>)</p>
        <p><strong>Trạng thái:</strong> <span class="status-chip"><?= View::e((string)($booking['status'] ?? '')) ?></span></p>
      </div>
      <hr style="margin: 4px 0 2px; border: none; border-top: 1px solid #e8f2ed;">
      <?php if ($customerPayment !== null && ($customerPayment['status'] ?? '') === 'paid'): ?>
        <p><strong>Thanh toán khách:</strong> <span style="color:#065f46;font-weight:700;">Đã thanh toán</span></p>
        <p><strong>Số tiền đã trả:</strong> <?= number_format((float)($customerPayment['amount'] ?? 0), 0, ',', '.') ?>đ</p>
        <p><strong>Thời gian thanh toán:</strong> <?= View::e((string)($customerPayment['paid_at'] ?? '')) ?></p>
      <?php elseif ($customerPayment !== null): ?>
        <p><strong>Thanh toán khách:</strong> <span style="color:#991b1b;font-weight:700;">Chưa thanh toán</span></p>
        <p><strong>Số tiền cần thanh toán:</strong> <?= number_format((float)($customerPayment['amount'] ?? ($booking['service_price'] ?? 0)), 0, ',', '.') ?>đ</p>
      <?php elseif ($payment !== null): ?>
        <p><strong>Thanh toán:</strong> <?= number_format((float)($payment['service_price'] ?? 0), 0, ',', '.') ?>đ</p>
      <?php else: ?>
        <p><strong>Thanh toán khách:</strong> <span style="color:#991b1b;font-weight:700;">Chưa thanh toán</span></p>
        <p><strong>Số tiền cần thanh toán:</strong> <?= number_format((float)($booking['service_price'] ?? 0), 0, ',', '.') ?>đ</p>
      <?php endif; ?>

      <hr style="margin: 8px 0 2px; border: none; border-top: 1px solid #e8f2ed;">
      <p><strong>Phân công worker:</strong></p>
      <form method="POST" action="/admin/bookings/assign" style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
        <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
        <input type="hidden" name="id" value="<?= (int)($booking['id'] ?? 0) ?>">
        <input type="hidden" name="return_to" value="/admin/bookings/<?= (int)($booking['id'] ?? 0) ?>">
        <select name="worker_id" required style="min-width:260px;padding:8px 10px;border:1px solid #cfe9dc;border-radius:10px;" <?= $isCustomerPaid ? '' : 'disabled' ?>>
          <option value="">-- Chọn worker --</option>
          <?php foreach (($workers ?? []) as $worker): ?>
            <option value="<?= (int)$worker['id'] ?>" <?= $assignedWorkerId === (int)$worker['id'] ? 'selected' : '' ?>>
              Worker #<?= (int)$worker['id'] ?> · <?= View::e((string)$worker['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <button class="home-btn home-btn-outline" type="submit" <?= $isCustomerPaid ? '' : 'disabled' ?>>Gán worker</button>
      </form>
      <?php if (!$isCustomerPaid): ?>
        <p style="margin:6px 0 0;color:#991b1b;font-size:13px;">Chỉ gán được worker sau khi khách thanh toán thành công.</p>
      <?php elseif ($assignedWorkerId > 0): ?>
        <p style="margin:6px 0 0;color:#065f46;font-size:13px;">Đơn đã có worker, bạn có thể đổi sang worker khác nếu cần.</p>
      <?php else: ?>
        <p style="margin:6px 0 0;color:#b26a00;font-size:13px;">Đơn đã thanh toán, bạn có thể phân công worker ngay tại đây.</p>
      <?php endif; ?>
    </div>
  </section>

  <section class="home-feature">
    <h2>Worker đã làm gì (tiến độ)</h2>
    <div class="review-box">
      <?php if (empty($progress)): ?>
        <p>Worker chưa cập nhật tiến độ.</p>
      <?php else: ?>
        <?php foreach ($progress as $item): ?>
          <article class="timeline-item">
            <p><strong><?= View::e(BookingProgress::stepLabel((string)($item['step'] ?? ''))) ?></strong> • <?= View::e((string)($item['created_at'] ?? '')) ?></p>
            <?php if (!empty($item['note'])): ?>
              <p><?= View::e((string)$item['note']) ?></p>
            <?php endif; ?>
            <?php if (!empty($item['photos'])): ?>
              <div class="photo-list">
                <?php foreach ($item['photos'] as $photo): ?>
                  <a href="<?= View::e((string)$photo) ?>" target="_blank" rel="noopener">
                    <img src="<?= View::e((string)$photo) ?>" alt="progress">
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
    <h2>Báo cáo hoàn thành</h2>
    <div class="review-box">
      <?php if ($report === null): ?>
        <p>Worker chưa gửi báo cáo hoàn thành.</p>
      <?php else: ?>
        <?php if (!empty($report['difficulties'])): ?>
          <div class="report-box">
            <p style="margin: 0; white-space: pre-wrap;"><?= View::e((string)$report['difficulties']) ?></p>
          </div>
        <?php endif; ?>
        <?php if (!empty($report['summary'])): ?>
          <p><strong>Tóm tắt:</strong> <?= View::e((string)$report['summary']) ?></p>
        <?php endif; ?>
        <p><small>Gửi lúc: <?= View::e((string)($report['created_at'] ?? '')) ?></small></p>
      <?php endif; ?>
    </div>
  </section>

  <section class="home-feature">
    <h2>Đánh giá của khách hàng</h2>
    <div class="review-box">
      <?php if ($review === null): ?>
        <p>Khách hàng chưa gửi đánh giá.</p>
      <?php else: ?>
        <p><strong>Điểm:</strong> <span class="review-stars"><?= str_repeat('★', (int)($review['rating'] ?? 0)) ?></span> (<?= (int)($review['rating'] ?? 0) ?>/5)</p>
        <?php if (!empty($review['comment'])): ?>
          <p><strong>Nội dung:</strong> "<?= View::e((string)$review['comment']) ?>"</p>
        <?php endif; ?>
        <p><small>Đánh giá lúc: <?= View::e((string)($review['created_at'] ?? '')) ?></small></p>
      <?php endif; ?>
    </div>
  </section>

  <section class="home-feature">
    <h2>Trao đổi trong đơn</h2>
    <div class="review-box">
      <?php if (empty($messages)): ?>
        <p>Chưa có tin nhắn trong đơn này.</p>
      <?php else: ?>
        <?php foreach ($messages as $message): ?>
          <article class="message-item">
            <p style="margin: 0 0 4px;"><strong><?= View::e((string)($message['sender_name'] ?? '')) ?></strong> (<?= View::e((string)($message['sender_role'] ?? '')) ?>)</p>
            <p style="margin: 0 0 4px;"><?= View::e((string)($message['content'] ?? '')) ?></p>
            <small><?= View::e((string)($message['created_at'] ?? '')) ?></small>
          </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>
</section>
