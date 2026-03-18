<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\DB;
use PDO;
use PDOException;

/**
 * Model Service dùng để quản lý danh mục dịch vụ.
 * Xử lý các thao tác CRUD cho dịch vụ vệ sinh.
 */
final class Service
{
    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 0;

    /**
     * Lấy toàn bộ dịch vụ đang hoạt động từ cơ sở dữ liệu.
     *
     * @return array Danh sách dịch vụ đang hoạt động với các trường cần thiết
     */
    public static function all(): array
    {
        $stmt = DB::pdo()->query(
            "SELECT id, name, description, icon, image_path, duration_text AS duration, price, unit, minimum_price AS minimum, is_active
             FROM services
             WHERE is_active = 1
             ORDER BY id ASC"
        );
        return $stmt->fetchAll() ?: [];
    }

    /**
     * Tìm dịch vụ theo ID.
     *
     * @param int $id ID dịch vụ
     * @return array|null Dữ liệu dịch vụ hoặc null nếu không tìm thấy
     */
    public static function getById(int $id): ?array
    {
        $stmt = DB::pdo()->prepare(
            "SELECT id, name, description, icon, image_path, duration_text AS duration, price, unit, minimum_price AS minimum, is_active
             FROM services
             WHERE id = :id
             LIMIT 1"
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Tìm kiếm dịch vụ theo keyword (tên hoặc mô tả).
     *
     * @param string $keyword Từ khóa tìm kiếm
     * @return array Danh sách dịch vụ khớp với từ khóa
     */
    public static function search(string $keyword): array
    {
        $searchTerm = '%' . $keyword . '%';
        $stmt = DB::pdo()->prepare(
            "SELECT id, name, description, icon, image_path, duration_text AS duration, price, unit, minimum_price AS minimum, is_active
             FROM services
             WHERE is_active = 1 AND (name LIKE :search_name OR description LIKE :search_desc)
             ORDER BY CASE 
                WHEN name LIKE :exact_match THEN 0
                ELSE 1
             END, name ASC"
        );
        $stmt->execute([
            'search_name' => $searchTerm,
            'search_desc' => $searchTerm,
            'exact_match' => $keyword
        ]);
        return $stmt->fetchAll() ?: [];
    }

    /**
     * Lấy toàn bộ dịch vụ cho trang quản trị (gồm cả bật/tắt).
     *
     * @return array Danh sách toàn bộ dịch vụ
     */
    public static function listAllAdmin(): array
    {
        $stmt = DB::pdo()->query("SELECT * FROM services ORDER BY id DESC");
        return $stmt->fetchAll() ?: [];
    }

    /**
     * Tạo mới một dịch vụ.
     *
     * @param array $data Mảng dữ liệu dịch vụ:
     *        - name: Tên dịch vụ
     *        - description: Mô tả dịch vụ
     *        - icon: Biểu tượng hoặc đường dẫn icon
     *        - duration: Thời lượng dịch vụ
     *        - price: Mức giá
     *        - unit: Đơn vị tính giá (ví dụ: theo giờ)
     *        - minimum: Giá tối thiểu
     *        - is_active: Trạng thái hoạt động (1 hoặc 0)
     * @return int ID dịch vụ vừa tạo
     */
    public static function create(array $data): int
    {
        $stmt = DB::pdo()->prepare(
            "INSERT INTO services (name, description, icon, duration_text, price, unit, minimum_price, is_active, created_at, updated_at)
             VALUES (:name, :description, :icon, :duration_text, :price, :unit, :minimum_price, :is_active, NOW(), NOW())"
        );
        $stmt->execute([
            'name' => (string)($data['name'] ?? ''),
            'description' => (string)($data['description'] ?? ''),
            'icon' => (string)($data['icon'] ?? ''),
            'duration_text' => (string)($data['duration'] ?? ''),
            'price' => (int)($data['price'] ?? 0),
            'unit' => (string)($data['unit'] ?? ''),
            'minimum_price' => (int)($data['minimum'] ?? 0),
            'is_active' => isset($data['is_active']) ? (int)$data['is_active'] : 1,
        ]);
        return (int)DB::pdo()->lastInsertId();
    }

    /**
     * Cập nhật dịch vụ theo dữ liệu đầu vào.
     * Chỉ cập nhật các trường có trong mảng dữ liệu.
     *
     * @param int $id ID dịch vụ cần cập nhật
     * @param array $data Các trường cần cập nhật
     * @return bool True nếu cập nhật thành công, false nếu không có trường nào để cập nhật
     */
    public static function update(int $id, array $data): bool
    {
        $fieldMapping = [
            'name' => 'name',
            'description' => 'description',
            'icon' => 'icon',
            'duration' => 'duration_text',
            'price' => 'price',
            'unit' => 'unit',
            'minimum' => 'minimum_price',
            'is_active' => 'is_active',
        ];

        $updates = [];
        $params = ['id' => $id];

        foreach ($fieldMapping as $inputKey => $columnName) {
            if (array_key_exists($inputKey, $data)) {
                $updates[] = "$columnName = :$inputKey";
                $params[$inputKey] = $data[$inputKey];
            }
        }

        if (empty($updates)) {
            return false;
        }

        $sql = "UPDATE services SET " . implode(', ', $updates) . ", updated_at = NOW() WHERE id = :id";
        $stmt = DB::pdo()->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Đảo trạng thái hoạt động của dịch vụ.
     *
     * @param int $id ID dịch vụ
     * @return bool True nếu đổi trạng thái thành công
     */
    public static function toggleActive(int $id): bool
    {
        $stmt = DB::pdo()->prepare(
            "UPDATE services SET is_active = 1 - is_active, updated_at = NOW() WHERE id = :id"
        );
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Xóa dịch vụ khỏi cơ sở dữ liệu.
     * Có thể thất bại nếu dịch vụ đang được đơn đặt tham chiếu (ràng buộc khóa ngoại).
     *
     * @param int $id ID dịch vụ cần xóa
     * @return bool True nếu xóa thành công, ngược lại là false
     */
    public static function delete(int $id): bool
    {
        try {
            $stmt = DB::pdo()->prepare("DELETE FROM services WHERE id = :id");
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $exception) {
            return false;
        }
    }

    /**
     * Cập nhật đường dẫn ảnh của dịch vụ.
     *
     * @param int $id ID dịch vụ
     * @param string $imagePath Đường dẫn đến ảnh
     * @return bool True nếu cập nhật thành công
     */
    public static function updateImage(int $id, string $imagePath): bool
    {
        $stmt = DB::pdo()->prepare(
            "UPDATE services SET image_path = :image_path, updated_at = NOW() WHERE id = :id"
        );
        return $stmt->execute(['id' => $id, 'image_path' => $imagePath]);
    }
    /**
     * Mảng ánh xạ tên dịch vụ thành tên file hình ảnh.
     * Sử dụng để fallback nếu database không có image_path.
     *
     * @return array Mảng mapping [service_name => image_filename]
     */
    private static function getServiceImagesMapping(): array
    {
        return [
            'combo tổng vệ sinh' => 'combo-tong-ve-sinh.png',
            'giặt nệm & sofa' => 'sofa.png',
            'giặt nệm và sofa' => 'sofa.png',
            'khử khuẩn' => 'khu-khuan.png',
            'vệ sinh nhà vệ sinh' => 'nha-ve-sinh.png',
            'vệ sinh phòng khách' => 'phong-khach.png',
            'vệ sinh phòng ngủ' => 'phong-ngu.png',
            'vệ sinh nhà bếp' => 'nha-bep.png',
            'combo chuyển nhà' => 'combo-chuyen-nha.png',
            'vệ sinh kính' => 'kinh.png',
            'combo cơ bản' => 'combo-co-ban.png',
            'combo gia đình' => 'combo-gia-dinh.png',
            'tổng vệ sinh nhà' => 'tong-ve-sinh-nha.png',
            'vệ sinh' => 'nha-ve-sinh.png',
        ];
    }

    /**
     * Lấy đường dẫn hình ảnh dịch vụ.
     * Nếu database có image_path, sử dụng nó; nếu không, fallback đến mảng mapping.
     *
     * @param array $service Dữ liệu dịch vụ từ database
     * @return string Đường dẫn hình ảnh
     */
    public static function resolveImagePath(array $service): string
    {
        // Ưu tiên sử dụng image_path từ database
        if (!empty($service['image_path'])) {
            return $service['image_path'];
        }

        // Fallback: Tìm hình ảnh từ mảng mapping dựa trên tên dịch vụ
        $serviceName = strtolower(trim($service['name'] ?? ''));
        $imageMapping = self::getServiceImagesMapping();

        // Tìm match chính xác (đã convert thành lowercase)
        if (isset($imageMapping[$serviceName])) {
            return '/assets/img/' . $imageMapping[$serviceName];
        }

        // Nếu không có match chính xác, tìm match theo từ khóa
        foreach ($imageMapping as $mapName => $filename) {
            if (strpos($serviceName, $mapName) !== false || strpos($mapName, $serviceName) !== false) {
                return '/assets/img/' . $filename;
            }
        }

        // Fallback cuối cùng: placeholder
        return '/assets/img/placeholder.png';
    }
}

