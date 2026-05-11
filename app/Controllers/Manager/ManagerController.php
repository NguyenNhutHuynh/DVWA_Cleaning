<?php

declare(strict_types=1);

namespace App\Controllers\Manager;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\View;
use App\Models\Booking;
use App\Models\BookingMessage;
use App\Models\BookingProgress;
use App\Models\BookingPayment;
use App\Models\BookingReport;
use App\Models\BookingReview;
use App\Models\PaymentTransaction;
use App\Models\User;

/**
 * ManagerController - Xử lý các nghiệp vụ quản trị dành cho Manager.
 * 
 * Quyền hạn:
 * - Quản lý và xem toàn bộ Booking
 * - Phân công Worker cho Booking
 * - Duyệt / Từ chối hồ sơ Worker
 * - Xem danh sách Customer và Worker
 * - Chat hỗ trợ với Worker
 * 
 * Không có quyền:
 * - Quản lý Dịch vụ (CRUD Service)
 * - Xem thống kê doanh thu
 * - Quản lý tài khoản Admin/Manager
 */
final class ManagerController extends BaseManagerController
{
    /**
     * Hiển thị trang Dashboard của Manager.
     * Hiển thị tổng quan: đơn đặt, worker, customer, doanh thu (nếu admin).
     */
    public function dashboard(): void
    {
        $this->requireManagerRole();

        $currentUser = User::findById((int)Auth::id());
        
        // Lấy dữ liệu thống kê
        $bookings = Booking::getAll();
        $users = User::listAll();
        
        // Tính toán thống kê
        $pendingBookings = count(array_filter($bookings, fn($b) => $b['status'] === 'pending'));
        $confirmedBookings = count(array_filter($bookings, fn($b) => $b['status'] === 'confirmed'));
        $inProgressBookings = count(array_filter($bookings, fn($b) => $b['status'] === 'in_progress'));
        $completedBookings = count(array_filter($bookings, fn($b) => $b['status'] === 'completed'));
        $totalBookings = count($bookings);
        
        $activeWorkers = count(array_filter(
            $users,
            fn($u) => $u['role'] === User::ROLE_WORKER && $u['approval_status'] === User::STATUS_ACTIVE
        ));
        $activeCustomers = count(array_filter(
            $users,
            fn($u) => $u['role'] === User::ROLE_CUSTOMER && $u['approval_status'] === User::STATUS_ACTIVE
        ));

        View::render('manager/dashboard', [
            'uid' => Auth::id(),
            'role' => Auth::role(),
            'name' => $currentUser['name'] ?? 'Manager',
            'stats' => [
                'totalBookings' => $totalBookings,
                'pendingBookings' => $pendingBookings,
                'confirmedBookings' => $confirmedBookings,
                'inProgressBookings' => $inProgressBookings,
                'completedBookings' => $completedBookings,
                'activeWorkers' => $activeWorkers,
                'activeCustomers' => $activeCustomers,
            ]
        ]);
    }

    /**
     * Hiển thị danh sách tất cả Booking.
     */
    public function bookings(): void
    {
        $this->requireManagerRole();

        $bookings = $this->enrichBookingsWithPaymentStatus(Booking::getAll());

        View::render('manager/bookings', [
            'bookings' => $bookings,
            'workers' => $this->getActiveWorkers(),
            'csrf' => Csrf::token(),
        ]);
    }

    /**
     * Hiển thị chi tiết một Booking.
     */
    public function bookingDetail(int $id): void
    {
        $this->requireManagerRole();

        $booking = Booking::getDetailById($id);
        if ($booking === null) {
            $this->setSessionMessage('error', 'Không tìm thấy đơn đặt #' . $id . '.');
            $this->redirect('/manager/bookings');
        }

        View::render('manager/booking-detail', [
            'booking' => $booking,
            'workers' => $this->getActiveWorkers(),
            'progress' => BookingProgress::byBookingId($id),
            'messages' => BookingMessage::byBookingId($id),
            'payment' => BookingPayment::byBookingId($id),
            'customerPayment' => PaymentTransaction::getLatestCustomerByBookingId($id),
            'customerPaidTransaction' => PaymentTransaction::getLatestPaidCustomerByBookingId($id),
            'report' => BookingReport::getByBookingId($id),
            'review' => BookingReview::getByBookingId($id),
            'csrf' => Csrf::token(),
        ]);
    }

    /**
     * Phân công Worker cho một Booking.
     */
    public function assignBooking(): void
    {
        $this->requireManagerRole();

        $bookingId = (int)($_POST['id'] ?? 0);
        $workerId = (int)($_POST['worker_id'] ?? 0);
        $returnTo = trim((string)($_POST['return_to'] ?? ''));
        $redirectTo = ($returnTo !== '' && str_starts_with($returnTo, '/manager/bookings'))
            ? $returnTo
            : '/manager/bookings';

        if ($bookingId <= 0 || $workerId <= 0) {
            $this->redirect($redirectTo);
        }

        $booking = Booking::getById($bookingId);
        if ($booking === null) {
            $this->setSessionMessage('error', 'Không tìm thấy đơn đặt cần phân công.');
            $this->redirect($redirectTo);
        }

        $worker = User::findById($workerId);
        if (
            $worker === null
            || ($worker['role'] ?? '') !== User::ROLE_WORKER
            || ($worker['approval_status'] ?? '') !== User::STATUS_ACTIVE
        ) {
            $this->setSessionMessage('error', 'Worker được chọn để phân công không hợp lệ.');
            $this->redirect($redirectTo);
        }

        if (!PaymentTransaction::hasSuccessfulCustomerPayment($bookingId)) {
            $this->setSessionMessage('error', 'Khách hàng chưa thanh toán đơn này. Chỉ được gán worker sau khi thanh toán thành công.');
            $this->redirect($redirectTo);
        }

        $assigned = Booking::assignWorker($bookingId, $workerId);
        if (!$assigned) {
            $this->setSessionMessage('error', 'Không thể phân công worker cho đơn này.');
            $this->redirect($redirectTo);
        }

        $currentStatus = (string)($booking['status'] ?? Booking::STATUS_PENDING);
        if (in_array($currentStatus, [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED], true)) {
            Booking::updateStatus($bookingId, Booking::STATUS_CONFIRMED);
        }

        $this->setSessionMessage('success', 'Đã phân công worker #' . $workerId . '. Đơn đã sẵn sàng để worker nhận việc.');
        $this->redirect($redirectTo);
    }

    /**
     * Xác nhận một Booking.
     */
    public function confirmBooking(): void
    {
        $this->requireManagerRole();

        $bookingId = (int)($_POST['id'] ?? 0);
        if ($bookingId <= 0) {
            $this->redirect('/manager/bookings');
        }

        $booking = Booking::getById($bookingId);
        if ($booking === null) {
            $this->setSessionMessage('error', 'Không tìm thấy đơn đặt.');
            $this->redirect('/manager/bookings');
        }

        if (empty($booking['assigned_worker_id'])) {
            $this->setSessionMessage('error', 'Vui lòng phân công worker trước khi xác nhận đơn đặt này.');
            $this->redirect('/manager/bookings');
        }

        if (!PaymentTransaction::hasSuccessfulCustomerPayment($bookingId)) {
            $this->setSessionMessage('error', 'Đơn chưa được khách thanh toán, chưa thể xác nhận.');
            $this->redirect('/manager/bookings');
        }

        Booking::updateStatus($bookingId, Booking::STATUS_CONFIRMED);
        $this->setSessionMessage('success', 'Đã xác nhận đơn đặt #' . $bookingId . '.');
        $this->redirect('/manager/bookings');
    }

    /**
     * Hủy một Booking.
     */
    public function cancelBooking(): void
    {
        $this->requireManagerRole();

        $bookingId = (int)($_POST['id'] ?? 0);
        if ($bookingId <= 0) {
            $this->redirect('/manager/bookings');
        }

        Booking::updateStatus($bookingId, Booking::STATUS_CANCELLED);
        $this->setSessionMessage('success', 'Đã hủy đơn đặt #' . $bookingId . '.');
        $this->redirect('/manager/bookings');
    }

    /**
     * Xem danh sách Worker.
     */
    public function workers(): void
    {
        $this->requireManagerRole();

        View::render('manager/workers', [
            'workers' => $this->getActiveWorkers(),
            'pendingWorkers' => User::listPendingWorkers(),
            'csrf' => Csrf::token(),
        ]);
    }

    /**
     * Xem chi tiết một Worker.
     */
    public function workerDetail(int $id): void
    {
        $this->requireManagerRole();

        if ($id <= 0) {
            $this->redirect('/manager/workers');
        }

        $worker = User::findById($id);
        if ($worker === null || $worker['role'] !== User::ROLE_WORKER) {
            $this->setSessionMessage('error', 'Không tìm thấy worker.');
            $this->redirect('/manager/workers');
        }

        View::render('manager/worker-detail', [
            'worker' => $worker,
            'csrf' => Csrf::token(),
        ]);
    }

    /**
     * Duyệt một Worker đang chờ xử lý.
     */
    public function approveWorker(): void
    {
        $this->requireManagerRole();

        $workerId = (int)($_POST['id'] ?? 0);
        if ($workerId <= 0) {
            $this->redirect('/manager/workers');
        }

        User::approveWorker($workerId, (int)Auth::id());
        $this->setSessionMessage('success', 'Duyệt worker thành công.');
        $this->redirect('/manager/workers');
    }

    /**
     * Từ chối một Worker đang chờ xử lý.
     */
    public function rejectWorker(): void
    {
        $this->requireManagerRole();

        $workerId = (int)($_POST['id'] ?? 0);
        if ($workerId <= 0) {
            $this->redirect('/manager/workers');
        }

        $reason = trim((string)($_POST['reason'] ?? ''));
        User::rejectWorker($workerId, (int)Auth::id(), $reason !== '' ? $reason : null);
        $this->setSessionMessage('success', 'Đã từ chối worker.');
        $this->redirect('/manager/workers');
    }

    /**
     * Xem danh sách Customer.
     */
    public function customers(): void
    {
        $this->requireManagerRole();

        View::render('manager/customers', [
            'customers' => $this->getActiveCustomers(),
            'csrf' => Csrf::token(),
        ]);
    }

    /**
     * Xem chi tiết một Customer.
     */
    public function customerDetail(int $id): void
    {
        $this->requireManagerRole();

        if ($id <= 0) {
            $this->redirect('/manager/customers');
        }

        $customer = User::findById($id);
        if ($customer === null || $customer['role'] !== User::ROLE_CUSTOMER) {
            $this->setSessionMessage('error', 'Không tìm thấy customer.');
            $this->redirect('/manager/customers');
        }

        View::render('manager/customer-detail', [
            'customer' => $customer,
            'csrf' => Csrf::token(),
        ]);
    }

    /**
     * Làm phong phú booking với thông tin trạng thái thanh toán.
     */
    private function enrichBookingsWithPaymentStatus(array $bookings): array
    {
        foreach ($bookings as &$booking) {
            $bookingId = (int)($booking['id'] ?? 0);
            $booking['hasPaidPayment'] = PaymentTransaction::hasSuccessfulCustomerPayment($bookingId);
        }
        return $bookings;
    }
}
