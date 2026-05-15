<?php
use App\Core\View;
/** @var array $worker Chi tiết worker */
/** @var string $csrf Token CSRF */
?>

<div style="max-width: 1200px; margin: 0 auto; padding: 24px 16px;">
  <a href="/manager/workers" style="color: #2eaf7d; text-decoration: none; font-weight: 600; margin-bottom: 20px; display: inline-block;">← Quay Lại</a>
  
  <h1 style="margin: 20px 0 24px; color: #1f2d3d; font-size: 32px;">Chi Tiết Worker</h1>

  <div style="background: #fff; border-radius: 12px; border: 1px solid #dcefe6; padding: 24px;">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
      <div>
        <h3 style="margin: 0 0 16px; color: #1f2d3d;">Thông Tin Cơ Bản</h3>
        <p style="margin: 12px 0;"><strong>Tên:</strong> <?= View::e($worker['name'] ?? 'N/A') ?></p>
        <p style="margin: 12px 0;"><strong>Email:</strong> <?= View::e($worker['email'] ?? 'N/A') ?></p>
        <p style="margin: 12px 0;"><strong>Điện Thoại:</strong> <?= View::e($worker['phone'] ?? 'N/A') ?></p>
        <p style="margin: 12px 0;"><strong>Địa Chỉ:</strong> <?= View::e($worker['address'] ?? 'N/A') ?></p>
      </div>
      <div>
        <h3 style="margin: 0 0 16px; color: #1f2d3d;">Trạng Thái</h3>
        <p style="margin: 12px 0;"><strong>Trạng Thái Duyệt:</strong> 
          <span style="display: inline-block; padding: 4px 8px; border-radius: 4px; font-weight: 600;
            <?php
              $status = $worker['approval_status'] ?? '';
              if ($status === 'active') echo 'background: #d4edda; color: #155724;';
              elseif ($status === 'pending') echo 'background: #fff3cd; color: #856404;';
              else echo 'background: #f8d7da; color: #721c24;';
            ?>">
            <?= View::e(ucfirst($status)) ?>
          </span>
        </p>
        <p style="margin: 12px 0;"><strong>Ngày Tạo:</strong> <?= View::e($worker['created_at'] ?? 'N/A') ?></p>
      </div>
    </div>

    <!-- Approve/Reject Actions (only for pending workers) -->
    <?php if (($worker['approval_status'] ?? '') === 'pending'): ?>
      <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid #dcefe6;">
        <h3 style="margin: 0 0 16px; color: #1f2d3d;">Xử Lý Duyệt</h3>
        <div style="display: flex; align-items: center; flex-wrap: wrap; gap: 10px;">
          <form method="post" action="/manager/workers/approve" style="display: inline-flex; gap: 10px; align-items: center; margin: 0;">
            <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
            <input type="hidden" name="id" value="<?= View::e((string)$worker['id']) ?>">
            <button type="submit" style="min-height: 44px; padding: 11px 22px; border-radius: 999px; border: none; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; font-size: 14px; font-weight: 900; cursor: pointer; background: linear-gradient(135deg, #2eaf7d, #16805a); color: white; box-shadow: 0 10px 22px rgba(46,175,125,0.22); transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;">
              ✓ Phê duyệt
            </button>
          </form>

          <form method="post" action="/manager/workers/reject" style="display: inline-flex; gap: 10px; align-items: center; margin: 0;">
            <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
            <input type="hidden" name="id" value="<?= View::e((string)$worker['id']) ?>">
            <input type="text" name="reason" placeholder="Lý do từ chối (tùy chọn)" style="min-height: 46px; padding: 12px 14px; border: 1px solid #dcefe6; border-radius: 14px; background: #fcfffd; color: #1f2d3d; font-size: 14px; font-family: inherit; transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;">
            <button type="submit" style="min-height: 44px; padding: 11px 22px; border-radius: 999px; border: 1.5px solid #dc2626; background: white; color: #dc2626; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; font-size: 14px; font-weight: 900; cursor: pointer; box-shadow: none; transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;">
              ✗ Từ chối
            </button>
          </form>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php if (!empty($bookings) && is_array($bookings)): ?>
  <div style="max-width:1200px; margin:18px auto 40px; padding:0 16px;">
    <h2 style="margin:8px 0 12px; color:#1f2d3d;">Lịch sử làm việc (<?= count($bookings) ?>)</h2>
    <div style="background:#fff; border:1px solid #dcefe6; border-radius:12px; padding:12px;">
      <?php if (count($bookings) === 0): ?>
        <p class="empty-bookings">Chưa có lịch sử làm việc.</p>
      <?php else: ?>
        <table style="width:100%; border-collapse:collapse;">
          <thead>
            <tr style="text-align:left; border-bottom:1px solid #eef6f1;">
              <th style="padding:8px;">Mã đơn</th>
              <th style="padding:8px;">Thời gian</th>
              <th style="padding:8px;">Dịch vụ</th>
              <th style="padding:8px;">Giá</th>
              <th style="padding:8px;">Trạng thái</th>
              <th style="padding:8px;">Khách</th>
              <th style="padding:8px;">Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($bookings as $b): ?>
              <tr style="border-bottom:1px solid #f3f7f4;">
                <td style="padding:10px; font-weight:800;">#<?= View::e($b['id'] ?? '') ?></td>
                <td style="padding:10px;"><?= View::e(($b['time'] ?? '') . ' • ' . ($b['date'] ?? '')) ?></td>
                <td style="padding:10px;"><?= View::e($b['service_name'] ?? '') ?></td>
                <?php $priceValue = $b['line_total'] ?? $b['service_price'] ?? null; ?>
                <td style="padding:10px; white-space:nowrap;"><?= $priceValue !== null ? View::e(number_format((float)$priceValue, 0, ',', '.')) . ' đ' : '—' ?></td>
                <td style="padding:10px;"><?= View::e(ucfirst((string)($b['status'] ?? ''))) ?></td>
                <td style="padding:10px;"><?= View::e($b['customer_name'] ?? ($b['user_name'] ?? '—')) ?></td>
                <td style="padding:10px;"><a href="/manager/bookings/<?= (int)($b['id'] ?? 0) ?>" style="color:#2eaf7d; font-weight:700;">Chi tiết</a></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </div>
<?php endif; ?>
