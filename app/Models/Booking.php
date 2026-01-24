<?php

namespace App\Models;

use App\Core\DB;

class Booking
{
    private $db;

    public function __construct()
    {
        $this->db = new DB();
    }

    /**
     * Create a new booking
     */
    public static function create($userId, $serviceId, $date, $time, $location, $description)
    {
        // Fake data storage - in production this would be a database
        $bookings = self::getAll();
        
        $booking = [
            'id' => count($bookings) + 1,
            'user_id' => $userId,
            'service_id' => $serviceId,
            'date' => $date,
            'time' => $time,
            'location' => $location,
            'description' => $description,
            'status' => 'pending', // pending, confirmed, completed, cancelled
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        return $booking;
    }

    /**
     * Get all bookings
     */
    public static function getAll()
    {
        $bookings = [
            [
                'id' => 1,
                'user_id' => 1,
                'service_id' => 1,
                'date' => '2026-01-25',
                'time' => '10:00',
                'location' => 'Quận 1, TP.HCM',
                'description' => 'Tổng vệ sinh căn hộ 100m²',
                'status' => 'confirmed',
                'created_at' => '2026-01-24 14:30:00',
                'updated_at' => '2026-01-24 15:00:00'
            ],
            [
                'id' => 2,
                'user_id' => 1,
                'service_id' => 2,
                'date' => '2026-01-26',
                'time' => '14:00',
                'location' => 'Quận 7, TP.HCM',
                'description' => 'Giặt sofa 3 chỗ + 1 ghế armchair',
                'status' => 'pending',
                'created_at' => '2026-01-24 10:00:00',
                'updated_at' => '2026-01-24 10:00:00'
            ],
            [
                'id' => 3,
                'user_id' => 2,
                'service_id' => 3,
                'date' => '2026-01-28',
                'time' => '08:00',
                'location' => 'Quận Gò Vấp, TP.HCM',
                'description' => 'Vệ sinh sau xây dựng căn hộ 120m²',
                'status' => 'pending',
                'created_at' => '2026-01-20 09:30:00',
                'updated_at' => '2026-01-20 09:30:00'
            ]
        ];

        return $bookings;
    }

    /**
     * Get booking by ID
     */
    public static function getById($id)
    {
        $bookings = self::getAll();
        foreach ($bookings as $booking) {
            if ($booking['id'] == $id) {
                return $booking;
            }
        }
        return null;
    }

    /**
     * Get bookings by user ID
     */
    public static function getByUserId($userId)
    {
        $bookings = self::getAll();
        $userBookings = [];
        foreach ($bookings as $booking) {
            if ($booking['user_id'] == $userId) {
                $userBookings[] = $booking;
            }
        }
        return $userBookings;
    }

    /**
     * Update booking status
     */
    public static function updateStatus($id, $status)
    {
        // In production, update in database
        return [
            'id' => $id,
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];
    }
}
