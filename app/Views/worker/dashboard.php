<?php
use App\Core\View;
use App\Models\Booking;
use App\Models\User;

/** @var int $uid ID người dùng */
/** @var string $role Vai trò hiện tại */
/** @var string $name Tên hiển thị */
?>
<section class="home-container">
  <header class="home-hero">
    <p class="home-kicker">WORKER DASHBOARD</p>
    <h1>Xin chào, <?= View::e($name) ?></h1>
    <p>Xem lịch làm việc, nhận việc, cập nhật tiến độ công việc.</p>
    <div class="hero-actions">
      <a class="home-btn" href="/worker/jobs">🎯 Nhận việc</a>
      <a class="home-btn home-btn-outline" href="/worker/progress">📊 Xem tiến độ</a>
    </div>
  </header>

  <section class="home-feature" aria-label="Chức năng công việc">
    <h2>Chức năng chính</h2>
    <div class="feature-grid">
      <a href="/worker/jobs" class="feature-card feature-card-link">
        <h3>🎯 Nhận công việc</h3>
        <p>Duyệt danh sách đơn chờ và nhận việc.</p>
      </a>
      <a href="/worker/schedule" class="feature-card feature-card-link">
        <h3>📅 Lịch làm việc</h3>
        <p>Xem lịch làm việc từ các đơn được phân công.</p>
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

  <section class="home-feature" aria-label="Lịch hôm nay">
    <h2>📍 Lịch công việc hôm nay</h2>
    <div class="feature-grid">
      <article class="feature-card">
        <h3>10:00 - 12:30 🏠</h3>
        <p>Quận 1: Tổng vệ sinh căn hộ 80m²</p>
      </article>
      <article class="feature-card">
        <h3>14:00 - 16:00 🛋️</h3>
        <p>Quận 7: Giặt sofa 3 chỗ + khử mùi</p>
      </article>
    </div>
  </section>
</section>