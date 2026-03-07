<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Models\Service;

/**
 * ServicesController xử lý danh sách dịch vụ và trang chi tiết dịch vụ.
 */
final class ServicesController
{
    /**
     * Hiển thị tất cả dịch vụ đang hoạt động.
     */
    public function index(): void
    {
        $services = Service::all();

        View::render('services', [
            'services' => $services,
        ]);
    }

    /**
     * Hiển thị trang chi tiết của một dịch vụ cụ thể.
     * Trả về trang 404 nếu không tìm thấy dịch vụ hoặc ID không hợp lệ.
     */
    public function show(): void
    {
        $serviceId = $this->extractServiceIdFromQuery();

        if ($serviceId <= 0) {
            View::render('404');
            return;
        }

        $service = Service::getById($serviceId);

        if ($service === null) {
            View::render('404');
            return;
        }

        View::render('service-detail', [
            'service' => $service,
        ]);
    }

    /**
     * Lấy và kiểm tra ID dịch vụ từ chuỗi truy vấn.
     */
    private function extractServiceIdFromQuery(): int
    {
        return isset($_GET['id']) ? (int)$_GET['id'] : 0;
    }
}

