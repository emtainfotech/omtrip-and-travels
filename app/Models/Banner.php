<?php
declare(strict_types=1);

namespace App\Models;

class Banner extends BaseModel
{
    protected string $table = 'banners';

    public function getActive(): array
    {
        return $this->db->fetchAll("SELECT * FROM banners WHERE is_active = 1 ORDER BY sort_order ASC");
    }
}

