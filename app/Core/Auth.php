<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Tiện ích xác thực để quản lý phiên người dùng.
 * Cung cấp các hàm đăng nhập, đăng xuất và kiểm tra trạng thái xác thực.
 */
final class Auth
{
    /**
     * Lấy ID người dùng hiện tại từ session.
     *
     * @return int|null ID người dùng nếu đã đăng nhập, ngược lại là null
     */
    public static function id(): ?int
    {
        self::ensureSessionActive();
        return isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
    }

    /**
     * Lấy vai trò hiện tại của người dùng từ session.
     *
     * @return string|null Vai trò người dùng nếu đã đăng nhập, ngược lại là null
     */
    public static function role(): ?string
    {
        self::ensureSessionActive();
        return $_SESSION['role'] ?? null;
    }

    /**
     * Kiểm tra người dùng hiện tại đã xác thực hay chưa.
     *
     * @return bool True nếu đã đăng nhập, ngược lại là false
     */
    public static function isAuthenticated(): bool
    {
        return self::id() !== null;
    }

    /**
     * Kiểm tra người dùng hiện tại có đúng vai trò chỉ định hay không.
     *
     * @param string $role Vai trò cần kiểm tra
     * @return bool True nếu người dùng có vai trò đó, ngược lại là false
     */
    public static function hasRole(string $role): bool
    {
        return self::role() === $role;
    }

    /**
     * Đăng nhập người dùng bằng cách lưu ID và vai trò vào session.
     * Tạo lại session ID để tăng bảo mật.
     *
     * @param int $userId ID người dùng cần đăng nhập
     * @param string $role Vai trò của người dùng
     */
    public static function login(int $userId, string $role): void
    {
        self::ensureSessionActive();
        session_regenerate_id(true);
        $_SESSION['user_id'] = $userId;
        $_SESSION['role'] = $role;
    }

    /**
     * Đăng xuất người dùng hiện tại.
     * Xóa dữ liệu session và hủy phiên làm việc.
     */
    public static function logout(): void
    {
        self::ensureSessionActive();
        $_SESSION = [];
        self::destroySessionCookie();
        session_destroy();
    }

    /**
     * Đảm bảo session đang hoạt động.
     * Tự khởi tạo session mới nếu chưa có session hoạt động.
     */
    private static function ensureSessionActive(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    /**
     * Hủy cookie session để trình duyệt xóa phiên hiện tại.
     */
    private static function destroySessionCookie(): void
    {
        if (ini_get('session.use_cookies')) {
            $cookieParams = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $cookieParams['path'],
                $cookieParams['domain'],
                $cookieParams['secure'],
                $cookieParams['httponly']
            );
        }
    }
}
