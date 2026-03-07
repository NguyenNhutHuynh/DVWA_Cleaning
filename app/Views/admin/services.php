<?php
use App\Core\View;
/** @var array $services Danh sách dịch vụ */
/** @var string $csrf Token CSRF */
?>

<style>
.admin-services {
  max-width: 1200px;
  margin: 0 auto 70px;
  padding: 0 16px;
}

.services-hero {
  background: linear-gradient(135deg, #2eaf7d 0%, #43c59e 50%, #8cdf94 100%);
  border-radius: 20px;
  padding: 50px 40px;
  color: #fff;
  position: relative;
  overflow: hidden;
  box-shadow: 0 10px 40px rgba(46, 175, 125, 0.3);
  animation: slideInDown 0.6s ease-out;
}

.services-hero::before {
  content: '';
  position: absolute;
  top: -50%;
  right: -50%;
  width: 200%;
  height: 200%;
  background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
  animation: rotate 20s linear infinite;
}

.services-hero .home-kicker {
  position: relative;
  z-index: 1;
  color: #fff;
  animation: fadeIn 0.5s ease-out;
}

.services-hero h1 {
  font-size: 2.5rem;
  margin: 0 0 10px 0;
  font-weight: 700;
  position: relative;
  z-index: 1;
  text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  animation: fadeInDown 0.6s ease-out 0.1s both;
}

.services-hero p {
  font-size: 1.1rem;
  margin: 0;
  opacity: 0.95;
  position: relative;
  z-index: 1;
  animation: fadeInUp 0.6s ease-out 0.2s both;
}

.services-section {
  margin-top: 40px;
  animation: fadeInUp 0.8s ease-out 0.2s both;
}

.section-title {
  margin: 0 0 14px;
  font-size: 1.5rem;
  color: #1f2d3d;
}

.service-form-box {
  background: linear-gradient(180deg, #f7fdf9 0%, #f0fff4 100%);
  border: 1px solid #d9efe5;
  border-radius: 16px;
  padding: 18px;
  box-shadow: 0 8px 24px rgba(32, 85, 66, 0.08);
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
  gap: 10px;
  margin-bottom: 10px;
}

.services-list-title {
  margin: 22px 0 14px;
}

.services-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  gap: 16px;
}

.service-card {
  background: #fff;
  border-radius: 16px;
  padding: 18px;
  border: 1px solid #dcefe6;
  box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
  transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
  position: relative;
  overflow: hidden;
}

.service-card::before {
  content: '';
  position: absolute;
  left: 0;
  right: 0;
  top: 0;
  height: 4px;
  background: linear-gradient(90deg, #2eaf7d, #43c59e);
  transform: scaleX(0);
  transform-origin: left;
  transition: transform .25s ease;
}

.service-card:hover {
  transform: translateY(-4px);
  border-color: #43c59e;
  box-shadow: 0 12px 40px rgba(46, 175, 125, 0.2);
}

.service-card:hover::before {
  transform: scaleX(1);
}

.service-card h3 {
  margin: 0 0 8px;
  color: #1f2d3d;
}

.service-desc {
  margin: 0 0 10px;
  color: #4f6470;
}

.service-meta {
  display: grid;
  gap: 6px;
  color: #455a64;
  font-size: 14px;
  margin: 8px 0 12px;
}

.service-meta strong {
  color: #1f2d3d;
}

.price-highlight {
  color: #2eaf7d;
  font-weight: 700;
}

.service-actions {
  display: flex;
  align-items: flex-start;
  flex-wrap: wrap;
  gap: 10px;
  margin-top: 10px;
}

.update-form {
  display: inline-block;
  max-width: 560px;
  text-align: left;
}

.inline-form {
  display: inline-block;
}

.form-actions {
  margin-top: 10px;
}

.btn-delete {
  border-color: #e53935;
  color: #e53935;
}

.empty-state {
  background: #fff;
  border: 1px dashed #b9dcca;
  border-radius: 14px;
  padding: 20px;
  color: #456;
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

@keyframes rotate {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

@media (max-width: 768px) {
  .services-hero {
    padding: 35px 25px;
  }

  .services-grid {
    grid-template-columns: 1fr;
  }
}
</style>

<section class="home-container admin-services">
  <header class="home-hero services-hero">
    <p class="home-kicker">ADMIN • DỊCH VỤ</p>
    <h1>Quản lý dịch vụ</h1>
    <p>Thêm, chỉnh sửa giá và mô tả dịch vụ.</p>
  </header>

  <section class="services-section" aria-label="Quản lý dịch vụ">
    <h2 class="section-title">Thêm dịch vụ mới</h2>
    <div class="service-form-box">
      <form method="post" action="/admin/services/create">
        <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
        <div class="form-grid">
          <input class="auth-input" name="name" placeholder="Tên" required>
          <input class="auth-input" name="icon" placeholder="Icon (tùy chọn)">
          <input class="auth-input" name="duration" placeholder="Thời gian (vd: 2-4 giờ)">
          <input class="auth-input" name="unit" placeholder="Đơn vị (vd: /m², /chiếc)" required>
          <input class="auth-input" name="price" type="number" min="0" placeholder="Giá" required>
          <input class="auth-input" name="minimum" type="number" min="0" placeholder="Tối thiểu">
          <select class="auth-input" name="is_active">
            <option value="1">Hiển thị</option>
            <option value="0">Ẩn</option>
          </select>
        </div>
        <textarea class="auth-input" name="description" rows="3" placeholder="Mô tả" required></textarea>
        <div class="form-actions">
          <button class="home-btn" type="submit">Thêm dịch vụ</button>
        </div>
      </form>
    </div>

    <h2 class="section-title services-list-title">Danh sách dịch vụ</h2>
    <?php if (empty($services)): ?>
      <div class="empty-state">Chưa có dịch vụ nào. Hãy thêm dịch vụ đầu tiên.</div>
    <?php else: ?>
      <div class="services-grid">
        <?php foreach ($services as $s): ?>
          <article class="service-card">
          <h3><?= View::e(($s['icon'] ?? '') . ' ' . $s['name']) ?></h3>
          <p class="service-desc"><?= View::e($s['description']) ?></p>

          <div class="service-meta">
            <div><strong>ID:</strong> <?= View::e((string)($s['id'] ?? '')) ?></div>
            <div><strong>Thời gian:</strong> <?= View::e($s['duration'] ?? ($s['duration_text'] ?? '—')) ?></div>
            <div><strong>Đơn vị tính:</strong> <?= View::e($s['unit']) ?></div>
            <div><strong>Giá cơ bản:</strong> <span class="price-highlight"><?= number_format((int)$s['price']) ?>đ</span> <?= View::e($s['unit']) ?></div>
            <div><strong>Tối thiểu:</strong> <?= number_format((int)($s['minimum'] ?? ($s['minimum_price'] ?? 0))) ?>đ</div>
            <div><strong>Trạng thái:</strong> <?= ((int)($s['is_active'] ?? 1) === 1) ? 'Đang hiển thị' : 'Đã ẩn' ?></div>
          </div>

          <div class="service-actions">
            <form method="post" action="/admin/services/update" class="update-form">
              <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
              <input type="hidden" name="id" value="<?= View::e((string)$s['id']) ?>">
              <div class="form-grid">
                <input class="auth-input" name="name" placeholder="Tên" value="<?= View::e($s['name']) ?>">
                <input class="auth-input" name="icon" placeholder="Icon" value="<?= View::e($s['icon'] ?? '') ?>">
                <input class="auth-input" name="duration" placeholder="Thời gian" value="<?= View::e($s['duration'] ?? ($s['duration_text'] ?? '')) ?>">
                <input class="auth-input" name="unit" placeholder="Đơn vị" value="<?= View::e($s['unit']) ?>">
                <input class="auth-input" name="price" type="number" min="0" placeholder="Giá" value="<?= View::e((string)$s['price']) ?>">
                <input class="auth-input" name="minimum" type="number" min="0" placeholder="Tối thiểu" value="<?= View::e((string)($s['minimum'] ?? ($s['minimum_price'] ?? 0))) ?>">
              </div>
              <textarea class="auth-input" name="description" rows="3" placeholder="Mô tả"><?= View::e($s['description']) ?></textarea>
              <div class="form-actions">
                <button class="home-btn" type="submit">Lưu</button>
              </div>
            </form>

            <form method="post" action="/admin/services/toggle" class="inline-form">
              <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
              <input type="hidden" name="id" value="<?= View::e((string)$s['id']) ?>">
              <button class="home-btn home-btn-outline" type="submit"><?= ((int)($s['is_active'] ?? 1) === 1) ? 'Ẩn' : 'Hiện' ?></button>
            </form>

            <form method="post" action="/admin/services/delete" class="inline-form" onsubmit="return confirm('Xóa dịch vụ này? Hành động không thể hoàn tác.');">
              <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
              <input type="hidden" name="id" value="<?= View::e((string)$s['id']) ?>">
              <button class="home-btn home-btn-outline btn-delete" type="submit">Xóa</button>
            </form>
          </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
</section>