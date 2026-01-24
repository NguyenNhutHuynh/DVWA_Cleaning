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
            return redirect('/login');
        }

        $bookings = Booking::getByUserId(Auth::id());

        return View::render('bookings', [
            'bookings' => $bookings
        ]);
    }

    public function create()
    {
        if (!Auth::id()) {
            return redirect('/login');
        }

        $services = Service::all();

        return View::render('book', [
            'services' => $services
        ]);
    }

    public function store()
    {
        if (!Auth::id()) {
            return redirect('/login');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect('/book');
        }

        $service_id = $_POST['service'] ?? null;
        $date = $_POST['date'] ?? null;
        $time = $_POST['time'] ?? null;
        $location = $_POST['location'] ?? null;
        $description = $_POST['description'] ?? '';

        if (!$service_id || !$date || !$time || !$location) {
            $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin';
            return redirect('/book');
        }

        // Validate date is in the future
        if (strtotime($date) < strtotime(date('Y-m-d'))) {
            $_SESSION['error'] = 'Ngày đặt phải là trong tương lai';
            return redirect('/book');
        }

        // Create booking
        $booking = Booking::create(
            Auth::id(),
            $service_id,
            $date,
            $time,
            $location,
            $description
        );

        $_SESSION['success'] = 'Đặt lịch thành công! Chúng tôi sẽ liên hệ xác nhận trong 2 giờ.';
        return redirect('/bookings');
    }

    public function cancel($id)
    {
        if (!Auth::id()) {
            return redirect('/login');
        }

        $booking = Booking::getById($id);

        if (!$booking || $booking['user_id'] != Auth::id()) {
            $_SESSION['error'] = 'Không tìm thấy lịch đặt';
            return redirect('/bookings');
        }

        Booking::updateStatus($id, 'cancelled');

        $_SESSION['success'] = 'Hủy lịch đặt thành công';
        return redirect('/bookings');
    }
}
