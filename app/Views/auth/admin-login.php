<?php
use App\Core\View;
/** @var string $csrf Token CSRF */
/** @var ?string $error Thông báo lỗi */
/** @var string $email Email gợi ý */
?>

<style>
  .auth-container {
    --primary: #d32f2f;
    --primary-dark: #b71c1c;
    --primary-soft: #ffebee;
    --bg-soft: #fdfcfc;
    --text-dark: #1f2d3d;
    --text-muted: #546e7a;
    --border: #ffcdd2;
    --white: #ffffff;
    --shadow-sm: 0 8px 24px rgba(31,45,61,0.08);
    --shadow-md: 0 16px 40px rgba(31,45,61,0.12);

    max-width: 520px;
    margin: 46px auto;
    padding: 0 16px;
    color: var(--text-dark);
    background: transparent !important;
    border: none !important;
    box-shadow: none !important;
    outline: none !important;
  }

  .auth-container * {
    box-sizing: border-box;
  }

  .auth-panel {
    position: relative;
    overflow: hidden;
    background:
      radial-gradient(circle at top left, rgba(211,47,47,0.16), transparent 34%),
      linear-gradient(135deg, #ffffff 0%, #fdfcfc 100%);
    border: 1px solid var(--border);
    border-radius: 28px;
    box-shadow: var(--shadow-md);
    padding: 38px;
  }

  .auth-panel::after {
    content: "";
    position: absolute;
    right: -70px;
    bottom: -70px;
    width: 190px;
    height: 190px;
    border-radius: 50%;
    background: rgba(211,47,47,0.10);
  }

  .auth-panel::before {
    content: "🔐";
    position: absolute;
    top: -20px;
    right: 20px;
    font-size: 60px;
    opacity: 0.1;
  }

  .auth-panel > * {
    position: relative;
    z-index: 1;
  }

  .auth-title {
    margin: 0;
    color: var(--text-dark);
    font-size: clamp(34px, 5vw, 46px);
    font-weight: 900;
    text-align: center;
    letter-spacing: -0.04em;
    line-height: 1.08;
  }

  .auth-subtitle {
    margin: 12px 0 26px;
    text-align: center;
    color: var(--text-muted);
    font-size: 16px;
    line-height: 1.55;
  }

  .auth-alert {
    display: grid;
    gap: 6px;
    background: #fff7ed;
    border: 1px solid #fed7aa;
    color: #9a3412;
    border-radius: 18px;
    padding: 14px 16px;
    margin-bottom: 18px;
    font-size: 14px;
    line-height: 1.5;
    text-align: center;
    box-shadow: var(--shadow-sm);
  }

  .auth-alert-title {
    font-weight: 900;
    text-align: center;
  }

  .auth-alert-body {
    margin: 0;
    text-align: center;
  }

  .auth-alert-danger {
    background: #fff1f1;
    border-color: #ffd1d1;
    color: #b42318;
  }

  .auth-form {
    display: grid;
    gap: 16px;
  }

  .auth-form-group {
    display: grid;
    gap: 8px;
  }

  .auth-form-group label {
    color: var(--text-dark);
    font-weight: 800;
    font-size: 14px;
  }

  .auth-input {
    width: 100%;
    height: 52px;
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 0 15px;
    color: var(--text-dark);
    background: #fcfffd;
    font-size: 15px;
    font-family: inherit;
    transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease, transform 0.2s ease;
  }

  .auth-input::placeholder {
    color: #8aa79b;
  }

  .auth-input:focus {
    outline: none;
    background: white;
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(211,47,47,0.12);
    transform: translateY(-1px);
  }

  .auth-btn {
    width: 100%;
    height: 52px;
    border: none;
    border-radius: 999px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: #fff;
    font-weight: 900;
    font-size: 16px;
    cursor: pointer;
    box-shadow: 0 10px 22px rgba(211,47,47,0.22);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }

  .auth-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 14px 30px rgba(211,47,47,0.28);
  }

  .auth-badge {
    display: inline-block;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 900;
    margin-bottom: 16px;
    text-align: center;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  @media (max-width: 520px) {
    .auth-container {
      margin: 28px auto;
      padding: 0 12px;
    }

    .auth-panel {
      padding: 28px 20px;
      border-radius: 22px;
    }
  }
</style>

<section class="auth-container" aria-labelledby="auth-admin-login-heading">
  <div class="auth-panel">
    <header>
      <div class="auth-badge">🔐 Khu vực quản trị</div>
      <h2 id="auth-admin-login-heading" class="auth-title">Đăng nhập Admin</h2>
      <p class="auth-subtitle">Chỉ dành cho nhân viên quản trị hệ thống</p>
    </header>

    <?php if ($error): ?>
      <div class="auth-alert auth-alert-danger" role="alert" aria-live="assertive">
        <div class="auth-alert-title">Đăng nhập thất bại</div>
        <p class="auth-alert-body"><?= nl2br(View::e((string)$error)) ?></p>
      </div>
    <?php endif; ?>

    <form method="post" action="/admin/login" aria-label="Form đăng nhập admin" class="auth-form">
      <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">

      <div class="auth-form-group">
        <label for="email">Email quản trị</label>
        <input id="email" name="email" type="email" required class="auth-input" placeholder="admin@example.com" value="<?= View::e($email ?? '') ?>" autocomplete="email">
      </div>

      <div class="auth-form-group">
        <label for="password">Mật khẩu</label>
        <input id="password" name="password" type="password" required class="auth-input" placeholder="Nhập mật khẩu" autocomplete="current-password">
      </div>

      <div class="auth-form-group">
        <button type="submit" class="auth-btn">Đăng nhập</button>
      </div>
    </form>
  </div>
</section>
