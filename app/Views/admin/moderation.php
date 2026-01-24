<?php
use App\Core\View;
/** @var array $contacts */
?>
<section class="home-container">
  <header class="home-hero">
    <p class="home-kicker">ADMIN • KIỂM DUYỆT</p>
    <h1>Kiểm duyệt nội dung</h1>
    <p>Duyệt phản hồi, khiếu nại, nội dung gửi lên.</p>
  </header>

  <section class="home-feature">
    <h2>Danh sách nội dung</h2>
    <div class="review-box">
      <?php foreach ($contacts as $c): ?>
        <div>
          <strong><?= View::e($c['subject']) ?></strong> • <?= View::e($c['name']) ?> (<?= View::e($c['email']) ?>)
          <p style="margin:6px 0;">"<?= View::e($c['message']) ?>"</p>
          <p>Trạng thái: <span style="color:#2eaf7d;font-weight:600;"><?= View::e($c['status']) ?></span></p>
          <div class="hero-actions" style="justify-content:flex-start;margin-top:6px;">
            <a class="home-btn" href="#">Duyệt</a>
            <a class="home-btn home-btn-outline" href="#">Ẩn</a>
            <a class="home-btn home-btn-outline" href="#">Gắn cờ</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
</section>