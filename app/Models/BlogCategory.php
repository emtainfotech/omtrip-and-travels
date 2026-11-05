<?php
declare(strict_types=1);

namespace App\Models;

class BlogCategory extends BaseModel
{
    protected string $table = 'blog_categories';

    public function getActive(): array
    {
        return $this->db->fetchAll(
            "SELECT c.*, COUNT(p.id) AS post_count FROM blog_categories c
             LEFT JOIN blog_posts p ON p.category_id = c.id AND p.status = 'published'
             WHERE c.is_active = 1
             GROUP BY c.id ORDER BY c.sort_order ASC"
        );
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->findBy('slug', $slug);
    }
}

