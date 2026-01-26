<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Contact;

class ContactController
{
    public function index()
    {
        return View::render('contact');
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect('/contact');
        }

        $name = $_POST['name'] ?? null;
        $email = $_POST['email'] ?? null;
        $phone = $_POST['phone'] ?? null;
        $subject = $_POST['subject'] ?? null;
        $message = $_POST['message'] ?? null;

        if (!$name || !$email || !$phone || !$subject || !$message) {
            $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin';
            return redirect('/contact');
        }

        // Kiểm tra email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Email không hợp lệ';
            return redirect('/contact');
        }

        // Tạo tin nhắn liên hệ
        $contact = Contact::create($name, $email, $phone, $subject, $message);

        // Trong môi trường sản xuất, gửi thông báo qua email
        $_SESSION['success'] = 'Cảm ơn bạn! Chúng tôi sẽ liên hệ lại trong 2 giờ.';
        return redirect('/contact');
    }
}
