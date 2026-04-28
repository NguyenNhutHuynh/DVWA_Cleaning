<?php
use App\Core\View;
?>

<style>
.account-edit-page {
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

.account-edit-page * {
  box-sizing: border-box;
}

.account-edit-card {
  overflow: hidden;
  border-radius: 28px;
  background: var(--white);
  border: 1px solid var(--border);
  box-shadow: var(--shadow-sm);
}

.account-edit-header {
  position: relative;
  overflow: hidden;
  padding: 42px 28px;
  background:
    radial-gradient(circle at top left, rgba(46,175,125,0.20), transparent 34%),
    linear-gradient(135deg, #f7fdf9 0%, #ffffff 48%, #e8f7f0 100%);
  border-bottom: 1px solid var(--border);
}

.account-edit-header::after {
  content: "";
  position: absolute;
  right: -70px;
  bottom: -70px;
  width: 200px;
  height: 200px;
  border-radius: 50%;
  background: rgba(46,175,125,0.13);
}

.account-edit-header h2 {
  position: relative;
  margin: 0;
  color: var(--text-dark);
  font-size: clamp(28px, 4vw, 42px);
  font-weight: 900;
  letter-spacing: -0.04em;
}

.account-edit-header p {
  position: relative;
  margin: 10px 0 0;
  color: var(--text-muted);
  font-size: 16px;
}

.account-edit-body {
  padding: 30px;
}

.account-edit-form {
  display: grid;
  gap: 18px;
  max-width: 620px;
  margin: 0 auto;
}

.account-form-group {
  display: grid;
  gap: 8px;
}

.account-form-group span {
  color: var(--text-dark);
  font-size: 14px;
  font-weight: 800;
}

.account-form-group input {
  width: 100%;
  min-height: 52px;
  padding: 14px 16px;
  border: 1px solid var(--border);
  border-radius: 16px;
  background: #fcfffd;
  color: var(--text-dark);
  font-size: 15px;
  font-family: inherit;
  transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
}

.account-form-group input:focus {
  outline: none;
  background: white;
  border-color: var(--primary);
  box-shadow: 0 0 0 4px rgba(46,175,125,0.12);
}

.avatar-preview {
  width: 78px;
  height: 78px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid var(--primary-soft);
  box-shadow: var(--shadow-sm);
}

.file-input {
  border: 1px dashed #bfe5d5 !important;
  background: var(--bg-soft) !important;
  cursor: pointer;
}

.account-help-text {
  color: var(--text-muted);
  font-size: 13px;
  line-height: 1.5;
}

.auth-btn {
  min-height: 50px;
  padding: 12px 26px;
  border-radius: 999px;
  border: none;
  background: linear-gradient(135deg, var(--primary), var(--primary-dark));
  color: white;
  font-size: 15px;
  font-weight: 900;
  cursor: pointer;
  box-shadow: 0 10px 22px rgba(46,175,125,0.22);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.auth-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 14px 30px rgba(46,175,125,0.28);
}

@media (max-width: 768px) {
  .account-edit-page {
    margin-top: 16px;
    padding: 0 12px 44px;
  }

  .account-edit-card {
    border-radius: 22px;
  }

  .account-edit-header {
    padding: 36px 20px;
  }

  .account-edit-body {
    padding: 22px;
  }

  .auth-btn {
    width: 100%;
  }
}
</style>

<section class="account-edit-page">
  <div class="account-edit-card">
    <header class="account-edit-header">
      <h2>Cập nhật thông tin</h2>
      <p>Chỉnh sửa hồ sơ cá nhân, thông tin liên hệ và ảnh đại diện.</p>
    </header>

    <div class="account-edit-body">
      <form method="post" action="/account/edit" enctype="multipart/form-data" class="account-edit-form">
        <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">

        <label class="account-form-group">
          <span>Họ tên</span>
          <input name="name" value="<?= View::e($user['name'] ?? '') ?>" required>
        </label>

        <label class="account-form-group">
          <span>Email</span>
          <input type="email" name="email" value="<?= View::e($user['email'] ?? '') ?>" required>
        </label>

        <label class="account-form-group">
          <span>Số điện thoại (tuỳ chọn)</span>
          <input name="phone" value="<?= View::e($user['phone'] ?? '') ?>">
        </label>

        <label class="account-form-group">
          <span>Địa chỉ (tuỳ chọn)</span>
          <input name="address" value="<?= View::e($user['address'] ?? '') ?>">
        </label>

        <label class="account-form-group">
          <span>Ảnh đại diện (tuỳ chọn)</span>

          <?php if (!empty($user['avatar'])): ?>
            <img src="<?= View::e($user['avatar']) ?>" alt="Avatar hiện tại" class="avatar-preview">
          <?php endif; ?>

          <input type="file" name="avatar" accept="image/*" class="file-input">
          <small class="account-help-text">Hỗ trợ: JPG, PNG, GIF, WebP. Tối đa 2MB.</small>
        </label>

        <button type="submit" class="auth-btn">Lưu thay đổi</button>
      </form>
    </div>
  </div>
</section>