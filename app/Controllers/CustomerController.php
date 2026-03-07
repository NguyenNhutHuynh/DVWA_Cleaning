<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\View;
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
}