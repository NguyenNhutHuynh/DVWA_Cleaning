<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\View;
use App\Models\AdminUserMessage;
use App\Models\User;

/**
 * CustomerController xử lý trang điều khiển dành cho khách hàng.
 */
final class CustomerController
{
    /**
     * Hiển thị dashboard của khách hàng.
     * Yêu cầu xác thực với vai trò customer.
     */
    public function dashboard(): void
    {
        $this->requireCustomerRole();

        $currentUser = User::findById(Auth::id());

        View::render('customer/dashboard', [
            'uid' => Auth::id(),
            'role' => Auth::role(),
            'name' => $currentUser['name'] ?? 'Customer',
        ]);
    }

    public function messages(): void
    {
        $this->requireCustomerRole();
        $userId = (int)Auth::id();
        $currentUser = User::findById($userId);

        View::render('customer/messages', [
            'uid' => $userId,
            'role' => Auth::role(),
            'name' => $currentUser['name'] ?? 'Customer',
            'messages' => AdminUserMessage::byUserId($userId),
            'csrf' => Csrf::token(),
        ]);
    }

    public function sendMessage(): void
    {
        $this->requireCustomerRole();


        $userId = (int)Auth::id();
        $content = trim((string)($_POST['content'] ?? ''));

        if ($content === '') {
            $_SESSION['error'] = 'Tin nhắn không được để trống.';
            $this->redirect('/customer/messages');
        }

        AdminUserMessage::add($userId, $userId, User::ROLE_CUSTOMER, $content);
        $_SESSION['success'] = 'Đã gửi tin nhắn cho admin.';
        $this->redirect('/customer/messages');
    }

    /**
     * Đảm bảo người dùng đã đăng nhập và có vai trò customer.
     */
    private function requireCustomerRole(): void
    {
        if (!Auth::isAuthenticated() || !Auth::hasRole(User::ROLE_CUSTOMER)) {
            header('Location: /login');
            exit(1);
        }
    }

    private function verifyCsrfToken(): void
    {
        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            $_SESSION['error_alert'] = 'Phiên làm việc hết hạn, vui lòng thử lại.';
            $this->redirect('/customer/messages');
        }
    }

    private function redirect(string $path): void
    {
        header('Location: ' . $path);
        exit(0);
    }
}
