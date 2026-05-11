<?php
use App\Core\View;
use App\Models\Service;
/** @var array $services Danh sách dịch vụ */
/** @var string $csrf Token CSRF */
?>

<style>
  /* ===== FIX ẢNH DỊCH VỤ TO HƠN ===== */

.admin-services .current-image-box {
  text-align: center;
}

.admin-services .current-image-box img {
  width: 130px;
  height: 130px;
  object-fit: cover;
  border-radius: 16px;
  border: 1px solid var(--border);
  box-shadow: var(--shadow-sm);
}

/* Ghi đè riêng ảnh trong form sửa */
.admin-services .update-form .current-image-box {
  text-align: center;
}

.admin-services .update-form .current-image-box img {
  width: 130px;
  height: 130px;
  border-radius: 16px;
}
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
  padding: 42px 24px;
  border-radius: 24px;
  text-align: center;
  background:
    radial-gradient(circle at top left, rgba(46,175,125,0.20), transparent 34%),
    linear-gradient(135deg, #f7fdf9 0%, #ffffff 48%, #e8f7f0 100%);
  border: 1px solid var(--border);
  box-shadow: var(--shadow-sm);
}

.admin-services .home-kicker {
  display: inline-flex;
  margin: 0 0 12px;
  padding: 7px 14px;
  border-radius: 999px;
  background: var(--primary-soft);
  color: var(--primary-dark);
  font-size: 13px;
  font-weight: 900;
  letter-spacing: 0.08em;
}

.admin-services .services-hero h1 {
  margin: 0 0 10px;
  font-size: clamp(30px, 4vw, 46px);
  line-height: 1.1;
  font-weight: 900;
  letter-spacing: -0.04em;
}

.admin-services .services-hero p {
  margin: 0;
  color: var(--text-muted);
  font-size: 16px;
  line-height: 1.6;
}

.admin-services .services-section {
  margin-top: 32px;
}

.admin-services .admin-panel {
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 24px;
  padding: 24px;
  box-shadow: var(--shadow-sm);
}

.admin-services .section-title {
  margin: 0 0 18px;
  font-size: clamp(22px, 3vw, 30px);
  font-weight: 900;
  letter-spacing: -0.03em;
}

.admin-services .service-form-box {
  background: linear-gradient(135deg, var(--bg-soft), #ffffff);
  border: 1px solid var(--border);
  border-radius: 20px;
  padding: 20px;
}

.admin-services .form-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
  gap: 12px;
  margin-bottom: 12px;
}

.admin-services .auth-input {
  width: 100%;
  min-height: 46px;
  padding: 12px 14px;
  border: 1px solid var(--border);
  border-radius: 14px;
  background: #fcfffd;
  color: var(--text-dark);
  font-size: 14px;
  font-family: inherit;
}

.admin-services textarea.auth-input {
  min-height: 95px;
  resize: vertical;
  margin-bottom: 12px;
}

.admin-services .auth-input:focus {
  outline: none;
  background: white;
  border-color: var(--primary);
  box-shadow: 0 0 0 4px rgba(46,175,125,0.12);
}

.admin-services .file-field {
  margin-bottom: 12px;
  padding: 14px;
  border-radius: 16px;
  background: white;
  border: 1px dashed #bfe5d5;
}

.admin-services .file-field label {
  display: block;
  margin-bottom: 8px;
  font-weight: 900;
  font-size: 14px;
}

.admin-services .file-field small {
  display: block;
  margin-top: 6px;
  color: var(--text-muted);
  font-size: 12px;
}

.admin-services .home-btn {
  min-height: 42px;
  padding: 10px 20px;
  border-radius: 999px;
  border: none;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  font-size: 14px;
  font-weight: 900;
  cursor: pointer;
  background: linear-gradient(135deg, var(--primary), var(--primary-dark));
  color: white;
  box-shadow: 0 10px 22px rgba(46,175,125,0.22);
}

.admin-services .home-btn-outline {
  background: white;
  color: var(--primary);
  border: 1.5px solid var(--primary);
  box-shadow: none;
}

.admin-services .btn-delete {
  color: var(--danger);
  border-color: var(--danger);
}

.admin-services .btn-delete:hover {
  background: var(--danger-soft);
}

.admin-services .form-actions {
  margin-top: 12px;
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
}

.admin-services .services-list-title {
  margin-top: 32px;
}

/* ===== DANH SÁCH DỊCH VỤ GỌN HƠN ===== */

.admin-services .services-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 16px;
}

.admin-services .service-card {
  overflow: hidden;
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 18px;
  box-shadow: var(--shadow-sm);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.admin-services .service-card:hover {
  transform: translateY(-3px);
  box-shadow: var(--shadow-md);
}

.admin-services .service-card-header {
  padding: 16px 18px;
  background: linear-gradient(135deg, #ffffff, var(--bg-soft));
  border-bottom: 1px solid var(--border);
}

.admin-services .service-card h3 {
  margin: 0 0 6px;
  font-size: 18px;
  font-weight: 900;
  line-height: 1.3;
}

.admin-services .service-desc {
  margin: 0;
  color: var(--text-muted);
  font-size: 14px;
  line-height: 1.45;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.admin-services .service-card-body {
  padding: 16px 18px;
}

.admin-services .service-meta {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 8px;
  margin-bottom: 12px;
}

.admin-services .service-meta-item {
  padding: 9px 10px;
  border-radius: 12px;
  background: var(--bg-soft);
  border: 1px solid var(--border);
}

.admin-services .service-meta-item strong {
  display: block;
  margin-bottom: 3px;
  color: var(--text-muted);
  font-size: 11px;
  font-weight: 800;
}

.admin-services .service-meta-item span {
  display: block;
  color: var(--text-dark);
  font-size: 13px;
  font-weight: 900;
  line-height: 1.35;
  word-break: break-word;
}

.admin-services .price-highlight {
  color: var(--primary) !important;
}

.admin-services .status-badge {
  display: inline-flex !important;
  width: fit-content;
  padding: 4px 9px;
  border-radius: 999px;
  background: var(--primary-soft);
  color: var(--primary-dark) !important;
  font-size: 12px;
  font-weight: 900;
}

.admin-services .status-badge.is-hidden {
  background: #f3f4f6;
  color: #6b7280 !important;
}

.admin-services .service-actions {
  display: grid;
  gap: 10px;
  margin-top: 10px;
}

.admin-services .update-form {
  display: block;
  width: 100%;
  padding: 10px;
  border-radius: 14px;
  background: #fcfffd;
  border: 1px solid var(--border);
}

.admin-services .update-form .form-grid {
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: 7px;
  margin-bottom: 8px;
}

.admin-services .update-form .auth-input {
  min-height: 32px;
  padding: 7px 9px;
  border-radius: 10px;
  font-size: 12px;
}

.admin-services .update-form textarea.auth-input {
  grid-column: 1 / -1;
  min-height: 44px;
  margin-bottom: 8px;
  line-height: 1.35;
}

.admin-services .current-image-box {
  margin-bottom: 8px;
  padding: 10px;
  background: var(--bg-soft);
  border-radius: 12px;
  border: 1px solid var(--border);
}

.admin-services .current-image-box p {
  margin: 0 0 8px;
  font-weight: 900;
  font-size: 12px;
}

.admin-services .current-image-box img {
  width: 58px;
  height: 58px;
  object-fit: cover;
  border-radius: 10px;
  border: 1px solid var(--border);
}

.admin-services .update-form .file-field {
  padding: 10px;
  margin-bottom: 8px;
  border-radius: 12px;
}

.admin-services .update-form .file-field label {
  margin-bottom: 5px;
  font-size: 12px;
}

.admin-services .update-form .file-field small {
  display: none;
}

.admin-services .update-form .form-actions {
  margin-top: 6px;
}

.admin-services .update-form .home-btn,
.admin-services .inline-actions .home-btn {
  min-height: 32px;
  padding: 7px 14px;
  font-size: 12px;
}

.admin-services .inline-actions {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 8px;
}

.admin-services .inline-form {
  display: inline-flex;
  margin: 0;
}

.admin-services .empty-state {
  background: var(--white);
  border: 1px dashed #cfe3d8;
  border-radius: 20px;
  padding: 30px 18px;
  color: var(--text-muted);
  text-align: center;
  box-shadow: var(--shadow-sm);
}

@media (max-width: 768px) {
  .admin-services {
    padding: 16px 12px 44px;
  }

  .admin-services .services-hero {
    padding: 36px 18px;
    border-radius: 20px;
  }

  .admin-services .admin-panel {
    padding: 18px;
    border-radius: 20px;
  }

  .admin-services .service-form-box,
  .admin-services .service-card {
    border-radius: 18px;
  }

  .admin-services .services-grid {
    grid-template-columns: 1fr;
  }

  .admin-services .service-meta {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }

  .admin-services .update-form .form-grid {
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
                <h3><?= View::e(trim(($s['icon'] ?? '') . ' ' . $s['name'])) ?></h3>
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
                    <strong>Đơn vị</strong>
                    <span><?= View::e($s['unit']) ?></span>
                  </div>

                  <div class="service-meta-item">
                    <strong>Giá</strong>
                    <span class="price-highlight"><?= number_format((int)$s['price']) ?>đ <?= View::e($s['unit']) ?></span>
                  </div>

                  <div class="service-meta-item">
                    <strong>Tối thiểu</strong>
                    <span><?= number_format((int)($s['minimum'] ?? ($s['minimum_price'] ?? 0))) ?>đ</span>
                  </div>

                  <div class="service-meta-item">
                    <strong>Trạng thái</strong>
                    <?php if ((int)($s['is_active'] ?? 1) === 1): ?>
                      <span class="status-badge">Hiển thị</span>
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

                    <textarea class="auth-input" name="description" rows="2" placeholder="Mô tả"><?= View::e($s['description']) ?></textarea>

                    <?php if (!empty($s['image_path'])): ?>
                      <div class="current-image-box">
                        <p>Ảnh hiện tại:</p>
                        <img src="<?= View::e($resolvedImagePath) ?>" alt="<?= View::e($s['name']) ?>">
                      </div>
                    <?php endif; ?>

                    <div class="file-field">
                      <label>Thay đổi ảnh:</label>
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