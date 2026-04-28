<?php
use App\Core\View;
?>

<style>
.account-page {
  --primary: #2eaf7d;
  --primary-dark: #16805a;
  --primary-soft: #e8f7f0;
  --bg-soft: #f7fdf9;
  --text-dark: #1f2d3d;
  --text-muted: #546e7a;
  --border: #dcefe6;
  --white: #ffffff;
  --shadow-sm: 0 8px 24px rgba(31,45,61,0.08);
  --shadow-md: 0 16px 40px rgba(31,45,61,0.12);

  max-width: 1080px;
  margin: 24px auto 60px;
  padding: 0 16px;
  color: var(--text-dark);
}

.account-page * {
  box-sizing: border-box;
}

.account-card {
  overflow: hidden;
  border-radius: 28px;
  background: var(--white);
  border: 1px solid var(--border);
  box-shadow: var(--shadow-sm);
}

.account-header {
  position: relative;
  overflow: hidden;
  padding: 42px 28px;
  background:
    radial-gradient(circle at top left, rgba(46,175,125,0.20), transparent 34%),
    linear-gradient(135deg, #f7fdf9 0%, #ffffff 48%, #e8f7f0 100%);
  border-bottom: 1px solid var(--border);
}

.account-header::after {
  content: "";
  position: absolute;
  right: -70px;
  bottom: -70px;
  width: 200px;
  height: 200px;
  border-radius: 50%;
  background: rgba(46,175,125,0.13);
}

.account-header h2 {
  position: relative;
  margin: 0;
  color: var(--text-dark);
  font-size: clamp(28px, 4vw, 42px);
  font-weight: 900;
  letter-spacing: -0.04em;
}

.account-header p {
  position: relative;
  margin: 10px 0 0;
  color: var(--text-muted);
  font-size: 16px;
}

.account-body {
  padding: 30px;
}

.account-empty {
  padding: 30px;
  border-radius: 20px;
  background: var(--bg-soft);
  border: 1px dashed #cfe3d8;
  color: var(--text-muted);
  text-align: center;
}

.account-profile {
  display: grid;
  grid-template-columns: 130px 1fr;
  gap: 24px;
  align-items: center;
}

.account-avatar {
  width: 104px;
  height: 104px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--primary-soft), #ffffff);
  border: 3px solid var(--primary-soft);
  color: var(--primary-dark);
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 900;
  font-size: 38px;
  overflow: hidden;
  box-shadow: var(--shadow-sm);
}

.account-avatar img {
  width: 104px;
  height: 104px;
  border-radius: 50%;
  object-fit: cover;
  display: block;
}

.account-info-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 14px;
}

.account-info-item {
  padding: 16px;
  border-radius: 18px;
  background: var(--bg-soft);
  border: 1px solid var(--border);
}

.account-info-label {
  display: block;
  margin-bottom: 6px;
  color: var(--text-muted);
  font-size: 13px;
  font-weight: 800;
}

.account-info-value {
  display: block;
  color: var(--text-dark);
  font-size: 15px;
  font-weight: 900;
  line-height: 1.5;
  word-break: break-word;
}

.account-role {
  color: var(--primary);
}

.account-actions {
  margin-top: 26px;
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
}

.account-btn,
/* .function-btn {
  min-height: 46px;
  padding: 12px 26px;
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
  transition: transform 0.2s ease, box-shadow 0.2s ease;
} */

.account-btn:hover,
.function-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 14px 30px rgba(46,175,125,0.28);
}

@media (max-width: 768px) {
  .account-page {
    margin-top: 16px;
    padding: 0 12px 44px;
  }

  .account-card {
    border-radius: 22px;
  }

  .account-header {
    padding: 36px 20px;
  }

  .account-body {
    padding: 22px;
  }

  .account-profile {
    grid-template-columns: 1fr;
    justify-items: center;
    text-align: center;
  }

  .account-info-grid {
    width: 100%;
    text-align: left;
  }

  .account-actions,
  .account-btn,
  .function-btn {
    width: 100%;
  }
}
</style>

<section class="account-page">
  <div class="account-card">
    <header class="account-header">
      <h2>Thông tin cá nhân</h2>
      <p>Quản lý thông tin tài khoản, vai trò và trạng thái hồ sơ của bạn.</p>
    </header>

    <div class="account-body">
      <?php if (!$user): ?>
        <p class="account-empty">Không tìm thấy thông tin người dùng.</p>
      <?php else: ?>
        <?php $initial = strtoupper(substr($user['name'] ?? 'U', 0, 1)); ?>

        <div class="account-profile">
          <div class="account-avatar">
            <?php if (!empty($user['avatar'])): ?>
              <img src="<?= View::e($user['avatar']) ?>" alt="Avatar">
            <?php else: ?>
              <?= View::e($initial) ?>
            <?php endif; ?>
          </div>

          <div class="account-info-grid">
            <div class="account-info-item">
              <span class="account-info-label">Họ tên</span>
              <span class="account-info-value"><?= View::e($user['name'] ?? '') ?></span>
            </div>

            <div class="account-info-item">
              <span class="account-info-label">Email</span>
              <span class="account-info-value"><?= View::e($user['email'] ?? '') ?></span>
            </div>

            <?php if (!empty($user['phone'])): ?>
              <div class="account-info-item">
                <span class="account-info-label">SĐT</span>
                <span class="account-info-value"><?= View::e($user['phone']) ?></span>
              </div>
            <?php endif; ?>

            <?php if (!empty($user['address'])): ?>
              <div class="account-info-item">
                <span class="account-info-label">Địa chỉ</span>
                <span class="account-info-value"><?= View::e($user['address']) ?></span>
              </div>
            <?php endif; ?>

            <div class="account-info-item">
              <span class="account-info-label">Vai trò</span>
              <span class="account-info-value account-role"><?= View::e($user['role'] ?? '') ?></span>
            </div>

            <?php if (!empty($user['approval_status'])): ?>
              <div class="account-info-item">
                <span class="account-info-label">Trạng thái</span>
                <span class="account-info-value"><?= View::e($user['approval_status']) ?></span>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <div class="account-actions">
          <a href="/account/edit" class="function-btn">Cập nhật thông tin</a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>