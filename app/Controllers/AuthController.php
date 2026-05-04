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
    private const ALLOWED_CITIES = ['TP.HCM', 'Thành phố Hồ Chí Minh', 'Ho Chi Minh City'];

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
        // CSRF token already verified by Router::dispatch() for all POST requests
        // No need to verify again here

        // Lấy và chuẩn hóa dữ liệu biểu mẫu
        $name = trim((string)($_POST['name'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');
        $role = trim((string)($_POST['role'] ?? User::ROLE_CUSTOMER));
        $phone = trim((string)($_POST['phone'] ?? ''));
        $city = trim((string)($_POST['city'] ?? ''));
        $district = trim((string)($_POST['district'] ?? ''));
        $ward = trim((string)($_POST['ward'] ?? ''));
        $addressDetail = trim((string)($_POST['address_detail'] ?? ''));

        // Kiểm tra dữ liệu đầu vào
        $validationError = self::validateRegistrationInput(
            $name,
            $email,
            $password,
            $role,
            $phone,
            $city,
            $district,
            $ward,
            $addressDetail
        );
        if ($validationError !== null) {
            View::render('auth/register', [
                'csrf' => Csrf::token(),
                'error' => $validationError,
            ]);
            return;
        }

        $fullAddress = $addressDetail . ', ' . $ward . ', ' . $district . ', ' . $city;

        // Tạo tài khoản người dùng
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $approvalStatus = $role === User::ROLE_WORKER ? User::STATUS_PENDING : User::STATUS_ACTIVE;
        $userId = User::create($name, $email, $passwordHash, $role, $approvalStatus, $phone, $fullAddress);

        // Gán thông báo thành công và chuyển hướng
        self::setSessionMessage(
            'success',
            $role === User::ROLE_WORKER
                ? 'Đăng ký thành công! Tài khoản worker đang chờ duyệt. Vui lòng đăng nhập sau khi được duyệt.'
                : 'Đăng ký thành công! Vui lòng đăng nhập để tiếp tục.'
        );
        $_SESSION['login_email'] = $email;
        self::redirect('/login');
    }

    /**
     * Hiển thị biểu mẫu đăng nhập.
     */
    public static function showLogin(): void
    {
        $prefillEmail = (string)($_SESSION['login_email'] ?? '');
        unset($_SESSION['login_email']);

        View::render('auth/login', [
            'csrf' => Csrf::token(),
            'error' => null,
            'email' => $prefillEmail,
        ]);
    }

    /**
     * Xử lý dữ liệu gửi lên từ biểu mẫu đăng nhập.
     * Xác thực người dùng và chuyển hướng tới trang điều khiển phù hợp.
     */
    public static function login(): void
    {
        // CSRF token already verified by Router::dispatch() for all POST requests
        // No need to verify again here

        // Lấy dữ liệu biểu mẫu
        $email = trim((string)($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        // Rate-limit theo IP: tối đa 5 lần trong cửa sổ 15 phút
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $now = time();
        $window = 15 * 60; // 15 phút
        $maxAttempts = 5;
        $attempts = $_SESSION['login_attempts'][$ip] ?? [];
        // Loại bỏ các attempt cũ
        $attempts = array_values(array_filter($attempts, static fn($t) => ($t + $window) >= $now));
        if (count($attempts) >= $maxAttempts) {
            View::render('auth/login', [
                'csrf' => Csrf::token(),
                'error' => 'Quá nhiều lần đăng nhập không thành công. Vui lòng thử lại sau.',
                'email' => $email,
            ]);
            return;
        }

        // Tìm người dùng theo email
        $user = User::findByEmail($email);

        // Kiểm tra mật khẩu và user existence
        $authOk = $user !== null && password_verify($password, $user['password_hash']);
        if (!$authOk) {
            // Ghi lại lần thử
            $attempts[] = $now;
            $_SESSION['login_attempts'][$ip] = $attempts;

            View::render('auth/login', [
                'csrf' => Csrf::token(),
                'error' => 'Đăng nhập không thành công. Vui lòng kiểm tra email hoặc mật khẩu.',
                'email' => $email,
            ]);
            return;
        }


        // Kiểm tra trạng thái tài khoản (nếu không cho phép đăng nhập vẫn trả về thông báo chung)
        $accountStatus = (string)($user['approval_status'] ?? User::STATUS_ACTIVE);
        $statusError = self::validateAccountStatus($accountStatus, $user);
        if ($statusError !== null) {
            // Ghi attempt khi bị chặn
            $attempts[] = $now;
            $_SESSION['login_attempts'][$ip] = $attempts;

            View::render('auth/login', [
                'csrf' => Csrf::token(),
                'error' => 'Đăng nhập không thành công. Vui lòng kiểm tra email hoặc mật khẩu.',
                'email' => $email,
            ]);
            return;
        }

        // Đăng nhập người dùng
        $userRole = self::normalizeUserRole((string)$user['role']);
        Auth::login((int)$user['id'], $userRole);

        // Thành công: xóa bộ đếm attempt cho IP này
        unset($_SESSION['login_attempts'][$ip]);

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
        string $role,
        string $phone,
        string $city,
        string $district,
        string $ward,
        string $addressDetail
    ): ?string {
        // Kiểm tra các trường bắt buộc
        if (
            empty($name)
            || empty($email)
            || empty($password)
            || empty($phone)
            || empty($city)
            || empty($district)
            || empty($ward)
            || empty($addressDetail)
        ) {
            return 'Vui lòng điền đầy đủ các trường bắt buộc.';
        }

        if (!in_array($city, self::ALLOWED_CITIES, true)) {
            return 'Chỉ hỗ trợ đăng ký trong khu vực TP.HCM.';
        }

        if (mb_strlen($addressDetail) < 5) {
            return 'Vui lòng nhập địa chỉ chi tiết hợp lệ.';
        }

        $normalizedPhone = preg_replace('/\D+/', '', $phone) ?? '';
        if (!preg_match('/^(0|84)([0-9]{9,10})$/', $normalizedPhone)) {
            return 'Số điện thoại không hợp lệ.';
        }

        // Kiểm tra định dạng email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'Vui lòng nhập địa chỉ email hợp lệ.';
        }

        // Kiểm tra độ dài mật khẩu
        if (strlen($password) < self::MIN_PASSWORD_LENGTH) {
            return 'Mật khẩu phải có ít nhất ' . self::MIN_PASSWORD_LENGTH . ' ký tự.';
        }

        // Kiểm tra vai trò
        if (!in_array($role, self::ALLOWED_ROLES, true)) {
            return 'Vai trò người dùng không hợp lệ.';
        }

        // Kiểm tra email đã tồn tại hay chưa
        if (User::findByEmail($email) !== null) {
            return 'Địa chỉ email đã tồn tại.';
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
            $reason = trim((string)($user['reject_reason'] ?? ''));
            if ($reason === '') {
                $reason = 'Tài khoản đã bị khóa. Vui lòng liên hệ bộ phận hỗ trợ.';
            }

            return "Tài khoản đã bị khóa.\nLý do: {$reason}";
        }

        if ($status === User::STATUS_DELETED) {
            $reason = trim((string)($user['reject_reason'] ?? ''));
            if ($reason === '') {
                $reason = 'Tài khoản đã bị xóa hoặc bị vô hiệu hóa.';
            }

            return "Tài khoản đã bị vô hiệu hóa.\nLý do: {$reason}";
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
            User::ROLE_CUSTOMER => '/',
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
