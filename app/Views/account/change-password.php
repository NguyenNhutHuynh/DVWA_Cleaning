<?php
use App\Core\View;
?>
<div style="max-width:1080px;margin:24px auto;padding:20px;border:1px solid #e5e7eb;border-radius:12px;background:#fff;">
  <h2 style="margin:0 0 16px 0;">Đổi mật khẩu</h2>
  <form method="post" action="/account/update-password" style="display:grid;gap:12px;max-width:520px;margin:0 auto;">
    <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
    <label style="display:flex;flex-direction:column;gap:6px;">
      <span>Mật khẩu hiện tại</span>
      <input type="password" name="current_password" placeholder="Nhập mật khẩu hiện tại" required style="padding:10px;border:1px solid #cfd8dc;border-radius:8px;">
    </label>
    <label style="display:flex;flex-direction:column;gap:6px;">
      <span>Mật khẩu mới</span>
      <input type="password" name="new_password" placeholder="Ít nhất 6 ký tự" required style="padding:10px;border:1px solid #cfd8dc;border-radius:8px;">
    </label>
    <label style="display:flex;flex-direction:column;gap:6px;">
      <span>Xác nhận mật khẩu mới</span>
      <input type="password" name="confirm_password" placeholder="Nhập lại mật khẩu mới" required style="padding:10px;border:1px solid #cfd8dc;border-radius:8px;">
    </label>
    <button type="submit" class="auth-btn">Đổi mật khẩu</button>
    <a href="/account/edit" style="text-align:center;padding:10px;text-decoration:none;color:#6366f1;">Quay lại</a>
  </form>
</div>
