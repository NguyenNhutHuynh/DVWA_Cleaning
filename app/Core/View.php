<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Bộ render View để nạp và hiển thị template PHP.
 * Cung cấp hàm escape HTML và bọc nội dung trong layout.
 */
final class View
{
    private const VIEWS_DIR = __DIR__ . '/../Views';
    private const LAYOUT_FILE = 'layout.php';

    /**
     * Escape chuỗi để xuất HTML an toàn.
     * Ngăn XSS bằng cách chuyển ký tự đặc biệt thành thực thể HTML.
     *
     * @param string|null $string Chuỗi cần escape
     * @return string Chuỗi đã escape
     */
    public static function escape(?string $string): string
    {
        return htmlspecialchars(
            $string ?? '',
            ENT_QUOTES | ENT_SUBSTITUTE,
            'UTF-8'
        );
    }

    /**
     * Hàm bí danh của escape() để tương thích ngược.
     *
     * @deprecated Nên dùng escape() thay thế
     */
    public static function e(?string $string): string
    {
        return self::escape($string);
    }

    /**
     * Render file template view với dữ liệu tùy chọn.
     * Nội dung view sẽ được bọc trong layout.
     *
     * @param string $view Tên file view (không gồm .php)
     * @param array $data Dữ liệu tùy chọn truyền vào view
     * @throws RuntimeException Nếu không tìm thấy file view
     */
    public static function render(string $view, array $data = []): void
    {
        $viewFile = self::getViewPath($view);
        self::validateViewFile($viewFile);
        self::loadView($viewFile, $data);
    }

    /**
     * Lấy đường dẫn đầy đủ tới file view.
     *
     * @param string $view Tên file view
     * @return string Đường dẫn đầy đủ của file
     */
    private static function getViewPath(string $view): string
    {
        return self::VIEWS_DIR . '/' . $view . '.php';
    }

    /**
     * Kiểm tra file view có tồn tại hay không.
     *
     * @param string $filePath Đường dẫn file cần kiểm tra
     * @throws RuntimeException Nếu file không tồn tại
     */
    private static function validateViewFile(string $filePath): void
    {
        if (!file_exists($filePath)) {
            http_response_code(500);
            echo 'View file not found: ' . basename($filePath);
            exit(1);
        }
    }

    /**
     * Nạp file view cùng dữ liệu và layout bao ngoài.
     *
     * @param string $viewFile Đường dẫn file view
     * @param array $data Dữ liệu cung cấp cho view
     */
    private static function loadView(string $viewFile, array $data): void
    {
        // Tạo biến từ dữ liệu để dùng trực tiếp trong phạm vi view
        foreach ($data as $key => $value) {
            $$key = $value;
        }

        // Nạp view bên trong layout
        require self::VIEWS_DIR . '/' . self::LAYOUT_FILE;
    }
}
