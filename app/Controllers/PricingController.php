<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Models\Service;

/**
 * PricingController hiển thị thông tin bảng giá dịch vụ.
 */
final class PricingController
{
    /**
     * Hiển thị trang bảng giá với toàn bộ dịch vụ.
     */
    public function index(): void
    {
        $services = Service::all();

        View::render('pricing', [
            'services' => $services,
        ]);
    }
}
