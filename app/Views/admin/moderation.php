<?php
use App\Core\View;
/** @var array $contacts Danh sách liên hệ */
/** @var array $reviews Danh sách đánh giá từ booking_reviews */
/** @var array $bookingMessages Danh sách tin nhắn khiếu nại từ booking_messages */
/** @var array $reports Danh sách báo cáo hoàn thành từ booking_reports */

$comments = [];
$ratings = [];
$complaints = [];

foreach ($contacts as $contact) {
  $subject = mb_strtolower((string)($contact['subject'] ?? ''));
  $message = mb_strtolower((string)($contact['message'] ?? ''));
  $content = $subject . ' ' . $message;

  $isComplaint = str_contains($content, 'khiếu nại')
    || str_contains($content, 'khieu nai')
    || str_contains($content, 'phản hồi')
    || str_contains($content, 'phan hoi')
    || str_contains($content, 'complaint');

  $isRating = str_contains($content, 'đánh giá')
    || str_contains($content, 'danh gia')
    || str_contains($content, 'rating')
    || str_contains($content, 'review')
    || str_contains($content, ' sao')
    || preg_match('/\b[1-5]\s*\/\s*5\b/u', $content) === 1;

  if ($isComplaint) {
    $complaints[] = $contact;
    continue;
  }

  if ($isRating) {
    $ratings[] = $contact;
    continue;
  }

  $comments[] = $contact;
}

$renderModerationList = static function (array $items, string $emptyText): void {
  if ($items === []) {
    echo '<p class="moderation-empty">' . View::e($emptyText) . '</p>';
    return;
  }

  foreach ($items as $c) {
    echo '<article class="moderation-item">';
    echo '<p class="moderation-meta"><strong>' . View::e((string)($c['subject'] ?? '')) . '</strong> • '
      . View::e((string)($c['name'] ?? '')) . ' (' . View::e((string)($c['email'] ?? '')) . ')</p>';
    echo '<p class="moderation-message">"' . View::e((string)($c['message'] ?? '')) . '"</p>';
    echo '<p class="moderation-status">Trạng thái: <span>'
      . View::e((string)($c['status'] ?? 'pending')) . '</span></p>';
    echo '<div class="hero-actions moderation-actions">';
    echo '<a class="home-btn" href="#">Duyệt</a>';
    echo '<a class="home-btn home-btn-outline" href="#">Ẩn</a>';
    echo '<a class="home-btn home-btn-outline" href="#">Gắn cờ</a>';
    echo '</div>';
    echo '</article>';
  }
};
?>

<style>
.admin-moderation {
  max-width: 1200px;
  margin: 0 auto 70px;
  padding: 0 16px;
}

.moderation-hero {
  background: linear-gradient(135deg, #2eaf7d 0%, #43c59e 50%, #8cdf94 100%);
  border-radius: 20px;
  padding: 50px 40px;
  color: #fff;
  position: relative;
  overflow: hidden;
  box-shadow: 0 10px 40px rgba(46, 175, 125, 0.3);
  animation: slideInDown 0.6s ease-out;
}

.moderation-hero::before {
  content: '';
  position: absolute;
  top: -50%;
  right: -50%;
  width: 200%;
  height: 200%;
  background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
  animation: rotate 20s linear infinite;
}

.moderation-hero .home-kicker {
  position: relative;
  z-index: 1;
  color: #fff;
}

.moderation-hero h1 {
  font-size: 2.5rem;
  margin: 0 0 10px 0;
  font-weight: 700;
  position: relative;
  z-index: 1;
  text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.moderation-hero p {
  font-size: 1.1rem;
  margin: 0;
  opacity: 0.95;
  position: relative;
  z-index: 1;
}

.moderation-section {
  margin-top: 40px;
  animation: fadeInUp 0.8s ease-out 0.2s both;
}

.moderation-page {
  max-width: 1180px;
  margin: 0 auto 80px;
  padding: 0 16px;
  display: grid;
  gap: 26px;
}

.moderation-page .home-hero {
  background: linear-gradient(135deg, #2eaf7d 0%, #43c59e 50%, #8cdf94 100%);
  border-radius: 20px;
  padding: 50px 40px;
  color: #fff;
  box-shadow: 0 10px 40px rgba(46, 175, 125, 0.3);
  position: relative;
  overflow: hidden;
  animation: slideInDown 0.6s ease-out;
}

.moderation-page .home-hero::before {
  content: '';
  position: absolute;
  top: -50%;
  right: -50%;
  width: 200%;
  height: 200%;
  background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
  animation: rotate 20s linear infinite;
}

.moderation-page .home-kicker {
  color: #fff;
  position: relative;
  z-index: 1;
  animation: fadeIn 0.5s ease-out;
}

.moderation-page .home-hero h1 {
  margin: 0 0 10px 0;
  font-size: 2.5rem;
  font-weight: 700;
  text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  position: relative;
  z-index: 1;
  color: #fff;
  animation: fadeInDown 0.6s ease-out 0.1s both;
}

.moderation-page .home-hero p {
  font-size: 1.1rem;
  margin: 0;
  opacity: 0.95;
  position: relative;
  z-index: 1;
  color: #fff;
  animation: fadeInUp 0.6s ease-out 0.2s both;
}

.moderation-page .home-feature {
  border: 1px solid #e7f3ed;
  background: #ffffff;
  box-shadow: 0 6px 20px rgba(44,62,80,0.06);
  animation: fadeInUp 0.8s ease-out 0.2s both;
}

.moderation-page .home-feature h2 {
  font-size: 1.45rem;
  margin-bottom: 16px;
  color: #1f2d3d;
}

.moderation-page .review-box {
  background: #fff;
  border: 1px solid #dff1e8;
  padding: 16px;
  gap: 12px;
}

.moderation-item {
  border: 1px solid #e0f2e9;
  border-radius: 14px;
  background: linear-gradient(135deg, #f9fefb 0%, #f3fff8 100%);
  padding: 14px;
  transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
}

.moderation-item:hover {
  transform: translateY(-4px);
  border-color: #43c59e;
  box-shadow: 0 12px 40px rgba(46, 175, 125, 0.2);
}

.moderation-meta {
  margin: 0 0 8px;
  color: #1f2d3d;
}

.moderation-message {
  margin: 0 0 10px;
  color: #415462;
  line-height: 1.5;
}

.moderation-status {
  margin: 0;
  color: #546e7a;
}

.moderation-status span {
  color: #2eaf7d;
  font-weight: 700;
  text-transform: capitalize;
}

.moderation-actions {
  justify-content: flex-start;
  margin-top: 10px;
}

.moderation-empty {
  margin: 0;
  padding: 12px;
  border-radius: 10px;
  background: #f8fbf9;
  color: #607d8b;
}

@keyframes slideInDown {
  from {
    opacity: 0;
    transform: translateY(-30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@keyframes fadeInDown {
  from {
    opacity: 0;
    transform: translateY(-15px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes rotate {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

@media (max-width: 768px) {
  .moderation-page .home-hero {
    padding: 35px 25px;
  }

  .moderation-page .home-hero h1 {
    font-size: 2rem;
  }
}
</style>

<section class="home-container moderation-page">
  <header class="home-hero">
    <p class="home-kicker">ADMIN • KIỂM DUYỆT</p>
    <h1>Kiểm duyệt nội dung</h1>
    <p>Duyệt phản hồi, khiếu nại, nội dung gửi lên.</p>
  </header>
  
  <section class="home-feature">
    <h2>Đánh giá từ khách hàng (Reviews)</h2>
    <div class="review-box">
      <?php if (empty($reviews)): ?>
        <p class="moderation-empty">Chưa có đánh giá nào.</p>
      <?php else: ?>
        <?php foreach ($reviews as $r): ?>
          <article class="moderation-item">
            <p class="moderation-meta">
              <strong><?= str_repeat('⭐', (int)($r['rating'] ?? 0)) ?> - <?= View::e((string)($r['service_name'] ?? 'N/A')) ?></strong> • 
              Booking #<?= (int)($r['booking_id'] ?? 0) ?>
            </p>
            <p class="moderation-message" style="color:#546e7a;margin-bottom:8px;">
              <strong>Khách hàng:</strong> <?= View::e((string)($r['customer_name'] ?? '')) ?> (<?= View::e((string)($r['customer_email'] ?? '')) ?>)
              <?php if (!empty($r['worker_name'])): ?>
                → <strong>Worker:</strong> <?= View::e((string)$r['worker_name']) ?>
              <?php endif; ?>
            </p>
            <?php if (!empty($r['comment'])): ?>
              <p class="moderation-message">"<?= View::e((string)$r['comment']) ?>"</p>
            <?php endif; ?>
            <p class="moderation-status">
              Đánh giá vào: <span><?= View::e((string)($r['created_at'] ?? '')) ?></span>
            </p>
            <div class="hero-actions moderation-actions">
              <a class="home-btn" href="/admin/bookings/<?= (int)($r['booking_id'] ?? 0) ?>">Xem đơn</a>
              <a class="home-btn home-btn-outline" href="#">Ẩn đánh giá</a>
              <a class="home-btn home-btn-outline" href="#">Gắn cờ</a>
            </div>
          </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>

  <section class="home-feature">
    <h2>Tin nhắn khiếu nại trong đơn hàng</h2>
    <div class="review-box">
      <?php if (empty($bookingMessages)): ?>
        <p class="moderation-empty">Không có khiếu nại nào.</p>
      <?php else: ?>
        <?php foreach ($bookingMessages as $msg): ?>
          <article class="moderation-item">
            <p class="moderation-meta">
              <strong>Booking #<?= (int)($msg['booking_id'] ?? 0) ?></strong> • 
              <?= View::e((string)($msg['service_name'] ?? 'N/A')) ?>
            </p>
            <p class="moderation-message" style="color:#546e7a;margin-bottom:8px;">
              <strong>Người gửi:</strong> <?= View::e((string)($msg['sender_name'] ?? '')) ?> 
              (<span style="text-transform:uppercase;color:#2eaf7d;font-weight:700;"><?= View::e((string)($msg['sender_role'] ?? '')) ?></span>) - 
              <?= View::e((string)($msg['sender_email'] ?? '')) ?>
            </p>
            <p class="moderation-message">"<?= View::e((string)($msg['content'] ?? '')) ?>"</p>
            <p class="moderation-status">
              Gửi lúc: <span><?= View::e((string)($msg['created_at'] ?? '')) ?></span>
            </p>
            <div class="hero-actions moderation-actions">
              <a class="home-btn" href="/admin/bookings/<?= (int)($msg['booking_id'] ?? 0) ?>">Xem đơn hàng</a>
              <a class="home-btn home-btn-outline" href="#">Liên hệ<?= ($msg['sender_role'] ?? '') === 'worker' ? ' Worker' : ' Khách' ?></a>
              <a class="home-btn home-btn-outline" href="#">Xử lý</a>
            </div>
          </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>

  <section class="home-feature">
    <h2>📋 Báo cáo hoàn thành từ Worker</h2>
    <div class="review-box">
      <?php if (empty($reports)): ?>
        <p class="moderation-empty">Chưa có báo cáo nào.</p>
      <?php else: ?>
        <?php foreach ($reports as $rep): ?>
          <article class="moderation-item">
            <p class="moderation-meta">
              <strong>Booking #<?= (int)($rep['booking_id'] ?? 0) ?> - <?= View::e((string)($rep['service_name'] ?? 'N/A')) ?></strong>
            </p>
            <p class="moderation-message" style="color:#546e7a;margin-bottom:8px;">
              <strong>Worker:</strong> <?= View::e((string)($rep['worker_name'] ?? '')) ?> (<?= View::e((string)($rep['worker_email'] ?? '')) ?>)
              <?php if (!empty($rep['customer_name'])): ?>
                • <strong>Khách hàng:</strong> <?= View::e((string)$rep['customer_name']) ?>
              <?php endif; ?>
            </p>
            <?php if (!empty($rep['difficulties'])): ?>
              <div style="background:#fff3cd;padding:12px;border-radius:8px;border-left:4px solid #ffc107;margin:8px 0;">
                <p class="moderation-message" style="margin:0;white-space:pre-wrap;"><?= View::e((string)$rep['difficulties']) ?></p>
              </div>
            <?php endif; ?>
            <?php if (!empty($rep['summary'])): ?>
              <p class="moderation-message"><strong>Tóm tắt:</strong> <?= View::e((string)$rep['summary']) ?></p>
            <?php endif; ?>
            <p class="moderation-status">
              Báo cáo vào: <span><?= View::e((string)($rep['created_at'] ?? '')) ?></span>
            </p>
            <div class="hero-actions moderation-actions">
              <a class="home-btn" href="/admin/bookings/<?= (int)($rep['booking_id'] ?? 0) ?>">Xem đơn hàng</a>
              <a class="home-btn home-btn-outline" href="#">Liên hệ Worker</a>
              <a class="home-btn home-btn-outline" href="#">Gắn cờ</a>
            </div>
          </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>
</section>