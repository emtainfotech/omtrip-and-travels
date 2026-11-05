<?php
declare(strict_types=1);

namespace App\Models;

class Faq extends BaseModel
{
    protected string $table = 'faqs';

    public function getActive(): array
    {
        return $this->db->fetchAll("SELECT * FROM faqs WHERE is_active = 1 ORDER BY sort_order ASC");
    }

    public function getGrouped(): array
    {
        $rows = $this->getActive();
        $grouped = [];
        foreach ($rows as $row) {
            $grouped[$row['category'] ?? 'General'][] = $row;
        }
        return $grouped;
    }
}

