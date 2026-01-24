<?php
use App\Core\View;
/** @var int $uid */
/** @var string $role */
/** @var string $name */
?>
<section class="home-container">
  <header class="home-hero">
    <p class="home-kicker">CUSTOMER DASHBOARD</p>
    <h1>Xin chào, <?= View::e($name) ?></h1>
    <p>Quản lý lịch đặt, xem hóa đơn và ưu đãi.</p>
    <div class="hero-actions">
      <a class="home-btn" href="/book">Đặt lịch mới</a>
      <a class="home-btn home-btn-outline" href="/bookings">Xem lịch đã đặt</a>
    </div>
  </header>

  <section class="home-feature" aria-label="Đề xuất">
    <h2>Gợi ý dịch vụ</h2>
    <div class="feature-grid">
      <article class="feature-card"><h3>🧹 Tổng vệ sinh</h3><p>Ưu đãi 10% cho lần tiếp theo.</p></article>
      <article class="feature-card"><h3>🛏️ Giặt nệm</h3><p>Khử khuẩn, khô nhanh, an toàn.</p></article>
    </div>
  </section>
</section>