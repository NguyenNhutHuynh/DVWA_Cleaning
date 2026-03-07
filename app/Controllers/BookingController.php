<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Models\Booking;
use App\Models\Service;

/**
 * BookingController xử lý tạo mới và quản lý lịch đặt dịch vụ.
 */
final class BookingController
{
    /**
     * Hiển thị toàn bộ đơn đặt của người dùng đang đăng nhập.
     */
    public function index(): void
    {
        $this->requireAuthentication();

        $userId = Auth::id();
        $bookings = Booking::getByUserId($userId);

        View::render('bookings', [
            'bookings' => $bookings,
        ]);
    }

    /**
     * Hiển thị biểu mẫu tạo lịch đặt cùng danh sách dịch vụ khả dụng.
     */
    public function create(): void
    {
        $this->requireAuthentication();

        $services = Service::all();
        $selectedServiceId = $this->extractServiceIdFromQuery();

        View::render('book', [
            'services' => $services,
            'selected' => $selectedServiceId,
        ]);
    }

    /**
     * Xử lý dữ liệu gửi lên từ biểu mẫu đặt lịch.
     * Kiểm tra dữ liệu và tạo đơn đặt mới.
     */
    public function store(): void
    {
        $this->requireAuthentication();
        $this->requirePostRequest();

        // Lấy dữ liệu từ biểu mẫu
        $serviceId = (int)($_POST['service'] ?? 0);
        $date = trim((string)($_POST['date'] ?? ''));
        $time = trim((string)($_POST['time'] ?? ''));
        $location = trim((string)($_POST['location'] ?? ''));
        $description = trim((string)($_POST['description'] ?? ''));
        $agreeTerms = isset($_POST['agree_terms']);

        // Kiểm tra các trường bắt buộc
        if (!$this->validateBookingData($serviceId, $date, $time, $location, $agreeTerms)) {
            $_SESSION['error'] = 'Please fill in all required information.';
            $this->redirect('/book');
            return;
        }

        // Kiểm tra ngày đặt phải ở tương lai
        if ($this->isDateInPast($date)) {
            $_SESSION['error'] = 'Booking date must be in the future.';
            $this->redirect('/book');
            return;
        }

        // Tạo đơn đặt lịch
        $bookingId = Booking::create(
            Auth::id(),
            $serviceId,
            $date,
            $time,
            $location,
            $description ?: null
        );

        $_SESSION['success'] = 'Booking created successfully! We will confirm within 2 hours.';
        $this->redirect('/bookings');
    }

    /**
     * Hủy một đơn đặt lịch.
     * Chỉ cho phép người dùng hủy các đơn của chính họ.
     *
     * @param int $id ID đơn đặt cần hủy
     */
    public function cancel(int $id): void
    {
        $this->requireAuthentication();

        $booking = Booking::getById($id);

        // Kiểm tra đơn đặt tồn tại và thuộc về người dùng hiện tại
        if ($booking === null || (int)$booking['user_id'] !== Auth::id()) {
            $_SESSION['error'] = 'Booking not found.';
            $this->redirect('/bookings');
            return;
        }

        // Cập nhật trạng thái đơn đặt
        Booking::updateStatus($id, Booking::STATUS_CANCELLED);

        $_SESSION['success'] = 'Booking cancelled successfully.';
        $this->redirect('/bookings');
    }

    /**
     * Lấy ID dịch vụ từ chuỗi truy vấn.
     */
    private function extractServiceIdFromQuery(): ?int
    {
        return isset($_GET['service']) ? (int)$_GET['service'] : null;
    }

    /**
     * Kiểm tra dữ liệu biểu mẫu đặt lịch.
     */
    private function validateBookingData(
        int $serviceId,
        string $date,
        string $time,
        string $location,
        bool $agreeTerms
    ): bool {
        return $serviceId > 0 && !empty($date) && !empty($time)
            && !empty($location) && $agreeTerms;
    }

    /**
     * Kiểm tra một chuỗi ngày có nằm trong quá khứ hay không.
     *
     * @param string $date Chuỗi ngày (YYYY-MM-DD)
     * @return bool True nếu ngày nhỏ hơn ngày hiện tại
     */
    private function isDateInPast(string $date): bool
    {
        $bookingDate = strtotime($date);
        $today = strtotime(date('Y-m-d'));
        return $bookingDate < $today;
    }

    /**
     * Đảm bảo phương thức request là POST.
     */
    private function requirePostRequest(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/book');
        }
    }

    /**
     * Đảm bảo người dùng đã được xác thực.
     */
    private function requireAuthentication(): void
    {
        if (!Auth::isAuthenticated()) {
            $this->redirect('/login');
        }
    }

    /**
     * Thực hiện chuyển hướng tới đường dẫn chỉ định.
     *
     * @param string $path Đường dẫn cần chuyển hướng tới
     */
    private function redirect(string $path): void
    {
        header("Location: $path");
        exit(0);
    }
}

