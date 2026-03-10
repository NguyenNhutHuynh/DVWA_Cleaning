<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Models\User;

/**
 * UserController xử lý các nghiệp vụ quản trị người dùng.
 * Bao gồm xem danh sách và quản lý tài khoản
 * (cập nhật thông tin, khóa, mở khóa, xóa).
 * Tất cả thao tác yêu cầu quyền quản trị viên.
 */
final class UserController
{
    /**
     * Hiển thị danh sách toàn bộ người dùng.
     * Yêu cầu xác thực quản trị viên.
     *
     * @return void
     */
    public function adminUsers(): void
    {
        $this->requireAdminRole();
        $users = User::getAllUsers();
        View::render('admin/users', ['users' => $users]);
    }

    /**
     * Cập nhật thông tin người dùng (tên, email, vai trò, trạng thái duyệt).
     * Yêu cầu xác thực quản trị viên và ID người dùng hợp lệ.
     *
     * @return void
     */
    public function updateUser(): void
    {
        $this->requireAdminRole();

        $id = (int)($_POST['id'] ?? 0);
        $name = trim((string)($_POST['name'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $role = trim((string)($_POST['role'] ?? ''));
        $approvalStatus = trim((string)($_POST['approval_status'] ?? ''));

        $validationError = $this->validateUserUpdateInput($id, $name, $email);
        if ($validationError) {
            http_response_code(400);
            echo $validationError;
            exit(1);
        }

        User::updateUser($id, $name, $email, $role, $approvalStatus);
        $this->redirect('/admin/users');
    }

    /**
     * Khóa tài khoản người dùng để ngăn đăng nhập.
     * Yêu cầu xác thực quản trị viên và ID người dùng hợp lệ.
     *
     * @return void
     */
    public function lockUser(): void
    {
        $this->requireAdminRole();

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(400);
            echo 'ID người dùng không hợp lệ';
            exit(1);
        }

        $reason = trim((string)($_POST['reason'] ?? 'Tài khoản bị khóa bởi quản trị viên'));
        User::lockUser($id, $reason);
        $this->redirect('/admin/users');
    }

    /**
     * Mở khóa tài khoản để khôi phục quyền đăng nhập.
     * Yêu cầu xác thực quản trị viên và ID người dùng hợp lệ.
     *
     * @return void
     */
    public function unlockUser(): void
    {
        $this->requireAdminRole();

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(400);
            echo 'ID người dùng không hợp lệ';
            exit(1);
        }

        User::unlockUser($id);
        $this->redirect('/admin/users');
    }

    /**
     * Xóa tài khoản người dùng vĩnh viễn.
     * Yêu cầu xác thực quản trị viên và ID người dùng hợp lệ.
     *
     * @return void
     */
    public function deleteUser(): void
    {
        $this->requireAdminRole();

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(400);
            echo 'ID người dùng không hợp lệ';
            exit(1);
        }

        User::delete($id);
        $this->redirect('/admin/users');
    }

    /**
     * Bắt buộc người dùng phải có vai trò admin.
     * Trả về 403 Forbidden nếu chưa xác thực hoặc không có quyền admin.
     *
     * @return void
     */
    private function requireAdminRole(): void
    {
        if (!Auth::hasRole(User::ROLE_ADMIN)) {
            http_response_code(403);
            echo 'Truy cập bị từ chối';
            exit(1);
        }
    }

    /**
     * Kiểm tra dữ liệu đầu vào khi cập nhật người dùng.
     * Xác minh ID hợp lệ, đồng thời tên và email không được rỗng.
     *
     * @param int $id ID người dùng cần cập nhật
     * @param string $name Họ tên người dùng
     * @param string $email Địa chỉ email
     * @return string|null Thông báo lỗi nếu kiểm tra thất bại, ngược lại là null
     */
    private function validateUserUpdateInput(int $id, string $name, string $email): ?string
    {
        if ($id <= 0) {
            return 'ID người dùng không hợp lệ';
        }

        if ($name === '' || $email === '') {
            return 'Dữ liệu đầu vào không hợp lệ';
        }

        $user = User::findById($id);
        if (!$user) {
            return 'Không tìm thấy người dùng';
        }

        return null;
    }

    /**
     * Chuyển hướng tới URL chỉ định và kết thúc xử lý.
     *
     * @param string $path Đường dẫn URL cần chuyển hướng
     * @return void
     */
    private function redirect(string $path): void
    {
        header('Location: ' . $path);
        exit(0);
    }
}
