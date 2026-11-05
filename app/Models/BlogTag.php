<?php
declare(strict_types=1);

namespace App\Models;

class BlogTag extends BaseModel
{
    protected string $table = 'blog_tags';

    public function getAll(): array
    {
        return $this->db->fetchAll("SELECT t.*, COUNT(pt.post_id) AS post_count FROM blog_tags t
            LEFT JOIN blog_post_tags pt ON pt.tag_id = t.id
            GROUP BY t.id ORDER BY t.name ASC");
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->findBy('slug', $slug);
    }

    public function findOrCreate(string $name): int
    {
        $slug = slugify($name);
        $existing = $this->findBy('slug', $slug);
        if ($existing) return (int) $existing['id'];
        return (int) $this->db->insert('blog_tags', ['name' => $name, 'slug' => $slug]);
    }
}

