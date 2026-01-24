<?php

namespace App\Models;

use App\Core\DB;

class Service
{
    private $db;

    public function __construct()
    {
        $this->db = new DB();
    }

    /**
     * Get all services
     */
    public static function all()
    {
        $services = [
            [
                'id' => 1,
                'name' => 'Tổng vệ sinh nhà',
                'description' => 'Dọn dẹp toàn bộ nhà phố, căn hộ, biệt thự với dụng cụ và hóa chất an toàn.',
                'icon' => '🧹',
                'duration' => '2-4 giờ',
                'price' => 50000,
                'unit' => '/m²',
                'minimum' => 2500000
            ],
            [
                'id' => 2,
                'name' => 'Giặt nệm & sofa',
                'description' => 'Thiết bị phun hút hiện đại, diệt khuẩn, khử mùi, khô nhanh chóng.',
                'icon' => '🛏️',
                'duration' => '1-2 giờ',
                'price' => 350000,
                'unit' => '/chiếc',
                'minimum' => 350000
            ],
            [
                'id' => 3,
                'name' => 'Vệ sinh sau xây dựng',
                'description' => 'Xử lý bụi mịn, sơn, xi; làm sạch kính, sàn, trần nhà hoàn toàn.',
                'icon' => '🧼',
                'duration' => '4-6 giờ',
                'price' => 60000,
                'unit' => '/m²',
                'minimum' => 3000000
            ],
            [
                'id' => 4,
                'name' => 'Khử khuẩn & diệt côn trùng',
                'description' => 'Phun khử khuẩn, diệt côn trùng an toàn cho trẻ nhỏ và vật nuôi.',
                'icon' => '🦠',
                'duration' => '1-2 giờ',
                'price' => 30000,
                'unit' => '/m²',
                'minimum' => 900000
            ],
            [
                'id' => 5,
                'name' => 'Cắt tỉa sân vườn',
                'description' => 'Cắt tỉa cây, chăm cỏ, lắp hệ thống tưới tự động, dọn lá rụng.',
                'icon' => '🌳',
                'duration' => '2-4 giờ',
                'price' => 40000,
                'unit' => '/m²',
                'minimum' => 1500000
            ],
            [
                'id' => 6,
                'name' => 'Chuyển nhà/Văn phòng',
                'description' => 'Trọn gói đóng gói, bốc xếp, vệ sinh trước và sau quá trình chuyển.',
                'icon' => '🚚',
                'duration' => '6-8 giờ',
                'price' => 15000000,
                'unit' => '/ngày',
                'minimum' => 15000000
            ]
        ];

        return $services;
    }

    /**
     * Get service by ID
     */
    public static function getById($id)
    {
        $services = self::all();
        foreach ($services as $service) {
            if ($service['id'] == $id) {
                return $service;
            }
        }
        return null;
    }
}
