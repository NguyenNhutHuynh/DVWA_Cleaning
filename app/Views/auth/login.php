<?php
use App\Core\View;
/** @var string $csrf Token CSRF */
/** @var ?string $error Thông báo lỗi */
?>
<section class="auth-container" aria-labelledby="auth-login-heading">
  <header>
    <h2 id="auth-login-heading" class="auth-title">Đăng nhập</h2>
  </header>

  <?php if ($error): ?>
    <div class="auth-error"><?= View::e($error) ?></div>
  <?php endif; ?>

  <form method="post" action="/login" aria-label="Form đăng nhập">
    <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
    <div class="auth-form-group">
      <label for="email">Email</label>
      <input id="email" name="email" type="email" required class="auth-input">
    </div>
    <div class="auth-form-group">
      <label for="password">Mật khẩu</label>
      <input id="password" name="password" type="password" required class="auth-input">
    </div>
    <!-- Vai trò được xác định tự động theo tài khoản -->
    <div class="auth-form-group">
      <button type="submit" class="auth-btn">Đăng nhập</button>
    </div>
  </form>

  <nav class="auth-link" aria-label="Liên kết chuyển trang đăng ký">
    Chưa có tài khoản? <a href="/register">Đăng ký</a>
  </nav>
</section>