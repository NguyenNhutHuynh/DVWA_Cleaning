<?php
use App\Core\View;
/** @var array $users Danh sách người dùng */
/** @var array $pendingWorkers Danh sách worker chờ duyệt */
/** @var string $csrf Token CSRF */
?>

<style>
.admin-users {
  max-width: 1200px;
  margin: 0 auto 70px;
  padding: 0 16px;
}

.users-hero {
  background: linear-gradient(135deg, #2eaf7d 0%, #43c59e 50%, #8cdf94 100%);
  border-radius: 20px;
  padding: 50px 40px;
  color: #fff;
  position: relative;
  overflow: hidden;
  box-shadow: 0 10px 40px rgba(46, 175, 125, 0.3);
  animation: slideInDown 0.6s ease-out;
}

.users-hero::before {
  content: '';
  position: absolute;
  top: -50%;
  right: -50%;
  width: 200%;
  height: 200%;
  background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
  animation: rotate 20s linear infinite;
}

.users-hero .home-kicker {
  position: relative;
  z-index: 1;
  color: #fff;
  animation: fadeIn 0.5s ease-out;
}

.users-hero h1 {
  font-size: 2.5rem;
  margin: 0 0 10px 0;
  font-weight: 700;
  position: relative;
  z-index: 1;
  text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  animation: fadeInDown 0.6s ease-out 0.1s both;
}

.users-hero p {
  font-size: 1.1rem;
  margin: 0;
  opacity: 0.95;
  position: relative;
  z-index: 1;
  animation: fadeInUp 0.6s ease-out 0.2s both;
}

.users-section {
  margin-top: 40px;
  animation: fadeInUp 0.8s ease-out 0.2s both;
}

.users-title {
  margin: 0 0 14px;
  font-size: 1.5rem;
  color: #1f2d3d;
}

.users-card {
  background: linear-gradient(180deg, #f7fdf9 0%, #f0fff4 100%);
  border: 1px solid #d9efe5;
  border-radius: 16px;
  padding: 16px;
  box-shadow: 0 8px 24px rgba(32, 85, 66, 0.08);
}

.empty-text {
  margin: 0;
  color: #546e7a;
}

.pending-list {
  display: grid;
  gap: 12px;
}

.pending-item {
  background: #fff;
  border: 1px solid #dcefe6;
  border-radius: 12px;
  padding: 12px;
}

.status-pending {
  color: #e67e22;
  font-weight: 700;
}

.pending-actions {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  justify-content: flex-start;
  margin-top: 8px;
  gap: 8px;
}

.inline-form {
  display: inline-block;
}

.reason-input {
  width: 220px;
}

.role-group-list {
  display: grid;
  gap: 12px;
}

.role-group {
  border: 1px solid #e0f2e9;
  border-radius: 12px;
  overflow: hidden;
  background: #fff;
  transition: box-shadow .2s ease, border-color .2s ease;
}

.role-group:hover {
  border-color: #43c59e;
  box-shadow: 0 12px 40px rgba(46, 175, 125, 0.2);
}

.role-toggle {
  width: 100%;
  text-align: left;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
  padding: 12px 14px;
  background: #f7fdf9;
  border: none;
  border-bottom: 1px solid #e0f2e9;
  cursor: pointer;
}

.role-toggle-title {
  font-weight: 700;
  color: #1f2d3d;
}

.role-toggle-right {
  display: inline-flex;
  align-items: center;
  gap: 8px;
}

.role-badge {
  background: #e0f2e9;
  color: #2eaf7d;
  border-radius: 14px;
  padding: 2px 10px;
  font-size: 13px;
  font-weight: 700;
}

.role-chevron {
  color: #2eaf7d;
  font-size: 12px;
  transition: transform .2s ease;
}

.role-toggle[aria-expanded="true"] .role-chevron {
  transform: rotate(180deg);
}

.role-panel {
  display: none;
  padding: 10px 14px;
}

.role-panel.open {
  display: block;
}

.role-user-list {
  display: grid;
  gap: 8px;
}

.role-user-item {
  border: 1px solid #edf7f2;
  border-radius: 10px;
  padding: 8px 10px;
  color: #455a64;
}

.role-text {
  color: #2eaf7d;
  font-weight: 700;
}

.user-inline-trigger {
  background: none;
  border: none;
  padding: 0;
  margin: 0;
  color: #1f2d3d;
  font: inherit;
  font-weight: 700;
  cursor: pointer;
}

.user-inline-trigger:hover {
  color: #2eaf7d;
  text-decoration: underline;
}

.role-user-item {
  width: 100%;
  text-align: left;
  background: #fff;
  cursor: pointer;
}

.role-user-item:hover {
  border-color: #43c59e;
  box-shadow: 0 12px 40px rgba(46, 175, 125, 0.2);
}

.user-detail-panel {
  margin-top: 18px;
  border: 1px solid #d9efe5;
  border-radius: 16px;
  background: linear-gradient(180deg, #ffffff 0%, #f7fdf9 100%);
  padding: 16px;
  box-shadow: 0 8px 24px rgba(32, 85, 66, 0.08);
}

.user-detail-panel[hidden] {
  display: none;
}

.user-detail-title {
  margin: 0;
  color: #1f2d3d;
}

.user-detail-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
  margin-bottom: 12px;
}

.edit-toggle-btn {
  border: 1px solid #b8e5d4;
  background: #fff;
  color: #2eaf7d;
  border-radius: 10px;
  width: 38px;
  height: 38px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  font-size: 18px;
  transition: all .2s ease;
}

.edit-toggle-btn:hover {
  border-color: #2eaf7d;
  transform: translateY(-1px);
  box-shadow: 0 6px 14px rgba(46, 175, 125, 0.16);
}

.edit-toggle-btn.active {
  background: #2eaf7d;
  color: #fff;
  border-color: #2eaf7d;
}

.edit-toggle-btn:disabled {
  opacity: .45;
  cursor: not-allowed;
  transform: none;
  box-shadow: none;
}

.user-detail-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
  gap: 10px;
  margin-bottom: 12px;
}

.user-detail-item {
  border: 1px solid #e6f5ee;
  border-radius: 10px;
  padding: 10px;
  background: #fff;
}

.user-detail-item strong {
  color: #1f2d3d;
  display: block;
  margin-bottom: 3px;
}

.detail-avatar {
  width: 56px;
  height: 56px;
  border-radius: 999px;
  object-fit: cover;
  border: 2px solid #d9efe5;
}

.status-badge {
  display: inline-block;
  padding: 3px 10px;
  border-radius: 999px;
  font-size: 12px;
  font-weight: 700;
}

.status-active {
  background: #e6f8ef;
  color: #2eaf7d;
}

.status-pending-chip {
  background: #fff3e0;
  color: #e67e22;
}

.status-locked {
  background: #fdecea;
  color: #c62828;
}

.status-other {
  background: #eceff1;
  color: #546e7a;
}

.user-detail-actions {
  display: flex;
  flex-direction: column;
  gap: 10px;
  border-top: 1px solid #e1f1e8;
  padding-top: 12px;
}

.account-action-buttons {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 8px;
}

.user-lock-form,
.user-unlock-form {
  display: inline-flex;
  align-items: center;
}

.lock-reason-input {
  min-width: 260px;
}

.detail-hint {
  margin: 0;
  color: #607d8b;
}

.edit-user-form {
  border-top: 1px solid #e1f1e8;
  margin-top: 12px;
  padding-top: 12px;
}

.edit-user-form[hidden] {
  display: none;
}

.edit-user-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
  gap: 10px;
}

.form-field {
  display: grid;
  gap: 5px;
}

.form-field label {
  font-size: 13px;
  color: #456;
  font-weight: 600;
}

.full-row {
  grid-column: 1 / -1;
}

.edit-form-actions {
  margin-top: 10px;
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
  .users-hero {
    padding: 35px 25px;
  }

  .pending-actions {
    flex-direction: column;
    align-items: stretch;
  }

  .inline-form {
    width: 100%;
  }

  .reason-input {
    width: 100%;
  }

  .lock-reason-input {
    min-width: 0;
    width: 100%;
  }

  .user-lock-form,
  .user-unlock-form {
    width: auto;
  }

  .account-action-buttons {
    flex-direction: column;
    align-items: stretch;
  }

  .account-action-buttons .user-lock-form,
  .account-action-buttons .user-unlock-form {
    width: 100%;
  }

  .edit-user-grid {
    grid-template-columns: 1fr;
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

      const detailPanel = document.getElementById('userDetailPanel');
      const detailHint = document.getElementById('userDetailHint');
      const detailGrid = document.getElementById('userDetailGrid');
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
    })();
    </script>
  </section>
</section>