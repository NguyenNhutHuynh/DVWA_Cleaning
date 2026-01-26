<?php
use App\Core\View;
?>
<div style="max-width:1080px;margin:24px auto;padding:20px;border:1px solid #e5e7eb;border-radius:12px;background:#fff;">
  <h2 style="margin:0 0 16px 0;">Cập nhật thông tin</h2>
  <form method="post" action="/account/edit" enctype="multipart/form-data" style="display:grid;gap:12px;max-width:520px;margin:0 auto;">
    <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
    <label style="display:flex;flex-direction:column;gap:6px;">
      <span>Họ tên</span>
      <input name="name" value="<?= View::e($user['name'] ?? '') ?>" required style="padding:10px;border:1px solid #cfd8dc;border-radius:8px;">
    </label>
    <label style="display:flex;flex-direction:column;gap:6px;">
      <span>Email</span>
      <input type="email" name="email" value="<?= View::e($user['email'] ?? '') ?>" required style="padding:10px;border:1px solid #cfd8dc;border-radius:8px;">
    </label>
    <label style="display:flex;flex-direction:column;gap:6px;">
      <span>Số điện thoại (tuỳ chọn)</span>
      <input name="phone" value="<?= View::e($user['phone'] ?? '') ?>" style="padding:10px;border:1px solid #cfd8dc;border-radius:8px;">
    </label>
    <label style="display:flex;flex-direction:column;gap:6px;">
      <span>Địa chỉ (tuỳ chọn)</span>
      <input name="address" value="<?= View::e($user['address'] ?? '') ?>" style="padding:10px;border:1px solid #cfd8dc;border-radius:8px;">
    </label>
    <label style="display:flex;flex-direction:column;gap:6px;">
      <span>Ảnh đại diện (tuỳ chọn)</span>
      <?php if (!empty($user['avatar'])): ?>
        <img src="<?= View::e($user['avatar']) ?>" alt="Avatar hiện tại" style="width:64px;height:64px;border-radius:50%;object-fit:cover;">
      <?php endif; ?>
      <input type="file" name="avatar" accept="image/*" style="padding:10px;border:1px solid #cfd8dc;border-radius:8px;">
      <small>Hỗ trợ: JPG, PNG, GIF, WebP. Tối đa 2MB.</small>
    </label>
    <button type="submit" class="auth-btn">Lưu thay đổi</button>
    <!-- <a href="/account/change-password" style="display:block;padding:10px 16px;background:#6366f1;color:#fff;text-decoration:none;border-radius:8px;font-weight:500;text-align:center;">Đổi mật khẩu</a> -->
  </form>
</div>
