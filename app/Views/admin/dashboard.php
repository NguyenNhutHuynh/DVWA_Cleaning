<?php
use App\Core\View;
/** @var int $uid ID người dùng */
/** @var string $role Vai trò hiện tại */
/** @var string $name Tên hiển thị */
/** @var array $stats Dữ liệu thống kê */
?>

<style>
.admin-dashboard {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px 60px;
}

/* Hero Section với Gradient */
.dashboard-hero {
  background: linear-gradient(135deg, #2eaf7d 0%, #43c59e 50%, #8cdf94 100%);
  border-radius: 20px;
  padding: 50px 40px;
  margin-bottom: 40px;
  color: white;
  text-align: center;
  box-shadow: 0 10px 40px rgba(46, 175, 125, 0.3);
  position: relative;
  overflow: hidden;
  animation: slideInDown 0.6s ease-out;
}

.dashboard-hero::before {
  content: '';
  position: absolute;
  top: -50%;
  right: -50%;
  width: 200%;
  height: 200%;
  background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
  animation: rotate 20s linear infinite;
}

@keyframes rotate {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

@keyframes slideInDown {
  from {
    opacity: 0;
    transform: translateY(-30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.dashboard-hero .home-kicker {
  position: relative;
  z-index: 1;
  color: #fff;
  animation: fadeIn 0.5s ease-out;
}

.dashboard-hero h1 {
  font-size: 2.5rem;
  margin: 0 0 10px 0;
  font-weight: 700;
  position: relative;
  z-index: 1;
  text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  animation: fadeInDown 0.6s ease-out 0.1s both;
}

.dashboard-hero p {
  font-size: 1.1rem;
  margin: 0;
  opacity: 0.95;
  position: relative;
  z-index: 1;
  animation: fadeInUp 0.6s ease-out 0.2s both;
}

/* Stats Grid */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 16px;
  margin-bottom: 40px;
  animation: fadeInUp 0.8s ease-out 0.2s both;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@keyframes fadeInDown {
  from {
    opacity: 0;
    transform: translateY(-15px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.stat-card {
  background: white;
  border-radius: 16px;
  padding: 25px 20px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
  border: 2px solid transparent;
}

.stat-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, #2eaf7d, #43c59e);
  transform: scaleX(0);
  transform-origin: left;
  transition: transform 0.3s ease;
}

.stat-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 40px rgba(46, 175, 125, 0.2);
  border-color: #43c59e;
}

.stat-card:hover::before {
  transform: scaleX(1);
}

.stat-icon {
  font-size: 2.2rem;
  margin-bottom: 12px;
  display: inline-block;
  animation: bounce 2s infinite;
}

@keyframes bounce {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-10px); }
}

.stat-card:hover .stat-icon {
  animation: none;
  transform: scale(1.15);
  transition: transform 0.3s ease;
}

.stat-label {
  font-size: 0.85rem;
  color: #666;
  margin: 0 0 8px 0;
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.stat-value {
  font-size: 1.9rem;
  font-weight: 700;
  margin: 0;
  background: linear-gradient(135deg, #2eaf7d, #43c59e);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.stat-subtitle {
  font-size: 0.8rem;
  color: #999;
  margin: 6px 0 0 0;
}

/* Quick Actions Section */
.section-title {
  font-size: 1.8rem;
  margin: 0 0 25px 0;
  color: #1f2d3d;
  font-weight: 700;
  text-align: center;
  animation: fadeInUp 1s ease-out 0.4s both;
}

.actions-grid {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 16px;
  animation: fadeInUp 1.2s ease-out 0.6s both;
}

.action-card {
  background: linear-gradient(135deg, #f7fdf9 0%, #f0fff4 100%);
  border-radius: 16px;
  padding: 25px 20px;
  text-decoration: none;
  color: inherit;
  display: block;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  border: 2px solid #e0f2e9;
  position: relative;
  overflow: hidden;
}

.action-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(135deg, rgba(46, 175, 125, 0.05), rgba(67, 197, 158, 0.05));
  transform: translateY(100%);
  transition: transform 0.3s ease;
}

.action-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 40px rgba(46, 175, 125, 0.2);
  border-color: #43c59e;
}

.action-card:hover::before {
  transform: translateY(0);
}

.action-card h3 {
  font-size: 1.15rem;
  margin: 0 0 10px 0;
  color: #2eaf7d;
  font-weight: 700;
  position: relative;
  z-index: 1;
}

.action-card p {
  margin: 0;
  color: #666;
  line-height: 1.5;
  font-size: 0.9rem;
  position: relative;
  z-index: 1;
}

/* Responsive */
@media (max-width: 1200px) {
  .stats-grid {
    grid-template-columns: repeat(3, 1fr);
  }
  
  .actions-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}

@media (max-width: 900px) {
  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .actions-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 768px) {
  .dashboard-hero {
    padding: 35px 25px;
  }
  
  .dashboard-hero h1 {
    font-size: 2rem;
  }
  
  .stats-grid {
    grid-template-columns: 1fr;
  }
  
  .actions-grid {
    grid-template-columns: 1fr;
  }
}
</style>

<div class="admin-dashboard">
  <!-- Hero Section -->
  <div class="dashboard-hero">
    <h1>👋 Xin chào, <?= View::e($name) ?>!</h1>
    <p>Chào mừng đến với bảng điều khiển quản trị viên</p>
  </div>

  <!-- Stats Overview -->
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

  <!-- Quick Actions -->
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