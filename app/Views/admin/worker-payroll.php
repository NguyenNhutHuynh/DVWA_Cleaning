<?php
use App\Core\View;
/** @var array $bookings */
/** @var string $csrf */

$statusLabels = [
    'pending_payout' => 'Cho duyet luong',
    'payout_processing' => 'Dang tao giao dich PayOS',
    'payout_paid' => 'Da chi luong',
    'ready' => 'San sang chi luong',
];
?>

<style>
.worker-payroll {
  max-width: 1200px;
  margin: 0 auto 70px;
  padding: 0 16px;
}

.payroll-hero {
  background: linear-gradient(135deg, #2eaf7d 0%, #43c59e 50%, #8cdf94 100%);
  border-radius: 20px;
  padding: 44px 34px;
  color: #fff;
  box-shadow: 0 10px 40px rgba(46, 175, 125, 0.3);
}

.payroll-table-wrap {
  margin-top: 18px;
  border: 1px solid #d9efe5;
  border-radius: 16px;
  background: #fff;
  overflow-x: auto;
}

.payroll-table {
  width: 100%;
  border-collapse: collapse;
  min-width: 980px;
}

.payroll-table th,
.payroll-table td {
  text-align: left;
  padding: 12px 10px;
  border-bottom: 1px solid #edf7f2;
  vertical-align: middle;
}

.payroll-table th {
  background: #f3fbf7;
  color: #245b43;
  font-weight: 700;
}

.status-chip {
  display: inline-block;
  padding: 4px 10px;
  border-radius: 999px;
  font-weight: 700;
  font-size: 12px;
  background: #e9f5ef;
  color: #166534;
}

.status-chip.pending {
  background: #fef3c7;
  color: #92400e;
}

.status-chip.done {
  background: #d1fae5;
  color: #065f46;
}

.salary-form {
  display: flex;
  align-items: center;
  gap: 8px;
}

.salary-input {
  width: 150px;
  border: 1px solid #cfe9dc;
  border-radius: 10px;
  padding: 8px 10px;
}

@media (max-width: 768px) {
  .payroll-hero {
    padding: 34px 22px;
  }
}
</style>

<section class="home-container worker-payroll">
  <header class="home-hero payroll-hero">
    <p class="home-kicker">ADMIN • DUYET LUONG WORKER</p>
    <h1>Chi luong worker qua PayOS</h1>
    <p>Chi duoc chi luong cho booking da duoc khach thanh toan va da gan worker.</p>
  </header>

  <section class="home-feature" style="margin-top: 22px;">
    <h2>Danh sach booking co the duyet luong</h2>
    <div class="payroll-table-wrap">
      <table class="payroll-table">
        <thead>
          <tr>
            <th>Booking</th>
            <th>Dich vu</th>
            <th>Worker</th>
            <th>Tien khach da tra</th>
            <th>Luong worker</th>
            <th>Trang thai</th>
            <th>Hanh dong</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($bookings)): ?>
            <tr>
              <td colspan="7" style="text-align:center;color:#6b7280;">Chua co booking nao du dieu kien chi luong.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($bookings as $item): ?>
              <?php
                $status = (string)($item['payout_status'] ?? 'pending_payout');
                $isPaid = $status === 'payout_paid';
              ?>
              <tr>
                <td>#<?= (int)$item['id'] ?><br><small><?= View::e($item['date']) ?> <?= View::e($item['time']) ?></small></td>
                <td><?= View::e($item['service_name']) ?></td>
                <td>#<?= (int)$item['worker_id'] ?> - <?= View::e($item['worker_name']) ?></td>
                <td><?= number_format((float)$item['service_price'], 0, ',', '.') ?>đ</td>
                <td><?= number_format((float)$item['worker_salary'], 0, ',', '.') ?>đ</td>
                <td>
                  <span class="status-chip <?= $isPaid ? 'done' : 'pending' ?>">
                    <?= View::e($statusLabels[$status] ?? $status) ?>
                  </span>
                </td>
                <td>
                  <form method="POST" action="/admin/worker-payroll/pay" class="salary-form">
                    <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
                    <input type="hidden" name="booking_id" value="<?= (int)$item['id'] ?>">
                    <input type="hidden" name="worker_id" value="<?= (int)$item['worker_id'] ?>">
                    <input
                      class="salary-input"
                      type="number"
                      name="worker_salary"
                      min="1000"
                      step="1000"
                      required
                      value="<?= (int)max(0, (float)$item['worker_salary']) ?>"
                      <?= $isPaid ? 'disabled' : '' ?>
                    >
                    <button class="home-btn" type="submit" <?= $isPaid ? 'disabled' : '' ?>>
                      <?= $isPaid ? 'Da chi xong' : 'Nhap luong va di PayOS' ?>
                    </button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </section>
</section>
