<?php
use App\Core\View;
/** @var array $jobs */
?>
<section class="home-container">
  <header class="home-hero">
    <p class="home-kicker">WORKER • NHẬN VIỆC</p>
    <h1>Việc khả dụng</h1>
    <p>Nhận các đơn đang chờ để bắt đầu làm việc.</p>
  </header>

  <section class="home-feature">
    <h2>Danh sách việc</h2>
    <div class="review-box">
      <?php foreach ($jobs as $j): ?>
        <div>
          <strong>#<?= View::e($j['id']) ?></strong> • <?= View::e($j['date']) ?> <?= View::e($j['time']) ?> • <?= View::e($j['location']) ?>
          <p style="margin:6px 0;"><?= View::e($j['description']) ?></p>
          <div class="hero-actions" style="justify-content:flex-start;margin-top:6px;">
            <a class="home-btn" href="#">Nhận việc</a>
            <a class="home-btn home-btn-outline" href="#">Xem chi tiết</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
</section>