<?php
use App\Core\View;
/** @var array $services */
?>
<section class="home-container">
  <header class="home-hero">
    <p class="home-kicker">ADMIN • DỊCH VỤ</p>
    <h1>Quản lý dịch vụ</h1>
    <p>Thêm, chỉnh sửa giá và mô tả dịch vụ.</p>
  </header>

  <section class="home-feature">
    <h2>Danh sách dịch vụ</h2>
    <div class="feature-grid">
      <?php foreach ($services as $s): ?>
        <article class="feature-card">
          <h3><?= View::e($s['icon'] . ' ' . $s['name']) ?></h3>
          <p><?= View::e($s['description']) ?></p>
          <p style="margin-top:8px;color:#2eaf7d;font-weight:600;">Giá: <?= number_format($s['price']) ?>đ <?= View::e($s['unit']) ?></p>
          <div class="hero-actions" style="justify-content:flex-start;">
            <a class="home-btn" href="#">Sửa</a>
            <a class="home-btn home-btn-outline" href="#">Ẩn/Hiện</a>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </section>
</section>