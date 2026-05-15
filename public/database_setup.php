<?php
/**
 * Database Verification & Health Check Script
 * Kiểm tra tình trạng database, đảm bảo data consistency
 */

require __DIR__ . '/../app/Core/DB.php';

use App\Core\DB;

try {
    $pdo = DB::pdo();
    
    echo "<style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .section { margin: 20px 0; padding: 15px; border-left: 4px solid #2eaf7d; background: #f9f9f9; }
        .success { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .warning { color: #ff9800; font-weight: bold; }
        .info { color: #2196f3; }
        hr { margin: 20px 0; border: none; border-top: 1px solid #ddd; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        table th, table td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        table th { background: #f5f5f5; font-weight: bold; }
    </style>";
    echo "<div class='container'>";
    echo "<h1>🔍 Database Health Check</h1>";
    
    // ====================================================================
    // SECTION 1: Table Existence Check
    // ====================================================================
    echo "<div class='section'>";
    echo "<h2>✅ Step 1: Kiểm tra bảng dữ liệu</h2>";
    
    $tables = ['bookings', 'booking_details', 'payment_transactions', 'users', 'services'];
    $tableStatus = [];
    
    foreach ($tables as $table) {
        $check = $pdo->query(
            "SELECT COUNT(*) FROM information_schema.TABLES 
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '$table'"
        )->fetch();
        $exists = (int)($check['COUNT(*)'] ?? 0) > 0;
        $tableStatus[$table] = $exists;
        
        echo "<p>";
        echo $exists ? "✅ " : "❌ ";
        echo "<strong>Bảng $table:</strong> ";
        echo $exists ? "Tồn tại" : "KHÔNG TỒN TẠI";
        echo "</p>";
    }
    
    echo "</div>";
    
    // ====================================================================
    // SECTION 2: Data Integrity Check
    // ====================================================================
    echo "<div class='section'>";
    echo "<h2>📊 Step 2: Kiểm tra tính toàn vẹn dữ liệu</h2>";
    
    // Count records
    $stats = [
        'bookings' => $pdo->query("SELECT COUNT(*) as cnt FROM bookings")->fetch()['cnt'],
        'booking_details' => $pdo->query("SELECT COUNT(*) as cnt FROM booking_details")->fetch()['cnt'],
        'payment_transactions' => $pdo->query("SELECT COUNT(*) as cnt FROM payment_transactions")->fetch()['cnt'],
        'users' => $pdo->query("SELECT COUNT(*) as cnt FROM users")->fetch()['cnt'],
        'services' => $pdo->query("SELECT COUNT(*) as cnt FROM services")->fetch()['cnt'],
    ];
    
    echo "<table>";
    echo "<tr><th>Bảng</th><th>Số lượng records</th><th>Trạng thái</th></tr>";
    foreach ($stats as $table => $count) {
        $status = $count > 0 ? "✅ OK" : "⚠️ Trống";
        echo "<tr><td>$table</td><td>$count</td><td>$status</td></tr>";
    }
    echo "</table>";
    
    echo "</div>";
    
    // ====================================================================
    // SECTION 3: Schema Compatibility Check
    // ====================================================================
    echo "<div class='section'>";
    echo "<h2>🔧 Step 3: Kiểm tra schema compatibility</h2>";
    
    $bookingColumns = $pdo->query("DESCRIBE bookings")->fetchAll();
    $bookingColumnNames = array_column($bookingColumns, 'Field');
    
    $detailColumns = $pdo->query("DESCRIBE booking_details")->fetchAll();
    $detailColumnNames = array_column($detailColumns, 'Field');
    
    echo "<p><strong>Bảng bookings có cột:</strong><br>";
    echo implode(", ", $bookingColumnNames) . "</p>";
    
    echo "<p><strong>Bảng booking_details có cột:</strong><br>";
    echo implode(", ", $detailColumnNames) . "</p>";
    
    // Check if bookings has work-related columns (old schema)
    $oldSchemaColumns = ['date', 'time', 'service_id', 'status'];
    $hasOldSchema = false;
    foreach ($oldSchemaColumns as $col) {
        if (in_array($col, $bookingColumnNames)) {
            echo "<p class='warning'>⚠️ Phát hiện cột cũ '$col' trong bảng bookings</p>";
            $hasOldSchema = true;
        }
    }
    
    if (!$hasOldSchema) {
        echo "<p class='success'>✅ Schema bookings sạch (chỉ metadata)</p>";
    }
    
    echo "</div>";
    
    // ====================================================================
    // SECTION 4: Relationship Integrity
    // ====================================================================
    echo "<div class='section'>";
    echo "<h2>🔗 Step 4: Kiểm tra liên kết dữ liệu</h2>";
    
    // Check orphaned bookings
    $orphaned = $pdo->query(
        "SELECT COUNT(*) as count FROM bookings b 
         WHERE NOT EXISTS (SELECT 1 FROM booking_details bd WHERE bd.booking_id = b.id)"
    )->fetch();
    $orphanedCount = (int)($orphaned['count'] ?? 0);
    
    if ($orphanedCount > 0) {
        echo "<p class='error'>❌ Phát hiện $orphanedCount booking(s) không có chi tiết!</p>";
        echo "<p>Danh sách booking orphan:</p>";
        $orphanList = $pdo->query(
            "SELECT b.id, b.user_id, b.created_at FROM bookings b 
             WHERE NOT EXISTS (SELECT 1 FROM booking_details bd WHERE bd.booking_id = b.id)"
        )->fetchAll();
        echo "<table>";
        echo "<tr><th>ID</th><th>User ID</th><th>Ngày tạo</th></tr>";
        foreach ($orphanList as $o) {
            echo "<tr><td>" . $o['id'] . "</td><td>" . $o['user_id'] . "</td><td>" . $o['created_at'] . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='success'>✅ Tất cả bookings đều có booking_details tương ứng</p>";
    }
    
    // Check payment transactions
    $paymentStats = $pdo->query(
        "SELECT 
            COUNT(*) as total,
            COUNT(CASE WHEN booking_id IS NULL OR booking_id = 0 THEN 1 END) as null_booking,
            COUNT(CASE WHEN status = 'paid' THEN 1 END) as paid_count
         FROM payment_transactions"
    )->fetch();
    
    echo "<p><strong>Payment Transactions:</strong></p>";
    echo "<table>";
    echo "<tr><th>Tổng</th><th>Có booking_id</th><th>NULL/0 booking_id</th><th>Đã thanh toán</th></tr>";
    echo "<tr>";
    echo "<td>" . $paymentStats['total'] . "</td>";
    echo "<td>" . ($paymentStats['total'] - $paymentStats['null_booking']) . "</td>";
    echo "<td class='warning'>" . $paymentStats['null_booking'] . "</td>";
    echo "<td class='success'>" . $paymentStats['paid_count'] . "</td>";
    echo "</tr>";
    echo "</table>";
    
    if ((int)$paymentStats['null_booking'] > 0) {
        echo "<p class='warning'>⚠️ Cảnh báo: Có " . $paymentStats['null_booking'] . " payment_transactions với booking_id = NULL/0</p>";
    } else {
        echo "<p class='success'>✅ Tất cả payment_transactions có booking_id hợp lệ</p>";
    }
    
    echo "</div>";
    
    // ====================================================================
    // SECTION 5: Query Test
    // ====================================================================
    echo "<div class='section'>";
    echo "<h2>🧪 Step 5: Test queries</h2>";
    
    // Test getAll with LEFT JOIN
    $testQuery = $pdo->query(
        "SELECT COUNT(*) as count FROM bookings b
         LEFT JOIN booking_details bd ON bd.booking_id = b.id
         LEFT JOIN users u ON u.id = b.user_id
         LEFT JOIN services s ON s.id = bd.service_id"
    )->fetch();
    
    echo "<p><strong>Test query LEFT JOIN:</strong> " . ($testQuery['count'] > 0 ? "✅ OK (" . $testQuery['count'] . " rows)" : "❌ Fail") . "</p>";
    
    // Test sample booking detail
    $sampleBooking = $pdo->query(
        "SELECT b.id, bd.work_date, bd.work_time, s.name, bd.detail_status, 
                COUNT(pt.id) as payment_count,
                SUM(CASE WHEN pt.status = 'paid' THEN 1 ELSE 0 END) as paid_count
         FROM bookings b
         LEFT JOIN booking_details bd ON bd.booking_id = b.id
         LEFT JOIN services s ON s.id = bd.service_id
         LEFT JOIN payment_transactions pt ON pt.booking_id = b.id
         GROUP BY b.id, bd.work_date, bd.work_time, s.name, bd.detail_status
         LIMIT 1"
    )->fetch();
    
    if ($sampleBooking) {
        echo "<p><strong>Sample booking test:</strong> ✅ OK</p>";
        echo "<table>";
        foreach ($sampleBooking as $key => $value) {
            echo "<tr><td>$key</td><td>" . ($value ?? 'NULL') . "</td></tr>";
        }
        echo "</table>";
    }
    
    echo "</div>";
    
    // ====================================================================
    // SUMMARY
    // ====================================================================
    echo "<div class='section'>";
    echo "<h2>📋 Kết luận</h2>";
    
    $allTablesExist = !in_array(false, array_values($tableStatus));
    $noOrphaned = $orphanedCount === 0;
    $paymentOK = (int)$paymentStats['null_booking'] === 0;
    
    if ($allTablesExist && $noOrphaned && $paymentOK) {
        echo "<p class='success' style='font-size: 18px;'>✅ DATABASE HỢP LỆ - SẴN SÀNG SỬ DỤNG</p>";
        echo "<p>Tất cả bảng tồn tại, dữ liệu toàn vẹn, không có lỗi liên kết.</p>";
    } else {
        echo "<p class='warning' style='font-size: 18px;'>⚠️ CÓ VẤN ĐỀ CẦN KIỂM TRA</p>";
        if (!$allTablesExist) echo "<p>- Thiếu một số bảng</p>";
        if (!$noOrphaned) echo "<p>- Có booking không có chi tiết</p>";
        if (!$paymentOK) echo "<p>- Có payment transaction với booking_id NULL</p>";
    }
    
    echo "</div>";
    
    echo "<hr>";
    echo "<p><a href='/'>← Trang chủ</a> | <a href='/manager/bookings'>← Quản lý đơn đặt</a></p>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div class='container'>";
    echo "<h1>❌ Lỗi Database</h1>";
    echo "<p class='error'>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre style='background: #f5f5f5; padding: 10px; border-radius: 4px; overflow-x: auto;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    echo "</div>";
}
?>
