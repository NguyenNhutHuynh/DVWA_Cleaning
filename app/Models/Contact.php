<?php

namespace App\Models;

class Contact
{
    /**
     * Get all contacts
     */
    public static function getAll()
    {
        return [
            [
                'id' => 1,
                'name' => 'Chị Lan',
                'email' => 'lan@example.com',
                'phone' => '0912345678',
                'subject' => 'Hỏi giá',
                'message' => 'Tôi muốn hỏi giá dịch vụ tổng vệ sinh cho căn hộ 100m². Có giảm giá cho hợp đồng dài hạn không?',
                'status' => 'replied',
                'created_at' => '2026-01-23 10:30:00'
            ],
            [
                'id' => 2,
                'name' => 'Anh Minh',
                'email' => 'minh.tran@company.com',
                'phone' => '0987654321',
                'subject' => 'Tư vấn',
                'message' => 'Công ty chúng tôi cần dịch vụ vệ sinh văn phòng hàng ngày. Có thể tư vấn gói phù hợp không?',
                'status' => 'replied',
                'created_at' => '2026-01-22 14:15:00'
            ],
            [
                'id' => 3,
                'name' => 'Chị Hạnh',
                'email' => 'hanh.ceo@agency.vn',
                'phone' => '0934567890',
                'subject' => 'Khiếu nại',
                'message' => 'Dịch vụ hôm qua không đạt tiêu chuẩn. Nhân viên đến muộn và chất lượng làm việc không tốt.',
                'status' => 'pending',
                'created_at' => '2026-01-24 11:00:00'
            ]
        ];
    }

    /**
     * Create a new contact message
     */
    public static function create($name, $email, $phone, $subject, $message)
    {
        $contacts = self::getAll();
        
        return [
            'id' => count($contacts) + 1,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'subject' => $subject,
            'message' => $message,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ];
    }

    /**
     * Get contact by ID
     */
    public static function getById($id)
    {
        $contacts = self::getAll();
        foreach ($contacts as $contact) {
            if ($contact['id'] == $id) {
                return $contact;
            }
        }
        return null;
    }

    /**
     * Update contact status
     */
    public static function updateStatus($id, $status)
    {
        return [
            'id' => $id,
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];
    }
}
