<?php
require __DIR__ . '/../app/Core/DB.php';
use App\Core\DB;

$updates = [
    'Combo Cơ bản' => '/assets/img/combo-co-ban.png',
    'Combo Tổng vệ sinh' => '/assets/img/combo-tong-ve-sinh.png',
    'Combo Gia đình' => '/assets/img/combo-gia-dinh.png',
    'Combo Chuyển Nhà' => '/assets/img/combo-chuyen-nha.png',
    'Vệ sinh nhà vệ sinh' => '/assets/img/nha-ve-sinh.png',
    'Vệ sinh phòng khách' => '/assets/img/phong-khach.png',
    'Vệ sinh phòng ngủ' => '/assets/img/phong-ngu.png',
    'Vệ sinh nhà bếp' => '/assets/img/nha-bep.png',
    'Giặt nệm & sofa' => '/assets/img/sofa.png',
    'Khử khuẩn' => '/assets/img/khu-khuan.png',
    'Vệ sinh kính' => '/assets/img/kinh.png',
    'Tổng vệ sinh nhà' => '/assets/img/tong-ve-sinh-nha.png',
];

try {
    $pdo = DB::pdo();
    $count = 0;
    
    echo "<style>body { font-family: Arial; margin: 20px; }</style>";
    
    foreach ($updates as $name => $path) {
        $stmt = $pdo->prepare("UPDATE services SET image_path = :path WHERE name = :name");
        $stmt->execute(['path' => $path, 'name' => $name]);
        if ($stmt->rowCount() > 0) {
            $count++;
            echo "✅ " . htmlspecialchars($name) . " → " . htmlspecialchars($path) . "<br>";
        }
    }
    
    echo "<hr><h3>✅ Updated $count services</h3>";
    echo "<p><a href='/services'>Go to services page →</a></p>";
    
} catch (Exception $e) {
    echo "<h3>❌ Error:</h3>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
}
?>
