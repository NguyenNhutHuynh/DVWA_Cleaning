<?php
use App\Core\View;
/** @var array $bookings */
/** @var array $workers */
/** @var string $csrf */
?>

<style>
.admin-bookings {
  --primary: #2eaf7d;
  --primary-dark: #16805a;
  --primary-soft: #e8f7f0;
  --bg-soft: #f7fdf9;
  --text-dark: #1f2d3d;
  --text-muted: #546e7a;
  --border: #dcefe6;
  --white: #ffffff;
  --danger: #dc2626;
  --danger-soft: #fff1f1;
  --warning: #d97706;
  --warning-soft: #fff7ed;
  --blue: #2563eb;
  --blue-soft: #eff6ff;
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

.bookings-panel {
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 26px;
  box-shadow: var(--shadow-sm);
  overflow: hidden;
}

.bookings-panel-head {
  padding: 24px 26px;
  border-bottom: 1px solid var(--border);
  background:
    radial-gradient(circle at top left, rgba(46,175,125,0.10), transparent 34%),
    linear-gradient(135deg, #ffffff, #f7fdf9);
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 16px;
  flex-wrap: wrap;
}

.bookings-panel-title {
  margin: 0;
  color: var(--text-dark);
  font-size: clamp(22px, 3vw, 30px);
  font-weight: 900;
  letter-spacing: -0.03em;
}

.bookings-count {
  display: inline-flex;
  align-items: center;
  padding: 7px 14px;
  border-radius: 999px;
  background: var(--primary-soft);
  color: var(--primary-dark);
  font-weight: 900;
  font-size: 14px;
}

.table-wrap {
  width: 100%;
  overflow-x: auto;
}

.bookings-table {
  width: 100%;
  border-collapse: collapse;
  background: #ffffff;
}

.bookings-table thead {
  background: #fbfefd;
}

.bookings-table th {
  padding: 16px 18px;
  text-align: left;
  color: var(--text-dark);
  font-size: 13px;
  font-weight: 900;
  text-transform: uppercase;
  letter-spacing: 0.04em;
  border-bottom: 1px solid var(--border);
  white-space: nowrap;
}

.bookings-table td {
  padding: 18px;
  vertical-align: top;
  border-bottom: 1px solid var(--border);
  color: var(--text-muted);
  line-height: 1.55;
}

.bookings-table tbody tr {
  transition: background 0.2s ease;
}

.bookings-table tbody tr:hover {
  background: #fbfefd;
}

.bookings-table tbody tr:last-child td {
  border-bottom: none;
}

.booking-id {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 54px;
  padding: 8px 13px;
  border-radius: 999px;
  background: var(--primary-soft);
  color: var(--primary-dark);
  font-weight: 900;
}

.booking-main-text {
  display: grid;
  gap: 6px;
}

.booking-service {
  color: var(--text-dark);
  font-weight: 900;
}

.booking-location {
  color: var(--text-muted);
  font-size: 14px;
}

.booking-time {
  color: var(--text-dark);
  font-weight: 900;
  white-space: nowrap;
}

.booking-date {
  color: var(--text-muted);
  font-size: 14px;
  margin-top: 3px;
}

.status-chip,
.payment-chip,
.worker-chip {
  display: inline-flex;
  width: fit-content;
  align-items: center;
  justify-content: center;
  padding: 7px 13px;
  border-radius: 999px;
  font-size: 13px;
  font-weight: 900;
  white-space: nowrap;
}

.status-chip {
  background: var(--primary-soft);
  color: var(--primary-dark);
  text-transform: capitalize;
}

.payment-chip.paid {
  background: #d1fae5;
  color: #065f46;
}

.payment-chip.unpaid {
  background: #fee2e2;
  color: #991b1b;
}

.worker-chip {
  background: var(--blue-soft);
  color: var(--blue);
}

.worker-chip.empty {
  background: #f3f4f6;
  color: #6b7280;
}

.row-actions {
  display: grid;
  gap: 10px;
  min-width: 280px;
}

.top-actions {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;
}

.top-actions form {
  margin: 0;
}

.assign-form {
  margin: 0;
  display: grid;
  grid-template-columns: minmax(150px, 1fr) auto;
  gap: 10px;
}

.home-btn {
  min-height: 42px;
  padding: 10px 18px;
  border-radius: 999px;
  border: none;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  font-size: 14px;
  font-weight: 900;
  cursor: pointer;
  background: linear-gradient(135deg, var(--primary), var(--primary-dark));
  color: white;
  box-shadow: 0 10px 22px rgba(46,175,125,0.18);
  transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
  white-space: nowrap;
}

.home-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 14px 30px rgba(46,175,125,0.26);
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

.worker-select {
  width: 100%;
  min-height: 42px;
  padding: 10px 13px;
  border-radius: 999px;
  border: 1px solid var(--border);
  background: #fcfffd;
  color: var(--text-dark);
  font-size: 14px;
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

.note-danger,
.note-warning {
  margin: 0;
  padding: 10px 13px;
  border-radius: 14px;
  font-size: 13px;
  font-weight: 800;
  text-align: left;
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

.empty-bookings {
  margin: 0;
  padding: 34px 20px;
  text-align: center;
  border: 1px dashed var(--border);
  border-radius: 22px;
  color: var(--text-muted);
  background: var(--bg-soft);
  font-weight: 700;
}

@media (max-width: 980px) {
  .bookings-table,
  .bookings-table thead,
  .bookings-table tbody,
  .bookings-table th,
  .bookings-table td,
  .bookings-table tr {
    display: block;
  }

  .bookings-table thead {
    display: none;
  }

  .bookings-table tbody {
    display: grid;
    gap: 16px;
    padding: 18px;
  }

  .bookings-table tr {
    border: 1px solid var(--border);
    border-radius: 20px;
    background: #fff;
    overflow: hidden;
  }

  .bookings-table td {
    display: grid;
    grid-template-columns: 130px 1fr;
    gap: 12px;
    padding: 14px 16px;
    border-bottom: 1px solid var(--border);
  }

  .bookings-table td::before {
    content: attr(data-label);
    color: var(--text-dark);
    font-weight: 900;
    font-size: 13px;
  }

  .bookings-table td:last-child {
    border-bottom: none;
  }

  .row-actions {
    min-width: 0;
  }

  .assign-form {
    grid-template-columns: 1fr;
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

  .bookings-panel {
    border-radius: 20px;
  }

  .bookings-panel-head {
    padding: 22px;
  }

  .bookings-table td {
    grid-template-columns: 1fr;
  }

  .top-actions {
    grid-template-columns: 1fr;
  }

  .home-btn,
  .worker-select {
    width: 100%;
  }
}
</style>

<section class="home-container admin-bookings">
  <header class="home-hero bookings-hero">
    <p class="home-kicker">ADMIN • ĐƠN ĐẶT</p>
    <h1>Quản lý đơn đặt</h1>
    <p>Danh sách đơn theo dạng bảng, dễ quét thông tin và thao tác nhanh.</p>
  </header>

  <section class="bookings-section">
    <?php if (empty($bookings)): ?>
      <p class="empty-bookings">Chưa có đơn đặt nào.</p>
    <?php else: ?>
      <div class="bookings-panel">
        <div class="bookings-panel-head">
          <h2 class="bookings-panel-title">Danh sách đơn đặt</h2>
          <span class="bookings-count"><?= count($bookings) ?> đơn</span>
        </div>

        <div class="table-wrap">
          <table class="bookings-table">
            <thead>
              <tr>
                <th>Mã đơn</th>
                <th>Thời gian</th>
                <th>Dịch vụ & địa điểm</th>
                <th>Trạng thái</th>
                <th>Thanh toán</th>
                <th>Worker</th>
                <th>Thao tác</th>
              </tr>
            </thead>

            <tbody>
              <?php foreach ($bookings as $b): ?>
                <?php $isCustomerPaid = !empty($b['is_customer_paid']); ?>

                <tr>
                  <td data-label="Mã đơn">
                    <span class="booking-id">#<?= View::e($b['id']) ?></span>
                  </td>

                  <td data-label="Thời gian">
                    <div class="booking-time"><?= View::e($b['time']) ?></div>
                    <div class="booking-date"><?= View::e($b['date']) ?></div>
                  </td>

                  <td data-label="Dịch vụ & địa điểm">
                    <div class="booking-main-text">
                      <div class="booking-service"><?= View::e($b['service_name'] ?? '') ?></div>
                      <div class="booking-location"><?= View::e($b['location']) ?></div>
                    </div>
                  </td>

                  <td data-label="Trạng thái">
                    <span class="status-chip"><?= View::e($b['status']) ?></span>
                  </td>

                  <td data-label="Thanh toán">
                    <span class="payment-chip <?= $isCustomerPaid ? 'paid' : 'unpaid' ?>">
                      <?= $isCustomerPaid ? 'Đã thanh toán' : 'Chưa thanh toán' ?>
                    </span>
                  </td>

                  <td data-label="Worker">
                    <?php if (!empty($b['assigned_worker_id'])): ?>
                      <span class="worker-chip">Worker #<?= View::e($b['assigned_worker_id']) ?></span>
                    <?php else: ?>
                      <span class="worker-chip empty">Chưa gán</span>
                    <?php endif; ?>
                  </td>

                  <td data-label="Thao tác">
                    <div class="row-actions">
                      <div class="top-actions">
                        <a class="home-btn home-btn-outline" href="/admin/bookings/<?= (int)$b['id'] ?>">Chi tiết</a>

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
                          Gán
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
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php endif; ?>
  </section>
</section>