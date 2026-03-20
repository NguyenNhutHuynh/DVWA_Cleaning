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
    max-width: 900px;
    margin: 44px auto;
    padding: 0 16px;
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

  .auth-error {
    background: #eef8f3;
    border: 1px solid #bfe8d6;
    color: #1f6f53;
    border-radius: 11px;
    padding: 11px 13px;
    margin-bottom: 14px;
    font-size: 14px;
  }

  .auth-form {
    display: grid;
    gap: 14px;
  }

  .auth-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
  }

  .auth-form-group {
    display: grid;
    gap: 8px;
  }

  .auth-form-group.full {
    grid-column: 1 / -1;
  }

  .auth-form-group label {
    color: #2b5347;
    font-weight: 700;
    font-size: 15px;
  }

  .auth-required {
    color: var(--auth-primary-dark);
  }

  .auth-input {
    width: 100%;
    height: 48px;
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

  @media (max-width: 760px) {
    .auth-grid {
      grid-template-columns: 1fr;
    }

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

<section class="auth-container" aria-labelledby="auth-register-heading">
  <div class="auth-panel">
    <header>
      <h2 id="auth-register-heading" class="auth-title">Đăng ký</h2>
      <p class="auth-subtitle">Tạo tài khoản để đặt lịch và quản lý dịch vụ của bạn</p>
    </header>

    <?php if ($error): ?>
      <div class="auth-error"><?= View::e($error) ?></div>
    <?php endif; ?>

    <form method="post" action="/register" aria-label="Form đăng ký" class="auth-form">
      <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">

      <div class="auth-grid">
        <div class="auth-form-group">
          <label for="name">Họ tên</label>
          <input id="name" name="name" required class="auth-input" placeholder="Nguyen Van A">
        </div>
        <div class="auth-form-group">
          <label for="email">Email</label>
          <input id="email" name="email" type="email" required class="auth-input" placeholder="you@example.com">
        </div>
        <div class="auth-form-group">
          <label for="phone">Số điện thoại <span class="auth-required">*</span></label>
          <input id="phone" name="phone" type="tel" required class="auth-input" placeholder="Ví dụ: 0901234567">
        </div>
        <div class="auth-form-group">
          <label for="city">Thành phố <span class="auth-required">*</span></label>
          <select id="city" name="city" required class="auth-input">
            <option value="">-- Chọn thành phố --</option>
            <option value="TP.HCM">TP.HCM</option>
          </select>
        </div>
        <div class="auth-form-group">
          <label for="ward">Phường/Xã <span class="auth-required">*</span></label>
          <select id="ward" name="ward" required class="auth-input">
            <option value="">-- Chọn phường/xã --</option>
            <option value="Phường Bến Nghé">Phường Bến Nghé</option>
            <option value="Phường Bến Thành">Phường Bến Thành</option>
            <option value="Phường Đa Kao">Phường Đa Kao</option>
            <option value="Phường Tân Định">Phường Tân Định</option>
            <option value="Phường Thảo Điền">Phường Thảo Điền</option>
            <option value="Phường An Phú">Phường An Phú</option>
            <option value="Phường Bình Trưng">Phường Bình Trưng</option>
            <option value="Phường Linh Tây">Phường Linh Tây</option>
            <option value="Phường Linh Đông">Phường Linh Đông</option>
            <option value="Phường Hiệp Bình">Phường Hiệp Bình</option>
            <option value="Phường Tăng Nhơn Phú">Phường Tăng Nhơn Phú</option>
            <option value="Phường Tân Sơn Nhất">Phường Tân Sơn Nhất</option>
            <option value="Phường 12 - Gò Vấp">Phường 12 - Gò Vấp</option>
            <option value="Phường 15 - Tân Bình">Phường 15 - Tân Bình</option>
            <option value="Phường 7 - Phú Nhuận">Phường 7 - Phú Nhuận</option>
            <option value="Phường 5 - Quận 8">Phường 5 - Quận 8</option>
            <option value="Xã Bình Hưng">Xã Bình Hưng</option>
            <option value="Xã Nhà Bè">Xã Nhà Bè</option>
            <option value="Xã Củ Chi">Xã Củ Chi</option>
          </select>
        </div>
        <div class="auth-form-group">
          <label for="role">Vai trò</label>
          <select id="role" name="role" required class="auth-input">
            <option value="customer">Khách hàng (Customer)</option>
            <option value="worker">Người lao động (Worker)</option>
          </select>
        </div>
        <div class="auth-form-group full">
          <label for="address_detail">Địa chỉ chi tiết <span class="auth-required">*</span></label>
          <input id="address_detail" name="address_detail" required class="auth-input" placeholder="Số nhà, tên đường...">
        </div>
        <div class="auth-form-group full">
          <label for="password">Mật khẩu</label>
          <input id="password" name="password" type="password" required minlength="6" class="auth-input" placeholder="Ít nhất 6 ký tự">
        </div>
      </div>

      <div class="auth-form-group">
        <button type="submit" class="auth-btn">Tạo tài khoản</button>
      </div>
    </form>

    <nav class="auth-link" aria-label="Liên kết chuyển trang đăng nhập">
      Đã có tài khoản? <a href="/login">Đăng nhập</a>
    </nav>
  </div>
</section>