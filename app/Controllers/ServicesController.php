<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Models\BookingReview;
use App\Models\Service;

/**
 * ServicesController xử lý danh sách dịch vụ và trang chi tiết dịch vụ.
 */
final class ServicesController
{
    /**
     * Hiển thị tất cả dịch vụ đang hoạt động hoặc kết quả tìm kiếm.
     */
    public function index(): void
    {
        $query = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
        
        if (!empty($query) && strlen($query) >= 2) {
            $services = Service::search($query);
        } else {
            $services = Service::all();
        }

        // Resolve image paths for all services
        foreach ($services as &$service) {
            $service['image_path'] = Service::resolveImagePath($service);
        }
        unset($service);

        View::render('services', [
            'services' => $services,
            'searchQuery' => $query,
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

        // Resolve image path
        $service['image_path'] = Service::resolveImagePath($service);

        $reviews = BookingReview::getByServiceId($serviceId);
        $totalReviews = count($reviews);
        $averageRating = $totalReviews > 0
            ? round(array_sum(array_map(static fn(array $review): int => (int)($review['rating'] ?? 0), $reviews)) / $totalReviews, 1)
            : null;

        View::render('service-detail', [
            'service' => $service,
            'reviews' => $reviews,
            'totalReviews' => $totalReviews,
            'averageRating' => $averageRating,
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

