<?php
use App\Core\Auth;
use App\Core\View;
?>
<nav class="dashboard" aria-label="Điều hướng chính">
  <div class="dashboard-logo">
    <?php
      $homeLink = '/';
      if (Auth::id()) {
        $role = Auth::role();
        if ($role === 'admin') $homeLink = '/admin/dashboard';
        elseif ($role === 'worker') $homeLink = '/worker/dashboard';
        else $homeLink = '/customer/dashboard';
      }
    ?>
    <a href="<?= View::e($homeLink) ?>" style="display:flex;align-items:center;text-decoration:none;">
      <img src="/assets/img/logo_nobg.png" alt="Logo" style="height: 100%; margin-right:10px;">
    </a>
  </div>
  <div class="functions">
    <?php if (Auth::id()): ?>
      <?php $role = Auth::role(); ?>
      <?php if ($role === 'admin'): ?>
        <!-- <a href="/admin/dashboard" class="function-btn">Admin</a> -->
        <a href="/admin/services" class="function-btn">Dịch vụ</a>
        <a href="/admin/bookings" class="function-btn">Đơn đặt</a>
        <a href="/admin/moderation" class="function-btn">Kiểm duyệt</a>
        <a href="/admin/users" class="function-btn">Người dùng</a>
        <a href="/admin/stats" class="function-btn">Thống kê</a>
        <a href="/logout" class="function-btn">Đăng xuất</a>
      <?php elseif ($role === 'worker'): ?>
        <!-- <a href="/worker/dashboard" class="function-btn">Worker</a> -->
        <a href="/worker/jobs" class="function-btn">Nhận việc</a>
        <a href="/worker/progress" class="function-btn">Tiến độ</a>
        <a href="/worker/schedule" class="function-btn">Lịch làm</a>
        <a href="/logout" class="function-btn">Đăng xuất</a>
      <?php else: ?>
        <a href="/customer/dashboard" class="function-btn">Khách hàng</a>
        <a href="/book" class="function-btn">Đặt lịch</a>
        <a href="/bookings" class="function-btn">Lịch đã đặt</a>
        <a href="/services" class="function-btn">Dịch vụ</a>
        <a href="/logout" class="function-btn">Đăng xuất</a>
      <?php endif; ?>
    <?php else: ?>
      <a href="/services" class="function-btn">Dịch vụ</a>
      <a href="/pricing" class="function-btn">Bảng giá</a>
      <a href="/contact" class="function-btn">Liên hệ</a>
      <a href="/login" class="function-btn">Đăng nhập</a>
      <a href="/register" class="function-btn">Đăng ký</a>
    <?php endif; ?>
  </div>
</nav>
