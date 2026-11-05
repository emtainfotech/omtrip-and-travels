<?php
declare(strict_types=1);

namespace App\Models;

/**
 * BlogPost model.
 */
class BlogPost extends BaseModel
{
    protected string $table = 'blog_posts';

    public function getPublished(int $page = 1, int $perPage = 9, int $categoryId = 0, int $tagId = 0, string $search = ''): array
    {
        $now = date('Y-m-d H:i:s');
        $sql = "
            SELECT p.*, c.name AS category_name, c.slug AS category_slug,
                   a.name AS author_name
            FROM blog_posts p
            LEFT JOIN blog_categories c ON p.category_id = c.id
            LEFT JOIN admins a ON p.author_id = a.id
            WHERE p.status = 'published' AND p.published_at <= ?
        ";
        $params = [$now];

        if ($categoryId > 0) {
            $sql .= " AND p.category_id = ?";
            $params[] = $categoryId;
        }

        if ($tagId > 0) {
            $sql .= " AND p.id IN (SELECT post_id FROM blog_post_tags WHERE tag_id = ?)";
            $params[] = $tagId;
        }

        if ($search) {
            $sql .= " AND (p.title LIKE ? OR p.excerpt LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }

        $total = (int) $this->db->fetchColumn("SELECT COUNT(*) FROM ({$sql}) AS t", $params);

        $sql .= " ORDER BY p.published_at DESC LIMIT ? OFFSET ?";
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

    public function findBySlug(string $slug): ?array
    {
        $now = date('Y-m-d H:i:s');
        return $this->db->fetchOne("
            SELECT p.*, c.name AS category_name, c.slug AS category_slug, a.name AS author_name
            FROM blog_posts p
            LEFT JOIN blog_categories c ON p.category_id = c.id
            LEFT JOIN admins a ON p.author_id = a.id
            WHERE p.slug = ? AND p.status = 'published' AND p.published_at <= ?
            LIMIT 1
        ", [$slug, $now]);
    }

    public function getLatest(int $limit = 3): array
    {
        $now = date('Y-m-d H:i:s');
        return $this->db->fetchAll("
            SELECT p.*, c.name AS category_name
            FROM blog_posts p
            LEFT JOIN blog_categories c ON p.category_id = c.id
            WHERE p.status = 'published' AND p.published_at <= ?
            ORDER BY p.published_at DESC
            LIMIT ?
        ", [$now, $limit]);
    }

    public function getTags(int $postId): array
    {
        return $this->db->fetchAll("
            SELECT t.* FROM blog_tags t
            JOIN blog_post_tags pt ON t.id = pt.tag_id
            WHERE pt.post_id = ?
        ", [$postId]);
    }

    public function syncTags(int $postId, array $tagIds): void
    {
        $this->db->query("DELETE FROM blog_post_tags WHERE post_id = ?", [$postId]);
        foreach ($tagIds as $tid) {
            $this->db->insert('blog_post_tags', ['post_id' => $postId, 'tag_id' => (int)$tid]);
        }
    }

    public function incrementViews(int $id): void
    {
        $this->db->query("UPDATE blog_posts SET views = views + 1 WHERE id = ?", [$id]);
    }

    public function getRelated(int $postId, int $categoryId, int $limit = 3): array
    {
        return $this->db->fetchAll("
            SELECT p.*, c.name AS category_name
            FROM blog_posts p
            LEFT JOIN blog_categories c ON p.category_id = c.id
            WHERE p.category_id = ? AND p.id != ? AND p.status = 'published'
            ORDER BY p.published_at DESC
            LIMIT ?
        ", [$categoryId, $postId, $limit]);
    }

    public function getAllForAdmin(string $search = '', string $status = '', int $categoryId = 0, int $page = 1, int $perPage = 20): array
    {
        $sql = "
            SELECT p.*, c.name AS category_name
            FROM blog_posts p
            LEFT JOIN blog_categories c ON p.category_id = c.id
            WHERE 1=1
        ";
        $params = [];

        if ($search) {
            $sql .= " AND (p.title LIKE ? OR p.slug LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }
        if ($status) {
            $sql .= " AND p.status = ?";
            $params[] = $status;
        }
        if ($categoryId > 0) {
            $sql .= " AND p.category_id = ?";
            $params[] = $categoryId;
        }

        $total = (int) $this->db->fetchColumn("SELECT COUNT(*) FROM ({$sql}) AS t", $params);
        $sql .= " ORDER BY p.created_at DESC LIMIT ? OFFSET ?";
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

