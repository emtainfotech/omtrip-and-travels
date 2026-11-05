<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

/**
 * BaseModel — shared query helpers for all models.
 */
abstract class BaseModel
{
    protected Database $db;
    protected string $table = '';
    protected string $primaryKey = 'id';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Find all records with optional WHERE, ORDER, LIMIT.
     */
    public function all(array $where = [], string $orderBy = '', int $limit = 0, int $offset = 0): array
    {
        $sql = "SELECT * FROM `{$this->table}`";
        $params = [];

        if (!empty($where)) {
            $conditions = [];
            foreach ($where as $col => $val) {
                $conditions[] = "`{$col}` = ?";
                $params[] = $val;
            }
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }

        if ($limit > 0) {
            $sql .= " LIMIT {$limit}";
            if ($offset > 0) {
                $sql .= " OFFSET {$offset}";
            }
        }

        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Find a single record by primary key.
     */
    public function find(int $id): ?array
    {
        return $this->db->fetchOne(
            "SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = ? LIMIT 1",
            [$id]
        );
    }

    /**
     * Find a single record by a column value.
     */
    public function findBy(string $column, mixed $value): ?array
    {
        return $this->db->fetchOne(
            "SELECT * FROM `{$this->table}` WHERE `{$column}` = ? LIMIT 1",
            [$value]
        );
    }

    /**
     * Insert a new record.
     */
    public function create(array $data): int|false
    {
        return $this->db->insert($this->table, $data);
    }

    /**
     * Update a record by primary key.
     */
    public function update(int $id, array $data): int|false
    {
        return $this->db->update($this->table, $data, [$this->primaryKey => $id]);
    }

    /**
     * Delete a record by primary key.
     */
    public function delete(int $id): int|false
    {
        return $this->db->delete($this->table, [$this->primaryKey => $id]);
    }

    /**
     * Count records matching optional WHERE.
     */
    public function count(array $where = []): int
    {
        $sql = "SELECT COUNT(*) FROM `{$this->table}`";
        $params = [];

        if (!empty($where)) {
            $conditions = [];
            foreach ($where as $col => $val) {
                $conditions[] = "`{$col}` = ?";
                $params[] = $val;
            }
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        return (int) $this->db->fetchColumn($sql, $params);
    }

    /**
     * Paginate results.
     * Returns ['data' => [], 'total' => 0, 'pages' => 0, 'current' => 1, 'per_page' => 15]
     */
    public function paginate(int $page = 1, int $perPage = 15, array $where = [], string $orderBy = ''): array
    {
        $total = $this->count($where);
        $pages = (int) ceil($total / $perPage);
        $offset = ($page - 1) * $perPage;

        $data = $this->all($where, $orderBy, $perPage, $offset);

        return [
            'data'     => $data,
            'total'    => $total,
            'pages'    => $pages,
            'current'  => $page,
            'per_page' => $perPage,
        ];
    }
}

