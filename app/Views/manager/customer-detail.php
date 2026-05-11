<?php
use App\Core\View;
/** @var array $customer Chi tiết customer */
/** @var string $csrf Token CSRF */
?>

<div style="max-width: 1200px; margin: 0 auto; padding: 24px 16px;">
  <a href="/manager/customers" style="color: #2eaf7d; text-decoration: none; font-weight: 600; margin-bottom: 20px; display: inline-block;">← Quay Lại</a>
  
  <h1 style="margin: 20px 0 24px; color: #1f2d3d; font-size: 32px;">Chi Tiết Khách Hàng</h1>

  <div style="background: #fff; border-radius: 12px; border: 1px solid #dcefe6; padding: 24px;">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
      <div>
        <h3 style="margin: 0 0 16px; color: #1f2d3d;">Thông Tin Cơ Bản</h3>
        <p style="margin: 12px 0;"><strong>Tên:</strong> <?= View::e($customer['name'] ?? 'N/A') ?></p>
        <p style="margin: 12px 0;"><strong>Email:</strong> <?= View::e($customer['email'] ?? 'N/A') ?></p>
        <p style="margin: 12px 0;"><strong>Điện Thoại:</strong> <?= View::e($customer['phone'] ?? 'N/A') ?></p>
        <p style="margin: 12px 0;"><strong>Địa Chỉ:</strong> <?= View::e($customer['address'] ?? 'N/A') ?></p>
      </div>
      <div>
        <h3 style="margin: 0 0 16px; color: #1f2d3d;">Trạng Thái Tài Khoản</h3>
        <p style="margin: 12px 0;"><strong>Trạng Thái:</strong> 
          <span style="display: inline-block; padding: 4px 8px; border-radius: 4px; font-weight: 600;
            <?php
              $status = $customer['approval_status'] ?? '';
              if ($status === 'active') echo 'background: #d4edda; color: #155724;';
              elseif ($status === 'pending') echo 'background: #fff3cd; color: #856404;';
              else echo 'background: #f8d7da; color: #721c24;';
            ?>">
            <?= View::e(ucfirst($status)) ?>
          </span>
        </p>
        <p style="margin: 12px 0;"><strong>Ngày Tạo:</strong> <?= View::e($customer['created_at'] ?? 'N/A') ?></p>
      </div>
    </div>
  </div>
</div>
