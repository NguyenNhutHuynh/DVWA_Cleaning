<?php
use App\Core\View;
/** @var array $readyJobs */
/** @var array $activeJobs */
/** @var string $csrf */
?>
<section class="home-container">
  <header class="home-hero">
    <p class="home-kicker">WORKER • NHẬN VIỆC</p>
    <h1>Việc khả dụng</h1>
    <p>Nhận các đơn đã được admin xác nhận và bắt đầu thực hiện.</p>
  </header>

  <section class="home-feature">
    <h2>Đơn chờ nhận việc</h2>
    <div class="review-box">
      <?php if (empty($readyJobs)): ?>
        <p>Hiện chưa có đơn nào chờ bạn nhận.</p>
      <?php endif; ?>
      <?php foreach ($readyJobs as $j): ?>
        <div>
          <strong>#<?= View::e($j['id']) ?></strong>
          • <?= View::e($j['date']) ?> <?= View::e($j['time']) ?>
          • <?= View::e($j['location']) ?>
          <p style="margin:6px 0;">Khách: <?= View::e($j['user_name'] ?? '') ?> • SĐT: <?= View::e($j['user_phone'] ?? '') ?></p>
          <p style="margin:6px 0;">Dịch vụ: <?= View::e($j['service_name'] ?? '') ?> • Thu khách: <?= number_format((float)($j['service_price'] ?? 0), 0, ',', '.') ?>đ</p>
          <div class="hero-actions" style="justify-content:flex-start;margin-top:6px;">
            <form method="post" action="/worker/jobs/<?= (int)$j['id'] ?>/accept" style="display:inline-block;">
              <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
              <button class="home-btn" type="submit">Nhận việc</button>
            </form>
            <a class="home-btn home-btn-outline" href="/worker/jobs/<?= (int)$j['id'] ?>">Xem chi tiết</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="home-feature">
    <h2>Đơn đang thực hiện</h2>
    <div class="review-box">
      <?php if (empty($activeJobs)): ?>
        <p>Bạn chưa có đơn đang thực hiện.</p>
      <?php endif; ?>
      <?php foreach ($activeJobs as $j): ?>
        <div>
          <strong>#<?= View::e($j['id']) ?></strong>
          • <?= View::e($j['date']) ?> <?= View::e($j['time']) ?>
          • Trạng thái: <span style="color:#2eaf7d;font-weight:700;"><?= View::e($j['status']) ?></span>
          <p style="margin:6px 0;">Dịch vụ: <?= View::e($j['service_name'] ?? '') ?> • Địa chỉ: <?= View::e($j['location'] ?? '') ?></p>
          <div class="hero-actions" style="justify-content:flex-start;margin-top:6px;">
            <a class="home-btn" href="/worker/jobs/<?= (int)$j['id'] ?>">Vào job</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
</section>