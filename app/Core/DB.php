<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

/**
 * Singleton kết nối cơ sở dữ liệu.
 * Cung cấp một thể hiện PDO duy nhất cho mọi thao tác DB.
 */
final class DB
{
    private static ?PDO $connection = null;

    /**
     * Lấy thể hiện kết nối PDO.
     * Tạo kết nối mới ở lần gọi đầu tiên, các lần sau dùng kết nối đã lưu.
     */
    public static function pdo(): PDO
    {
        if (self::$connection !== null) {
            return self::$connection;
        }

        self::$connection = self::createConnection();
        return self::$connection;
    }

    /**
     * Tạo kết nối PDO mới tới cơ sở dữ liệu.
     *
     * @throws PDOException
     * @return PDO
     */
    private static function createConnection(): PDO
    {
        $config = require __DIR__ . '/../../config/app.php';
        $databaseConfig = $config['db'];

        $dsn = self::buildDsn($databaseConfig);

        try {
            return new PDO(
                $dsn,
                $databaseConfig['user'],
                $databaseConfig['pass'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $exception) {
            http_response_code(500);
            echo 'Database connection failed.';
            exit(1);
        }
    }

    /**
     * Tạo chuỗi DSN cho kết nối cơ sở dữ liệu.
     */
    private static function buildDsn(array $config): string
    {
        return sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $config['host'],
            (int)$config['port'],
            $config['name'],
            $config['charset']
        );
    }
}
