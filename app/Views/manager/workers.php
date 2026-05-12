<?php
use App\Core\View;
/** @var array $workers Danh sách worker */
/** @var array $pendingWorkers Danh sách worker chờ duyệt */
/** @var string $csrf Token CSRF */
?>

<div style="max-width: 1200px; margin: 0 auto; padding: 24px 16px;">
  <h1 style="margin: 0 0 24px; color: #1f2d3d; font-size: 32px;">Quản Lý Worker</h1>

  <!-- ACTIVE WORKERS -->
  <div style="margin-bottom: 40px;">
    <h2 style="margin: 0 0 20px; color: #1f2d3d; font-size: 20px;">Worker Đang Hoạt Động (<?= count($workers) ?>)</h2>
    <div style="background: #fff; border-radius: 12px; border: 1px solid #dcefe6; overflow: hidden;">
      <table style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: #f7fdf9; border-bottom: 1px solid #dcefe6;">
            <th style="padding: 16px; text-align: left; color: #1f2d3d; font-weight: 700;">Tên</th>
            <th style="padding: 16px; text-align: left; color: #1f2d3d; font-weight: 700;">Email</th>
            <th style="padding: 16px; text-align: left; color: #1f2d3d; font-weight: 700;">Điện Thoại</th>
            <th style="padding: 16px; text-align: center; color: #1f2d3d; font-weight: 700;">Hành Động</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($workers as $worker): ?>
            <tr style="border-bottom: 1px solid #dcefe6;">
              <td style="padding: 16px; color: #1f2d3d; font-weight: 600;"><?= View::e($worker['name']) ?></td>
              <td style="padding: 16px; color: #546e7a;"><?= View::e($worker['email']) ?></td>
              <td style="padding: 16px; color: #546e7a;"><?= View::e($worker['phone'] ?? 'N/A') ?></td>
              <td style="padding: 16px; text-align: center;">
                <a href="/manager/workers/<?= $worker['id'] ?>" style="color: #2eaf7d; text-decoration: none; margin: 0 8px; font-weight: 600;">Chi Tiết</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php if (empty($workers)): ?>
      <div style="text-align: center; padding: 40px 20px; color: #546e7a;">
        <p>Không có worker đang hoạt động</p>
      </div>
    <?php endif; ?>
  </div>

  <!-- PENDING WORKERS -->
  <?php if (!empty($pendingWorkers)): ?>
    <div>
      <h2 style="margin: 0 0 20px; color: #1f2d3d; font-size: 20px;">Worker Chờ Duyệt (<?= count($pendingWorkers) ?>)</h2>
      <div style="background: #fff; border-radius: 12px; border: 1px solid #dcefe6; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
          <thead>
            <tr style="background: #f7fdf9; border-bottom: 1px solid #dcefe6;">
              <th style="padding: 16px; text-align: left; color: #1f2d3d; font-weight: 700;">Tên</th>
              <th style="padding: 16px; text-align: left; color: #1f2d3d; font-weight: 700;">Email</th>
              <th style="padding: 16px; text-align: center; color: #1f2d3d; font-weight: 700;">Hành Động</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($pendingWorkers as $worker): ?>
              <tr style="border-bottom: 1px solid #dcefe6;">
                <td style="padding: 16px; color: #1f2d3d; font-weight: 600;"><?= View::e($worker['name']) ?></td>
                <td style="padding: 16px; color: #546e7a;"><?= View::e($worker['email']) ?></td>
                <td style="padding: 16px; text-align: center;">
                  <a href="/manager/workers/<?= $worker['id'] ?>" style="color: #2eaf7d; text-decoration: none; margin: 0 8px; font-weight: 600;">Chi Tiết</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php endif; ?>
</div>
