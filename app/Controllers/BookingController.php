<?php

namespace App\Controllers;

use App\Core\View;
use App\Core\Auth;
use App\Models\Booking;
use App\Models\Service;

class BookingController
{
    public function index()
    {
        if (!Auth::id()) {
            header('Location: /login');
            exit;
        }

        $bookings = Booking::getByUserId(Auth::id());

        return View::render('bookings', [
            'bookings' => $bookings
        ]);
    }

    public function create()
    {
        if (!Auth::id()) {
            header('Location: /login');
            exit;
        }

        $services = Service::all();
        $selected = isset($_GET['service']) ? (int)$_GET['service'] : null;

        return View::render('book', [
            'services' => $services,
            'selected' => $selected
        ]);
    }

    public function store()
    {
        if (!Auth::id()) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /book');
            exit;
        }

        $service_id = $_POST['service'] ?? null;
        $date = $_POST['date'] ?? null;
        $time = $_POST['time'] ?? null;
        $location = $_POST['location'] ?? null;
        $description = $_POST['description'] ?? '';
        $agree = isset($_POST['agree_terms']);

        if (!$service_id || !$date || !$time || !$location || !$agree) {
            $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin';
            header('Location: /book');
            exit;
        }

        // Kiểm tra ngày là trong tương lai
        if (strtotime($date) < strtotime(date('Y-m-d'))) {
            $_SESSION['error'] = 'Ngày đặt phải là trong tương lai';
            header('Location: /book');
            exit;
        }

        // Tạo đặt lịch
        $booking = Booking::create(
            Auth::id(),
            $service_id,
            $date,
            $time,
            $location,
            $description
        );

        $_SESSION['success'] = 'Đặt lịch thành công! Chúng tôi sẽ liên hệ xác nhận trong 2 giờ.';
        header('Location: /bookings');
        exit;
    }

    public function cancel($id)
    {
        if (!Auth::id()) {
            header('Location: /login');
            exit;
        }

        $booking = Booking::getById($id);

        if (!$booking || $booking['user_id'] != Auth::id()) {
            $_SESSION['error'] = 'Không tìm thấy lịch đặt';
            header('Location: /bookings');
            exit;
        }

        Booking::updateStatus($id, 'cancelled');

        $_SESSION['success'] = 'Hủy lịch đặt thành công';
        header('Location: /bookings');
        exit;
    }
}
