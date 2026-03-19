<?php
require __DIR__ . '/../app/Core/DB.php';
use App\Core\DB;

try {
    $pdo = DB::pdo();
    
    echo "<style>body { font-family: Arial; margin: 20px; }</style>";
    
    // Check table structure
    $result = $pdo->query("PRAGMA table_info(contacts)");
    $columns = $result->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2>Cấu trúc bảng contacts:</h2>";
    $columnNames = array_column($columns, 'name');
    echo "<pre>";
    print_r($columnNames);
    echo "</pre>";
    
    // Add columns if they don't exist
    $columnsToAdd = [
        'reply' => 'TEXT',
        'replied_at' => 'TIMESTAMP',
        'replied_by' => 'INTEGER',
    ];
    
    foreach ($columnsToAdd as $colName => $colType) {
        if (!in_array($colName, $columnNames)) {
            try {
                $pdo->exec("ALTER TABLE contacts ADD COLUMN $colName $colType");
                echo "<p style='color: green;'><strong>✅ Đã thêm cột '$colName'</strong></p>";
            } catch (Exception $e) {
                echo "<p style='color: orange;'><strong>⚠️ Lỗi thêm '$colName':</strong> " . $e->getMessage() . "</p>";
            }
        } else {
            echo "<p style='color: blue;'><strong>ℹ️ Cột '$colName' đã tồn tại</strong></p>";
        }
    }
    
    echo "<hr>";
    echo "<p style='color: green;'><strong>✅ Migration hoàn thành!</strong></p>";
    
} catch (Exception $e) {
    echo "<h3 style='color: red;'>❌ Lỗi:</h3>";
    echo "<pre style='color: red;'>" . htmlspecialchars($e->getMessage()) . "</pre>";
}
?>
