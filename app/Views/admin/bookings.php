<?php
use App\Core\View;
/** @var array $bookings Danh sách đơn đặt */
/** @var array $workers Danh sách worker */
/** @var string $csrf Token CSRF */
?>

<style>
.admin-bookings {
  max-width: 1200px;
  margin: 0 auto 70px;
  padding: 0 16px;
}

.bookings-hero {
  background: linear-gradient(135deg, #2eaf7d 0%, #43c59e 50%, #8cdf94 100%);
  border-radius: 20px;
  padding: 50px 40px;
  color: #fff;
  position: relative;
  overflow: hidden;
  box-shadow: 0 10px 40px rgba(46, 175, 125, 0.3);
  animation: slideInDown 0.6s ease-out;
}

.bookings-hero::before {
  content: '';
  position: absolute;
  top: -50%;
  right: -50%;
  width: 200%;
  height: 200%;
  background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
  animation: rotate 20s linear infinite;
}

.bookings-hero .home-kicker {
  position: relative;
  z-index: 1;
  color: #fff;
  animation: fadeIn 0.5s ease-out;
}

.bookings-hero h1 {
  font-size: 2.5rem;
  margin: 0 0 10px 0;
  font-weight: 700;
  position: relative;
  z-index: 1;
  text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  animation: fadeInDown 0.6s ease-out 0.1s both;
}

.bookings-hero p {
  font-size: 1.1rem;
  margin: 0;
  opacity: 0.95;
  position: relative;
  z-index: 1;
  animation: fadeInUp 0.6s ease-out 0.2s both;
}

.bookings-section {
  margin-top: 40px;
  animation: fadeInUp 0.8s ease-out 0.2s both;
}

.bookings-title {
  margin: 0 0 14px;
  font-size: 1.5rem;
  color: #1f2d3d;
}

.bookings-list {
  display: grid;
  gap: 14px;
  background: linear-gradient(180deg, #f7fdf9 0%, #f0fff4 100%);
  border: 1px solid #d9efe5;
  border-radius: 16px;
  padding: 16px;
  box-shadow: 0 8px 24px rgba(32, 85, 66, 0.08);
}

.booking-card {
  border: 1px solid #dcefe6;
  border-radius: 14px;
  background: #fff;
  padding: 14px;
  transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
}

.booking-card:hover {
  transform: translateY(-4px);
  border-color: #43c59e;
  box-shadow: 0 12px 40px rgba(46, 175, 125, 0.2);
}

.booking-main {
  margin: 0;
  color: #1f2d3d;
  line-height: 1.55;
}

.booking-main strong {
  color: #0f1f2f;
}

.status-chip {
  color: #2eaf7d;
  font-weight: 700;
  text-transform: capitalize;
}

.worker-chip {
  display: inline-block;
  margin-top: 6px;
  padding: 3px 10px;
  border-radius: 999px;
  font-size: 13px;
  font-weight: 700;
  background: #e7f8ef;
  color: #2eaf7d;
}

.booking-actions {
  justify-content: flex-start;
  margin-top: 10px;
  gap: 8px;
  flex-wrap: wrap;
}

.booking-actions form {
  display: inline-block;
}

.assign-form {
  display: flex !important;
  align-items: center;
  gap: 8px;
  flex-wrap: wrap;
}

.worker-select {
  padding: 8px 10px;
  border: 1px solid #e0f2e9;
  border-radius: 10px;
  background: #fff;
  min-width: 220px;
}

.empty-bookings {
  margin: 0;
  color: #546e7a;
  background: #fff;
  border: 1px dashed #b9dcca;
  border-radius: 12px;
  padding: 16px;
  text-align: center;
}

@keyframes slideInDown {
  from {
    opacity: 0;
    transform: translateY(-30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@keyframes fadeInDown {
  from {
    opacity: 0;
    transform: translateY(-15px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes rotate {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

@media (max-width: 768px) {
  .bookings-hero {
    padding: 35px 25px;
  }

  .assign-form {
    width: 100%;
    flex-direction: column;
    align-items: stretch;
  }

  .worker-select {
    min-width: 0;
    width: 100%;
  }
}
</style>

<section class="home-container admin-bookings">
  <header class="home-hero bookings-hero">
    <p class="home-kicker">ADMIN • ĐƠN ĐẶT</p>
    <h1>Quản lý đơn đặt</h1>
    <p>Xác nhận, hủy, cập nhật trạng thái.</p>
  </header>

  <section class="bookings-section" aria-label="Danh sách đơn đặt">
    <h2 class="bookings-title">Danh sách đơn đặt</h2>
    <div class="bookings-list">
      <?php if (empty($bookings)): ?>
        <p class="empty-bookings">Chưa có đơn đặt nào.</p>
      <?php else: ?>
        <?php foreach ($bookings as $b): ?>
          <article class="booking-card">
            <p class="booking-main">
              <strong>#<?= View::e($b['id']) ?></strong>
              • <?= View::e($b['date']) ?> <?= View::e($b['time']) ?>
              • <?= View::e($b['location']) ?>
              • Dịch vụ: <?= View::e($b['service_name'] ?? '') ?>
              • Trạng thái: <span class="status-chip"><?= View::e($b['status']) ?></span>
            </p>
            <?php if (!empty($b['assigned_worker_id'])): ?>
              <span class="worker-chip">Đã gán cho Worker #<?= View::e($b['assigned_worker_id']) ?></span>
            <?php endif; ?>

            <div class="hero-actions booking-actions">
              <form method="POST" action="/admin/bookings/confirm">
                <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
                <input type="hidden" name="id" value="<?= (int)$b['id'] ?>">
                <button class="home-btn" type="submit">Xác nhận</button>
              </form>

              <form method="POST" action="/admin/bookings/cancel">
                <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
                <input type="hidden" name="id" value="<?= (int)$b['id'] ?>">
                <button class="home-btn home-btn-outline" type="submit">Hủy</button>
              </form>

              <form method="POST" action="/admin/bookings/assign" class="assign-form">
                <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
                <input type="hidden" name="id" value="<?= (int)$b['id'] ?>">
                <select name="worker_id" required class="worker-select">
                  <option value="">-- Chọn worker --</option>
                  <?php foreach (($workers ?? []) as $w): ?>
                    <option value="<?= (int)$w['id'] ?>">Worker #<?= (int)$w['id'] ?> · <?= View::e($w['name']) ?></option>
                  <?php endforeach; ?>
                </select>
                <button class="home-btn home-btn-outline" type="submit">Gán worker</button>
              </form>
            </div>
          </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>
</section>