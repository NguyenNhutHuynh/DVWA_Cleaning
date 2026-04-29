<?php
require __DIR__ . '/../app/Core/DB.php';
use App\Core\DB;

try {
    $pdo = DB::pdo();
    
    echo "<style>body { font-family: 'Oswald', sans-serif; margin: 20px; }</style>";
    
    // Kiểm tra xem các cột đã tồn tại hay chưa (MySQL)
    $result = $pdo->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='contacts' AND TABLE_SCHEMA=DATABASE()");
    $columns = $result->fetchAll(PDO::FETCH_ASSOC);
    $existingColumns = [];
    
    foreach ($columns as $column) {
        $existingColumns[$column['COLUMN_NAME']] = true;
    }
    
    // Các cột cần thiết
    $requiredColumns = [
        'processed_by' => 'INT',
        'processed_at' => 'TIMESTAMP NULL DEFAULT NULL',
        'reply' => 'LONGTEXT',
        'replied_by' => 'INT',
        'replied_at' => 'TIMESTAMP NULL DEFAULT NULL',
        'status' => 'VARCHAR(20)',
    ];
    
    foreach ($requiredColumns as $columnName => $columnType) {
        if (!isset($existingColumns[$columnName])) {
            $pdo->exec("ALTER TABLE contacts ADD COLUMN $columnName $columnType");
            echo "<h3>✅ Đã thêm cột $columnName vào bảng contacts</h3>";
        } else {
            echo "<h3>✅ Cột $columnName đã tồn tại</h3>";
        }
    }
    
    echo "<hr>";
    echo "<p style='color: green;'><strong>✅ Migration hoàn thành!</strong></p>";
    echo "<p><a href='/contact'>Quay lại trang liên hệ →</a></p>";
    
} catch (Exception $e) {
    echo "<h3>❌ Lỗi:</h3>";
    echo "<pre style='color: red;'>" . htmlspecialchars($e->getMessage()) . "</pre>";
}
?>
