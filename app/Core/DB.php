<?php
namespace App\Core;

use PDO;
use PDOException;

final class DB {
  private static ?PDO $pdo = null;

  public static function pdo(): PDO {
    if (self::$pdo) return self::$pdo;

    $config = require __DIR__ . '/../../config/app.php';
    $db = $config['db'];

    $dsn = sprintf(
      "mysql:host=%s;port=%d;dbname=%s;charset=%s",
      $db['host'],
      (int)$db['port'],
      $db['name'],
      $db['charset']
    );

    try {
      self::$pdo = new PDO($dsn, $db['user'], $db['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
      ]);
    } catch (PDOException $e) {
      http_response_code(500);
      echo "DB connection failed.";
      exit;
    }

    return self::$pdo;
  }
}
?>