<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\View;
use App\Models\User;

/**
 * AuthController xử lý xác thực người dùng (đăng nhập, đăng ký, đăng xuất).
 */
final class AuthController
{
    // Hằng số kiểm tra độ mạnh mật khẩu
    private const MIN_PASSWORD_LENGTH = 6;

    // Các vai trò người dùng được phép
    private const ALLOWED_ROLES = ['customer', 'worker'];

    // Mã trạng thái HTTP
    private const STATUS_CSRF_TOKEN_MISMATCH = 419;

    /**
     * Hiển thị biểu mẫu đăng ký người dùng.
     */
    public static function showRegister(): void
    {
        View::render('auth/register', [
            'csrf' => Csrf::token(),
            'error' => null,
        ]);
    }

    /**
     * Xử lý dữ liệu gửi lên từ biểu mẫu đăng ký.
     * Tạo tài khoản mới với trạng thái duyệt phù hợp theo vai trò.
     */
    public static function register(): void
    {
        // Xác thực token CSRF
        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            http_response_code(self::STATUS_CSRF_TOKEN_MISMATCH);
            echo 'Security token mismatch. Please try again.';
            exit(1);
        }

        // Lấy và chuẩn hóa dữ liệu biểu mẫu
        $name = trim((string)($_POST['name'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');
        $role = trim((string)($_POST['role'] ?? User::ROLE_CUSTOMER));

        // Kiểm tra dữ liệu đầu vào
        $validationError = self::validateRegistrationInput($name, $email, $password, $role);
        if ($validationError !== null) {
            View::render('auth/register', [
                'csrf' => Csrf::token(),
                'error' => $validationError,
            ]);
            return;
        }

        // Tạo tài khoản người dùng
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $approvalStatus = $role === User::ROLE_WORKER ? User::STATUS_PENDING : User::STATUS_ACTIVE;
        $userId = User::create($name, $email, $passwordHash, $role, $approvalStatus);

        // Gán thông báo thành công và chuyển hướng
        self::setSessionMessage(
            'success',
            $role === User::ROLE_WORKER
                ? 'Đăng ký thành công! Tài khoản worker đang chờ duyệt. Vui lòng đăng nhập sau khi được duyệt.'
                : 'Đăng ký thành công! Vui lòng đăng nhập để tiếp tục.'
        );
        self::redirect('/');
    }

    /**
     * Hiển thị biểu mẫu đăng nhập.
     */
    public static function showLogin(): void
    {
        View::render('auth/login', [
            'csrf' => Csrf::token(),
            'error' => null,
        ]);
    }

    /**
     * Xử lý dữ liệu gửi lên từ biểu mẫu đăng nhập.
     * Xác thực người dùng và chuyển hướng tới trang điều khiển phù hợp.
     */
    public static function login(): void
    {
        // Xác thực token CSRF
        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            http_response_code(self::STATUS_CSRF_TOKEN_MISMATCH);
            echo 'Security token mismatch. Please try again.';
            exit(1);
        }

        // Lấy dữ liệu biểu mẫu
        $email = trim((string)($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        // Tìm người dùng theo email
        $user = User::findByEmail($email);
        if ($user === null) {
            View::render('auth/login', [
                'csrf' => Csrf::token(),
                'error' => 'Không tìm thấy địa chỉ email.',
            ]);
            return;
        }

        // Kiểm tra mật khẩu
        if (!password_verify($password, $user['password_hash'])) {
            View::render('auth/login', [
                'csrf' => Csrf::token(),
                'error' => 'Password is incorrect.',
            ]);
            return;
        }

        // Kiểm tra trạng thái tài khoản
        $accountStatus = (string)($user['approval_status'] ?? User::STATUS_ACTIVE);
        $statusError = self::validateAccountStatus($accountStatus, $user);
        if ($statusError !== null) {
            View::render('auth/login', [
                'csrf' => Csrf::token(),
                'error' => $statusError,
            ]);
            return;
        }

        // Đăng nhập người dùng
        $userRole = self::normalizeUserRole((string)$user['role']);
        Auth::login((int)$user['id'], $userRole);

        // Chuyển hướng tới trang điều khiển phù hợp
        self::redirectToUserDashboard($userRole, $accountStatus);
    }

    /**
     * Xử lý đăng xuất.
     * Xóa session và chuyển hướng về trang chủ.
     */
    public static function logout(): void
    {
        Auth::logout();
        self::redirect('/');
    }

    /**
     * Kiểm tra dữ liệu đầu vào của biểu mẫu đăng ký.
     *
     * @return string|null Thông báo lỗi nếu kiểm tra thất bại, ngược lại là null
     */
    private static function validateRegistrationInput(
        string $name,
        string $email,
        string $password,
        string $role
    ): ?string {
        // Kiểm tra các trường bắt buộc
        if (empty($name) || empty($email) || empty($password)) {
            return 'Please fill in all required fields.';
        }

        // Kiểm tra định dạng email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'Please provide a valid email address.';
        }

        // Kiểm tra độ dài mật khẩu
        if (strlen($password) < self::MIN_PASSWORD_LENGTH) {
            return 'Password must be at least ' . self::MIN_PASSWORD_LENGTH . ' characters.';
        }

        // Kiểm tra vai trò
        if (!in_array($role, self::ALLOWED_ROLES, true)) {
            return 'Invalid user role.';
        }

        // Kiểm tra email đã tồn tại hay chưa
        if (User::findByEmail($email) !== null) {
            return 'Email address already exists.';
        }

        return null;
    }

    /**
     * Kiểm tra trạng thái tài khoản khi đăng nhập.
     * Xác định tài khoản có bị khóa hoặc đã bị xóa hay không.
     *
     * @param string $status Trạng thái tài khoản
     * @param array $user Dữ liệu người dùng
     * @return string|null Thông báo lỗi nếu tài khoản không thể đăng nhập, ngược lại là null
     */
    private static function validateAccountStatus(string $status, array $user): ?string
    {
        if ($status === User::STATUS_LOCKED) {
            $reason = (string)($user['reject_reason'] ?? 'Account has been locked. Please contact support.');
            return $reason;
        }

        if ($status === User::STATUS_DELETED) {
            $reason = (string)($user['reject_reason'] ?? 'Account has been deleted or deactivated.');
            return $reason;
        }

        return null;
    }

    /**
     * Chuẩn hóa vai trò người dùng (đổi vai trò cũ 'cleaner' thành 'worker').
     */
    private static function normalizeUserRole(string $role): string
    {
        return $role === 'cleaner' ? User::ROLE_WORKER : $role;
    }

    /**
     * Chuyển hướng người dùng tới dashboard phù hợp theo vai trò và trạng thái.
     */
    private static function redirectToUserDashboard(string $role, string $status): void
    {
        $path = match ($role) {
            User::ROLE_ADMIN => '/admin/dashboard',
            User::ROLE_WORKER => $status !== User::STATUS_ACTIVE ? '/worker/pending' : '/worker/dashboard',
            User::ROLE_CUSTOMER => '/customer/dashboard',
            default => '/',
        };

        self::redirect($path);
    }

    /**
     * Gán thông báo flash vào session.
     */
    private static function setSessionMessage(string $type, string $message): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $_SESSION[$type] = $message;
    }

    /**
     * Thực hiện chuyển hướng tới đường dẫn chỉ định.
     */
    private static function redirect(string $path): void
    {
        header("Location: $path");
        exit(0);
    }
}
