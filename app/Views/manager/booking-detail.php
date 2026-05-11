<?php
use App\Core\View;
/** @var array $booking Chi tiết đơn đặt */
/** @var array $workers Danh sách worker */
/** @var array $progress Tiến độ công việc */
/** @var array $messages Tin nhắn */
/** @var array $payment Thanh toán */
/** @var array $customerPayment Thanh toán của khách */
/** @var array $customerPaidTransaction Giao dịch thanh toán của khách */
/** @var array|null $report Báo cáo */
/** @var array|null $review Đánh giá */
/** @var string $csrf Token CSRF */

$isCustomerPaid = $customerPayment !== null && (($customerPayment['status'] ?? '') === 'paid');
$assignedWorkerId = (int)($booking['assigned_worker_id'] ?? 0);
$bookingStatus = (string)($booking['status'] ?? '');
?>

<div style="max-width: 1200px; margin: 0 auto; padding: 24px 16px;">
  <a href="/manager/bookings" style="color: #2eaf7d; text-decoration: none; font-weight: 600; margin-bottom: 20px; display: inline-block;">← Quay Lại</a>
  
  <h1 style="margin: 20px 0 24px; color: #1f2d3d; font-size: 32px;">Chi Tiết Đơn Đặt</h1>

  <!-- THÔNG TIN CƠ BẢN -->
  <div style="background: #fff; border-radius: 12px; border: 1px solid #dcefe6; padding: 24px; margin-bottom: 20px;">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
      <div>
        <h3 style="margin: 0 0 12px; color: #1f2d3d;">Thông Tin Cơ Bản</h3>
        <p style="margin: 8px 0;"><strong>Khách Hàng:</strong> <?= View::e($booking['customer_name'] ?? 'N/A') ?></p>
        <p style="margin: 8px 0;"><strong>Dịch Vụ:</strong> <?= View::e($booking['service_name'] ?? 'N/A') ?></p>
        <p style="margin: 8px 0;"><strong>Ngày Đặt:</strong> <?= View::e($booking['booking_date'] ?? 'N/A') ?></p>
        <p style="margin: 8px 0;"><strong>Trạng Thái:</strong> 
          <span style="display: inline-block; padding: 4px 8px; border-radius: 4px; font-weight: 600;
            <?php
              if ($bookingStatus === 'pending') echo 'background: #fff3cd; color: #856404;';
              elseif ($bookingStatus === 'confirmed') echo 'background: #cfe2ff; color: #084298;';
              elseif ($bookingStatus === 'in_progress') echo 'background: #d1ecf1; color: #0c5460;';
              elseif ($bookingStatus === 'completed') echo 'background: #d4edda; color: #155724;';
              else echo 'background: #f8d7da; color: #721c24;';
            ?>">
            <?= View::e(ucfirst($bookingStatus)) ?>
          </span>
        </p>
      </div>
      <div>
        <h3 style="margin: 0 0 12px; color: #1f2d3d;">Thanh Toán</h3>
        <p style="margin: 8px 0;"><strong>Trạng Thái:</strong>
          <span style="display: inline-block; padding: 4px 8px; border-radius: 4px; font-weight: 600;
            <?php echo $isCustomerPaid ? 'background: #d4edda; color: #155724;' : 'background: #fff3cd; color: #856404;'; ?>">
            <?= $isCustomerPaid ? 'Đã Thanh Toán' : 'Chưa Thanh Toán' ?>
          </span>
        </p>
        <p style="margin: 8px 0;"><strong>Giá:</strong> <?= View::e($booking['price'] ?? '0') ?> VNĐ</p>
        <p style="margin: 8px 0;"><strong>Ghi Chú:</strong> <?= View::e($booking['notes'] ?? 'Không có') ?></p>
      </div>
    </div>
  </div>

  <!-- PHÂN CÔNG WORKER -->
  <div style="background: #fff; border-radius: 12px; border: 1px solid #dcefe6; padding: 24px; margin-bottom: 20px;">
    <h3 style="margin: 0 0 16px; color: #1f2d3d; font-size: 18px;">Phân Công Worker</h3>
    
    <?php if ($assignedWorkerId > 0): ?>
      <p style="margin: 0 0 16px; color: #546e7a;">
        <strong>Worker Hiện Tại:</strong> <?= View::e($booking['worker_name'] ?? 'N/A') ?>
      </p>
    <?php else: ?>
      <p style="margin: 0 0 16px; color: #d97706; font-weight: 600;">⚠️ Chưa phân công worker</p>
    <?php endif; ?>

    <?php if ($isCustomerPaid): ?>
      <form method="post" action="/manager/bookings/assign" style="display: grid; gap: 12px;">
        <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
        <input type="hidden" name="id" value="<?= View::e($booking['id'] ?? '') ?>">
        <input type="hidden" name="return_to" value="/manager/bookings/<?= View::e($booking['id'] ?? '') ?>">
        
        <div style="display: grid; grid-template-columns: 1fr auto; gap: 12px;">
          <select name="worker_id" required style="padding: 10px; border: 1px solid #dcefe6; border-radius: 6px; background: #fff; color: #1f2d3d;">
            <option value="">-- Chọn Worker --</option>
            <?php foreach ($workers as $w): ?>
              <option value="<?= View::e($w['id']) ?>" <?= $w['id'] === $assignedWorkerId ? 'selected' : '' ?>>
                <?= View::e($w['name']) ?> (<?= View::e($w['email']) ?>)
              </option>
            <?php endforeach; ?>
          </select>
          <button type="submit" style="padding: 10px 20px; background: #2eaf7d; color: #fff; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: background 0.2s;">
            Gán Worker
          </button>
        </div>
      </form>
    <?php else: ?>
      <div style="padding: 12px; background: #fff3cd; border-radius: 6px; color: #856404; margin-bottom: 12px;">
        <strong>Lưu ý:</strong> Khách hàng chưa thanh toán. Chỉ có thể gán worker sau khi thanh toán thành công.
      </div>
    <?php endif; ?>
  </div>

  <!-- THÔNG TIN KHÁCH HÀNG -->
  <div style="background: #fff; border-radius: 12px; border: 1px solid #dcefe6; padding: 24px; margin-bottom: 20px;">
    <h3 style="margin: 0 0 16px; color: #1f2d3d; font-size: 18px;">Thông Tin Khách Hàng</h3>
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
      <p style="margin: 0;"><strong>Tên:</strong> <?= View::e($booking['customer_name'] ?? 'N/A') ?></p>
      <p style="margin: 0;"><strong>Email:</strong> <?= View::e($booking['customer_email'] ?? 'N/A') ?></p>
      <p style="margin: 0;"><strong>Điện Thoại:</strong> <?= View::e($booking['customer_phone'] ?? 'N/A') ?></p>
      <p style="margin: 0;"><strong>Địa Chỉ:</strong> <?= View::e($booking['customer_address'] ?? 'N/A') ?></p>
    </div>
  </div>

  <!-- TIẾN ĐỘ CÔNG VIỆC -->
  <?php if (!empty($progress)): ?>
    <div style="background: #fff; border-radius: 12px; border: 1px solid #dcefe6; padding: 24px; margin-bottom: 20px;">
      <h3 style="margin: 0 0 16px; color: #1f2d3d; font-size: 18px;">Tiến Độ Công Việc</h3>
      <div style="display: grid; gap: 10px;">
        <?php foreach ($progress as $p): ?>
          <div style="padding: 12px; background: #f7fdf9; border-radius: 6px; border-left: 4px solid #2eaf7d;">
            <p style="margin: 0 0 4px; color: #1f2d3d; font-weight: 600;"><?= View::e($p['status'] ?? 'N/A') ?></p>
            <p style="margin: 0; color: #546e7a; font-size: 13px;"><?= View::e($p['created_at'] ?? 'N/A') ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>

  <!-- BÁO CÁO & ĐÁNH GIÁ -->
  <?php if ($report !== null || $review !== null): ?>
    <div style="background: #fff; border-radius: 12px; border: 1px solid #dcefe6; padding: 24px; margin-bottom: 20px;">
      <h3 style="margin: 0 0 16px; color: #1f2d3d; font-size: 18px;">Báo Cáo & Đánh Giá</h3>
      
      <?php if ($report !== null): ?>
        <div style="margin-bottom: 16px;">
          <h4 style="margin: 0 0 8px; color: #1f2d3d;">Báo Cáo</h4>
          <p style="margin: 0; color: #546e7a;"><?= View::e($report['content'] ?? 'Không có báo cáo') ?></p>
        </div>
      <?php endif; ?>

      <?php if ($review !== null): ?>
        <div>
          <h4 style="margin: 0 0 8px; color: #1f2d3d;">Đánh Giá (<?= View::e($review['rating'] ?? '0') ?> sao)</h4>
          <p style="margin: 0; color: #546e7a;"><?= View::e($review['comment'] ?? 'Không có bình luận') ?></p>
        </div>
      <?php endif; ?>
    </div>
  <?php endif; ?>
</div>
