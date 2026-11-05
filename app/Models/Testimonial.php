<?php
declare(strict_types=1);

namespace App\Models;

class Testimonial extends BaseModel
{
    protected string $table = 'testimonials';

    public function getFeatured(int $limit = 6): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM testimonials WHERE is_featured = 1 AND is_active = 1 ORDER BY sort_order ASC LIMIT ?",
            [$limit]
        );
    }

    public function getActive(int $page = 1, int $perPage = 12): array
    {
        $total = (int) $this->db->fetchColumn("SELECT COUNT(*) FROM testimonials WHERE is_active = 1");
        $data = $this->db->fetchAll(
            "SELECT * FROM testimonials WHERE is_active = 1 ORDER BY sort_order ASC LIMIT ? OFFSET ?",
            [$perPage, ($page - 1) * $perPage]
        );
        return ['data' => $data, 'total' => $total, 'pages' => (int)ceil($total / $perPage), 'current' => $page];
    }
}

