<?php
use App\Core\View;
/** @var array $readyJobs */
/** @var array $activeJobs */
/** @var string $csrf */
?>

<style>
.worker-jobs-page {
  --primary: #2eaf7d;
  --primary-dark: #16805a;
  --primary-soft: #e8f7f0;
  --bg-soft: #f7fdf9;
  --text-dark: #1f2d3d;
  --text-muted: #546e7a;
  --border: #dcefe6;
  --white: #ffffff;
  --blue: #2563eb;
  --shadow-sm: 0 8px 24px rgba(31,45,61,0.08);
  --shadow-md: 0 16px 40px rgba(31,45,61,0.12);

  max-width: 1180px;
  margin: 0 auto;
  padding: 24px 16px 60px;
  color: var(--text-dark);
}

.worker-jobs-page * {
  box-sizing: border-box;
}

.worker-jobs-page .home-hero {
  position: relative;
  overflow: hidden;
  padding: 56px 28px;
  border-radius: 28px;
  text-align: center;
  background:
    radial-gradient(circle at top left, rgba(46,175,125,0.20), transparent 34%),
    linear-gradient(135deg, #f7fdf9 0%, #ffffff 48%, #e8f7f0 100%);
  border: 1px solid var(--border);
  box-shadow: var(--shadow-sm);
}

.worker-jobs-page .home-hero::after {
  content: "";
  position: absolute;
  right: -80px;
  bottom: -80px;
  width: 220px;
  height: 220px;
  border-radius: 50%;
  background: rgba(46,175,125,0.13);
}

.worker-jobs-page .home-kicker {
  position: relative;
  display: inline-flex;
  margin: 0 0 14px;
  padding: 7px 14px;
  border-radius: 999px;
  background: var(--primary-soft);
  color: var(--primary-dark);
  font-size: 13px;
  font-weight: 900;
  letter-spacing: 0.08em;
}

.worker-jobs-page .home-hero h1 {
  position: relative;
  margin: 0 0 12px;
  color: var(--text-dark);
  font-size: clamp(32px, 5vw, 52px);
  line-height: 1.1;
  font-weight: 900;
  letter-spacing: -0.04em;
}

.worker-jobs-page .home-hero p {
  position: relative;
  margin: 0;
  color: var(--text-muted);
  font-size: 17px;
}

.worker-section {
  margin-top: 40px;
}

.worker-section-card {
  padding: 34px;
  border-radius: 26px;
  background: white;
  border: 1px solid var(--border);
  box-shadow: var(--shadow-sm);
}

.worker-section h2 {
  margin: 0 0 24px;
  color: var(--text-dark);
  font-size: clamp(24px, 3vw, 34px);
  font-weight: 900;
  letter-spacing: -0.03em;
}

.jobs-list {
  display: grid;
  gap: 18px;
}

.job-card {
  padding: 24px;
  border-radius: 22px;
  background: #ffffff;
  border: 1px solid var(--border);
  box-shadow: 0 5px 16px rgba(31,45,61,0.05);
  transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
}

.job-card:hover {
  transform: translateY(-4px);
  border-color: rgba(46,175,125,0.45);
  box-shadow: var(--shadow-md);
}

.job-head {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 16px;
  margin-bottom: 14px;
}

.job-code {
  display: inline-flex;
  padding: 7px 14px;
  border-radius: 999px;
  background: var(--primary-soft);
  color: var(--primary-dark);
  font-size: 14px;
  font-weight: 900;
}

.job-time {
  margin: 10px 0 0;
  color: var(--text-dark);
  font-weight: 900;
}

.job-location {
  margin: 8px 0 0;
  color: var(--text-muted);
  line-height: 1.6;
}

.job-status {
  display: inline-flex;
  padding: 7px 13px;
  border-radius: 999px;
  background: var(--primary-soft);
  color: var(--primary-dark);
  font-size: 13px;
  font-weight: 900;
  white-space: nowrap;
}

.job-info-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
  gap: 12px;
  margin-top: 14px;
}

.job-info-item {
  padding: 14px 16px;
  border-radius: 16px;
  background: var(--bg-soft);
  border: 1px solid var(--border);
}

.job-info-label {
  display: block;
  margin-bottom: 5px;
  color: var(--text-muted);
  font-size: 13px;
  font-weight: 700;
}

.job-info-value {
  color: var(--text-dark);
  font-weight: 900;
  line-height: 1.5;
}

.job-price {
  color: var(--primary);
}

.job-actions {
  display: flex;
  justify-content: flex-start;
  align-items: center;
  gap: 10px;
  flex-wrap: wrap;
  margin-top: 18px;
}

.job-actions form {
  margin: 0;
}

.worker-btn {
  min-height: 44px;
  padding: 11px 22px;
  border-radius: 999px;
  border: none;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  font-size: 14px;
  font-weight: 900;
  cursor: pointer;
  white-space: nowrap;
  transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
}

.worker-btn:hover {
  transform: translateY(-2px);
}

.worker-btn-primary {
  background: linear-gradient(135deg, var(--primary), var(--primary-dark));
  color: white;
  box-shadow: 0 10px 22px rgba(46,175,125,0.22);
}

.worker-btn-outline {
  background: white;
  color: var(--primary);
  border: 1.5px solid var(--primary);
}

.worker-btn-outline:hover {
  background: var(--primary-soft);
}

.worker-btn-blue {
  background: var(--blue);
  color: white;
  box-shadow: 0 10px 22px rgba(37,99,235,0.18);
}

.empty-state {
  padding: 34px 20px;
  text-align: center;
  color: var(--text-muted);
  background: var(--bg-soft);
  border: 1px dashed #cfe3d8;
  border-radius: 20px;
  margin: 0;
}

@media (max-width: 768px) {
  .worker-jobs-page {
    padding: 16px 12px 44px;
  }

  .worker-jobs-page .home-hero {
    padding: 42px 18px;
    border-radius: 22px;
  }

  .worker-section-card {
    padding: 22px;
    border-radius: 20px;
  }

  .job-card {
    padding: 20px;
    border-radius: 18px;
  }

  .job-head {
    flex-direction: column;
  }

  .job-actions,
  .job-actions form,
  .worker-btn {
    width: 100%;
  }
}
</style>

<section class="home-container worker-jobs-page">
  <header class="home-hero">
    <p class="home-kicker">WORKER • NHẬN VIỆC</p>
    <h1>Việc khả dụng</h1>
    <p>Nhận các đơn đã được admin xác nhận và bắt đầu thực hiện.</p>
  </header>

  <section class="worker-section">
    <div class="worker-section-card">
      <h2>Đơn chờ nhận việc</h2>

      <div class="jobs-list">
        <?php if (empty($readyJobs)): ?>
          <p class="empty-state">Hiện chưa có đơn nào chờ bạn nhận.</p>
        <?php endif; ?>

        <?php foreach ($readyJobs as $j): ?>
          <article class="job-card">
            <div class="job-head">
              <div>
                <span class="job-code">#<?= View::e($j['id']) ?></span>
                <p class="job-time">📅 <?= View::e($j['date']) ?> • <?= View::e($j['time']) ?></p>
                <p class="job-location">📍 <?= View::e($j['location']) ?></p>
              </div>
            </div>

            <div class="job-info-grid">
              <div class="job-info-item">
                <span class="job-info-label">Khách</span>
                <span class="job-info-value"><?= View::e($j['user_name'] ?? '') ?></span>
              </div>

              <div class="job-info-item">
                <span class="job-info-label">SĐT</span>
                <span class="job-info-value"><?= View::e($j['user_phone'] ?? '') ?></span>
              </div>

              <div class="job-info-item">
                <span class="job-info-label">Dịch vụ</span>
                <span class="job-info-value"><?= View::e($j['service_name'] ?? '') ?></span>
              </div>

              <div class="job-info-item">
                <span class="job-info-label">Thu khách</span>
                <span class="job-info-value job-price">
                  <?php if (in_array($j['status'] ?? '', ['confirmed', 'accepted', 'in_progress', 'completed'], true)): ?>
                    Đã thanh toán
                  <?php else: ?>
                    <?= number_format((float)($j['service_price'] ?? 0), 0, ',', '.') ?>đ
                  <?php endif; ?>
                </span>
              </div>
            </div>

            <div class="job-actions">
              <form method="post" action="/worker/jobs/<?= (int)$j['id'] ?>/accept">
                <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
                <button class="worker-btn worker-btn-primary" type="submit">Nhận việc</button>
              </form>

              <a class="worker-btn worker-btn-outline" href="/worker/jobs/<?= (int)$j['id'] ?>">Xem chi tiết</a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="worker-section">
    <div class="worker-section-card">
      <h2>Đơn đang thực hiện</h2>

      <div class="jobs-list">
        <?php if (empty($activeJobs)): ?>
          <p class="empty-state">Bạn chưa có đơn đang thực hiện.</p>
        <?php endif; ?>

        <?php foreach ($activeJobs as $j): ?>
          <article class="job-card">
            <div class="job-head">
              <div>
                <span class="job-code">#<?= View::e($j['id']) ?></span>
                <p class="job-time">📅 <?= View::e($j['date']) ?> • <?= View::e($j['time']) ?></p>
              </div>

              <span class="job-status"><?= View::e($j['status']) ?></span>
            </div>

            <div class="job-info-grid">
              <div class="job-info-item">
                <span class="job-info-label">Dịch vụ</span>
                <span class="job-info-value"><?= View::e($j['service_name'] ?? '') ?></span>
              </div>

              <div class="job-info-item">
                <span class="job-info-label">Địa chỉ</span>
                <span class="job-info-value"><?= View::e($j['location'] ?? '') ?></span>
              </div>
            </div>

            <div class="job-actions">
              <a class="worker-btn worker-btn-blue" href="/worker/jobs/<?= (int)$j['id'] ?>">Vào job</a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
</section>