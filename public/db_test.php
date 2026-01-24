<?php
require __DIR__ . '/../app/Core/DB.php';

use App\Core\DB;

spl_autoload_register(function($class){
  $prefix = 'App\\';
  if (str_starts_with($class, $prefix)) {
    $path = __DIR__ . '/../app/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
    if (file_exists($path)) require $path;
  }
});

$pdo = DB::pdo();
echo "DB OK. Server version: " . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION);
