<?php
/** @var array $stats */
?>
<section class="home-container">
  <header class="home-hero">
    <p class="home-kicker">ADMIN • THỐNG KÊ</p>
    <h1>Thống kê nhanh</h1>
    <p>Tổng quan về dịch vụ, đơn đặt, tương tác.</p>
  </header>

  <section class="home-stats">
    <div class="stat-card"><strong><?= $stats['service_count'] ?></strong><span>Dịch vụ</span></div>
    <div class="stat-card"><strong><?= $stats['booking_count'] ?></strong><span>Đơn đặt</span></div>
    <div class="stat-card"><strong><?= $stats['contact_count'] ?></strong><span>Liên hệ</span></div>
  </section>

  <section class="home-feature" style="margin-top:16px;">
    <h2>Tỷ lệ trạng thái đơn</h2>
    <div class="feature-grid">
      <article class="feature-card"><h3>✅ Đã xác nhận</h3><p><?= $stats['confirmed_rate'] ?>%</p></article>
      <article class="feature-card"><h3>⏳ Đang chờ</h3><p><?= $stats['pending_rate'] ?>%</p></article>
    </div>
  </section>
</section>