<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\View;
use App\Models\Booking;
use App\Models\BookingMessage;
use App\Models\BookingPayment;
use App\Models\BookingProgress;
use App\Models\BookingReview;
use App\Models\Service;
use App\Models\User;

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

        foreach ($bookings as &$booking) {
            $booking['has_review'] = BookingReview::exists((int)$booking['id']);
        }

        View::render('customer/bookings', [
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
        $user = User::findById((int)Auth::id());
        $userAddress = trim((string)($user['address'] ?? ''));

        View::render('customer/book', [
            'services' => $services,
            'selected' => $selectedServiceId,
            'userAddress' => $userAddress,
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
        $quantity = $this->parseQuantity($_POST['quantity'] ?? null);
        $agreeTerms = isset($_POST['agree_terms']);

        // Kiểm tra các trường bắt buộc
        if (!$this->validateBookingData($serviceId, $date, $time, $location, $agreeTerms)) {
            $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin bắt buộc.';
            $this->redirect('/book');
            return;
        }

        $service = Service::getById($serviceId);
        if ($service === null) {
            $_SESSION['error'] = 'Dịch vụ không tồn tại.';
            $this->redirect('/book');
            return;
        }

        if ($quantity <= 0) {
            $_SESSION['error'] = 'Vui lòng nhập số lượng hợp lệ.';
            $this->redirect('/book');
            return;
        }

        $serviceUnit = (string)($service['unit'] ?? 'lần');
        if ($this->requiresIntegerQuantity($serviceUnit) && floor($quantity) !== $quantity) {
            $_SESSION['error'] = 'Đơn vị này chỉ chấp nhận số nguyên.';
            $this->redirect('/book');
            return;
        }

        $unitPrice = (float)($service['price'] ?? 0);
        $lineTotal = round($unitPrice * $quantity, 2);

        // Kiểm tra ngày đặt phải ở tương lai
        if ($this->isDateInPast($date)) {
            $_SESSION['error'] = 'Ngày đặt lịch phải là ngày trong tương lai.';
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
            $description ?: null,
            $quantity,
            $serviceUnit,
            $unitPrice,
            $lineTotal
        );

        $_SESSION['success'] = 'Đặt lịch thành công! Thành tiền tạm tính: ' . number_format((int)$lineTotal, 0, ',', '.') . 'đ. Chúng tôi sẽ xác nhận trong vòng 2 giờ.';
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
            $_SESSION['error'] = 'Không tìm thấy đơn đặt.';
            $this->redirect('/bookings');
            return;
        }

        // Cập nhật trạng thái đơn đặt
        Booking::updateStatus($id, Booking::STATUS_CANCELLED);

        $_SESSION['success'] = 'Hủy đơn đặt thành công.';
        $this->redirect('/bookings');
    }

    /**
     * Chi tiết booking để khách theo dõi tiến độ, chat, thanh toán.
     */
    public function detail(int $id): void
    {
        $this->requireAuthentication();

        $booking = Booking::getDetailById($id);
        if ($booking === null || (int)$booking['user_id'] !== (int)Auth::id()) {
            $_SESSION['error'] = 'Không tìm thấy đơn đặt.';
            $this->redirect('/bookings');
        }

        $progress = BookingProgress::byBookingId($id);
        $messages = BookingMessage::byBookingId($id);
        $payment = BookingPayment::byBookingId($id);
        $hasReview = BookingReview::exists($id);
        $review = BookingReview::getByBookingId($id);

        View::render('customer/booking-detail', [
            'booking' => $booking,
            'progress' => $progress,
            'messages' => $messages,
            'payment' => $payment,
            'hasReview' => $hasReview,
            'review' => $review,
            'csrf' => Csrf::token(),
        ]);
    }

    /**
     * Khách gửi tin nhắn cho worker.
     */
    public function sendMessage(int $id): void
    {
        $this->requireAuthentication();
        $this->requirePostRequest();
        $this->verifyCsrfToken();

        $booking = Booking::getById($id);
        if ($booking === null || (int)$booking['user_id'] !== (int)Auth::id()) {
            $this->redirect('/bookings');
        }

        $content = trim((string)($_POST['content'] ?? ''));
        if ($content === '') {
            $_SESSION['error'] = 'Tin nhắn không được để trống.';
            $this->redirect('/bookings/' . $id);
        }

        BookingMessage::add($id, (int)Auth::id(), $content);
        $this->redirect('/bookings/' . $id);
    }

    /**
     * Trang đánh giá khi đơn hoàn thành.
     */
    public function review(int $id): void
    {
        $this->requireAuthentication();

        $booking = Booking::getDetailById($id);
        if ($booking === null || (int)$booking['user_id'] !== (int)Auth::id()) {
            $this->redirect('/bookings');
        }

        if (($booking['status'] ?? '') !== Booking::STATUS_COMPLETED) {
            $_SESSION['error'] = 'Đơn đặt chưa hoàn thành.';
            $this->redirect('/bookings/' . $id);
        }

        if (BookingReview::exists($id)) {
            $_SESSION['success'] = 'Bạn đã đánh giá đơn này rồi.';
            $this->redirect('/bookings/' . $id);
        }

        View::render('customer/review', [
            'booking' => $booking,
            'csrf' => Csrf::token(),
        ]);
    }

    /**
     * Gửi đánh giá và bình luận.
     */
    public function submitReview(int $id): void
    {
        $this->requireAuthentication();
        $this->requirePostRequest();
        $this->verifyCsrfToken();

        $booking = Booking::getDetailById($id);
        if ($booking === null || (int)$booking['user_id'] !== (int)Auth::id()) {
            $this->redirect('/bookings');
        }

        if (BookingReview::exists($id)) {
            $this->redirect('/bookings/' . $id);
        }

        $rating = (int)($_POST['rating'] ?? 0);
        $comment = trim((string)($_POST['comment'] ?? ''));
        if ($rating < 1 || $rating > 5) {
            $_SESSION['error'] = 'Điểm đánh giá phải nằm trong khoảng từ 1 đến 5.';
            $this->redirect('/bookings/' . $id . '/review');
        }

        BookingReview::add(
            $id,
            (int)Auth::id(),
            (int)($booking['assigned_worker_id'] ?? 0),
            $rating,
            $comment !== '' ? $comment : null
        );

        $_SESSION['success'] = 'Cảm ơn bạn đã gửi đánh giá.';
        $this->redirect('/bookings/' . $id);
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

    private function verifyCsrfToken(): void
    {
        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            http_response_code(419);
            echo 'Mã bảo mật không hợp lệ. Vui lòng thử lại.';
            exit(1);
        }
    }

    private function parseQuantity(mixed $rawValue): float
    {
        $normalized = str_replace(',', '.', trim((string)$rawValue));
        return is_numeric($normalized) ? (float)$normalized : 0.0;
    }

    private function requiresIntegerQuantity(string $unit): bool
    {
        $normalized = mb_strtolower(trim($unit));
        return str_contains($normalized, 'phòng')
            || str_contains($normalized, 'phong')
            || str_contains($normalized, 'lần')
            || str_contains($normalized, 'lan')
            || str_contains($normalized, 'gói')
            || str_contains($normalized, 'goi');
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

