<?php
declare(strict_types=1);

namespace App\Models;

/**
 * VehicleCategory model.
 */
class VehicleCategory extends BaseModel
{
    protected string $table = 'vehicle_categories';

    public function getActive(): array
    {
        return $this->all(['is_active' => 1], 'sort_order ASC, name ASC');
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->findBy('slug', $slug);
    }

    public function getWithVehicleCount(): array
    {
        return $this->db->fetchAll("
            SELECT c.*, COUNT(v.id) AS vehicle_count
            FROM vehicle_categories c
            LEFT JOIN vehicles v ON v.category_id = c.id AND v.is_active = 1
            WHERE c.is_active = 1
            GROUP BY c.id
            ORDER BY c.sort_order ASC
        ");
    }
}

