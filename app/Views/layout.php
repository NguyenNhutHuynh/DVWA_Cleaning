<?php
use App\Core\Auth;
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cleaning Service</title>
  <link rel="stylesheet" href="/assets/css/app.css">
  <!-- Leaflet Map Library CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" integrity="sha512-h9FcoyWjrSciIFUuxfbMg8TABgkG91B6YGYRtDgGV7ZUnqN1nJikQjalLm65OEftzL6KY3E5IavtrHHMvg/ypA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Fallback: jsDelivr CDN CSS if cdnjs fails -->
  <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.min.css" /> -->
</head>
<body>
  <header class="site-header">
    <?php require __DIR__ . '/dashboard.php'; ?>
  </header>

  <main class="site-main">
    <?php if (session_status() !== PHP_SESSION_ACTIVE) session_start(); ?>
    <?php if (!empty($_SESSION['success'])): ?>
      <div style="max-width:1080px;margin:0 auto 12px auto;padding:10px 14px;border:1px solid #c8e6c9;background:#e8f5e9;color:#256029;border-radius:8px;">
        <?= App\Core\View::e($_SESSION['success']) ?>
      </div>
      <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['error'])): ?>
      <div style="max-width:1080px;margin:0 auto 12px auto;padding:10px 14px;border:1px solid #ffcdd2;background:#ffebee;color:#b71c1c;border-radius:8px;">
        <?= App\Core\View::e($_SESSION['error']) ?>
      </div>
      <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['error_alert'])): ?>
      <script>
        alert(<?= json_encode((string)$_SESSION['error_alert'], JSON_UNESCAPED_UNICODE) ?>);
      </script>
      <?php unset($_SESSION['error_alert']); ?>
    <?php endif; ?>
    <?php require $viewFile; ?>
  </main>

  <footer class="site-footer" style="background: #f7fdf9; border-top: 1px solid #e0f2e9;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 50px 20px;">
      <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 40px; margin-bottom: 40px;">
        <!-- Company Info -->
        <div>
          <h4 style="color: #1f2d3d; margin: 0 0 12px 0; font-size: 15px; font-weight: 700;">Về công ty</h4>
          <p style="color: #546e7a; font-size: 13px; margin: 0 0 12px 0; line-height: 1.6;">Dịch vụ dọn dẹp chuyên nghiệp, đặt lịch nhanh, theo dõi tiện lợi.</p>
          <p style="color: #546e7a; font-size: 13px; margin: 0;">Cleaning Service &copy; <?= date('Y') ?></p>
        </div>
        
        <!-- Quick Links -->
        <div>
          <h4 style="color: #1f2d3d; margin: 0 0 12px 0; font-size: 15px; font-weight: 700;">Liên kết nhanh</h4>
          <ul style="list-style: none; padding: 0; margin: 0;">
            <li style="margin: 0 0 8px 0;"><a href="/" style="color: #546e7a; text-decoration: none; font-size: 13px;">Trang chủ</a></li>
            <li style="margin: 0 0 8px 0;"><a href="/services" style="color: #546e7a; text-decoration: none; font-size: 13px;">Danh sách dịch vụ</a></li>
            <li style="margin: 0 0 8px 0;"><a href="/pricing" style="color: #546e7a; text-decoration: none; font-size: 13px;">Bảng giá</a></li>
            <li style="margin: 0 0 0 0;"><a href="/contact" style="color: #546e7a; text-decoration: none; font-size: 13px;">Liên hệ chúng tôi</a></li>
          </ul>
        </div>
        
        <!-- Why Us -->
        <div>
          <h4 style="color: #1f2d3d; margin: 0 0 12px 0; font-size: 15px; font-weight: 700;">Ưu điểm</h4>
          <ul style="list-style: none; padding: 0; margin: 0;">
            <li style="margin: 0 0 8px 0; font-size: 13px;"><span style="color: #43c59e;">✓</span> Nhân viên được kiểm duyệt</li>
            <li style="margin: 0 0 8px 0; font-size: 13px;"><span style="color: #43c59e;">✓</span> Hóa chất an toàn</li>
            <li style="margin: 0 0 8px 0; font-size: 13px;"><span style="color: #43c59e;">✓</span> Giá minh bạch</li>
            <li style="margin: 0 0 0 0; font-size: 13px;"><span style="color: #43c59e;">✓</span> Hỗ trợ 24/7</li>
          </ul>
        </div>
        
        <!-- Contact -->
        <div>
          <h4 style="color: #1f2d3d; margin: 0 0 12px 0; font-size: 15px; font-weight: 700;">Liên hệ</h4>
          <p style="color: #546e7a; font-size: 13px; margin: 0 0 8px 0;">
            <strong>📞</strong> 1900 123 456
          </p>
          <p style="color: #546e7a; font-size: 13px; margin: 0 0 8px 0;">
            <strong>📧</strong> support@cleaning.local
          </p>
          <p style="color: #546e7a; font-size: 13px; margin: 0;">
            <strong>📍</strong> 12 Nguyễn Văn Bảo<br>
            Gò Vấp, TP.HCM
          </p>
        </div>
      </div>
      
      <!-- Bottom Footer -->
      <div style="border-top: 1px solid #e0f2e9; padding-top: 20px; text-align: center;">
        <p style="color: #546e7a; font-size: 12px; margin: 0;">
          Giữ nhà sạch • Sống khỏe • Tất cả quyền được bảo lưu
        </p>
      </div>
    </div>
  </footer>

  <!-- Leaflet Map Library JS - Primary source -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js" integrity="sha512-WXoSL2lrKOSIDlsNfbIQxhcO1jNC38wHqN++VM5ccZgEmcRs6AFWGhCW+NvxF2wxQLeB3co7D6K6YfLAxjUTeA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  
  <!-- Leaflet Fallback - jsDelivr -->
  <script>
    if (typeof L === 'undefined') {
      console.log('[Leaflet] Primary CDN failed, loading fallback...');
      var s = document.createElement('script');
      s.src = 'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.min.js';
      s.onload = function() {
        console.log('[Leaflet] Fallback CDN loaded');
        window.leafletReady = true;
        document.dispatchEvent(new Event('leafletLoaded'));
      };
      s.onerror = function() {
        console.error('[Leaflet] All CDN sources failed');
      };
      document.head.appendChild(s);
    } else {
      window.leafletReady = true;
      console.log('[Leaflet] Primary CDN loaded');
    }
  </script>
  
  <!-- Map Handler Script -->
  <script src="/assets/js/map-handler.js" defer></script>
</body>
</html>
