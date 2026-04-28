<?php
use App\Core\View;
/** @var array $users Danh sách người dùng */
/** @var array $pendingWorkers Danh sách worker chờ duyệt */
/** @var string $csrf Token CSRF */
?>

<style>
.admin-users {
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
  --warning: #d97706;
  --warning-soft: #fff7ed;
  --blue: #2563eb;
  --blue-soft: #eff6ff;
  --shadow-sm: 0 8px 24px rgba(31,45,61,0.08);
  --shadow-md: 0 16px 40px rgba(31,45,61,0.12);

  max-width: 1180px;
  margin: 0 auto;
  padding: 24px 16px 60px;
  color: var(--text-dark);
}

.admin-users * {
  box-sizing: border-box;
}

/* ===== HERO ===== */
.admin-users .users-hero {
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

.admin-users .users-hero::after {
  content: "";
  position: absolute;
  right: -80px;
  bottom: -80px;
  width: 220px;
  height: 220px;
  border-radius: 50%;
  background: rgba(46,175,125,0.13);
}

.admin-users .home-kicker {
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

.admin-users .users-hero h1 {
  position: relative;
  margin: 0 0 12px;
  color: var(--text-dark);
  font-size: clamp(32px, 5vw, 52px);
  line-height: 1.1;
  font-weight: 900;
  letter-spacing: -0.04em;
}

.admin-users .users-hero p {
  position: relative;
  margin: 0;
  color: var(--text-muted);
  font-size: 17px;
  line-height: 1.6;
}

/* ===== GENERAL SECTION ===== */
.admin-users .users-section {
  margin-top: 40px;
}

.admin-users .users-title {
  margin: 0 0 18px;
  color: var(--text-dark);
  font-size: clamp(24px, 3vw, 34px);
  font-weight: 900;
  letter-spacing: -0.03em;
}

.admin-users .users-card {
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 26px;
  padding: 28px;
  box-shadow: var(--shadow-sm);
}

.admin-users .empty-text {
  margin: 0;
  color: var(--text-muted);
  line-height: 1.6;
}

.admin-users .auth-input {
  width: 100%;
  min-height: 46px;
  padding: 12px 14px;
  border: 1px solid var(--border);
  border-radius: 14px;
  background: #fcfffd;
  color: var(--text-dark);
  font-size: 14px;
  font-family: inherit;
  transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
}

.admin-users .auth-input:focus {
  outline: none;
  background: white;
  border-color: var(--primary);
  box-shadow: 0 0 0 4px rgba(46,175,125,0.12);
}

.admin-users .home-btn {
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
  background: linear-gradient(135deg, var(--primary), var(--primary-dark));
  color: white;
  box-shadow: 0 10px 22px rgba(46,175,125,0.22);
  transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
}

.admin-users .home-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 14px 30px rgba(46,175,125,0.28);
}

.admin-users .home-btn-outline {
  background: white;
  color: var(--primary);
  border: 1.5px solid var(--primary);
  box-shadow: none;
}

.admin-users .home-btn-outline:hover {
  background: var(--primary-soft);
  box-shadow: var(--shadow-sm);
}

/* ===== PENDING WORKERS ===== */
.admin-users .pending-list {
  display: grid;
  gap: 14px;
}

.admin-users .pending-item {
  background: linear-gradient(135deg, #ffffff, var(--bg-soft));
  border: 1px solid var(--border);
  border-radius: 22px;
  padding: 20px;
  box-shadow: 0 5px 16px rgba(31,45,61,0.05);
  transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
}

.admin-users .pending-item:hover {
  transform: translateY(-4px);
  border-color: rgba(46,175,125,0.45);
  box-shadow: var(--shadow-md);
}

.admin-users .status-pending {
  display: inline-flex;
  padding: 5px 11px;
  border-radius: 999px;
  background: var(--warning-soft);
  color: var(--warning);
  font-size: 13px;
  font-weight: 900;
}

.admin-users .pending-actions {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  justify-content: flex-start;
  margin-top: 14px;
  gap: 10px;
}

.admin-users .inline-form {
  display: inline-flex;
  gap: 10px;
  align-items: center;
  margin: 0;
}

.admin-users .reason-input {
  width: 240px;
}

/* ===== ROLE GROUPS ===== */
.admin-users .role-group-list {
  display: grid;
  gap: 14px;
}

.admin-users .role-group {
  border: 1px solid var(--border);
  border-radius: 22px;
  overflow: hidden;
  background: #fff;
  box-shadow: 0 5px 16px rgba(31,45,61,0.05);
  transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
}

.admin-users .role-group:hover {
  transform: translateY(-3px);
  border-color: rgba(46,175,125,0.45);
  box-shadow: var(--shadow-md);
}

.admin-users .role-toggle {
  width: 100%;
  text-align: left;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 14px;
  padding: 18px 20px;
  background:
    radial-gradient(circle at top left, rgba(46,175,125,0.12), transparent 34%),
    linear-gradient(135deg, #ffffff, var(--bg-soft));
  border: none;
  border-bottom: 1px solid var(--border);
  cursor: pointer;
}

.admin-users .role-toggle-title {
  font-weight: 900;
  color: var(--text-dark);
  font-size: 16px;
}

.admin-users .role-toggle-right {
  display: inline-flex;
  align-items: center;
  gap: 10px;
}

.admin-users .role-badge {
  background: var(--primary-soft);
  color: var(--primary-dark);
  border-radius: 999px;
  padding: 5px 12px;
  font-size: 13px;
  font-weight: 900;
}

.admin-users .role-chevron {
  color: var(--primary);
  font-size: 13px;
  transition: transform .2s ease;
}

.admin-users .role-toggle[aria-expanded="true"] .role-chevron {
  transform: rotate(180deg);
}

.admin-users .role-panel {
  display: none;
  padding: 18px;
}

.admin-users .role-panel.open {
  display: block;
}

.admin-users .role-user-list {
  display: grid;
  gap: 10px;
}

.admin-users .role-user-item {
  width: 100%;
  text-align: left;
  background: #fcfffd;
  border: 1px solid var(--border);
  border-radius: 16px;
  padding: 13px 15px;
  color: var(--text-muted);
  cursor: pointer;
  line-height: 1.6;
  transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease, background 0.2s ease;
}

.admin-users .role-user-item:hover {
  transform: translateY(-2px);
  background: white;
  border-color: rgba(46,175,125,0.45);
  box-shadow: var(--shadow-sm);
}

.admin-users .role-text {
  color: var(--primary);
  font-weight: 900;
}

.admin-users .user-inline-trigger {
  background: none;
  border: none;
  padding: 0;
  margin: 0;
  color: var(--text-dark);
  font: inherit;
  font-weight: 900;
  cursor: pointer;
}

.admin-users .user-inline-trigger:hover {
  color: var(--primary);
  text-decoration: underline;
}

/* ===== USER DETAIL PANEL ===== */
.admin-users .user-detail-panel {
  margin-top: 24px;
  border: 1px solid var(--border);
  border-radius: 26px;
  background: var(--white);
  padding: 28px;
  box-shadow: var(--shadow-sm);
}

.admin-users .user-detail-panel[hidden] {
  display: none;
}

.admin-users .user-detail-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  margin-bottom: 16px;
  padding-bottom: 16px;
  border-bottom: 1px solid var(--border);
}

.admin-users .user-detail-title {
  margin: 0;
  color: var(--text-dark);
  font-size: 22px;
  font-weight: 900;
  letter-spacing: -0.02em;
}

.admin-users .edit-toggle-btn {
  border: 1.5px solid var(--primary);
  background: #fff;
  color: var(--primary);
  border-radius: 14px;
  width: 44px;
  height: 44px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  font-size: 18px;
  font-weight: 900;
  transition: all .2s ease;
}

.admin-users .edit-toggle-btn:hover {
  transform: translateY(-2px);
  background: var(--primary-soft);
  box-shadow: var(--shadow-sm);
}

.admin-users .edit-toggle-btn.active {
  background: linear-gradient(135deg, var(--primary), var(--primary-dark));
  color: #fff;
  border-color: transparent;
  box-shadow: 0 10px 22px rgba(46,175,125,0.22);
}

.admin-users .edit-toggle-btn:disabled {
  opacity: .45;
  cursor: not-allowed;
  transform: none;
  box-shadow: none;
}

.admin-users .detail-hint {
  margin: 0 0 16px;
  color: var(--text-muted);
  line-height: 1.6;
}

.admin-users .user-detail-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
  gap: 14px;
  margin-bottom: 16px;
}

.admin-users .user-detail-item {
  border: 1px solid var(--border);
  border-radius: 18px;
  padding: 16px;
  background: var(--bg-soft);
  color: var(--text-muted);
  line-height: 1.6;
}

.admin-users .user-detail-item strong {
  color: var(--text-dark);
  display: block;
  margin-bottom: 6px;
  font-weight: 900;
}

.admin-users .detail-avatar {
  width: 64px;
  height: 64px;
  border-radius: 999px;
  object-fit: cover;
  border: 3px solid var(--primary-soft);
  box-shadow: var(--shadow-sm);
}

.admin-users .status-badge {
  display: inline-flex;
  padding: 5px 12px;
  border-radius: 999px;
  font-size: 12px;
  font-weight: 900;
}

.admin-users .status-active {
  background: var(--primary-soft);
  color: var(--primary-dark);
}

.admin-users .status-pending-chip {
  background: var(--warning-soft);
  color: var(--warning);
}

.admin-users .status-locked {
  background: var(--danger-soft);
  color: var(--danger);
}

.admin-users .status-other {
  background: #f3f4f6;
  color: #6b7280;
}

/* ===== ACTIONS ===== */
.admin-users .user-detail-actions {
  display: flex;
  flex-direction: column;
  gap: 12px;
  border-top: 1px solid var(--border);
  padding-top: 16px;
}

.admin-users .account-action-buttons {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 10px;
}

.admin-users .user-lock-form,
.admin-users .user-unlock-form {
  display: inline-flex;
  align-items: center;
  margin: 0;
}

.admin-users .lock-reason-input {
  max-width: 420px;
}

/* ===== EDIT FORM ===== */
.admin-users .edit-user-form {
  border-top: 1px solid var(--border);
  margin-top: 18px;
  padding-top: 18px;
}

.admin-users .edit-user-form[hidden] {
  display: none;
}

.admin-users .edit-user-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
  gap: 14px;
}

.admin-users .form-field {
  display: grid;
  gap: 7px;
}

.admin-users .form-field label {
  font-size: 13px;
  color: var(--text-dark);
  font-weight: 900;
}

.admin-users .full-row {
  grid-column: 1 / -1;
}

.admin-users .edit-form-actions {
  margin-top: 14px;
}

/* ===== MESSAGE PANEL ===== */
.admin-users .user-message-panel {
  margin-top: 18px;
  border-top: 1px solid var(--border);
  padding-top: 18px;
}

.admin-users .user-message-panel[hidden] {
  display: none;
}

.admin-users .user-message-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 14px;
  margin-bottom: 12px;
}

.admin-users .user-message-title {
  margin: 0;
  color: var(--text-dark);
  font-size: 18px;
  font-weight: 900;
}

.admin-users .user-message-count {
  background: var(--primary-soft);
  color: var(--primary-dark);
  border-radius: 999px;
  padding: 5px 12px;
  font-size: 12px;
  font-weight: 900;
}

.admin-users .user-message-thread {
  display: flex;
  flex-direction: column;
  gap: 10px;
  min-height: 130px;
  max-height: 420px;
  overflow-y: auto;
  background: var(--bg-soft);
  border: 1px solid var(--border);
  border-radius: 18px;
  padding: 16px;
}

.admin-users .user-message-empty {
  margin: 0;
  color: var(--text-muted);
  text-align: center;
  padding: 14px;
  font-weight: 700;
}

.admin-users .user-message-bubble {
  max-width: 78%;
  padding: 12px 14px;
  border-radius: 18px;
  border: 1px solid var(--border);
  background: #fff;
  color: var(--text-dark);
  box-shadow: 0 6px 16px rgba(31,45,61,0.06);
}

.admin-users .user-message-bubble.is-admin {
  margin-left: auto;
  background: linear-gradient(135deg, var(--primary), var(--primary-dark));
  color: #fff;
  border-color: transparent;
}

.admin-users .user-message-meta {
  margin: 0 0 5px;
  font-size: 12px;
  font-weight: 900;
  color: var(--text-muted);
}

.admin-users .user-message-bubble.is-admin .user-message-meta {
  color: rgba(255,255,255,0.86);
}

.admin-users .user-message-content {
  margin: 0;
  line-height: 1.5;
  white-space: pre-wrap;
}

.admin-users .user-message-form {
  margin-top: 12px;
  display: grid;
  gap: 10px;
}

.admin-users .user-message-form textarea {
  width: 100%;
  min-height: 92px;
  padding: 13px 15px;
  border: 1px solid var(--border);
  border-radius: 16px;
  background: #fcfffd;
  color: var(--text-dark);
  font-family: inherit;
  font-size: 14px;
  resize: vertical;
}

.admin-users .user-message-form textarea:focus {
  outline: none;
  background: white;
  border-color: var(--primary);
  box-shadow: 0 0 0 4px rgba(46,175,125,0.12);
}

.admin-users .user-message-form textarea:disabled,
.admin-users .user-message-form button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

@media (max-width: 768px) {
  .admin-users {
    padding: 16px 12px 44px;
  }

  .admin-users .users-hero {
    padding: 42px 18px;
    border-radius: 22px;
  }

  .admin-users .users-card,
  .admin-users .user-detail-panel {
    padding: 22px;
    border-radius: 20px;
  }

  .admin-users .pending-actions,
  .admin-users .inline-form,
  .admin-users .account-action-buttons,
  .admin-users .user-lock-form,
  .admin-users .user-unlock-form,
  .admin-users .home-btn {
    width: 100%;
  }

  .admin-users .inline-form {
    flex-direction: column;
    align-items: stretch;
  }

  .admin-users .reason-input,
  .admin-users .lock-reason-input {
    width: 100%;
    max-width: none;
  }

  .admin-users .edit-user-grid {
    grid-template-columns: 1fr;
  }

  .admin-users .user-detail-head,
  .admin-users .user-message-header {
    flex-direction: column;
    align-items: flex-start;
  }

  .admin-users .user-message-bubble {
    max-width: 100%;
  }
}
</style>

<section class="home-container admin-users">
  <header class="home-hero users-hero">
    <p class="home-kicker">ADMIN • NGƯỜI DÙNG</p>
    <h1>Quản lý người dùng</h1>
    <p>Xem đăng ký làm Worker và danh sách người dùng.</p>
  </header>

  <section class="users-section" aria-label="Đăng ký worker chờ duyệt">
    <h2 class="users-title">Đăng ký làm Worker (chờ duyệt)</h2>
    <div class="users-card">
      <?php if (empty($pendingWorkers)): ?>
        <p class="empty-text">Không có đăng ký mới.</p>
      <?php else: ?>
        <div class="pending-list">
          <?php foreach ($pendingWorkers as $w): ?>
            <div class="pending-item">
              <button type="button" class="user-inline-trigger" data-user-id="<?= View::e((string)$w['id']) ?>">
                #<?= View::e($w['id']) ?> • <?= View::e($w['name']) ?> (<?= View::e($w['email']) ?>)
              </button>
              • Trạng thái: <span class="status-pending"><?= View::e($w['approval_status']) ?></span>

              <div class="pending-actions">
                <form method="post" action="/admin/users/approve" class="inline-form">
                  <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
                  <input type="hidden" name="id" value="<?= View::e($w['id']) ?>">
                  <button class="home-btn" type="submit">Phê duyệt</button>
                </form>

                <form method="post" action="/admin/users/reject" class="inline-form">
                  <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
                  <input type="hidden" name="id" value="<?= View::e($w['id']) ?>">
                  <input name="reason" placeholder="Lý do (tuỳ chọn)" class="auth-input reason-input">
                  <button class="home-btn home-btn-outline" type="submit">Từ chối</button>
                </form>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <section class="users-section" aria-label="Danh sách người dùng theo vai trò">
    <h2 class="users-title">Tất cả người dùng</h2>

    <?php
      $admins = array_filter($users, fn($u) => ($u['role'] ?? '') === 'admin');
      $workers = array_filter($users, fn($u) => in_array(($u['role'] ?? ''), ['worker'], true));
      $customers = array_filter($users, fn($u) => ($u['role'] ?? '') === 'customer');
      $groupDefs = [
        ['key' => 'admins', 'title' => 'Quản trị viên (Admin)', 'items' => $admins, 'badge' => count($admins)],
        ['key' => 'workers', 'title' => 'Người lao động (Worker)', 'items' => $workers, 'badge' => count($workers)],
        ['key' => 'customers', 'title' => 'Khách hàng (Customer)', 'items' => $customers, 'badge' => count($customers)],
      ];
    ?>

    <div class="users-card role-group-list" id="user-roles-box">
      <?php foreach ($groupDefs as $g): ?>
        <div class="role-group">
          <button type="button"
                  class="role-toggle"
                  data-toggle-target="role-<?= View::e($g['key']) ?>"
                  aria-expanded="false">
            <span class="role-toggle-title"><?= View::e($g['title']) ?></span>
            <span class="role-toggle-right">
              <span class="role-badge"><?= View::e((string)$g['badge']) ?></span>
              <span class="role-chevron">▼</span>
            </span>
          </button>

          <div id="role-<?= View::e($g['key']) ?>" class="role-panel">
            <?php if (empty($g['items'])): ?>
              <p class="empty-text">Chưa có người dùng thuộc nhóm này.</p>
            <?php else: ?>
              <div class="role-user-list">
                <?php foreach ($g['items'] as $u): ?>
                  <button type="button" class="role-user-item" data-user-id="<?= View::e((string)$u['id']) ?>">
                    <?= View::e($u['name']) ?> (<?= View::e($u['email']) ?>)
                    • Vai trò: <span class="role-text"><?= View::e($u['role']) ?></span>
                    • Trạng thái: <?= View::e($u['approval_status'] ?? 'active') ?>
                  </button>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="user-detail-panel" id="userDetailPanel" hidden>
      <div class="user-detail-head">
        <h3 class="user-detail-title">Thông tin chi tiết người dùng</h3>
        <button type="button" class="edit-toggle-btn" id="toggleUserEdit" title="Bật chỉnh sửa" aria-label="Bật chỉnh sửa" disabled>✎</button>
      </div>

      <p class="detail-hint" id="userDetailHint">Chọn người dùng để xem thông tin.</p>
      <div class="user-detail-grid" id="userDetailGrid"></div>

      <form method="post" action="/admin/user/update" class="edit-user-form" id="editUserForm" hidden>
        <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
        <input type="hidden" name="id" id="editUserId" value="">
        <input type="hidden" name="return_to" value="/admin/users">

        <div class="edit-user-grid">
          <div class="form-field">
            <label for="editUserName">Họ tên</label>
            <input id="editUserName" name="name" class="auth-input" required>
          </div>

          <div class="form-field">
            <label for="editUserEmail">Email</label>
            <input id="editUserEmail" type="email" name="email" class="auth-input" required>
          </div>

          <div class="form-field">
            <label for="editUserPhone">SĐT</label>
            <input id="editUserPhone" name="phone" class="auth-input">
          </div>

          <div class="form-field">
            <label for="editUserRole">Vai trò</label>
            <select id="editUserRole" name="role" class="auth-input">
              <option value="customer">customer</option>
              <option value="worker">worker</option>
              <option value="admin">admin</option>
            </select>
          </div>

          <div class="form-field">
            <label for="editUserStatus">Trạng thái</label>
            <select id="editUserStatus" name="approval_status" class="auth-input">
              <option value="active">active</option>
              <option value="pending">pending</option>
              <option value="rejected">rejected</option>
              <option value="locked">locked</option>
              <option value="deleted">deleted</option>
            </select>
          </div>

          <div class="form-field full-row">
            <label for="editUserAddress">Địa chỉ</label>
            <input id="editUserAddress" name="address" class="auth-input">
          </div>

          <div class="form-field full-row">
            <label for="editUserReason">Lý do trạng thái</label>
            <input id="editUserReason" name="reject_reason" class="auth-input" placeholder="Ví dụ: yêu cầu xác minh thêm thông tin">
          </div>
        </div>

        <div class="edit-form-actions">
          <button class="home-btn" type="submit">Lưu toàn bộ thông tin</button>
        </div>
      </form>

      <div class="user-message-panel" id="userMessagePanel" hidden>
        <div class="user-message-header">
          <h4 class="user-message-title">Nhắn tin liên hệ</h4>
          <span class="user-message-count" id="userMessageCount">0 tin nhắn</span>
        </div>

        <div class="user-message-thread" id="userMessageList">
          <p class="user-message-empty">Chọn người dùng để xem tin nhắn.</p>
        </div>

        <form method="post" action="/admin/user/message" class="user-message-form" id="userMessageForm">
          <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
          <input type="hidden" name="user_id" id="userMessageUserId" value="">
          <textarea name="content" id="userMessageContent" rows="3" required placeholder="Nhập tin nhắn..." disabled></textarea>
          <button class="home-btn" type="submit" id="userMessageSubmit" disabled>Gửi tin nhắn</button>
        </form>
      </div>

      <div class="user-detail-actions" id="userDetailActions" hidden>
        <input type="text" id="lockReasonInput" class="auth-input lock-reason-input" placeholder="Lý do khóa tài khoản (tùy chọn)">

        <div class="account-action-buttons">
          <form method="post" action="/admin/user/lock" class="user-lock-form" id="lockUserForm">
            <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
            <input type="hidden" name="id" id="lockUserId" value="">
            <input type="hidden" name="return_to" value="/admin/users">
            <input type="hidden" name="reason" id="lockReasonHidden" value="">
            <button class="home-btn home-btn-outline" type="submit">Khóa tài khoản</button>
          </form>

          <form method="post" action="/admin/user/unlock" class="user-unlock-form" id="unlockUserForm">
            <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
            <input type="hidden" name="id" id="unlockUserId" value="">
            <input type="hidden" name="return_to" value="/admin/users">
            <button class="home-btn" type="submit">Mở khóa tài khoản</button>
          </form>
        </div>
      </div>
    </div>

    <script>
    (function(){
      function escapeHtml(value) {
        const map = {
          '&': '&amp;',
          '<': '&lt;',
          '>': '&gt;',
          '"': '&quot;',
          "'": '&#039;'
        };
        return String(value ?? '').replace(/[&<>"']/g, (char) => map[char]);
      }

      function formatStatus(status) {
        const normalized = String(status || '').toLowerCase();
        if (normalized === 'active') {
          return { label: 'active', className: 'status-active' };
        }
        if (normalized === 'pending') {
          return { label: 'pending', className: 'status-pending-chip' };
        }
        if (normalized === 'locked') {
          return { label: 'locked', className: 'status-locked' };
        }
        return { label: normalized || 'n/a', className: 'status-other' };
      }

      function formatMessageContent(value) {
        return escapeHtml(value).replace(/\n/g, '<br>');
      }

      function setMessageFormEnabled(enabled) {
        if (messageContent) {
          messageContent.disabled = !enabled;
        }
        if (messageSubmit) {
          messageSubmit.disabled = !enabled;
        }
      }

      function renderMessages(messages) {
        if (!messageList) return;
        const safeMessages = Array.isArray(messages) ? messages : [];
        if (messageCount) {
          messageCount.textContent = safeMessages.length + ' tin nhắn';
        }
        if (safeMessages.length === 0) {
          messageList.innerHTML = '<p class="user-message-empty">Chưa có tin nhắn nào.</p>';
          return;
        }
        messageList.innerHTML = safeMessages.map((message) => {
          const isAdmin = String(message.sender_role || '') === 'admin';
          return [
            '<div class="user-message-bubble ' + (isAdmin ? 'is-admin' : 'is-user') + '">',
            '<p class="user-message-meta">',
            escapeHtml(message.sender_name || ''),
            ' • ',
            escapeHtml(message.created_at || ''),
            '</p>',
            '<p class="user-message-content">',
            formatMessageContent(message.content || ''),
            '</p>',
            '</div>'
          ].join('');
        }).join('');
      }

      async function loadUserMessages(userId) {
        if (!messagePanel || !messageList) return;
        messagePanel.hidden = false;
        messageList.innerHTML = '<p class="user-message-empty">Đang tải tin nhắn...</p>';
        if (messageUserId) {
          messageUserId.value = userId;
        }
        if (messageContent) {
          messageContent.value = '';
        }
        setMessageFormEnabled(false);

        try {
          const response = await fetch('/admin/user/messages?id=' + encodeURIComponent(userId), {
            headers: { 'Accept': 'application/json' }
          });
          if (!response.ok) {
            throw new Error('request_failed');
          }
          const payload = await response.json();
          if (payload?.error) {
            throw new Error(payload.error);
          }
          renderMessages(payload?.messages || []);
        } catch (error) {
          messageList.innerHTML = '<p class="user-message-empty">Không thể tải tin nhắn.</p>';
        }

        setMessageFormEnabled(true);
      }

      const detailPanel = document.getElementById('userDetailPanel');
      const detailHint = document.getElementById('userDetailHint');
      const detailGrid = document.getElementById('userDetailGrid');
      const messagePanel = document.getElementById('userMessagePanel');
      const messageList = document.getElementById('userMessageList');
      const messageCount = document.getElementById('userMessageCount');
      const messageForm = document.getElementById('userMessageForm');
      const messageUserId = document.getElementById('userMessageUserId');
      const messageContent = document.getElementById('userMessageContent');
      const messageSubmit = document.getElementById('userMessageSubmit');
      const detailActions = document.getElementById('userDetailActions');
      const toggleUserEdit = document.getElementById('toggleUserEdit');
      const lockUserForm = document.getElementById('lockUserForm');
      const unlockUserForm = document.getElementById('unlockUserForm');
      const lockUserId = document.getElementById('lockUserId');
      const unlockUserId = document.getElementById('unlockUserId');
      const lockReasonInput = document.getElementById('lockReasonInput');
      const lockReasonHidden = document.getElementById('lockReasonHidden');
      const editUserForm = document.getElementById('editUserForm');
      const editUserId = document.getElementById('editUserId');
      const editUserName = document.getElementById('editUserName');
      const editUserEmail = document.getElementById('editUserEmail');
      const editUserPhone = document.getElementById('editUserPhone');
      const editUserRole = document.getElementById('editUserRole');
      const editUserStatus = document.getElementById('editUserStatus');
      const editUserAddress = document.getElementById('editUserAddress');
      const editUserReason = document.getElementById('editUserReason');
      let currentUserId = '';
      let isEditMode = false;

      function setEditMode(enabled) {
        isEditMode = Boolean(enabled);
        if (editUserForm) {
          editUserForm.hidden = !isEditMode;
        }
        if (toggleUserEdit) {
          toggleUserEdit.classList.toggle('active', isEditMode);
          toggleUserEdit.title = isEditMode ? 'Tắt chỉnh sửa' : 'Bật chỉnh sửa';
          toggleUserEdit.setAttribute('aria-label', isEditMode ? 'Tắt chỉnh sửa' : 'Bật chỉnh sửa');
        }
      }

      async function showUserDetail(userId) {
        if (!userId) return;
        detailPanel.hidden = false;
        detailActions.hidden = true;
        setEditMode(false);
        if (messagePanel) {
          messagePanel.hidden = true;
        }
        if (messageList) {
          messageList.innerHTML = '';
        }
        if (messageCount) {
          messageCount.textContent = '0 tin nhắn';
        }
        setMessageFormEnabled(false);
        detailHint.textContent = 'Đang tải thông tin người dùng...';
        detailGrid.innerHTML = '';

        try {
          const response = await fetch('/admin/user/json?id=' + encodeURIComponent(userId), {
            headers: {
              'Accept': 'application/json'
            }
          });

          if (!response.ok) {
            throw new Error('request_failed');
          }

          const data = await response.json();
          if (!data || data.error) {
            throw new Error(data?.error || 'invalid_data');
          }

          const statusInfo = formatStatus(data.approval_status);
          const rows = [
            ['ID', String(data.id ?? '—')],
            ['Họ tên', String(data.name || '—')],
            ['Email', String(data.email || '—')],
            ['SĐT', String(data.phone || '—')],
            ['Địa chỉ', String(data.address || '—')],
            ['Vai trò', String(data.role || '—')],
            ['Trạng thái', `<span class="status-badge ${statusInfo.className}">${escapeHtml(statusInfo.label)}</span>`, true],
            ['Người duyệt', String(data.approved_by_name || data.approved_by || '—')],
            ['Thời điểm duyệt', String(data.approved_at || '—')],
            ['Lý do', String(data.reject_reason || '—')],
            ['Ảnh đại diện', data.avatar ? `<img src="${escapeHtml(data.avatar)}" alt="Avatar" class="detail-avatar">` : '—', !!data.avatar]
          ];

          detailGrid.innerHTML = rows.map(([label, value, isHtml]) => (
            `<div class="user-detail-item"><strong>${escapeHtml(label)}</strong>${isHtml ? value : escapeHtml(value)}</div>`
          )).join('');

          const userIdText = String(data.id || '');
          currentUserId = userIdText;
          lockUserId.value = userIdText;
          unlockUserId.value = userIdText;
          editUserId.value = userIdText;
          editUserName.value = data.name || '';
          editUserEmail.value = data.email || '';
          editUserPhone.value = data.phone || '';
          editUserRole.value = data.role || 'customer';
          editUserStatus.value = data.approval_status || 'active';
          editUserAddress.value = data.address || '';
          editUserReason.value = data.reject_reason || '';
          if (lockReasonInput) lockReasonInput.value = '';
          detailHint.textContent = 'Đang xem thông tin của user #' + userIdText + '.';
          detailActions.hidden = false;
          if (toggleUserEdit) {
            toggleUserEdit.disabled = false;
          }
          setEditMode(false);
          loadUserMessages(userIdText);

          detailPanel.scrollIntoView({ behavior: 'smooth', block: 'start' });
        } catch (error) {
          detailHint.textContent = 'Không thể tải thông tin người dùng. Vui lòng thử lại.';
          detailGrid.innerHTML = '';
          detailActions.hidden = true;
          setEditMode(false);
          currentUserId = '';
          if (toggleUserEdit) {
            toggleUserEdit.disabled = true;
          }
          if (messagePanel) {
            messagePanel.hidden = true;
          }
          setMessageFormEnabled(false);
        }
      }

      const btns = document.querySelectorAll('[data-toggle-target]');
      btns.forEach(btn => {
        btn.addEventListener('click', () => {
          const id = btn.getAttribute('data-toggle-target');
          const panel = document.getElementById(id);
          if (!panel) return;
          const isOpen = panel.classList.contains('open');
          panel.classList.toggle('open', !isOpen);
          btn.setAttribute('aria-expanded', String(!isOpen));
        });
      });

      const userTriggers = document.querySelectorAll('[data-user-id]');
      userTriggers.forEach(trigger => {
        trigger.addEventListener('click', () => {
          const userId = trigger.getAttribute('data-user-id');
          showUserDetail(userId);
        });
      });

      lockUserForm?.addEventListener('submit', () => {
        if (lockReasonHidden) {
          lockReasonHidden.value = lockReasonInput ? lockReasonInput.value : '';
        }
      });

      toggleUserEdit?.addEventListener('click', () => {
        if (!currentUserId) {
          return;
        }
        setEditMode(!isEditMode);
      });

      const initialUserId = new URLSearchParams(window.location.search).get('user_id');
      if (initialUserId) {
        showUserDetail(initialUserId);
      }
    })();
    </script>
  </section>
</section>