<?php
use App\Core\View;
?>
<div style="max-width:1080px;margin:24px auto;padding:20px;border:1px solid #e5e7eb;border-radius:12px;background:#fff;">
  <h2 style="margin:0 0 16px 0;">Thông tin cá nhân</h2>
  <?php if (!$user): ?>
    <p>Không tìm thấy thông tin người dùng.</p>
  <?php else: ?>
    <div style="display:grid;grid-template-columns:200px 1fr;gap:12px;align-items:center;margin-bottom:16px;">
      <div style="width:80px;height:80px;border-radius:50%;background:#eceff1;color:#37474f;display:flex;align-items:center;justify-content:center;font-weight:600;font-size:28px;overflow:hidden;">
        <?php $initial = strtoupper(substr($user['name'] ?? 'U', 0, 1)); ?>
        <?php if (!empty($user['avatar'])): ?>
          <img src="<?= View::e($user['avatar']) ?>" alt="Avatar" style="width:80px;height:80px;border-radius:50%;object-fit:cover;display:block;">
        <?php else: ?>
          <?= View::e($initial) ?>
        <?php endif; ?>
      </div>
      <div>
        <div><strong>Họ tên:</strong> <?= View::e($user['name'] ?? '') ?></div>
        <div><strong>Email:</strong> <?= View::e($user['email'] ?? '') ?></div>
        <?php if (!empty($user['phone'])): ?>
          <div><strong>SĐT:</strong> <?= View::e($user['phone']) ?></div>
        <?php endif; ?>
        <?php if (!empty($user['address'])): ?>
          <div><strong>Địa chỉ:</strong> <?= View::e($user['address']) ?></div>
        <?php endif; ?>
        <div><strong>Vai trò:</strong> <?= View::e($user['role'] ?? '') ?></div>
        <?php if (!empty($user['approval_status'])): ?>
          <div><strong>Trạng thái:</strong> <?= View::e($user['approval_status']) ?></div>
        <?php endif; ?>
      </div>
    </div>
    <a href="/account/edit" class="function-btn">Cập nhật thông tin</a>
  <?php endif; ?>
</div>
