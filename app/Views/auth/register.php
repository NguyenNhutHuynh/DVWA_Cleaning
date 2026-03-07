<?php
use App\Core\View;
/** @var string $csrf Token CSRF */
/** @var ?string $error Thông báo lỗi */
?>

<section class="auth-container" aria-labelledby="auth-register-heading">
  <header>
    <h2 id="auth-register-heading" class="auth-title">Đăng ký</h2>
  </header>

  <?php if ($error): ?>
    <div class="auth-error"><?= View::e($error) ?></div>
  <?php endif; ?>

  <form method="post" action="/register" aria-label="Form đăng ký">
    <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
    <div class="auth-form-group">
      <label for="name">Họ tên</label>
      <input id="name" name="name" required class="auth-input">
    </div>
    <div class="auth-form-group">
      <label for="email">Email</label>
      <input id="email" name="email" type="email" required class="auth-input">
    </div>
    <div class="auth-form-group">
      <label for="password">Mật khẩu</label>
      <input id="password" name="password" type="password" required minlength="6" class="auth-input">
    </div>
    <div class="auth-form-group">
      <label for="role">Vai trò</label>
      <select id="role" name="role" required class="auth-input">
        <option value="customer">Khách hàng (Customer)</option>
        <option value="worker">Người lao động (Worker)</option>
      </select>
    </div>
    <div class="auth-form-group">
      <button type="submit" class="auth-btn">Tạo tài khoản</button>
    </div>
  </form>

  <nav class="auth-link" aria-label="Liên kết chuyển trang đăng nhập">
    Đã có tài khoản? <a href="/login">Đăng nhập</a>
  </nav>
</section>