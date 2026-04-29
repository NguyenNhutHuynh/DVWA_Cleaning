<?php
use App\Core\View;
/** @var array $bookings */
/** @var array $workers */
/** @var string $csrf */

$bookingStatusMap = [
  'pending' => 'Chờ thanh toán',
  'paid' => 'Đã thanh toán',
  'confirmed' => 'Đã xác nhận',
  'accepted' => 'Đang thực hiện',
  'in_progress' => 'Đang thực hiện',
  'completed' => 'Đã hoàn thành',
  'cancelled' => 'Đã hủy',
];
?>

<style>
.admin-bookings {
  --primary: #2eaf7d;
  --primary-dark: #16805a;
  --primary-soft: #e8f7f0;
  --bg-soft: #f7fdf9;
  --bg-card: #ffffff;
  --text-dark: #1f2d3d;
  --text-muted: #5f7482;
  --border: #dcefe6;
  --border-strong: #bfe4d3;
  --danger: #dc2626;
  --danger-soft: #fff1f1;
  --warning: #d97706;
  --warning-soft: #fff7ed;
  --success-soft: #dff6ea;
  --success-text: #0f7a55;
  --shadow-sm: 0 8px 24px rgba(31,45,61,0.08);
  --shadow-md: 0 16px 40px rgba(31,45,61,0.12);

  max-width: 1240px;
  margin: 0 auto;
  padding: 24px 16px 60px;
  color: var(--text-dark);
}

.admin-bookings * {
  box-sizing: border-box;
}

.admin-bookings .bookings-hero {
  position: relative;
  overflow: hidden;
  padding: 52px 28px;
  border-radius: 28px;
  text-align: center;
  background:
    radial-gradient(circle at top left, rgba(46,175,125,0.18), transparent 34%),
    linear-gradient(135deg, #f7fdf9 0%, #ffffff 48%, #e8f7f0 100%);
  border: 1px solid var(--border);
  box-shadow: var(--shadow-sm);
}

.admin-bookings .bookings-hero::after {
  content: "";
  position: absolute;
  right: -70px;
  bottom: -70px;
  width: 200px;
  height: 200px;
  border-radius: 50%;
  background: rgba(46,175,125,0.10);
}

.admin-bookings .home-kicker {
  position: relative;
  display: inline-flex;
  margin: 0 0 14px;
  padding: 7px 14px;
  border-radius: 999px;
  background: var(--primary-soft);
  color: var(--primary-dark);
  font-size: 13px;
  font-weight: 900;
  letter-spacing: 0.08em;
}

.admin-bookings .bookings-hero h1 {
  position: relative;
  margin: 0 0 12px;
  color: var(--text-dark);
  font-size: clamp(32px, 5vw, 50px);
  line-height: 1.1;
  font-weight: 900;
  letter-spacing: -0.04em;
}

.admin-bookings .bookings-hero p {
  position: relative;
  margin: 0;
  color: var(--text-muted);
  font-size: 17px;
  line-height: 1.6;
}

.bookings-section {
  margin-top: 34px;
}

.bookings-list {
  display: grid;
  gap: 20px;
}

.empty-bookings {
  margin: 0;
  padding: 34px 20px;
  text-align: center;
  border: 1px dashed var(--border-strong);
  border-radius: 22px;
  color: var(--text-muted);
  background: var(--bg-soft);
  font-weight: 700;
}

.booking-card {
  background: var(--bg-card);
  border: 1px solid var(--border-strong);
  border-radius: 26px;
  padding: 24px;
  box-shadow: var(--shadow-sm);
  transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
}

.booking-card:hover {
  transform: translateY(-4px);
  box-shadow: var(--shadow-md);
  border-color: #9dd8bf;
}

.booking-card-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  margin-bottom: 18px;
  flex-wrap: wrap;
}

.booking-code {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 62px;
  padding: 9px 16px;
  border-radius: 999px;
  background: var(--primary-soft);
  color: var(--primary-dark);
  font-size: 18px;
  font-weight: 900;
}

.status-chip {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 9px 16px;
  border-radius: 999px;
  background: #e5f7ef;
  color: var(--primary-dark);
  font-size: 14px;
  font-weight: 900;
  text-transform: capitalize;
}

.booking-body {
  display: grid;
  grid-template-columns: 1.45fr 0.9fr 1.1fr;
  gap: 18px;
  align-items: stretch;
}

.booking-panel {
  height: 100%;
  padding: 18px;
  border-radius: 20px;
  background: #fbfefd;
  border: 1px solid var(--border);
}

.booking-panel-title {
  margin: 0 0 14px;
  color: var(--text-dark);
  font-size: 14px;
  font-weight: 900;
  letter-spacing: 0.02em;
}

.booking-info-grid {
  display: grid;
  gap: 12px;
}

.booking-info-item {
  display: grid;
  gap: 4px;
}

.booking-info-label {
  color: var(--text-dark);
  font-size: 13px;
  font-weight: 900;
}

.booking-info-value {
  color: var(--text-muted);
  font-size: 15px;
  line-height: 1.55;
  word-break: break-word;
}

.booking-meta-list {
  display: grid;
  gap: 12px;
}

.meta-pill {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 44px;
  padding: 10px 14px;
  border-radius: 14px;
  text-align: center;
  font-size: 14px;
  font-weight: 900;
}

.worker-chip {
  background: var(--primary-soft);
  color: var(--primary-dark);
}

.payment-chip.paid {
  background: var(--success-soft);
  color: var(--success-text);
}

.payment-chip.unpaid {
  background: #ffe7e7;
  color: #a61b1b;
}

.payment-inline {
  display: flex;
  align-items: center;
  gap: 10px;
  flex-wrap: wrap;
}

.payment-detail-link {
  display: inline-flex;
  align-items: center;
  min-height: 36px;
  padding: 6px 12px;
  border-radius: 999px;
  border: 1px solid var(--border);
  background: white;
  color: var(--primary);
  font-size: 12px;
  font-weight: 900;
  text-decoration: none;
  transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
}

.payment-detail-link:hover {
  background: var(--primary-soft);
  box-shadow: var(--shadow-sm);
  transform: translateY(-1px);
}

.booking-actions-panel {
  display: grid;
  gap: 12px;
}

.action-inline {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;
}

.action-inline form,
.assign-form {
  margin: 0;
}

.assign-form {
  display: grid;
  gap: 10px;
}

.worker-select {
  width: 100%;
  min-height: 46px;
  padding: 11px 14px;
  border-radius: 14px;
  border: 1px solid var(--border);
  background: #fff;
  color: var(--text-dark);
  font-size: 15px;
  font-weight: 800;
  font-family: inherit;
}

.worker-select:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 4px rgba(46,175,125,0.12);
}

.worker-select:disabled {
  opacity: 0.65;
  cursor: not-allowed;
  background: #f5f7f6;
}

.home-btn {
  width: 100%;
  min-height: 46px;
  padding: 11px 18px;
  border-radius: 14px;
  border: none;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  font-size: 15px;
  font-weight: 900;
  cursor: pointer;
  background: linear-gradient(135deg, var(--primary), var(--primary-dark));
  color: white;
  box-shadow: 0 10px 22px rgba(46,175,125,0.22);
  transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
}

.home-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 14px 30px rgba(46,175,125,0.28);
}

.home-btn:disabled {
  opacity: 0.55;
  cursor: not-allowed;
  transform: none;
  box-shadow: none;
}

.home-btn-outline {
  background: #fff;
  color: var(--primary);
  border: 1.5px solid var(--primary);
  box-shadow: none;
}

.home-btn-outline:hover {
  background: var(--primary-soft);
  box-shadow: var(--shadow-sm);
}

.note-danger,
.note-warning {
  margin: 0;
  padding: 13px 16px;
  border-radius: 16px;
  font-size: 14px;
  font-weight: 800;
  text-align: center;
}

.note-danger {
  background: var(--danger-soft);
  color: #991b1b;
  border: 1px solid #fecaca;
}

.note-warning {
  background: var(--warning-soft);
  color: var(--warning);
  border: 1px solid #fed7aa;
}

@media (max-width: 1100px) {
  .booking-body {
    grid-template-columns: 1fr 1fr;
  }

  .booking-actions-wrap {
    grid-column: 1 / -1;
  }
}

@media (max-width: 768px) {
  .admin-bookings {
    padding: 16px 12px 44px;
  }

  .admin-bookings .bookings-hero {
    padding: 42px 18px;
    border-radius: 22px;
  }

  .booking-card {
    padding: 18px;
    border-radius: 20px;
  }

  .booking-body {
    grid-template-columns: 1fr;
  }

  .action-inline {
    grid-template-columns: 1fr;
  }
}
</style>

<section class="home-container admin-bookings">
  <header class="home-hero bookings-hero">
    <p class="home-kicker">ADMIN • ĐƠN ĐẶT</p>
    <h1>Quản lý đơn đặt</h1>
    <p>Theo dõi trạng thái, kiểm tra thanh toán và phân công worker một cách trực quan, dễ nhìn hơn.</p>
  </header>

  <section class="bookings-section">
    <div class="bookings-list">
      <?php if (empty($bookings)): ?>
        <p class="empty-bookings">Chưa có đơn đặt nào.</p>
      <?php else: ?>
        <?php foreach ($bookings as $b): ?>
          <?php
            $status = (string)($b['status'] ?? '');
            $statusLabel = $bookingStatusMap[$status] ?? 'Không rõ';
            $isCustomerPaid = !empty($b['is_customer_paid']);
          ?>

          <article class="booking-card">
            <div class="booking-card-head">
              <span class="booking-code">#<?= View::e($b['id']) ?></span>
              <span class="status-chip"><?= View::e($statusLabel) ?></span>
            </div>

            <div class="booking-body">
              <div class="booking-panel">
                <h3 class="booking-panel-title">Thông tin đơn</h3>

                <div class="booking-info-grid">
                  <div class="booking-info-item">
                    <span class="booking-info-label">Thời gian</span>
                    <div class="booking-info-value">
                      <?= View::e($b['date']) ?> <?= View::e($b['time']) ?>
                    </div>
                  </div>

                  <div class="booking-info-item">
                    <span class="booking-info-label">Địa điểm</span>
                    <div class="booking-info-value">
                      <?= View::e($b['location']) ?>
                    </div>
                  </div>

                  <div class="booking-info-item">
                    <span class="booking-info-label">Dịch vụ</span>
                    <div class="booking-info-value">
                      <?= View::e($b['service_name'] ?? '') ?>
                    </div>
                  </div>
                </div>
              </div>

              <div class="booking-panel">
                <h3 class="booking-panel-title">Trạng thái xử lý</h3>

                <div class="booking-meta-list">
                  <?php if (!empty($b['assigned_worker_id'])): ?>
                    <div class="meta-pill worker-chip">
                      Worker #<?= View::e($b['assigned_worker_id']) ?>
                    </div>
                  <?php else: ?>
                    <div class="meta-pill worker-chip">
                      Chưa gán worker
                    </div>
                  <?php endif; ?>

                  <div class="payment-inline">
                    <div class="meta-pill payment-chip <?= $isCustomerPaid ? 'paid' : 'unpaid' ?>">
                      <?= $isCustomerPaid ? 'Đã thanh toán' : 'Chưa thanh toán' ?>
                    </div>
                    <?php if ($isCustomerPaid && !empty($b['customer_paid_transaction_id'])): ?>
                      <a class="payment-detail-link" href="/admin/payment-transactions/<?= (int)$b['customer_paid_transaction_id'] ?>">Xem chi tiết giao dịch</a>
                    <?php endif; ?>
                  </div>
                </div>
              </div>

              <div class="booking-panel booking-actions-wrap">
                <h3 class="booking-panel-title">Thao tác</h3>

                <div class="booking-actions-panel">
                  <div class="action-inline">
                    <a class="home-btn home-btn-outline" href="/admin/bookings/<?= (int)$b['id'] ?>">
                      Chi tiết
                    </a>

                    <form method="POST" action="/admin/bookings/cancel">
                      <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
                      <input type="hidden" name="id" value="<?= (int)$b['id'] ?>">
                      <button class="home-btn home-btn-outline" type="submit">Hủy</button>
                    </form>
                  </div>

                  <form method="POST" action="/admin/bookings/assign" class="assign-form">
                    <input type="hidden" name="_csrf" value="<?= View::e($csrf) ?>">
                    <input type="hidden" name="id" value="<?= (int)$b['id'] ?>">

                    <select name="worker_id" class="worker-select" <?= $isCustomerPaid ? '' : 'disabled' ?>>
                      <option value="">-- Chọn worker --</option>
                      <?php foreach ($workers as $w): ?>
                        <option value="<?= $w['id'] ?>" <?= $b['assigned_worker_id'] == $w['id'] ? 'selected' : '' ?>>
                          <?= View::e($w['name']) ?>
                        </option>
                      <?php endforeach; ?>
                    </select>

                    <button class="home-btn" type="submit" <?= $isCustomerPaid ? '' : 'disabled' ?>>
                      Gán worker
                    </button>
                  </form>

                  <?php if (empty($b['assigned_worker_id'])): ?>
                    <?php if (!$isCustomerPaid): ?>
                      <p class="note-danger">Chưa thanh toán → chưa thể gán worker.</p>
                    <?php else: ?>
                      <p class="note-warning">Đã thanh toán → có thể gán worker.</p>
                    <?php endif; ?>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>
</section>