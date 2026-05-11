<?php
use App\Core\View;
/** @var int $uid ID người dùng */
/** @var string $role Vai trò hiện tại */
/** @var string $name Tên hiển thị */
/** @var array $stats Dữ liệu thống kê */
?>

<style>
.manager-dashboard {
  --primary: #2eaf7d;
  --primary-dark: #16805a;
  --primary-soft: #e8f7f0;
  --bg-soft: #f7fdf9;
  --text-dark: #1f2d3d;
  --text-muted: #546e7a;
  --border: #dcefe6;
  --white: #ffffff;
  --shadow-sm: 0 8px 24px rgba(31,45,61,0.08);
  --shadow-md: 0 16px 40px rgba(31,45,61,0.12);

  max-width: 1180px;
  margin: 0 auto;
  padding: 24px 16px 60px;
  color: var(--text-dark);
}

.manager-dashboard * {
  box-sizing: border-box;
}

/* HERO */
.manager-dashboard .dashboard-hero {
  position: relative;
  overflow: hidden;
  padding: 56px 28px;
  border-radius: 28px;
  text-align: center;
  background:
    radial-gradient(circle at top left, rgba(46,175,125,0.20), transparent 34%),
    linear-gradient(135deg, #f7fdf9 0%, #ffffff 48%, #e8f7f0 100%);
  border: 1px solid var(--border);
  box-shadow: var(--shadow-sm);
}

.manager-dashboard .dashboard-hero::after {
  content: "";
  position: absolute;
  right: -80px;
  bottom: -80px;
  width: 220px;
  height: 220px;
  border-radius: 50%;
  background: rgba(46,175,125,0.13);
}

.manager-dashboard .dashboard-kicker {
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

.manager-dashboard .dashboard-hero h1 {
  position: relative;
  margin: 0 0 12px;
  color: var(--text-dark);
  font-size: clamp(32px, 5vw, 52px);
  line-height: 1.1;
  font-weight: 900;
  letter-spacing: -0.04em;
  text-shadow: none;
}

.manager-dashboard .dashboard-hero p {
  position: relative;
  margin: 0;
  color: var(--text-muted);
  font-size: 17px;
  line-height: 1.6;
}

/* SECTION TITLE */
.manager-dashboard .section-title {
  margin: 50px 0 24px;
  color: var(--text-dark);
  font-size: clamp(24px, 3vw, 34px);
  font-weight: 900;
  letter-spacing: -0.03em;
  text-align: center;
}

/* STATS */
.manager-dashboard .stats-grid {
  display: grid;
  grid-template-columns: repeat(4, minmax(0, 1fr));
  gap: 18px;
  margin-top: 40px;
}

.manager-dashboard .stat-card {
  position: relative;
  overflow: hidden;
  min-height: 190px;
  padding: 26px 22px;
  border-radius: 24px;
  background: var(--white);
  border: 1px solid var(--border);
  box-shadow: var(--shadow-sm);
  transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
}

.manager-dashboard .stat-card::after {
  content: "";
  position: absolute;
  right: -42px;
  bottom: -42px;
  width: 118px;
  height: 118px;
  border-radius: 50%;
  background: rgba(46,175,125,0.09);
}

.manager-dashboard .stat-card:hover {
  transform: translateY(-5px);
  border-color: rgba(46,175,125,0.45);
  box-shadow: var(--shadow-md);
}

.manager-dashboard .stat-icon {
  position: relative;
  z-index: 1;
  width: 54px;
  height: 54px;
  margin-bottom: 16px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 18px;
  background: var(--primary-soft);
  font-size: 28px;
}

.manager-dashboard .stat-label {
  position: relative;
  z-index: 1;
  margin: 0 0 8px;
  color: var(--text-muted);
  font-size: 13px;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.manager-dashboard .stat-value {
  position: relative;
  z-index: 1;
  margin: 0;
  color: var(--primary);
  font-size: clamp(28px, 3vw, 38px);
  line-height: 1.1;
  font-weight: 900;
  letter-spacing: -0.04em;
}

.manager-dashboard .stat-subtitle {
  position: relative;
  z-index: 1;
  margin: 10px 0 0;
  color: var(--text-muted);
  font-size: 13px;
  line-height: 1.5;
}

/* ACTIONS */
.manager-dashboard .actions-grid {
  display: grid;
  grid-template-columns: repeat(4, minmax(0, 1fr));
  gap: 18px;
}

.manager-dashboard .action-card {
  position: relative;
  overflow: hidden;
  min-height: 180px;
  padding: 26px 22px;
  border-radius: 24px;
  background: linear-gradient(135deg, #ffffff 0%, #f7fdf9 100%);
  border: 1px solid var(--border);
  box-shadow: var(--shadow-sm);
  text-decoration: none;
  color: inherit;
  display: block;
  transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
}

.manager-dashboard .action-card:hover {
  transform: translateY(-5px);
  border-color: var(--primary);
  box-shadow: var(--shadow-md);
}

.manager-dashboard .action-icon {
  position: relative;
  z-index: 1;
  width: 54px;
  height: 54px;
  margin-bottom: 14px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 14px;
  background: var(--primary-soft);
  font-size: 24px;
}

.manager-dashboard .action-title {
  position: relative;
  z-index: 1;
  margin: 0;
  color: var(--text-dark);
  font-size: 16px;
  font-weight: 700;
  line-height: 1.4;
}
</style>

<div class="manager-dashboard">
  <!-- HERO SECTION -->
  <div class="dashboard-hero">
    <div class="dashboard-kicker">BẢNG ĐIỀU KHIỂN</div>
    <h1>Chào mừng, <?= View::e($name) ?>!</h1>
    <p>Quản lý đơn đặt, phân công công việc và duyệt hồ sơ nhân viên</p>
  </div>

  <!-- STATS SECTION -->
  <div class="section-title">Tổng Quan Hoạt Động</div>
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon">📊</div>
      <div class="stat-label">Tổng Đơn Đặt</div>
      <div class="stat-value"><?= $stats['totalBookings'] ?? 0 ?></div>
      <div class="stat-subtitle">Tất cả đơn đặt</div>
    </div>

    <div class="stat-card">
      <div class="stat-icon">⏳</div>
      <div class="stat-label">Đơn Chờ Xử Lý</div>
      <div class="stat-value"><?= $stats['pendingBookings'] ?? 0 ?></div>
      <div class="stat-subtitle">Cần phân công</div>
    </div>

    <div class="stat-card">
      <div class="stat-icon">✓</div>
      <div class="stat-label">Đơn Đã Xác Nhận</div>
      <div class="stat-value"><?= $stats['confirmedBookings'] ?? 0 ?></div>
      <div class="stat-subtitle">Sẵn sàng thực hiện</div>
    </div>

    <div class="stat-card">
      <div class="stat-icon">🔄</div>
      <div class="stat-label">Đang Thực Hiện</div>
      <div class="stat-value"><?= $stats['inProgressBookings'] ?? 0 ?></div>
      <div class="stat-subtitle">Đơn đang làm</div>
    </div>
  </div>

  <!-- MORE STATS -->
  <div class="stats-grid" style="margin-top: 18px;">
    <div class="stat-card">
      <div class="stat-icon">✅</div>
      <div class="stat-label">Đã Hoàn Thành</div>
      <div class="stat-value"><?= $stats['completedBookings'] ?? 0 ?></div>
      <div class="stat-subtitle">Đơn hoàn tất</div>
    </div>

    <div class="stat-card">
      <div class="stat-icon">👷</div>
      <div class="stat-label">Nhân Viên Hoạt Động</div>
      <div class="stat-value"><?= $stats['activeWorkers'] ?? 0 ?></div>
      <div class="stat-subtitle">Worker đang làm</div>
    </div>

    <div class="stat-card">
      <div class="stat-icon">👥</div>
      <div class="stat-label">Khách Hàng Hoạt Động</div>
      <div class="stat-value"><?= $stats['activeCustomers'] ?? 0 ?></div>
      <div class="stat-subtitle">Customer đang sử dụng</div>
    </div>

    <div class="stat-card">
      <div class="stat-icon">📈</div>
      <div class="stat-label">Tỷ Lệ Hoàn Thành</div>
      <div class="stat-value">
        <?php
          $completed = $stats['completedBookings'] ?? 0;
          $total = $stats['totalBookings'] ?? 0;
          echo $total > 0 ? round(($completed / $total) * 100) : 0;
        ?>%
      </div>
      <div class="stat-subtitle">So với tổng đơn</div>
    </div>
  </div>

  <!-- QUICK ACTIONS -->
  <div class="section-title">Hành Động Nhanh</div>
  <div class="actions-grid">
    <a href="/manager/bookings" class="action-card">
      <div class="action-icon">📋</div>
      <div class="action-title">Quản Lý Đơn Đặt</div>
    </a>

    <a href="/manager/workers" class="action-card">
      <div class="action-icon">👷</div>
      <div class="action-title">Quản Lý Worker</div>
    </a>

    <a href="/manager/customers" class="action-card">
      <div class="action-icon">👥</div>
      <div class="action-title">Quản Lý Customer</div>
    </a>

    <a href="/manager/dashboard" class="action-card">
      <div class="action-icon">🔄</div>
      <div class="action-title">Làm Mới Dữ Liệu</div>
    </a>
  </div>
</div>
