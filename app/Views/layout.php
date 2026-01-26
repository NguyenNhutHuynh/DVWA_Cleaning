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
    <?php require $viewFile; ?>
  </main>

  <footer class="site-footer" style="text-align:center;padding:20px 12px;color:#666;font-size:14px;">
    <div>Cleaning Service &copy; <?= date('Y') ?>. Giữ nhà sạch - sống khỏe.</div>
    <div style="margin-top:6px;">Liên hệ: 1900 123 456 · support@cleaning.local</div>
  </footer>
</body>
</html>
