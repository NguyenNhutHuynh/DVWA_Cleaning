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
    <?php require $viewFile; ?>
  </main>

  <footer class="site-footer" style="text-align:center;padding:20px 12px;color:#666;font-size:14px;">
    <div>Cleaning Service &copy; <?= date('Y') ?>. Giữ nhà sạch - sống khỏe.</div>
    <div style="margin-top:6px;">Liên hệ: 1900 123 456 · support@cleaning.local</div>
  </footer>
</body>
</html>
