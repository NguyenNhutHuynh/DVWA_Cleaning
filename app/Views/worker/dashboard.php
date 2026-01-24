<?php
use App\Core\View;
/** @var int $uid */
/** @var string $role */
/** @var string $name */
?>
<section class="home-container">
  <header class="home-hero">
    <p class="home-kicker">WORKER DASHBOARD</p>
    <h1>Xin chào, <?= View::e($name) ?></h1>
    <p>Xem lịch làm việc, nhận việc, cập nhật tiến độ.</p>
  </header>

  <section class="home-feature" aria-label="Lịch trong ngày">
    <h2>Lịch công việc hôm nay</h2>
    <div class="feature-grid">
      <article class="feature-card"><h3>10:00 - Quận 1</h3><p>Tổng vệ sinh căn hộ 80m².</p></article>
      <article class="feature-card"><h3>14:00 - Quận 7</h3><p>Giặt sofa 3 chỗ + khử mùi.</p></article>
    </div>
  </section>
</section>