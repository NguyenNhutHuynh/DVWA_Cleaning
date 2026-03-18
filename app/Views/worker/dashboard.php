<?php
use App\Core\View;
use App\Models\Booking;
use App\Models\User;

/** @var int $uid ID người dùng */
/** @var string $role Vai trò hiện tại */
/** @var string $name Tên hiển thị */
/** @var array $todayBookings Công việc hôm nay */
/** @var int $newJobs Số công việc chưa xem */
/** @var int $activeJobs Số công việc đang thực hiện */
/** @var int $completedThisWeek Công việc hoàn thành trong tuần */
?>
<section class="home-container">
  <header class="home-hero">
    <p class="home-kicker">WORKER DASHBOARD</p>
    <h1>Xin chào, <?= View::e($name) ?></h1>
    <p>Xem lịch làm việc, nhận việc, cập nhật tiến độ công việc.</p>
    <div class="hero-actions">
      <a class="home-btn" href="/worker/jobs">🎯 Nhận việc<?php if ($newJobs > 0): ?> <span style="background: #ff6b6b; border-radius: 50%; width: 20px; height: 20px; display: inline-flex; align-items: center; justify-content: center; color: white; font-size: 12px; font-weight: bold;"><?= $newJobs ?></span><?php endif; ?></a>
      <a class="home-btn home-btn-outline" href="/worker/progress">📊 Xem tiến độ</a>
    </div>
  </header>

  <?php if (count($todayBookings) === 0): ?>
  <section class="home-feature" aria-label="Thống kê">
    <h2>📈 Thống kê</h2>
    <div class="feature-grid">
      <article class="feature-card">
        <h3><?= $newJobs ?></h3>
        <p>Công việc chưa xem</p>
      </article>
      <article class="feature-card">
        <h3><?= $activeJobs ?></h3>
        <p>Công việc đang thực hiện</p>
      </article>
      <article class="feature-card">
        <h3><?= $completedThisWeek ?></h3>
        <p>Hoàn thành tuần này</p>
      </article>
    </div>
  </section>

  <section class="home-feature" aria-label="Không có công việc hôm nay">
    <div style="text-align: center; padding: 40px 20px;">
      <p style="font-size: 48px; margin: 0;">😌</p>
      <h3>Không có công việc hôm nay</h3>
      <p>Bạn có thể kiểm tra các công việc khác hoặc chỉnh sửa hồ sơ cá nhân.</p>
    </div>
  </section>
  <?php else: ?>

  <section class="home-feature" aria-label="Thống kê">
    <h2>📈 Thống kê</h2>
    <div class="feature-grid">
      <article class="feature-card">
        <h3><?= $newJobs ?></h3>
        <p>Công việc chưa xem</p>
      </article>
      <article class="feature-card">
        <h3><?= $activeJobs ?></h3>
        <p>Công việc đang thực hiện</p>
      </article>
      <article class="feature-card">
        <h3><?= $completedThisWeek ?></h3>
        <p>Hoàn thành tuần này</p>
      </article>
    </div>
  </section>

  <section class="home-feature" aria-label="Lịch công việc hôm nay">
    <h2>📍 Lịch công việc hôm nay (<?= count($todayBookings) ?> việc)</h2>
    <div class="feature-grid">
      <?php foreach ($todayBookings as $booking): ?>
      <a href="/worker/jobs/<?= $booking['id'] ?>" class="feature-card feature-card-link" style="text-align: left;">
        <h3><?= View::e($booking['time']) ?></h3>
        <p><strong>Dịch vụ:</strong> <?= View::e($booking['service_name'] ?? 'N/A') ?></p>
        <p><strong>Địa điểm:</strong> <?= View::e($booking['location'] ?? 'N/A') ?></p>
        <p><strong>Khách:</strong> <?= View::e($booking['user_name'] ?? 'N/A') ?></p>
        <?php 
          $statusText = match($booking['status']) {
            Booking::STATUS_CONFIRMED => '⏳ Chờ xác nhận',
            Booking::STATUS_ACCEPTED => '✅ Đã nhận',
            Booking::STATUS_IN_PROGRESS => '🔄 Đang thực hiện',
            default => $booking['status']
          };
        ?>
        <p style="margin-top: 10px; color: #666; font-size: 12px;"><?= $statusText ?></p>
      </a>
      <?php endforeach; ?>
    </div>
  </section>

  <?php endif; ?>

  <section class="home-feature" aria-label="Chức năng công việc">
    <h2>Chức năng khác</h2>
    <div class="feature-grid">
      <a href="/worker/schedule" class="feature-card feature-card-link">
        <h3>📅 Lịch làm việc</h3>
        <p>Xem toàn bộ lịch làm việc của bạn.</p>
      </a>
      <a href="/worker/progress" class="feature-card feature-card-link">
        <h3>📊 Cập nhật tiến độ</h3>
        <p>Báo cáo trạng thái công việc theo thời gian.</p>
      </a>
      <a href="/account" class="feature-card feature-card-link">
        <h3>👤 Hồ sơ cá nhân</h3>
        <p>Xem/chỉnh sửa thông tin và ảnh đại diện.</p>
      </a>
    </div>
  </section>
</section>