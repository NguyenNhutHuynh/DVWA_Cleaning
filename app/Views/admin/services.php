<?php
use App\Core\View;
/** @var array $services */
/** @var string $csrf */
?>
<section class="home-container">
  <header class="home-hero">
    <p class="home-kicker">ADMIN • DỊCH VỤ</p>
    <h1>Quản lý dịch vụ</h1>
    <p>Thêm, chỉnh sửa giá và mô tả dịch vụ.</p>
  </header>

  <section class="home-feature">
    <h2>Thêm dịch vụ mới</h2>
    <div class="review-box">
      <form method="post" action="/admin/services/create" style="display:block;">
        <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:8px;margin-bottom:8px;">
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
        <div style="margin-top:8px;">
          <button class="home-btn" type="submit">Thêm dịch vụ</button>
        </div>
      </form>
    </div>

    <h2 style="margin-top:16px;">Danh sách dịch vụ</h2>
    <div class="feature-grid">
      <?php foreach ($services as $s): ?>
        <article class="feature-card">
          <h3><?= View::e(($s['icon'] ?? '') . ' ' . $s['name']) ?></h3>
          <p style="margin:0 0 8px 0;"><?= View::e($s['description']) ?></p>

          <div style="display:grid;gap:6px;color:#455a64;font-size:14px;margin-top:6px;">
            <div><strong style="color:#1f2d3d;">ID:</strong> <?= View::e((string)($s['id'] ?? '')) ?></div>
            <div><strong style="color:#1f2d3d;">Thời gian:</strong> <?= View::e($s['duration'] ?? ($s['duration_text'] ?? '—')) ?></div>
            <div><strong style="color:#1f2d3d;">Đơn vị tính:</strong> <?= View::e($s['unit']) ?></div>
            <div><strong style="color:#1f2d3d;">Giá cơ bản:</strong> <span style="color:#2eaf7d;font-weight:600;"><?= number_format((int)$s['price']) ?>đ</span> <?= View::e($s['unit']) ?></div>
            <div><strong style="color:#1f2d3d;">Tối thiểu:</strong> <?= number_format((int)($s['minimum'] ?? ($s['minimum_price'] ?? 0))) ?>đ</div>
            <div><strong style="color:#1f2d3d;">Trạng thái:</strong> <?= ((int)($s['is_active'] ?? 1) === 1) ? 'Đang hiển thị' : 'Đã ẩn' ?></div>
          </div>

          <div class="hero-actions" style="justify-content:flex-start;margin-top:10px;gap:8px;">
            <form method="post" action="/admin/services/update" style="display:inline-block;max-width:560px;text-align:left;">
              <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
              <input type="hidden" name="id" value="<?= View::e((string)$s['id']) ?>">
              <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:8px;margin-bottom:8px;">
                <input class="auth-input" name="name" placeholder="Tên" value="<?= View::e($s['name']) ?>">
                <input class="auth-input" name="icon" placeholder="Icon" value="<?= View::e($s['icon'] ?? '') ?>">
                <input class="auth-input" name="duration" placeholder="Thời gian" value="<?= View::e($s['duration'] ?? ($s['duration_text'] ?? '')) ?>">
                <input class="auth-input" name="unit" placeholder="Đơn vị" value="<?= View::e($s['unit']) ?>">
                <input class="auth-input" name="price" type="number" min="0" placeholder="Giá" value="<?= View::e((string)$s['price']) ?>">
                <input class="auth-input" name="minimum" type="number" min="0" placeholder="Tối thiểu" value="<?= View::e((string)($s['minimum'] ?? ($s['minimum_price'] ?? 0))) ?>">
              </div>
              <textarea class="auth-input" name="description" rows="3" placeholder="Mô tả"><?= View::e($s['description']) ?></textarea>
              <div style="margin-top:8px;">
                <button class="home-btn" type="submit">Lưu</button>
              </div>
            </form>

            <form method="post" action="/admin/services/toggle" style="display:inline-block;">
              <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
              <input type="hidden" name="id" value="<?= View::e((string)$s['id']) ?>">
              <button class="home-btn home-btn-outline" type="submit"><?= ((int)($s['is_active'] ?? 1) === 1) ? 'Ẩn' : 'Hiện' ?></button>
            </form>

            <form method="post" action="/admin/services/delete" style="display:inline-block;" onsubmit="return confirm('Xóa dịch vụ này? Hành động không thể hoàn tác.');">
              <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
              <input type="hidden" name="id" value="<?= View::e((string)$s['id']) ?>">
              <button class="home-btn home-btn-outline" type="submit" style="border-color:#e53935;color:#e53935;">Xóa</button>
            </form>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </section>
</section>