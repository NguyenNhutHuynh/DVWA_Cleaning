<?php
use App\Core\View;
/** @var int $uid ID người dùng */
/** @var string $role Vai trò hiện tại */
/** @var string $name Tên hiển thị */
?>
<section class="home-container">
  <header class="home-hero">
    <p class="home-kicker">CUSTOMER DASHBOARD</p>
    <h1>Xin chào, <?= View::e($name) ?></h1>
    <p>Quản lý lịch đặt, duyệt dịch vụ và xem hóa đơn của bạn.</p>
    <div class="hero-actions">
      <a class="home-btn" href="/book">✨ Đặt lịch mới</a>
      <a class="home-btn home-btn-outline" href="/bookings">📋 Xem lịch đã đặt</a>
    </div>
  </header>

  <section class="home-feature" aria-label="Chức năng chính">
    <h2>Chức năng của bạn</h2>
    <div class="feature-grid">
      <a href="/book" class="feature-card feature-card-link">
        <h3>📅 Đặt dịch vụ mới</h3>
        <p>Chọn dịch vụ, thời gian, địa điểm phù hợp.</p>
      </a>
      <a href="/bookings" class="feature-card feature-card-link">
        <h3>📜 Lịch sử đặt lịch</h3>
        <p>Xem tất cả đơn đặt, trạng thái và chi tiết.</p>
      </a>
      <a href="/services" class="feature-card feature-card-link">
        <h3>🧹 Danh mục dịch vụ</h3>
        <p>Khám phá các dịch vụ vệ sinh chuyên nghiệp.</p>
      </a>
      <a href="/pricing" class="feature-card feature-card-link">
        <h3>💰 Bảng giá</h3>
        <p>Xem chi tiết giá, gói cước và ưu đãi.</p>
      </a>
    </div>
  </section>

  <section class="home-feature" aria-label="Gợi ý dịch vụ">
    <h2>Dịch vụ nổi bật</h2>
    <div class="feature-grid">
      <article class="feature-card">
        <h3>✨ Tổng vệ sinh</h3>
        <p>Vệ sinh toàn bộ nhà cửa, nhanh gọn, an toàn.</p>
      </article>
      <article class="feature-card">
        <h3>🛏️ Giặt nệm</h3>
        <p>Khử khuẩn, khô nhanh, trả lại mềm mại.</p>
      </article>
      <article class="feature-card">
        <h3>🪟 Lau kính</h3>
        <p>Lau sạch kính cửa, bảng hiệu chuyên nghiệp.</p>
      </article>
      <article class="feature-card">
        <h3>🛋️ Giặt sofa</h3>
        <p>Khử mùi, diệt khuẩn, không làm hỏng chất liệu.</p>
      </article>
    </div>
  </section>
</section>