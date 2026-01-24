<?php
use App\Core\View;
/** @var array $users */
/** @var array $pendingWorkers */
/** @var string $csrf */
?>
<section class="home-container">
  <header class="home-hero">
    <p class="home-kicker">ADMIN • NGƯỜI DÙNG</p>
    <h1>Quản lý người dùng</h1>
    <p>Xem đăng ký làm Worker và danh sách người dùng.</p>
  </header>

  <section class="home-feature">
    <h2>Đăng ký làm Worker (chờ duyệt)</h2>
    <div class="review-box">
      <?php if (empty($pendingWorkers)): ?>
        <p>Không có đăng ký mới.</p>
      <?php else: ?>
        <?php foreach ($pendingWorkers as $w): ?>
          <div>
            <strong>#<?= View::e($w['id']) ?></strong> • <?= View::e($w['name']) ?> (<?= View::e($w['email']) ?>)
            • Trạng thái: <span style="color:#e67e22;font-weight:600;"><?= View::e($w['approval_status']) ?></span>
            <div class="hero-actions" style="justify-content:flex-start;margin-top:6px;gap:8px;">
              <form method="post" action="/admin/users/approve" style="display:inline-block;">
                <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
                <input type="hidden" name="id" value="<?= View::e($w['id']) ?>">
                <button class="home-btn" type="submit">Phê duyệt</button>
              </form>
              <form method="post" action="/admin/users/reject" style="display:inline-block;">
                <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
                <input type="hidden" name="id" value="<?= View::e($w['id']) ?>">
                <input name="reason" placeholder="Lý do (tuỳ chọn)" class="auth-input" style="width:220px;">
                <button class="home-btn home-btn-outline" type="submit">Từ chối</button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>

  <section class="home-feature">
    <h2>Tất cả người dùng</h2>
    <div class="review-box">
      <?php foreach ($users as $u): ?>
        <div>
          <strong>#<?= View::e($u['id']) ?></strong> • <?= View::e($u['name']) ?> (<?= View::e($u['email']) ?>)
          • Vai trò: <span style="color:#2eaf7d;font-weight:600;"><?= View::e($u['role']) ?></span>
          • Trạng thái: <?= View::e($u['approval_status'] ?? 'active') ?>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
</section>