<?php
use App\Core\View;
use App\Models\Service;
/** @var array $services Danh sách dịch vụ */
/** @var string $csrf Token CSRF */
?>

<style>
.admin-services {
  --primary: #2eaf7d;
  --primary-dark: #16805a;
  --primary-soft: #e8f7f0;
  --bg-soft: #f7fdf9;
  --text-dark: #1f2d3d;
  --text-muted: #546e7a;
  --border: #dcefe6;
  --white: #ffffff;
  --danger: #dc2626;
  --danger-soft: #fff1f1;
  --shadow-sm: 0 8px 24px rgba(31,45,61,0.08);
  --shadow-md: 0 16px 40px rgba(31,45,61,0.12);

  max-width: 1180px;
  margin: 0 auto;
  padding: 24px 16px 60px;
  color: var(--text-dark);
}

.admin-services * {
  box-sizing: border-box;
}

.admin-services .services-hero {
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

.admin-services .services-hero::after {
  content: "";
  position: absolute;
  right: -80px;
  bottom: -80px;
  width: 220px;
  height: 220px;
  border-radius: 50%;
  background: rgba(46,175,125,0.13);
}

.admin-services .home-kicker {
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

.admin-services .services-hero h1 {
  position: relative;
  margin: 0 0 12px;
  color: var(--text-dark);
  font-size: clamp(32px, 5vw, 52px);
  line-height: 1.1;
  font-weight: 900;
  letter-spacing: -0.04em;
}

.admin-services .services-hero p {
  position: relative;
  margin: 0;
  color: var(--text-muted);
  font-size: 17px;
  line-height: 1.6;
}

.admin-services .services-section {
  margin-top: 40px;
}

.admin-services .admin-panel {
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 26px;
  padding: 30px;
  box-shadow: var(--shadow-sm);
}

.admin-services .section-title {
  margin: 0 0 22px;
  color: var(--text-dark);
  font-size: clamp(24px, 3vw, 34px);
  font-weight: 900;
  letter-spacing: -0.03em;
}

.admin-services .service-form-box {
  background: linear-gradient(135deg, var(--bg-soft), #ffffff);
  border: 1px solid var(--border);
  border-radius: 24px;
  padding: 24px;
  box-shadow: 0 5px 16px rgba(31,45,61,0.05);
}

.admin-services .form-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
  gap: 14px;
  margin-bottom: 14px;
}

.admin-services .auth-input {
  width: 100%;
  min-height: 50px;
  padding: 13px 15px;
  border: 1px solid var(--border);
  border-radius: 16px;
  background: #fcfffd;
  color: var(--text-dark);
  font-size: 15px;
  font-family: inherit;
  transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
}

.admin-services textarea.auth-input {
  min-height: 110px;
  resize: vertical;
  margin-bottom: 14px;
}

.admin-services .auth-input:focus {
  outline: none;
  background: white;
  border-color: var(--primary);
  box-shadow: 0 0 0 4px rgba(46,175,125,0.12);
}

.admin-services .file-field {
  margin-bottom: 14px;
  padding: 16px;
  border-radius: 18px;
  background: white;
  border: 1px dashed #bfe5d5;
}

.admin-services .file-field label {
  display: block;
  margin-bottom: 8px;
  color: var(--text-dark);
  font-weight: 900;
  font-size: 14px;
}

.admin-services .file-field small {
  display: block;
  margin-top: 7px;
  color: var(--text-muted);
  font-size: 13px;
}

.admin-services .home-btn {
  min-height: 46px;
  padding: 12px 24px;
  border-radius: 999px;
  border: none;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  font-size: 15px;
  font-weight: 900;
  cursor: pointer;
  background: linear-gradient(135deg, var(--primary), var(--primary-dark));
  color: white;
  box-shadow: 0 10px 22px rgba(46,175,125,0.22);
  transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
}

.admin-services .home-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 14px 30px rgba(46,175,125,0.28);
}

.admin-services .home-btn-outline {
  background: white;
  color: var(--primary);
  border: 1.5px solid var(--primary);
  box-shadow: none;
}

.admin-services .home-btn-outline:hover {
  background: var(--primary-soft);
  box-shadow: var(--shadow-sm);
}

.admin-services .btn-delete {
  color: var(--danger);
  border-color: var(--danger);
}

.admin-services .btn-delete:hover {
  background: var(--danger-soft);
  color: var(--danger);
}

.admin-services .form-actions {
  margin-top: 14px;
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
}

.admin-services .services-list-title {
  margin-top: 40px;
}

.admin-services .services-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
  gap: 22px;
}

.admin-services .service-card {
  overflow: hidden;
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 26px;
  box-shadow: var(--shadow-sm);
  transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
}

.admin-services .service-card:hover {
  transform: translateY(-5px);
  border-color: rgba(46,175,125,0.45);
  box-shadow: var(--shadow-md);
}

.admin-services .service-card-header {
  padding: 24px;
  background:
    radial-gradient(circle at top left, rgba(46,175,125,0.16), transparent 36%),
    linear-gradient(135deg, #ffffff, var(--bg-soft));
  border-bottom: 1px solid var(--border);
}

.admin-services .service-card h3 {
  margin: 0 0 10px;
  color: var(--text-dark);
  font-size: 22px;
  font-weight: 900;
  letter-spacing: -0.02em;
  line-height: 1.35;
}

.admin-services .service-desc {
  margin: 0;
  color: var(--text-muted);
  line-height: 1.6;
}

.admin-services .service-card-body {
  padding: 24px;
}

.admin-services .service-meta {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 12px;
  margin-bottom: 18px;
}

.admin-services .service-meta-item {
  padding: 14px 15px;
  border-radius: 16px;
  background: var(--bg-soft);
  border: 1px solid var(--border);
}

.admin-services .service-meta-item strong {
  display: block;
  margin-bottom: 5px;
  color: var(--text-muted);
  font-size: 13px;
  font-weight: 800;
}

.admin-services .service-meta-item span {
  display: block;
  color: var(--text-dark);
  font-weight: 900;
  line-height: 1.5;
  word-break: break-word;
}

.admin-services .price-highlight {
  color: var(--primary) !important;
}

.admin-services .status-badge {
  display: inline-flex !important;
  width: fit-content;
  padding: 6px 12px;
  border-radius: 999px;
  background: var(--primary-soft);
  color: var(--primary-dark) !important;
  font-size: 13px;
  font-weight: 900;
}

.admin-services .status-badge.is-hidden {
  background: #f3f4f6;
  color: #6b7280 !important;
}

.admin-services .current-image-box {
  margin-bottom: 14px;
  padding: 16px;
  background: var(--bg-soft);
  border-radius: 18px;
  border: 1px solid var(--border);
}

.admin-services .current-image-box p {
  margin: 0 0 10px;
  color: var(--text-dark);
  font-weight: 900;
}

.admin-services .current-image-box img {
  width: 130px;
  height: 130px;
  object-fit: cover;
  border-radius: 16px;
  border: 1px solid var(--border);
  box-shadow: var(--shadow-sm);
}

.admin-services .service-actions {
  display: grid;
  gap: 14px;
  margin-top: 14px;
}

.admin-services .update-form {
  display: block;
  width: 100%;
  padding: 18px;
  border-radius: 22px;
  background: #fcfffd;
  border: 1px solid var(--border);
}

.admin-services .inline-actions {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 10px;
}

.admin-services .inline-form {
  display: inline-flex;
  margin: 0;
}

.admin-services .empty-state {
  background: var(--white);
  border: 1px dashed #cfe3d8;
  border-radius: 22px;
  padding: 36px 20px;
  color: var(--text-muted);
  text-align: center;
  box-shadow: var(--shadow-sm);
}

@media (max-width: 768px) {
  .admin-services {
    padding: 16px 12px 44px;
  }

  .admin-services .services-hero {
    padding: 42px 18px;
    border-radius: 22px;
  }

  .admin-services .admin-panel,
  .admin-services .service-form-box,
  .admin-services .service-card {
    border-radius: 20px;
  }

  .admin-services .admin-panel,
  .admin-services .service-card-header,
  .admin-services .service-card-body {
    padding: 22px;
  }

  .admin-services .services-grid {
    grid-template-columns: 1fr;
  }

  .admin-services .home-btn,
  .admin-services .inline-form,
  .admin-services .inline-actions {
    width: 100%;
  }

  .admin-services .inline-form .home-btn {
    width: 100%;
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
    <div class="admin-panel">
      <h2 class="section-title">Thêm dịch vụ mới</h2>

      <div class="service-form-box">
        <form method="post" action="/admin/services/create" enctype="multipart/form-data">
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

          <div class="file-field">
            <label>Ảnh dịch vụ:</label>
            <input type="file" class="auth-input" name="service_image" accept="image/jpeg,image/png,image/webp" placeholder="Chọn ảnh">
            <small>JPG, PNG, WebP. Tối đa 5MB</small>
          </div>

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
            <?php $resolvedImagePath = Service::resolveImagePath($s); ?>

            <article class="service-card">
              <div class="service-card-header">
                <h3><?= View::e(($s['icon'] ?? '') . ' ' . $s['name']) ?></h3>
                <p class="service-desc"><?= View::e($s['description']) ?></p>
              </div>

              <div class="service-card-body">
                <div class="service-meta">
                  <div class="service-meta-item">
                    <strong>ID</strong>
                    <span><?= View::e((string)($s['id'] ?? '')) ?></span>
                  </div>

                  <div class="service-meta-item">
                    <strong>Thời gian</strong>
                    <span><?= View::e($s['duration'] ?? ($s['duration_text'] ?? '—')) ?></span>
                  </div>

                  <div class="service-meta-item">
                    <strong>Đơn vị tính</strong>
                    <span><?= View::e($s['unit']) ?></span>
                  </div>

                  <div class="service-meta-item">
                    <strong>Giá cơ bản</strong>
                    <span class="price-highlight"><?= number_format((int)$s['price']) ?>đ <?= View::e($s['unit']) ?></span>
                  </div>

                  <div class="service-meta-item">
                    <strong>Tối thiểu</strong>
                    <span><?= number_format((int)($s['minimum'] ?? ($s['minimum_price'] ?? 0))) ?>đ</span>
                  </div>

                  <div class="service-meta-item">
                    <strong>Trạng thái</strong>
                    <?php if ((int)($s['is_active'] ?? 1) === 1): ?>
                      <span class="status-badge">Đang hiển thị</span>
                    <?php else: ?>
                      <span class="status-badge is-hidden">Đã ẩn</span>
                    <?php endif; ?>
                  </div>
                </div>

                <div class="service-actions">
                  <form method="post" action="/admin/services/update" class="update-form" enctype="multipart/form-data">
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

                    <?php if (!empty($s['image_path'])): ?>
                      <div class="current-image-box">
                        <p>Ảnh hiện tại:</p>
                        <img src="<?= View::e($resolvedImagePath) ?>" alt="<?= View::e($s['name']) ?>">
                      </div>
                    <?php endif; ?>

                    <div class="file-field">
                      <label>Thay đổi ảnh (tùy chọn):</label>
                      <input type="file" class="auth-input" name="service_image" accept="image/jpeg,image/png,image/webp" placeholder="Chọn ảnh">
                      <small>JPG, PNG, WebP. Tối đa 5MB</small>
                    </div>

                    <div class="form-actions">
                      <button class="home-btn" type="submit">Lưu</button>
                    </div>
                  </form>

                  <div class="inline-actions">
                    <form method="post" action="/admin/services/toggle" class="inline-form">
                      <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
                      <input type="hidden" name="id" value="<?= View::e((string)$s['id']) ?>">
                      <button class="home-btn home-btn-outline" type="submit">
                        <?= ((int)($s['is_active'] ?? 1) === 1) ? 'Ẩn' : 'Hiện' ?>
                      </button>
                    </form>

                    <form method="post" action="/admin/services/delete" class="inline-form" onsubmit="return confirm('Xóa dịch vụ này? Hành động không thể hoàn tác.');">
                      <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
                      <input type="hidden" name="id" value="<?= View::e((string)$s['id']) ?>">
                      <button class="home-btn home-btn-outline btn-delete" type="submit">Xóa</button>
                    </form>
                  </div>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </section>
</section>