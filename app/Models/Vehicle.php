<?php
declare(strict_types=1);

namespace App\Models;

/**
 * Vehicle model.
 */
class Vehicle extends BaseModel
{
    protected string $table = 'vehicles';

    /**
     * Get all active vehicles with category name.
     */
    public function getActiveVehicles(int $categoryId = 0, int $seats = 0): array
    {
        $sql = "
            SELECT v.*, c.name AS category_name, c.slug AS category_slug
            FROM vehicles v
            JOIN vehicle_categories c ON v.category_id = c.id
            WHERE v.is_active = 1 AND c.is_active = 1
        ";
        $params = [];

        if ($categoryId > 0) {
            $sql .= " AND v.category_id = ?";
            $params[] = $categoryId;
        }

        if ($seats > 0) {
            $sql .= " AND v.seats = ?";
            $params[] = $seats;
        }

        $sql .= " ORDER BY v.sort_order ASC, v.id ASC";

        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Get featured vehicles.
     */
    public function getFeatured(int $limit = 6): array
    {
        return $this->db->fetchAll("
            SELECT v.*, c.name AS category_name
            FROM vehicles v
            JOIN vehicle_categories c ON v.category_id = c.id
            WHERE v.is_featured = 1 AND v.is_active = 1
            ORDER BY v.sort_order ASC
            LIMIT ?
        ", [$limit]);
    }

    /**
     * Find vehicle by slug with category info.
     */
    public function findBySlug(string $slug): ?array
    {
        return $this->db->fetchOne("
            SELECT v.*, c.name AS category_name, c.slug AS category_slug
            FROM vehicles v
            JOIN vehicle_categories c ON v.category_id = c.id
            WHERE v.slug = ? AND v.is_active = 1
            LIMIT 1
        ", [$slug]);
    }

    /**
     * Get vehicle images.
     */
    public function getImages(int $vehicleId): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM vehicle_images WHERE vehicle_id = ? ORDER BY sort_order ASC, is_primary DESC",
            [$vehicleId]
        );
    }

    /**
     * Get all vehicles for admin (with category).
     */
    public function getAllForAdmin(string $search = '', int $categoryId = 0, int $page = 1, int $perPage = 20): array
    {
        $sql = "
            SELECT v.*, c.name AS category_name
            FROM vehicles v
            LEFT JOIN vehicle_categories c ON v.category_id = c.id
            WHERE 1=1
        ";
        $params = [];

        if ($search) {
            $sql .= " AND (v.name LIKE ? OR v.slug LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }

        if ($categoryId > 0) {
            $sql .= " AND v.category_id = ?";
            $params[] = $categoryId;
        }

        $countSql = "SELECT COUNT(*) FROM ({$sql}) AS t";
        $total = (int) $this->db->fetchColumn($countSql, $params);

        $sql .= " ORDER BY v.sort_order ASC, v.id ASC LIMIT ? OFFSET ?";
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

    /**
     * Delete vehicle images.
     */
    public function deleteImages(int $vehicleId): void
    {
        $images = $this->getImages($vehicleId);
        foreach ($images as $img) {
            $path = BASE_PATH . '/public' . $img['image_path'];
            if (file_exists($path)) @unlink($path);
        }
        $this->db->query("DELETE FROM vehicle_images WHERE vehicle_id = ?", [$vehicleId]);
    }

    /**
     * Add image record.
     */
    public function addImage(int $vehicleId, string $imagePath, string $altText = '', int $sortOrder = 0, bool $isPrimary = false): int|false
    {
        return $this->db->insert('vehicle_images', [
            'vehicle_id'  => $vehicleId,
            'image_path'  => $imagePath,
            'alt_text'    => $altText,
            'sort_order'  => $sortOrder,
            'is_primary'  => $isPrimary ? 1 : 0,
        ]);
    }

    /**
     * Set primary image.
     */
    public function setPrimaryImage(int $vehicleId, int $imageId): void
    {
        $this->db->query("UPDATE vehicle_images SET is_primary = 0 WHERE vehicle_id = ?", [$vehicleId]);
        $this->db->query("UPDATE vehicle_images SET is_primary = 1 WHERE id = ? AND vehicle_id = ?", [$imageId, $vehicleId]);
    }

    /**
     * Get related vehicles (same category).
     */
    public function getRelated(int $vehicleId, int $categoryId, int $limit = 3): array
    {
        return $this->db->fetchAll("
            SELECT v.*, c.name AS category_name
            FROM vehicles v
            JOIN vehicle_categories c ON v.category_id = c.id
            WHERE v.category_id = ? AND v.id != ? AND v.is_active = 1
            ORDER BY v.sort_order ASC
            LIMIT ?
        ", [$categoryId, $vehicleId, $limit]);
    }
}

