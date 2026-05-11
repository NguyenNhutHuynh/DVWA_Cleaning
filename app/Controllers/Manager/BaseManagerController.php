<?php

declare(strict_types=1);

namespace App\Controllers\Manager;

use App\Core\Auth;
use App\Models\User;

/**
 * BaseManagerController - Lớp cơ sở cho tất cả các controller của Manager.
 * 
 * Cung cấp các phương thức chung cho:
 * - Kiểm tra quyền truy cập (Manager hoặc Admin)
 * - Xử lý chuyển hướng khi chưa có quyền
 * - Các phương thức tiện ích chung
 * 
 * Chính sách quyền:
 * - Manager: Quản lý Booking, phân công Worker, duyệt/từ chối Worker, xem Customer/Worker, Chat
 * - Admin: Có toàn bộ quyền của Manager + quản lý Service, thống kê, quản lý tài khoản
 */
abstract class BaseManagerController
{
    /**
     * Kiểm tra xem người dùng hiện tại có phải là manager hoặc admin không.
     * Nếu không, sẽ chuyển hướng đến trang đăng nhập.
     * 
     * @throws void Thực hiện chuyển hướng nếu không có quyền
     */
    protected function requireManagerRole(): void
    {
        if (!Auth::isAuthenticated()) {
            $this->redirect('/login');
        }

        $userRole = Auth::role();
        if ($userRole !== User::ROLE_MANAGER && $userRole !== User::ROLE_ADMIN) {
            // Chuyển hướng dựa trên vai trò hiện tại
            $this->redirectToUserDashboard($userRole);
        }
    }

    /**
     * Kiểm tra xem người dùng có phải là admin không.
     * Dùng cho các tính năng chỉ dành cho admin.
     * 
     * @throws void Thực hiện chuyển hướng nếu không phải admin
     */
    protected function requireAdminRole(): void
    {
        if (!Auth::isAuthenticated() || Auth::role() !== User::ROLE_ADMIN) {
            $this->redirect('/login');
        }
    }

    /**
     * Kiểm tra xem người dùng có phải là manager không (không phải admin).
     * Dùng cho các tính năng chỉ dành cho manager.
     * 
     * @throws void Thực hiện chuyển hướng nếu không phải manager
     */
    protected function requireManagerRoleExclusive(): void
    {
        if (!Auth::isAuthenticated() || Auth::role() !== User::ROLE_MANAGER) {
            $this->redirect('/login');
        }
    }

    /**
     * Chuyển hướng người dùng đến trang chủ phù hợp với vai trò của họ.
     * 
     * @param string $role Vai trò của người dùng
     */
    protected function redirectToUserDashboard(?string $role = null): void
    {
        $role = $role ?? Auth::role();
        
        match ($role) {
            User::ROLE_ADMIN => $this->redirect('/admin/dashboard'),
            User::ROLE_MANAGER => $this->redirect('/manager/dashboard'),
            User::ROLE_WORKER => $this->redirect('/worker/dashboard'),
            default => $this->redirect('/'),
        };
    }

    /**
     * Thiết lập thông báo session để hiển thị trên trang tiếp theo.
     * 
     * @param string $type Loại thông báo (success, error, info)
     * @param string $message Nội dung thông báo
     */
    protected function setSessionMessage(string $type, string $message): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $_SESSION[$type] = $message;
    }

    /**
     * Chuyển hướng đến một URL cụ thể.
     * 
     * @param string $path Đường dẫn để chuyển hướng
     */
    protected function redirect(string $path): void
    {
        header('Location: ' . $path, true, 302);
        exit(0);
    }

    /**
     * Lấy danh sách các worker đang hoạt động.
     * 
     * @return array Danh sách worker
     */
    protected function getActiveWorkers(): array
    {
        $allUsers = User::listAll();
        return array_values(array_filter(
            $allUsers,
            static fn(array $user): bool => ($user['role'] ?? '') === User::ROLE_WORKER
                && ($user['approval_status'] ?? '') === User::STATUS_ACTIVE
        ));
    }

    /**
     * Lấy danh sách các customer đang hoạt động.
     * 
     * @return array Danh sách customer
     */
    protected function getActiveCustomers(): array
    {
        $allUsers = User::listAll();
        return array_values(array_filter(
            $allUsers,
            static fn(array $user): bool => ($user['role'] ?? '') === User::ROLE_CUSTOMER
                && ($user['approval_status'] ?? '') === User::STATUS_ACTIVE
        ));
    }

    /**
     * Xác thực token CSRF từ request.
     * 
     * @param string|null $token Token CSRF
     * @return bool True nếu token hợp lệ
     */
    protected function verifyCsrfToken(?string $token): bool
    {
        $csrfClass = 'App\Core\Csrf';
        return class_exists($csrfClass) && $csrfClass::verify($token);
    }
}
