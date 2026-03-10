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
      <label for="phone">Số điện thoại <span style="color:#dc3545;">*</span></label>
      <input id="phone" name="phone" type="tel" required class="auth-input" placeholder="Ví dụ: 0901234567">
    </div>
    <div class="auth-form-group">
      <label for="city">Thành phố <span style="color:#dc3545;">*</span></label>
      <select id="city" name="city" required class="auth-input">
        <option value="">-- Chọn thành phố --</option>
        <option value="TP.HCM">TP.HCM</option>
      </select>
    </div>
    <div class="auth-form-group">
      <label for="ward">Phường/Xã <span style="color:#dc3545;">*</span></label>
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
      <label for="address_detail">Địa chỉ chi tiết <span style="color:#dc3545;">*</span></label>
      <input id="address_detail" name="address_detail" required class="auth-input" placeholder="Số nhà, tên đường...">
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