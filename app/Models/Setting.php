<?php
declare(strict_types=1);

namespace App\Models;

/**
 * Setting model — key-value store for site configuration.
 */
class Setting extends BaseModel
{
    protected string $table = 'settings';
    private static array $cache = [];

    /**
     * Get a setting value by key with optional default.
     */
    public function get(string $key, string $default = ''): string
    {
        if (isset(self::$cache[$key])) {
            return self::$cache[$key];
        }

        $row = $this->db->fetchOne("SELECT value FROM settings WHERE `key` = ? LIMIT 1", [$key]);
        $value = $row ? (string)($row['value'] ?? $default) : $default;
        self::$cache[$key] = $value;
        return $value;
    }

    /**
     * Set a setting value.
     */
    public function set(string $key, string $value): void
    {
        $exists = $this->db->fetchOne("SELECT id FROM settings WHERE `key` = ?", [$key]);
        if ($exists) {
            $this->db->update($this->table, ['value' => $value], ['key' => $key]);
        } else {
            $this->db->insert($this->table, ['key' => $key, 'value' => $value]);
        }
        self::$cache[$key] = $value;
    }

    /**
     * Get all settings grouped by group.
     */
    public function getAllGrouped(): array
    {
        $rows = $this->db->fetchAll("SELECT * FROM settings ORDER BY `group` ASC, id ASC");
        $grouped = [];
        foreach ($rows as $row) {
            $grouped[$row['group']][] = $row;
        }
        return $grouped;
    }

    /**
     * Get all settings as flat key => value array.
     */
    public function getAllFlat(): array
    {
        $rows = $this->db->fetchAll("SELECT `key`, `value` FROM settings");
        $flat = [];
        foreach ($rows as $row) {
            $flat[$row['key']] = $row['value'];
            self::$cache[$row['key']] = $row['value'];
        }
        return $flat;
    }

    /**
     * Update multiple settings at once.
     */
    public function updateMany(array $data): void
    {
        foreach ($data as $key => $value) {
            $this->set((string)$key, (string)$value);
        }
    }

    /**
     * Clear internal cache.
     */
    public static function clearCache(): void
    {
        self::$cache = [];
    }
}

