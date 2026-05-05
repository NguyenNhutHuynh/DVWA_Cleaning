<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\View;
use App\Models\Contact;
use App\Models\User;

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
        $isAuthenticated = Auth::isAuthenticated();
        $currentUser = $isAuthenticated ? User::findById((int)Auth::id()) : null;
        $contactPrefill = [
            'name' => (string)($currentUser['name'] ?? ''),
            'email' => (string)($currentUser['email'] ?? ''),
            'phone' => (string)($currentUser['phone'] ?? ''),
        ];
        $userContacts = $isAuthenticated
            ? Contact::getByUserId((int)Auth::id(), (string)($currentUser['email'] ?? ''))
            : [];
        
        View::render('contact', [
            'isAuthenticated' => $isAuthenticated,
            'contactPrefill' => $contactPrefill,
            'previousContacts' => array_values($userContacts),
            'csrf' => Csrf::token(),
        ]);
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

        if (!Auth::isAuthenticated()) {
            $_SESSION['error'] = 'Bạn cần đăng nhập để gửi tin nhắn liên hệ.';
            $this->redirect('/login');
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
            $_SESSION['error'] = 'Vui lòng điền đầy đủ các trường bắt buộc.';
            $this->redirect('/contact');
            return;
        }

        // Kiểm tra định dạng email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Vui lòng nhập địa chỉ email hợp lệ.';
            $this->redirect('/contact');
            return;
        }

        // Tạo bản ghi liên hệ (lưu user_id của người gửi)
        Contact::create(
            Auth::id() ?? 0,
            $name,
            $email,
            $phone,
            $subject,
            $message
        );

        // Hiển thị thông báo thành công
        $_SESSION['success'] = 'Cảm ơn bạn! Tin nhắn của bạn đã được gửi. Chúng tôi sẽ liên hệ lại trong vòng 2 giờ.';
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
        $_SESSION['error'] = 'Phương thức yêu cầu không hợp lệ.';
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

