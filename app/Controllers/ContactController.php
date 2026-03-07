<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Models\Contact;

/**
 * ContactController xử lý hiển thị và gửi biểu mẫu liên hệ.
 */
final class ContactController
{
    /**
     * Hiển thị biểu mẫu liên hệ.
     */
    public function index(): void
    {
        View::render('contact');
    }

    /**
     * Xử lý dữ liệu gửi lên từ biểu mẫu liên hệ.
     * Kiểm tra và lưu lại tin nhắn liên hệ.
     */
    public function store(): void
    {
        // Chỉ chấp nhận request POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->handleInvalidMethod();
            return;
        }

        // Lấy và chuẩn hóa dữ liệu biểu mẫu
        $name = trim((string)($_POST['name'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $phone = trim((string)($_POST['phone'] ?? ''));
        $subject = trim((string)($_POST['subject'] ?? ''));
        $message = trim((string)($_POST['message'] ?? ''));

        // Kiểm tra các trường bắt buộc
        if (!$this->validateContactData($name, $email, $phone, $subject, $message)) {
            $_SESSION['error'] = 'Please fill in all required fields.';
            $this->redirect('/contact');
            return;
        }

        // Kiểm tra định dạng email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Please provide a valid email address.';
            $this->redirect('/contact');
            return;
        }

        // Tạo bản ghi liên hệ
        Contact::create($name, $email, $phone, $subject, $message);

        // Hiển thị thông báo thành công
        $_SESSION['success'] = 'Thank you! We will contact you back within 2 hours.';
        $this->redirect('/contact');
    }

    /**
     * Kiểm tra dữ liệu biểu mẫu liên hệ.
     *
     * @param string $name Trường họ tên
     * @param string $email Trường email
     * @param string $phone Trường số điện thoại
     * @param string $subject Trường tiêu đề
     * @param string $message Trường nội dung
     * @return bool True nếu tất cả trường đều có dữ liệu
     */
    private function validateContactData(
        string $name,
        string $email,
        string $phone,
        string $subject,
        string $message
    ): bool {
        return !empty($name) && !empty($email) && !empty($phone)
            && !empty($subject) && !empty($message);
    }

    /**
     * Xử lý khi phương thức HTTP không hợp lệ.
     */
    private function handleInvalidMethod(): void
    {
        $_SESSION['error'] = 'Invalid request method.';
        $this->redirect('/contact');
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

