<?php
use App\Core\View;
/** @var int $uid ID người dùng */
/** @var string $role Vai trò hiện tại */
/** @var string $name Tên hiển thị */
/** @var array $stats Dữ liệu thống kê */
?>

<style>
.admin-dashboard {
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

.admin-dashboard * {
  box-sizing: border-box;
}

/* HERO */
.admin-dashboard .dashboard-hero {
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

.admin-dashboard .dashboard-hero::after {
  content: "";
  position: absolute;
  right: -80px;
  bottom: -80px;
  width: 220px;
  height: 220px;
  border-radius: 50%;
  background: rgba(46,175,125,0.13);
}

.admin-dashboard .dashboard-kicker {
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

.admin-dashboard .dashboard-hero h1 {
  position: relative;
  margin: 0 0 12px;
  color: var(--text-dark);
  font-size: clamp(32px, 5vw, 52px);
  line-height: 1.1;
  font-weight: 900;
  letter-spacing: -0.04em;
  text-shadow: none;
}

.admin-dashboard .dashboard-hero p {
  position: relative;
  margin: 0;
  color: var(--text-muted);
  font-size: 17px;
  line-height: 1.6;
}

/* SECTION TITLE */
.admin-dashboard .section-title {
  margin: 50px 0 24px;
  color: var(--text-dark);
  font-size: clamp(24px, 3vw, 34px);
  font-weight: 900;
  letter-spacing: -0.03em;
  text-align: center;
}

/* STATS */
.admin-dashboard .stats-grid {
  display: grid;
  grid-template-columns: repeat(5, minmax(0, 1fr));
  gap: 18px;
  margin-top: 40px;
}

.admin-dashboard .stat-card {
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

.admin-dashboard .stat-card::after {
  content: "";
  position: absolute;
  right: -42px;
  bottom: -42px;
  width: 118px;
  height: 118px;
  border-radius: 50%;
  background: rgba(46,175,125,0.09);
}

.admin-dashboard .stat-card:hover {
  transform: translateY(-5px);
  border-color: rgba(46,175,125,0.45);
  box-shadow: var(--shadow-md);
}

.admin-dashboard .stat-icon {
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

.admin-dashboard .stat-label {
  position: relative;
  z-index: 1;
  margin: 0 0 8px;
  color: var(--text-muted);
  font-size: 13px;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.admin-dashboard .stat-value {
  position: relative;
  z-index: 1;
  margin: 0;
  color: var(--primary);
  font-size: clamp(28px, 3vw, 38px);
  line-height: 1.1;
  font-weight: 900;
  letter-spacing: -0.04em;
}

.admin-dashboard .stat-subtitle {
  position: relative;
  z-index: 1;
  margin: 10px 0 0;
  color: var(--text-muted);
  font-size: 13px;
  line-height: 1.5;
}

/* ACTIONS */
.admin-dashboard .actions-grid {
  display: grid;
  grid-template-columns: repeat(5, minmax(0, 1fr));
  gap: 18px;
}

.admin-dashboard .action-card {
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

.admin-dashboard .action-card::before {
  content: "";
  position: absolute;
  inset: auto -48px -48px auto;
  width: 128px;
  height: 128px;
  border-radius: 50%;
  background: rgba(46,175,125,0.10);
  transition: transform 0.25s ease;
}

.admin-dashboard .action-card:hover {
  transform: translateY(-5px);
  border-color: rgba(46,175,125,0.45);
  box-shadow: var(--shadow-md);
}

.admin-dashboard .action-card:hover::before {
  transform: scale(1.18);
}

.admin-dashboard .action-card h3 {
  position: relative;
  z-index: 1;
  margin: 0 0 12px;
  color: var(--text-dark);
  font-size: 20px;
  font-weight: 900;
  letter-spacing: -0.02em;
  line-height: 1.35;
}

.admin-dashboard .action-card p {
  position: relative;
  z-index: 1;
  margin: 0;
  color: var(--text-muted);
  line-height: 1.6;
  font-size: 14px;
}

/* RESPONSIVE */
@media (max-width: 1200px) {
  .admin-dashboard .stats-grid,
  .admin-dashboard .actions-grid {
    grid-template-columns: repeat(3, minmax(0, 1fr));
  }
}

@media (max-width: 900px) {
  .admin-dashboard .stats-grid,
  .admin-dashboard .actions-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}

@media (max-width: 768px) {
  .admin-dashboard {
    padding: 16px 12px 44px;
  }

  .admin-dashboard .dashboard-hero {
    padding: 42px 18px;
    border-radius: 22px;
  }

  .admin-dashboard .stats-grid,
  .admin-dashboard .actions-grid {
    grid-template-columns: 1fr;
  }

  .admin-dashboard .stat-card,
  .admin-dashboard .action-card {
    border-radius: 20px;
  }
}
</style>

<div class="admin-dashboard">
  <div class="dashboard-hero">
    <p class="dashboard-kicker">ADMIN DASHBOARD</p>
    <h1>👋 Xin chào, <?= View::e($name) ?>!</h1>
    <p>Chào mừng đến với bảng điều khiển quản trị viên</p>
  </div>

  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon">👥</div>
      <p class="stat-label">Tổng người dùng</p>
      <h2 class="stat-value"><?= number_format($stats['totalUsers']) ?></h2>
      <p class="stat-subtitle">Đã đăng ký</p>
    </div>

    <div class="stat-card">
      <div class="stat-icon">🗓️</div>
      <p class="stat-label">Tổng đơn đặt</p>
      <h2 class="stat-value"><?= number_format($stats['totalBookings']) ?></h2>
      <p class="stat-subtitle"><?= $stats['pendingBookings'] ?> đang chờ xử lý</p>
    </div>

    <div class="stat-card">
      <div class="stat-icon">💰</div>
      <p class="stat-label">Doanh thu</p>
      <h2 class="stat-value"><?= number_format($stats['totalRevenue']) ?></h2>
      <p class="stat-subtitle">VNĐ từ <?= $stats['completedBookings'] ?> đơn hoàn thành</p>
    </div>

    <div class="stat-card">
      <div class="stat-icon">🧹</div>
      <p class="stat-label">Dịch vụ</p>
      <h2 class="stat-value"><?= number_format($stats['totalServices']) ?></h2>
      <p class="stat-subtitle">Đang hoạt động</p>
    </div>

    <div class="stat-card">
      <div class="stat-icon">📩</div>
      <p class="stat-label">Liên hệ mới</p>
      <h2 class="stat-value"><?= number_format($stats['unreadContacts']) ?></h2>
      <p class="stat-subtitle">Cần kiểm duyệt</p>
    </div>
  </div>

  <h2 class="section-title">⚡ Chức năng quản trị</h2>

  <div class="actions-grid">
    <a href="/admin/users" class="action-card">
      <h3>👥 Quản lý người dùng</h3>
      <p>Xem, khóa, mở khóa và duyệt tài khoản người dùng</p>
    </a>

    <a href="/admin/bookings" class="action-card">
      <h3>🗓️ Quản lý lịch đặt</h3>
      <p>Xác nhận, hủy và cập nhật trạng thái đơn đặt</p>
    </a>

    <a href="/admin/services" class="action-card">
      <h3>🧹 Quản lý dịch vụ</h3>
      <p>Thêm, sửa, xóa dịch vụ và cập nhật giá cả</p>
    </a>

    <a href="/admin/moderation" class="action-card">
      <h3>✅ Kiểm duyệt nội dung</h3>
      <p>Xem xét tin nhắn liên hệ và đánh giá từ khách hàng</p>
    </a>

    <a href="/admin/stats" class="action-card">
      <h3>📈 Báo cáo thống kê</h3>
      <p>Phân tích doanh thu, hiệu suất và xu hướng hệ thống</p>
    </a>
  </div>
</div>