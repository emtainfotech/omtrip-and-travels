<?php
declare(strict_types=1);

namespace App\Models;

/**
 * TourPackage model.
 */
class TourPackage extends BaseModel
{
    protected string $table = 'tour_packages';

    public function getActive(int $limit = 0): array
    {
        $sql = "SELECT * FROM tour_packages WHERE is_active = 1 ORDER BY sort_order ASC, id ASC";
        if ($limit > 0) $sql .= " LIMIT {$limit}";
        return $this->db->fetchAll($sql);
    }

    public function getFeatured(int $limit = 4): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM tour_packages WHERE is_featured = 1 AND is_active = 1 ORDER BY sort_order ASC LIMIT ?",
            [$limit]
        );
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->db->fetchOne(
            "SELECT * FROM tour_packages WHERE slug = ? AND is_active = 1 LIMIT 1",
            [$slug]
        );
    }

    public function getVehicles(int $packageId): array
    {
        return $this->db->fetchAll("
            SELECT v.*, c.name AS category_name
            FROM package_vehicles pv
            JOIN vehicles v ON pv.vehicle_id = v.id
            JOIN vehicle_categories c ON v.category_id = c.id
            WHERE pv.package_id = ? AND v.is_active = 1
            ORDER BY v.sort_order ASC
        ", [$packageId]);
    }

    public function syncVehicles(int $packageId, array $vehicleIds): void
    {
        $this->db->query("DELETE FROM package_vehicles WHERE package_id = ?", [$packageId]);
        foreach ($vehicleIds as $vid) {
            $this->db->insert('package_vehicles', ['package_id' => $packageId, 'vehicle_id' => (int)$vid]);
        }
    }

    public function getImages(int $packageId): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM package_images WHERE package_id = ? ORDER BY sort_order ASC",
            [$packageId]
        );
    }

    public function getAllForAdmin(string $search = '', int $page = 1, int $perPage = 20): array
    {
        $sql = "SELECT * FROM tour_packages WHERE 1=1";
        $params = [];

        if ($search) {
            $sql .= " AND (name LIKE ? OR route LIKE ? OR destination LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }

        $total = (int) $this->db->fetchColumn("SELECT COUNT(*) FROM ({$sql}) AS t", $params);
        $sql .= " ORDER BY sort_order ASC, id ASC LIMIT ? OFFSET ?";
        $params[] = $perPage;
        $params[] = ($page - 1) * $perPage;

        return [
            'data'     => $this->db->fetchAll($sql, $params),
            'total'    => $total,
            'pages'    => (int) ceil($total / $perPage),
            'current'  => $page,
            'per_page' => $perPage,
        ];
    }
}

