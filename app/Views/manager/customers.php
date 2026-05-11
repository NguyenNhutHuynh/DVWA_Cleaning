<?php
use App\Core\View;
/** @var array $customers Danh sách customer */
/** @var string $csrf Token CSRF */
?>

<div style="max-width: 1200px; margin: 0 auto; padding: 24px 16px;">
  <h1 style="margin: 0 0 24px; color: #1f2d3d; font-size: 32px;">Quản Lý Khách Hàng</h1>

  <div style="background: #fff; border-radius: 12px; border: 1px solid #dcefe6; overflow: hidden;">
    <table style="width: 100%; border-collapse: collapse;">
      <thead>
        <tr style="background: #f7fdf9; border-bottom: 1px solid #dcefe6;">
          <th style="padding: 16px; text-align: left; color: #1f2d3d; font-weight: 700;">Tên</th>
          <th style="padding: 16px; text-align: left; color: #1f2d3d; font-weight: 700;">Email</th>
          <th style="padding: 16px; text-align: left; color: #1f2d3d; font-weight: 700;">Điện Thoại</th>
          <th style="padding: 16px; text-align: left; color: #1f2d3d; font-weight: 700;">Địa Chỉ</th>
          <th style="padding: 16px; text-align: center; color: #1f2d3d; font-weight: 700;">Hành Động</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($customers as $customer): ?>
          <tr style="border-bottom: 1px solid #dcefe6;">
            <td style="padding: 16px; color: #1f2d3d; font-weight: 600;"><?= View::e($customer['name']) ?></td>
            <td style="padding: 16px; color: #546e7a;"><?= View::e($customer['email']) ?></td>
            <td style="padding: 16px; color: #546e7a;"><?= View::e($customer['phone'] ?? 'N/A') ?></td>
            <td style="padding: 16px; color: #546e7a;"><?= View::e($customer['address'] ?? 'N/A') ?></td>
            <td style="padding: 16px; text-align: center;">
              <a href="/manager/customers/<?= $customer['id'] ?>" style="color: #2eaf7d; text-decoration: none; margin: 0 8px; font-weight: 600;">Chi Tiết</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <?php if (empty($customers)): ?>
    <div style="text-align: center; padding: 60px 20px; color: #546e7a;">
      <p style="font-size: 18px;">Không có khách hàng nào</p>
    </div>
  <?php endif; ?>
</div>
