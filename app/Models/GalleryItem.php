<?php
declare(strict_types=1);

namespace App\Models;

class GalleryItem extends BaseModel
{
    protected string $table = 'gallery_items';

    public function getActive(string $category = '', int $page = 1, int $perPage = 24): array
    {
        $sql = "SELECT * FROM gallery_items WHERE is_active = 1";
        $params = [];
        if ($category) {
            $sql .= " AND category = ?";
            $params[] = $category;
        }
        $total = (int) $this->db->fetchColumn("SELECT COUNT(*) FROM ({$sql}) AS t", $params);
        $sql .= " ORDER BY sort_order ASC LIMIT ? OFFSET ?";
        $params[] = $perPage;
        $params[] = ($page - 1) * $perPage;
        return ['data' => $this->db->fetchAll($sql, $params), 'total' => $total,
                'pages' => (int)ceil($total / $perPage), 'current' => $page];
    }

    public function getCategories(): array
    {
        $rows = $this->db->fetchAll("SELECT DISTINCT category FROM gallery_items WHERE is_active = 1 AND category IS NOT NULL ORDER BY category");
        return array_column($rows, 'category');
    }
}

