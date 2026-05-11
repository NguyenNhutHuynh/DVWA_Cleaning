<?php
use App\Core\View;
use App\Models\Booking;
use App\Models\PaymentTransaction;
/** @var array $bookings Danh sách các đơn đặt */
/** @var array $workers Danh sách worker */
/** @var string $csrf Token CSRF */
?>

<div style="max-width: 1200px; margin: 0 auto; padding: 24px 16px;">
  <h1 style="margin: 0 0 24px; color: #1f2d3d; font-size: 32px;">Quản Lý Đơn Đặt</h1>

  <div style="background: #fff; border-radius: 12px; border: 1px solid #dcefe6; overflow: hidden;">
    <table style="width: 100%; border-collapse: collapse;">
      <thead>
        <tr style="background: #f7fdf9; border-bottom: 1px solid #dcefe6;">
          <th style="padding: 16px; text-align: left; color: #1f2d3d; font-weight: 700;">Khách Hàng</th>
          <th style="padding: 16px; text-align: left; color: #1f2d3d; font-weight: 700;">Dịch Vụ</th>
          <th style="padding: 16px; text-align: left; color: #1f2d3d; font-weight: 700;">Ngày</th>
          <th style="padding: 16px; text-align: left; color: #1f2d3d; font-weight: 700;">Trạng Thái</th>
          <th style="padding: 16px; text-align: left; color: #1f2d3d; font-weight: 700;">Thanh Toán</th>
          <th style="padding: 16px; text-align: center; color: #1f2d3d; font-weight: 700;">Hành Động</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($bookings as $booking): ?>
          <tr style="border-bottom: 1px solid #dcefe6;">
            <td style="padding: 16px; color: #546e7a;"><?= View::e($booking['customer_name'] ?? 'N/A') ?></td>
            <td style="padding: 16px; color: #546e7a;"><?= View::e($booking['service_name'] ?? 'N/A') ?></td>
            <td style="padding: 16px; color: #546e7a;"><?= View::e($booking['booking_date'] ?? 'N/A') ?></td>
            <td style="padding: 16px;">
              <span style="display: inline-block; padding: 6px 12px; border-radius: 6px; font-size: 13px; font-weight: 600;
                <?php
                  $status = $booking['status'] ?? '';
                  if ($status === 'pending') {
                    echo 'background: #fff3cd; color: #856404;';
                  } elseif ($status === 'confirmed') {
                    echo 'background: #cfe2ff; color: #084298;';
                  } elseif ($status === 'in_progress') {
                    echo 'background: #d1ecf1; color: #0c5460;';
                  } elseif ($status === 'completed') {
                    echo 'background: #d4edda; color: #155724;';
                  } else {
                    echo 'background: #f8d7da; color: #721c24;';
                  }
                ?>">
                <?= View::e(ucfirst($status)) ?>
              </span>
            </td>
            <td style="padding: 16px;">
              <?php if ($booking['hasPaidPayment'] ?? false): ?>
                <span style="color: #28a745; font-weight: 600;">✓ Đã thanh toán</span>
              <?php else: ?>
                <span style="color: #dc3545; font-weight: 600;">✗ Chưa thanh toán</span>
              <?php endif; ?>
            </td>
            <td style="padding: 16px; text-align: center;">
              <a href="/manager/bookings/<?= $booking['id'] ?>" style="color: #2eaf7d; text-decoration: none; margin: 0 8px; font-weight: 600;">Chi Tiết</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <?php if (empty($bookings)): ?>
    <div style="text-align: center; padding: 60px 20px; color: #546e7a;">
      <p style="font-size: 18px;">Không có đơn đặt nào</p>
    </div>
  <?php endif; ?>
</div>
