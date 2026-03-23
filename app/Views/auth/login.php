<?php
use App\Core\View;
/** @var string $csrf Token CSRF */
/** @var ?string $error Thông báo lỗi */
?>
<style>
  .auth-container {
    --auth-primary: #2ea77f;
    --auth-primary-dark: #248a69;
    --auth-text: #1f3f35;
    --auth-muted: #4f7668;
    --auth-border: #d9e5df;
    --auth-surface: #ffffff;
    max-width: 480px;
    margin: 46px auto;
    padding: 0 16px;
    background: transparent !important;
    border: none !important;
    box-shadow: none !important;
    outline: none !important;
  }

  .auth-panel {
    background: #ffffff;
    border: 1px solid var(--auth-border);
    border-radius: 18px;
    box-shadow: 0 10px 18px rgba(23, 50, 77, 0.06);
    padding: 30px;
  }

  .auth-title {
    margin: 0;
    color: var(--auth-text);
    font-size: 40px;
    font-weight: 900;
    text-align: center;
    letter-spacing: 0.2px;
    line-height: 1.08;
  }

  .auth-subtitle {
    margin: 10px 0 22px;
    text-align: center;
    color: var(--auth-muted);
    font-size: 17px;
    line-height: 1.45;
  }

  .auth-alert {
    display: grid;
    gap: 6px;
    background: #fff3e8;
    border: 1px solid #ffd5b2;
    color: #8c3a00;
    border-radius: 11px;
    padding: 12px 14px;
    margin-bottom: 14px;
    font-size: 14px;
    line-height: 1.5;
    text-align: center;
  }

  .auth-alert-title {
    font-weight: 800;
    letter-spacing: 0.2px;
    text-align: center;
  }

  .auth-alert-body {
    margin: 0;
    text-align: center;
  }

  .auth-alert-danger {
    background: #fff1f1;
    border-color: #ffc7c7;
    color: #8f1d1d;
  }

  .auth-form {
    display: grid;
    gap: 14px;
  }

  .auth-form-group {
    display: grid;
    gap: 8px;
  }

  .auth-form-group label {
    color: #2b5347;
    font-weight: 700;
    font-size: 16px;
  }

  .auth-input {
    width: 100%;
    height: 52px;
    border: 1px solid #c4d7cf;
    border-radius: 12px;
    padding: 0 14px;
    color: #24473c;
    background: var(--auth-surface);
    font-size: 16px;
    transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
    box-sizing: border-box;
  }

  .auth-input::placeholder {
    color: #7aa292;
  }

  .auth-input:focus {
    outline: none;
    border-color: var(--auth-primary);
    box-shadow: 0 0 0 4px rgba(45, 165, 127, 0.14);
    transform: translateY(-1px);
  }

  .auth-btn {
    width: 100%;
    height: 50px;
    border: none;
    border-radius: 12px;
    background: var(--auth-primary);
    color: #fff;
    font-weight: 800;
    font-size: 17px;
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
  }

  .auth-btn:hover {
    background: var(--auth-primary-dark);
    transform: translateY(-1px);
    box-shadow: 0 12px 20px rgba(45, 165, 127, 0.3);
  }

  .auth-link {
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid var(--auth-border);
    text-align: center;
    color: #54786c;
    font-size: 16px;
  }

  .auth-link a {
    color: var(--auth-primary);
    text-decoration: none;
    font-weight: 800;
  }

  .auth-link a:hover {
    color: var(--auth-primary-dark);
    text-decoration: underline;
  }

  @media (max-width: 520px) {
    .auth-panel {
      padding: 22px 16px;
      border-radius: 14px;
    }

    .auth-title {
      font-size: 32px;
    }

    .auth-subtitle {
      font-size: 15px;
    }
  }
</style>
<section class="auth-container" aria-labelledby="auth-login-heading">
  <div class="auth-panel">
    <header>
      <h2 id="auth-login-heading" class="auth-title">Đăng nhập</h2>
      <p class="auth-subtitle">Truy cập tài khoản để đặt lịch và theo dõi dịch vụ</p>
    </header>

    <?php if ($error): ?>
      <?php $isAccountLock = stripos((string)$error, 'tài khoản đã bị khóa') !== false; ?>
      <div class="auth-alert <?= $isAccountLock ? 'auth-alert-danger' : '' ?>" role="alert" aria-live="assertive">
        <div class="auth-alert-title"><?= $isAccountLock ? 'Cảnh báo tài khoản' : 'Thông báo đăng nhập' ?></div>
        <p class="auth-alert-body"><?= nl2br(View::e((string)$error)) ?></p>
      </div>
    <?php endif; ?>

    <form method="post" action="/login" aria-label="Form đăng nhập" class="auth-form">
      <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
      <div class="auth-form-group">
        <label for="email">Email</label>
        <input id="email" name="email" type="email" required class="auth-input" placeholder="you@example.com">
      </div>
      <div class="auth-form-group">
        <label for="password">Mật khẩu</label>
        <input id="password" name="password" type="password" required class="auth-input" placeholder="Nhập mật khẩu">
      </div>
      <div class="auth-form-group">
        <button type="submit" class="auth-btn">Đăng nhập</button>
      </div>
    </form>

    <nav class="auth-link" aria-label="Liên kết chuyển trang đăng ký">
      Chưa có tài khoản? <a href="/register">Đăng ký</a>
    </nav>
  </div>
</section>