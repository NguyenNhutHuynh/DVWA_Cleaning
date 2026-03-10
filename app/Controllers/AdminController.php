<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\View;
use App\Models\Booking;
use App\Models\BookingPayment;
use App\Models\Contact;
use App\Models\Service;
use App\Models\User;

/**
 * AdminController xử lý các nghiệp vụ quản trị cho người dùng, đơn đặt và dịch vụ.
 * Tất cả phương thức đều yêu cầu quyền quản trị viên.
 */
final class AdminController
{
    private const MAX_AVATAR_SIZE = 2 * 1024 * 1024;
    private const ALLOWED_AVATAR_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    public function dashboard(): void
    {
        $this->requireAdminRole();

        $currentUser = User::findById((int)Auth::id());
        
        // Lấy dữ liệu thống kê tổng quan
        $users = User::listAll();
        $bookings = Booking::getAll();
        $services = Service::listAllAdmin();
        $contacts = Contact::getAll();
        
        // Tính toán thống kê
        $totalUsers = count($users);
        $totalBookings = count($bookings);
        $totalServices = count($services);
        $pendingBookings = count(array_filter($bookings, fn($b) => $b['status'] === 'pending'));
        $completedBookings = count(array_filter($bookings, fn($b) => $b['status'] === 'completed'));
        $totalRevenue = array_sum(array_map(fn($b) => $b['service_price'] ?? 0, 
            array_filter($bookings, fn($b) => $b['status'] === 'completed')));
        $unreadContacts = count(array_filter($contacts, fn($c) => $c['status'] === 'pending'));
        
        View::render('admin/dashboard', [
            'uid' => Auth::id(),
            'role' => Auth::role(),
            'name' => $currentUser['name'] ?? 'Admin',
            'stats' => [
                'totalUsers' => $totalUsers,
                'totalBookings' => $totalBookings,
                'totalServices' => $totalServices,
                'pendingBookings' => $pendingBookings,
                'completedBookings' => $completedBookings,
                'totalRevenue' => $totalRevenue,
                'unreadContacts' => $unreadContacts,
            ]
        ]);
    }

    public function services(): void
    {
        $this->requireAdminRole();

        View::render('admin/services', [
            'services' => Service::listAllAdmin(),
            'csrf' => Csrf::token(),
        ]);
    }

    public function bookings(): void
    {
        $this->requireAdminRole();

        View::render('admin/bookings', [
            'bookings' => Booking::getAll(),
            'workers' => $this->getActiveWorkers(),
            'csrf' => Csrf::token(),
        ]);
    }

    public function moderation(): void
    {
        $this->requireAdminRole();
        View::render('admin/moderation', ['contacts' => Contact::getAll()]);
    }

    public function users(): void
    {
        $this->requireAdminRole();

        View::render('admin/users', [
            'users' => User::listAll(),
            'pendingWorkers' => User::listPendingWorkers(),
            'csrf' => Csrf::token(),
        ]);
    }

    public function userDetail(): void
    {
        $this->requireAdminRole();

        $userId = (int)($_GET['id'] ?? 0);
        if ($userId <= 0) {
            $this->redirect('/admin/users');
        }

        $user = User::findById($userId);
        if ($user === null) {
            $this->setSessionMessage('error', 'Không tìm thấy người dùng.');
            $this->redirect('/admin/users');
        }

        $user['approved_by_name'] = $this->getApproverName($user);
        View::render('admin/user_detail', [
            'user' => $user,
            'csrf' => Csrf::token(),
        ]);
    }

    public function userDetailJson(): void
    {
        $this->requireAdminRole();
        header('Content-Type: application/json; charset=utf-8');

        $userId = (int)($_GET['id'] ?? 0);
        if ($userId <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'invalid_id'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $user = User::findById($userId);
        if ($user === null) {
            http_response_code(404);
            echo json_encode(['error' => 'not_found'], JSON_UNESCAPED_UNICODE);
            return;
        }

        echo json_encode($this->formatUserAsJson($user), JSON_UNESCAPED_UNICODE);
    }

    public function userUpdate(): void
    {
        $this->requireAdminRole();
        $this->verifyCsrfToken();

        $userId = (int)($_POST['id'] ?? 0);
        if ($userId <= 0) {
            $this->redirect('/admin/users');
        }

        $existingUser = User::findById($userId);
        if ($existingUser === null) {
            $this->setSessionMessage('error', 'Không tìm thấy người dùng.');
            $this->redirect('/admin/users');
        }

        $name = trim((string)($_POST['name'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $phone = trim((string)($_POST['phone'] ?? ''));
        $address = trim((string)($_POST['address'] ?? ''));
        $role = trim((string)($_POST['role'] ?? ($existingUser['role'] ?? User::ROLE_CUSTOMER)));
        $approvalStatus = trim((string)($_POST['approval_status'] ?? ($existingUser['approval_status'] ?? User::STATUS_ACTIVE)));
        $rejectReason = trim((string)($_POST['reject_reason'] ?? ($existingUser['reject_reason'] ?? '')));
        $returnTo = trim((string)($_POST['return_to'] ?? ''));

        $validationError = $this->validateUserUpdateData($name, $email, $phone);
        if ($validationError !== null) {
            $this->setSessionMessage('error', $validationError);
            $this->redirect($returnTo !== '' ? $returnTo : '/admin/user?id=' . $userId);
        }

        $allowedRoles = [User::ROLE_ADMIN, User::ROLE_WORKER, User::ROLE_CUSTOMER];
        if (!in_array($role, $allowedRoles, true)) {
            $this->setSessionMessage('error', 'Vai trò người dùng không hợp lệ.');
            $this->redirect($returnTo !== '' ? $returnTo : '/admin/user?id=' . $userId);
        }

        $allowedStatuses = [
            User::STATUS_ACTIVE,
            User::STATUS_PENDING,
            User::STATUS_REJECTED,
            User::STATUS_LOCKED,
            User::STATUS_DELETED,
        ];
        if (!in_array($approvalStatus, $allowedStatuses, true)) {
            $this->setSessionMessage('error', 'Trạng thái tài khoản không hợp lệ.');
            $this->redirect($returnTo !== '' ? $returnTo : '/admin/user?id=' . $userId);
        }

        $updated = User::updateAdminEditable(
            $userId,
            $name,
            $email,
            $phone !== '' ? $phone : null,
            $address !== '' ? $address : null,
            $role,
            $approvalStatus,
            $rejectReason !== '' ? $rejectReason : null
        );

        if (!$updated) {
            $this->setSessionMessage('error', 'Email đã tồn tại hoặc cập nhật thất bại.');
            $this->redirect($returnTo !== '' ? $returnTo : '/admin/user?id=' . $userId);
        }

        if ($this->hasAvatarUpload()) {
            $avatarError = $this->handleAvatarUpload($userId);
            if ($avatarError !== null) {
                $this->setSessionMessage('error', $avatarError);
                $this->redirect($returnTo !== '' ? $returnTo : '/admin/user?id=' . $userId);
            }
        }

        $this->setSessionMessage('success', 'Cập nhật thông tin người dùng #' . $userId . ' thành công.');
        $this->redirect($returnTo !== '' ? $returnTo : '/admin/user?id=' . $userId);
    }

    public function userLock(): void
    {
        $this->requireAdminRole();
        $this->verifyCsrfToken();

        $userId = (int)($_POST['id'] ?? 0);
        if ($userId <= 0) {
            $this->redirect('/admin/users');
        }

        $reason = trim((string)($_POST['reason'] ?? ''));
        User::setStatusAndReason($userId, User::STATUS_LOCKED, $reason !== '' ? $reason : null);

        $this->setSessionMessage('success', 'Đã khóa người dùng #' . $userId . ' thành công.');
        $returnTo = trim((string)($_POST['return_to'] ?? ''));
        $this->redirect($returnTo !== '' ? $returnTo : '/admin/user?id=' . $userId);
    }

    public function userUnlock(): void
    {
        $this->requireAdminRole();
        $this->verifyCsrfToken();

        $userId = (int)($_POST['id'] ?? 0);
        if ($userId <= 0) {
            $this->redirect('/admin/users');
        }

        User::setStatusAndReason($userId, User::STATUS_ACTIVE, null);

        $this->setSessionMessage('success', 'Đã mở khóa người dùng #' . $userId . ' thành công.');
        $returnTo = trim((string)($_POST['return_to'] ?? ''));
        $this->redirect($returnTo !== '' ? $returnTo : '/admin/user?id=' . $userId);
    }

    public function userDelete(): void
    {
        $this->requireAdminRole();
        $this->verifyCsrfToken();

        $userId = (int)($_POST['id'] ?? 0);
        if ($userId <= 0) {
            $this->redirect('/admin/users');
        }

        $reason = trim((string)($_POST['reason'] ?? ''));
        $success = User::setStatusAndReason($userId, User::STATUS_DELETED, $reason !== '' ? $reason : null);

        if ($success) {
            $this->setSessionMessage('success', 'Đã xóa người dùng #' . $userId . ' thành công.');
        } else {
            $this->setSessionMessage('error', 'Xóa người dùng #' . $userId . ' thất bại.');
        }

        $this->redirect('/admin/users');
    }

    public function stats(): void
    {
        $this->requireAdminRole();

        $bookings = Booking::getAll();
        $users = User::listAll();
        $paymentTotals = BookingPayment::totals();
        
        $stats = [
            'service_count' => count(Service::all()),
            'booking_count' => count($bookings),
            'contact_count' => count(Contact::getAll()),
            'user_count' => count($users),
            'confirmed_rate' => $this->calculateConfirmedRate($bookings),
            'pending_rate' => $this->calculatePendingRate($bookings),
            'booking_status_breakdown' => $this->getBookingStatusBreakdown($bookings),
            'monthly_bookings' => $this->getMonthlyBookings($bookings),
            'user_role_distribution' => $this->getUserRoleDistribution($users),
            // Doanh thu và conversion
            'total_revenue' => $this->calculateTotalRevenue($bookings),
            'monthly_revenue' => $this->getMonthlyRevenue($bookings),
            'average_order_value' => $this->calculateAverageOrderValue($bookings),
            'conversion_rate' => $this->calculateConversionRate($bookings),
            'completion_rate' => $this->calculateCompletionRate($bookings),
            'payment_totals' => $paymentTotals,
        ];

        View::render('admin/stats', ['stats' => $stats]);
    }

    public function approveWorker(): void
    {
        $this->requireAdminRole();
        $this->verifyCsrfToken();

        $workerId = (int)($_POST['id'] ?? 0);
        if ($workerId <= 0) {
            $this->redirect('/admin/users');
        }

        User::approveWorker($workerId, (int)Auth::id());
        $this->setSessionMessage('success', 'Duyệt worker thành công.');
        $this->redirect('/admin/users');
    }

    public function rejectWorker(): void
    {
        $this->requireAdminRole();
        $this->verifyCsrfToken();

        $workerId = (int)($_POST['id'] ?? 0);
        if ($workerId <= 0) {
            $this->redirect('/admin/users');
        }

        $reason = trim((string)($_POST['reason'] ?? ''));
        User::rejectWorker($workerId, (int)Auth::id(), $reason !== '' ? $reason : null);
        $this->setSessionMessage('success', 'Đã từ chối worker.');
        $this->redirect('/admin/users');
    }

    public function updateService(): void
    {
        $this->requireAdminRole();
        $this->verifyCsrfToken();

        $serviceId = (int)($_POST['id'] ?? 0);
        if ($serviceId <= 0) {
            $this->redirect('/admin/services');
        }

        Service::update($serviceId, $this->extractServiceData());
        $this->setSessionMessage('success', 'Cập nhật dịch vụ #' . $serviceId . ' thành công.');
        $this->redirect('/admin/services');
    }

    public function toggleService(): void
    {
        $this->requireAdminRole();
        $this->verifyCsrfToken();

        $serviceId = (int)($_POST['id'] ?? 0);
        if ($serviceId <= 0) {
            $this->redirect('/admin/services');
        }

        Service::toggleActive($serviceId);
        $this->setSessionMessage('success', 'Đã thay đổi trạng thái dịch vụ #' . $serviceId . '.');
        $this->redirect('/admin/services');
    }

    public function deleteService(): void
    {
        $this->requireAdminRole();
        $this->verifyCsrfToken();

        $serviceId = (int)($_POST['id'] ?? 0);
        if ($serviceId <= 0) {
            $this->redirect('/admin/services');
        }

        $success = Service::delete($serviceId);
        if ($success) {
            $this->setSessionMessage('success', 'Đã xóa dịch vụ #' . $serviceId . ' thành công.');
        } else {
            $this->setSessionMessage('error', 'Không thể xóa dịch vụ #' . $serviceId . '. Dịch vụ có thể đang được tham chiếu bởi các đơn đặt.');
        }

        $this->redirect('/admin/services');
    }

    public function createService(): void
    {
        $this->requireAdminRole();
        $this->verifyCsrfToken();

        $payload = $this->extractServiceData();
        $payload['is_active'] = (int)($_POST['is_active'] ?? 1);

        if (empty($payload['name']) || empty($payload['unit'])) {
            $this->setSessionMessage('error', 'Tên dịch vụ và đơn vị là bắt buộc.');
            $this->redirect('/admin/services');
        }

        $serviceId = Service::create($payload);
        $this->setSessionMessage('success', 'Tạo dịch vụ #' . $serviceId . ' thành công.');
        $this->redirect('/admin/services');
    }

    public function confirmBooking(): void
    {
        $this->requireAdminRole();
        $this->verifyCsrfToken();

        $bookingId = (int)($_POST['id'] ?? 0);
        if ($bookingId <= 0) {
            $this->redirect('/admin/bookings');
        }

        $booking = Booking::getById($bookingId);
        if ($booking === null) {
            $this->setSessionMessage('error', 'Không tìm thấy đơn đặt.');
            $this->redirect('/admin/bookings');
        }

        if (empty($booking['assigned_worker_id'])) {
            $this->setSessionMessage('error', 'Vui lòng phân công worker trước khi xác nhận đơn đặt này.');
            $this->redirect('/admin/bookings');
        }

        Booking::updateStatus($bookingId, Booking::STATUS_CONFIRMED);
        $this->setSessionMessage('success', 'Đã xác nhận đơn đặt #' . $bookingId . '.');
        $this->redirect('/admin/bookings');
    }

    public function cancelBooking(): void
    {
        $this->requireAdminRole();
        $this->verifyCsrfToken();

        $bookingId = (int)($_POST['id'] ?? 0);
        if ($bookingId <= 0) {
            $this->redirect('/admin/bookings');
        }

        Booking::updateStatus($bookingId, Booking::STATUS_CANCELLED);
        $this->setSessionMessage('success', 'Đã hủy đơn đặt #' . $bookingId . '.');
        $this->redirect('/admin/bookings');
    }

    public function assignBooking(): void
    {
        $this->requireAdminRole();
        $this->verifyCsrfToken();

        $bookingId = (int)($_POST['id'] ?? 0);
        $workerId = (int)($_POST['worker_id'] ?? 0);
        if ($bookingId <= 0 || $workerId <= 0) {
            $this->redirect('/admin/bookings');
        }

        $worker = User::findById($workerId);
        if (
            $worker === null
            || ($worker['role'] ?? '') !== User::ROLE_WORKER
            || ($worker['approval_status'] ?? '') !== User::STATUS_ACTIVE
        ) {
            $this->setSessionMessage('error', 'Worker được chọn để phân công không hợp lệ.');
            $this->redirect('/admin/bookings');
        }

        Booking::assignWorker($bookingId, $workerId);
        $this->setSessionMessage('success', 'Đã phân công worker #' . $workerId . '. Hãy xác nhận đơn khi sẵn sàng.');
        $this->redirect('/admin/bookings');
    }

    private function requireAdminRole(): void
    {
        if (!Auth::isAuthenticated() || !Auth::hasRole(User::ROLE_ADMIN)) {
            $this->redirect('/login');
        }
    }

    private function verifyCsrfToken(): void
    {
        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            http_response_code(419);
            echo 'Mã bảo mật không hợp lệ. Vui lòng thử lại.';
            exit(1);
        }
    }

    private function getActiveWorkers(): array
    {
        $allUsers = User::listAll();
        return array_values(array_filter(
            $allUsers,
            static fn(array $user): bool => ($user['role'] ?? '') === User::ROLE_WORKER
                && ($user['approval_status'] ?? '') === User::STATUS_ACTIVE
        ));
    }

    private function getApproverName(?array $user): ?string
    {
        if ($user === null || empty($user['approved_by'])) {
            return null;
        }

        $approver = User::findById((int)$user['approved_by']);
        return $approver['name'] ?? null;
    }

    private function formatUserAsJson(array $user): array
    {
        $data = [
            'id' => (int)($user['id'] ?? 0),
            'name' => (string)($user['name'] ?? ''),
            'email' => (string)($user['email'] ?? ''),
            'phone' => $user['phone'] ?? null,
            'address' => $user['address'] ?? null,
            'avatar' => $user['avatar'] ?? null,
            'role' => (string)($user['role'] ?? ''),
            'approval_status' => (string)($user['approval_status'] ?? ''),
            'approved_by' => $user['approved_by'] ?? null,
            'approved_at' => $user['approved_at'] ?? null,
            'reject_reason' => $user['reject_reason'] ?? null,
        ];

        if (!empty($user['approved_by'])) {
            $data['approved_by_name'] = $this->getApproverName($user);
        }

        return $data;
    }

    private function validateUserUpdateData(string $name, string $email, string $phone): ?string
    {
        if ($name === '' || $email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'Họ tên và email hợp lệ là bắt buộc.';
        }

        if ($phone !== '' && !preg_match('/^[0-9+\-\s]{6,20}$/', $phone)) {
            return 'Định dạng số điện thoại không hợp lệ.';
        }

        return null;
    }

    private function hasAvatarUpload(): bool
    {
        return isset($_FILES['avatar'])
            && is_array($_FILES['avatar'])
            && (($_FILES['avatar']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE);
    }

    private function handleAvatarUpload(int $userId): ?string
    {
        $file = $_FILES['avatar'];

        if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            return 'Tải ảnh đại diện lên thất bại.';
        }

        if (($file['size'] ?? 0) > self::MAX_AVATAR_SIZE) {
            return 'Ảnh đại diện vượt quá giới hạn 2MB.';
        }

        $extension = $this->getValidatedImageExtension($file);
        if ($extension === null) {
            return 'Định dạng ảnh đại diện không hợp lệ. Chỉ chấp nhận: jpg, png, gif, webp.';
        }

        $filename = $this->generateAvatarFilename($userId, $extension);
        $filePath = $this->getAvatarDirectory() . '/' . $filename;

        if (!@move_uploaded_file((string)$file['tmp_name'], $filePath)) {
            return 'Không thể lưu tệp ảnh đại diện.';
        }

        User::updateAvatar($userId, '/uploads/avatars/' . $filename);
        return null;
    }

    private function getValidatedImageExtension(array $file): ?string
    {
        $extension = strtolower(pathinfo((string)($file['name'] ?? ''), PATHINFO_EXTENSION));
        if (in_array($extension, self::ALLOWED_AVATAR_EXTENSIONS, true)) {
            return $extension;
        }

        $imageInfo = @getimagesize((string)($file['tmp_name'] ?? ''));
        if (is_array($imageInfo) && isset($imageInfo['mime'])) {
            $mimeMap = [
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/gif' => 'gif',
                'image/webp' => 'webp',
            ];
            $mimeType = strtolower((string)$imageInfo['mime']);
            return $mimeMap[$mimeType] ?? null;
        }

        return null;
    }

    private function generateAvatarFilename(int $userId, string $extension): string
    {
        return sprintf('u%d_%d_%s.%s', $userId, time(), bin2hex(random_bytes(4)), $extension);
    }

    private function getAvatarDirectory(): string
    {
        $dir = dirname(__DIR__, 2) . '/public/uploads/avatars';
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }
        return $dir;
    }

    private function extractServiceData(): array
    {
        $payload = [
            'name' => trim((string)($_POST['name'] ?? '')),
            'description' => trim((string)($_POST['description'] ?? '')),
            'icon' => trim((string)($_POST['icon'] ?? '')),
            'duration' => trim((string)($_POST['duration'] ?? '')),
            'price' => (int)($_POST['price'] ?? 0),
            'unit' => trim((string)($_POST['unit'] ?? '')),
            'minimum' => (int)($_POST['minimum'] ?? 0),
        ];

        foreach ($payload as $key => $value) {
            if ($key !== 'price' && $key !== 'minimum' && $value === '') {
                unset($payload[$key]);
            }
        }

        return $payload;
    }

    /**
     * Lấy phân tích trạng thái đơn đặt.
     *
     * @param array $bookings Danh sách đơn đặt
     * @return array Mảng với key là tên trạng thái, value là số lượng
     */
    private function getBookingStatusBreakdown(array $bookings): array
    {
        $breakdown = [
            'pending' => 0,
            'confirmed' => 0,
            'completed' => 0,
            'cancelled' => 0,
        ];

        foreach ($bookings as $booking) {
            $status = strtolower($booking['status'] ?? 'pending');
            if (isset($breakdown[$status])) {
                $breakdown[$status]++;
            }
        }

        return $breakdown;
    }

    /**
     * Lấy số lượng đơn đặt theo từng tháng (6 tháng gần nhất).
     *
     * @param array $bookings Danh sách đơn đặt
     * @return array Mảng với key là "YYYY-MM", value là số lượng đơn
     */
    private function getMonthlyBookings(array $bookings): array
    {
        $monthlyData = [];
        
        // Tạo 6 tháng gần nhất
        for ($i = 5; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $monthlyData[$month] = 0;
        }

        // Đếm số lượng booking theo tháng
        foreach ($bookings as $booking) {
            if (!empty($booking['created_at'])) {
                $bookingMonth = date('Y-m', strtotime($booking['created_at']));
                if (isset($monthlyData[$bookingMonth])) {
                    $monthlyData[$bookingMonth]++;
                }
            }
        }

        return $monthlyData;
    }

    /**
     * Lấy phân bố vai trò người dùng.
     *
     * @param array $users Danh sách người dùng
     * @return array Mảng với key là vai trò, value là số lượng
     */
    private function getUserRoleDistribution(array $users): array
    {
        $distribution = [
            'admin' => 0,
            'worker' => 0,
            'customer' => 0,
        ];

        foreach ($users as $user) {
            $role = strtolower($user['role'] ?? 'customer');
            if (isset($distribution[$role])) {
                $distribution[$role]++;
            }
        }

        return $distribution;
    }

    /**
     * Tính tổng doanh thu từ các đơn đã hoàn thành.
     *
     * @param array $bookings Danh sách đơn đặt
     * @return float Tổng doanh thu (VNĐ)
     */
    private function calculateTotalRevenue(array $bookings): float
    {
        $revenue = 0.0;
        
        foreach ($bookings as $booking) {
            $status = strtolower($booking['status'] ?? '');
            if ($status === Booking::STATUS_COMPLETED) {
                $revenue += (float)($booking['service_price'] ?? 0);
            }
        }
        
        return $revenue;
    }

    /**
     * Lấy doanh thu theo từng tháng (6 tháng gần nhất).
     *
     * @param array $bookings Danh sách đơn đặt
     * @return array Mảng với key là "YYYY-MM", value là doanh thu
     */
    private function getMonthlyRevenue(array $bookings): array
    {
        $monthlyData = [];
        
        // Tạo 6 tháng gần nhất
        for ($i = 5; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $monthlyData[$month] = 0.0;
        }

        // Tính doanh thu theo tháng (chỉ tính đơn completed)
        foreach ($bookings as $booking) {
            $status = strtolower($booking['status'] ?? '');
            if ($status === Booking::STATUS_COMPLETED && !empty($booking['created_at'])) {
                $bookingMonth = date('Y-m', strtotime($booking['created_at']));
                if (isset($monthlyData[$bookingMonth])) {
                    $monthlyData[$bookingMonth] += (float)($booking['service_price'] ?? 0);
                }
            }
        }

        return $monthlyData;
    }

    /**
     * Tính giá trị đơn hàng trung bình (Average Order Value).
     *
     * @param array $bookings Danh sách đơn đặt
     * @return float Giá trị trung bình mỗi đơn (VNĐ)
     */
    private function calculateAverageOrderValue(array $bookings): float
    {
        $completedBookings = array_filter(
            $bookings,
            static fn(array $b): bool => strtolower($b['status'] ?? '') === Booking::STATUS_COMPLETED
        );

        if (empty($completedBookings)) {
            return 0.0;
        }

        $totalRevenue = array_sum(array_map(
            static fn(array $b): float => (float)($b['service_price'] ?? 0),
            $completedBookings
        ));

        return round($totalRevenue / count($completedBookings), 0);
    }

    /**
     * Tính tỷ lệ chuyển đổi (Conversion Rate).
     * = (Số đơn hoàn thành / Tổng số đơn) * 100
     *
     * @param array $bookings Danh sách đơn đặt
     * @return float Tỷ lệ chuyển đổi (%)
     */
    private function calculateConversionRate(array $bookings): float
    {
        if (empty($bookings)) {
            return 0.0;
        }

        $completedCount = count(array_filter(
            $bookings,
            static fn(array $b): bool => strtolower($b['status'] ?? '') === Booking::STATUS_COMPLETED
        ));

        return round(($completedCount / count($bookings)) * 100, 1);
    }

    /**
     * Tính tỷ lệ hoàn thành (không bao gồm đơn bị hủy).
     * = (Số đơn hoàn thành / (Tổng đơn - Đơn hủy)) * 100
     *
     * @param array $bookings Danh sách đơn đặt
     * @return float Tỷ lệ hoàn thành (%)
     */
    private function calculateCompletionRate(array $bookings): float
    {
        if (empty($bookings)) {
            return 0.0;
        }

        $cancelledCount = count(array_filter(
            $bookings,
            static fn(array $b): bool => strtolower($b['status'] ?? '') === Booking::STATUS_CANCELLED
        ));

        $completedCount = count(array_filter(
            $bookings,
            static fn(array $b): bool => strtolower($b['status'] ?? '') === Booking::STATUS_COMPLETED
        ));

        $effectiveTotal = count($bookings) - $cancelledCount;

        if ($effectiveTotal <= 0) {
            return 0.0;
        }

        return round(($completedCount / $effectiveTotal) * 100, 1);
    }

    private function calculateConfirmedRate(array $bookings): float
    {
        if (empty($bookings)) {
            return 0.0;
        }

        $confirmed = count(array_filter(
            $bookings,
            static fn(array $booking): bool => ($booking['status'] ?? '') === Booking::STATUS_CONFIRMED
        ));

        return round(($confirmed / count($bookings)) * 100, 1);
    }

    private function calculatePendingRate(array $bookings): float
    {
        if (empty($bookings)) {
            return 0.0;
        }

        $pending = count(array_filter(
            $bookings,
            static fn(array $booking): bool => ($booking['status'] ?? '') === Booking::STATUS_PENDING
        ));

        return round(($pending / count($bookings)) * 100, 1);
    }

    private function setSessionMessage(string $type, string $message): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION[$type] = $message;
    }

    private function redirect(string $path): void
    {
        header('Location: ' . $path);
        exit(0);
    }
}
