<?php
use App\Core\View;
/** @var int $uid */
/** @var string $role */
?>
<section class="home-container">
  <header class="home-hero">
    <p class="home-kicker">ADMIN DASHBOARD</p>
    <h1>Xin chào, <?= View::e($name) ?></h1>
    <p>Quản lý người dùng, đặt lịch, dịch vụ và báo cáo.</p>
  </header>

  <section class="home-feature" aria-label="Chức năng nhanh">
    <h2>Chức năng quản trị</h2>
    <div class="feature-grid">
      <article class="feature-card"><h3>👥 Người dùng</h3><p>Xem/khóa/mở khóa tài khoản.</p></article>
      <article class="feature-card"><h3>🗓️ Lịch đặt</h3><p>Xác nhận/hủy/ghi chú.</p></article>
      <article class="feature-card"><h3>🧹 Dịch vụ</h3><p>Thêm/sửa giá & mô tả.</p></article>
      <article class="feature-card"><h3>📈 Báo cáo</h3><p>Doanh thu, hiệu suất, đánh giá.</p></article>
    </div>
  </section>
</section>