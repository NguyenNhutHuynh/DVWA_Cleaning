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
    <?php
      $admins = array_filter($users, fn($u) => ($u['role'] ?? '') === 'admin');
      $workers = array_filter($users, fn($u) => in_array(($u['role'] ?? ''), ['worker','cleaner'], true));
      $customers = array_filter($users, fn($u) => ($u['role'] ?? '') === 'customer');
      $groupDefs = [
        ['key' => 'admins', 'title' => 'Quản trị viên (Admin)', 'items' => $admins, 'badge' => count($admins)],
        ['key' => 'workers', 'title' => 'Người lao động (Worker/Cleaner)', 'items' => $workers, 'badge' => count($workers)],
        ['key' => 'customers', 'title' => 'Khách hàng (Customer)', 'items' => $customers, 'badge' => count($customers)],
      ];
    ?>

    <div class="review-box" id="user-roles-box">
      <?php foreach ($groupDefs as $g): ?>
        <div style="border:1px solid #e0f2e9;border-radius:10px;overflow:hidden;background:#fff;">
          <button type="button"
                  class="function-btn"
                  data-toggle-target="role-<?= View::e($g['key']) ?>"
                  style="width:100%;text-align:left;display:block;padding:12px 14px;background:#f7fdf9;border:none;border-bottom:1px solid #e0f2e9;">
            <span style="font-weight:600;color:#1f2d3d;"><?= View::e($g['title']) ?></span>
            <span style="float:right;background:#e0f2e9;color:#2eaf7d;border-radius:14px;padding:2px 10px;font-size:13px;"><?= View::e((string)$g['badge']) ?></span>
          </button>
          <div id="role-<?= View::e($g['key']) ?>" style="display:none;padding:10px;">
            <?php if (empty($g['items'])): ?>
              <p style="margin:6px 0;color:#546e7a;">Chưa có người dùng thuộc nhóm này.</p>
            <?php else: ?>
              <?php foreach ($g['items'] as $u): ?>
                <div>
                  <strong>#<?= View::e($u['id']) ?></strong> • <?= View::e($u['name']) ?> (<?= View::e($u['email']) ?>)
                  • Vai trò: <span style="color:#2eaf7d;font-weight:600;"><?= View::e($u['role']) ?></span>
                  • Trạng thái: <?= View::e($u['approval_status'] ?? 'active') ?>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <script>
    (function(){
      const btns = document.querySelectorAll('[data-toggle-target]');
      btns.forEach(btn => {
        btn.addEventListener('click', () => {
          const id = btn.getAttribute('data-toggle-target');
          const panel = document.getElementById(id);
          if (!panel) return;
          const isHidden = panel.style.display === 'none' || panel.style.display === '';
          panel.style.display = isHidden ? 'block' : 'none';
        });
      });
    })();
    </script>
  </section>
</section>