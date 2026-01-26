<?php

namespace App\Models;

use App\Core\DB;
use PDO;

class Service
{
    /**
     * Get all active services from DB
     * @return array<int,array>
     */
    public static function all(): array
    {
        $stmt = DB::pdo()->query(
            "SELECT id, name, description, icon, duration_text AS duration, price, unit, minimum_price AS minimum, is_active
             FROM services WHERE is_active = 1 ORDER BY id ASC"
        );
        return $stmt->fetchAll() ?: [];
    }

    /**
     * Get a service by ID
     */
    public static function getById(int $id): ?array
    {
        $stmt = DB::pdo()->prepare(
            "SELECT id, name, description, icon, duration_text AS duration, price, unit, minimum_price AS minimum, is_active
             FROM services WHERE id = :id LIMIT 1"
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Admin: list all services (active and inactive)
     */
    public static function listAllAdmin(): array
    {
        $stmt = DB::pdo()->query("SELECT * FROM services ORDER BY id DESC");
        return $stmt->fetchAll() ?: [];
    }

    /**
     * Create a new service
     * @return int inserted ID
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
     * Update a service's fields
     */
    public static function update(int $id, array $data): bool
    {
        $fields = [
            'name' => 'name',
            'description' => 'description',
            'icon' => 'icon',
            'duration' => 'duration_text',
            'price' => 'price',
            'unit' => 'unit',
            'minimum' => 'minimum_price',
            'is_active' => 'is_active',
        ];
        $sets = [];
        $params = ['id' => $id];
        foreach ($fields as $key => $column) {
            if (array_key_exists($key, $data)) {
                $sets[] = "$column = :$key";
                $params[$key] = $data[$key];
            }
        }
        if (empty($sets)) return false;
        $sql = "UPDATE services SET " . implode(', ', $sets) . ", updated_at = NOW() WHERE id = :id";
        $stmt = DB::pdo()->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Toggle active flag
     */
    public static function toggleActive(int $id): bool
    {
        $stmt = DB::pdo()->prepare("UPDATE services SET is_active = 1 - is_active, updated_at = NOW() WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Delete a service (will fail if referenced by bookings)
     */
    public static function delete(int $id): bool
    {
        try {
            $stmt = DB::pdo()->prepare("DELETE FROM services WHERE id = :id");
            return $stmt->execute(['id' => $id]);
        } catch (\PDOException $e) {
            return false;
        }
    }
}
