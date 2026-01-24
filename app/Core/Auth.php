<?php
namespace App\Core;

final class Auth {
  public static function id(): ?int {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    return isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
  }

  public static function role(): ?string {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    return $_SESSION['role'] ?? null;
  }

  public static function login(int $userId, string $role): void {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    session_regenerate_id(true);
    $_SESSION['user_id'] = $userId;
    $_SESSION['role'] = $role;
  }

  public static function logout(): void {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
      $p = session_get_cookie_params();
      setcookie(session_name(), '', time() - 42000, $p["path"], $p["domain"], $p["secure"], $p["httponly"]);
    }
    session_destroy();
  }
}
?>