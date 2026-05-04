<?php
/**
 * Setup admin user for testing
 * Run: php public/create_admin.php
 */
declare(strict_types=1);

// Load .env và config
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with($line, '#') || !str_contains($line, '=')) continue;
        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        if (!empty($key) && empty($_ENV[$key])) {
            putenv("{$key}={$value}");
        }
    }
}

spl_autoload_register(function($class){
  $prefix = 'App\\';
  if (str_starts_with($class, $prefix)) {
    $path = __DIR__ . '/../app/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
    if (file_exists($path)) require $path;
  }
});

use App\Core\DB;
use App\Models\User;

try {
    $email = 'admin@cleaning.local';
    $password = 'admin123';
    
    // Check if user exists
    $existing = User::findByEmail($email);
    if ($existing) {
        echo "✅ Admin user already exists: " . htmlspecialchars($email) . "\n";
        exit(0);
    }
    
    // Create admin user
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $userId = User::create(
        'Admin User',
        $email,
        $passwordHash,
        User::ROLE_ADMIN,
        User::STATUS_ACTIVE,
        '0987654321',
        'Admin Office'
    );
    
    echo "✅ Admin user created!\n";
    echo "Email: " . htmlspecialchars($email) . "\n";
    echo "Password: " . htmlspecialchars($password) . "\n";
    echo "User ID: " . $userId . "\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . htmlspecialchars($e->getMessage()) . "\n";
    exit(1);
}
?>
