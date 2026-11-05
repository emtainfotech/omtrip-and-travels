<?php
declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

/**
 * Database - PDO singleton wrapper.
 * All queries must use prepared statements only.
 * Never interpolate user data directly into SQL strings.
 */
class Database
{
    private static ?Database $instance = null;
    private ?PDO $pdo = null;
    private bool $connected = false;

    /**
     * Private constructor — use getInstance().
     */
    private function __construct()
    {
        $this->connect();
    }

    /**
     * Return the single Database instance (lazy init).
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Establish the PDO connection using .env values.
     */
    private function connect(): void
    {
        $connection = env('DB_CONNECTION', 'mysql');

        // Check if SQLite is explicitly requested or if MySQL is unconfigured
        $sqlitePath = dirname(__DIR__, 2) . '/storage/database.sqlite';

        if ($connection === 'sqlite' || (!empty($sqlitePath) && file_exists($sqlitePath) && (empty(env('DB_NAME')) || empty(env('DB_USER'))))) {
            try {
                $this->pdo = new PDO("sqlite:" . $sqlitePath);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                $this->connected = true;
                return;
            } catch (PDOException $e) {
                error_log('SQLite Connection Failed: ' . $e->getMessage());
            }
        }

        $host = env('DB_HOST', 'localhost');
        $port = env('DB_PORT', '3306');
        $name = env('DB_NAME', '');
        $user = env('DB_USER', '');
        $pass = env('DB_PASS', '');

        // If MySQL credentials aren't provided, try SQLite fallback
        if (empty($name) || empty($user)) {
            if (file_exists($sqlitePath)) {
                try {
                    $this->pdo = new PDO("sqlite:" . $sqlitePath);
                    $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                    $this->connected = true;
                    return;
                } catch (\Throwable $e) {}
            }
            return;
        }

        $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_TIMEOUT            => 1, // 1-second network timeout to prevent stalling
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
        ];

        try {
            $this->pdo       = new PDO($dsn, $user, $pass, $options);
            $this->connected = true;
        } catch (PDOException $e) {
            $this->connected = false;
            error_log('DB Connection Failed: ' . $e->getMessage());
            // Fallback to SQLite if available so local testing continues seamlessly
            if (file_exists($sqlitePath)) {
                try {
                    $this->pdo = new PDO("sqlite:" . $sqlitePath);
                    $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                    $this->connected = true;
                } catch (\Throwable $ex) {}
            }
        }
    }

    /**
     * Whether a live PDO connection is established.
     */
    public function isConnected(): bool
    {
        return $this->connected;
    }

    /**
     * Return raw PDO handle (use sparingly; prefer query/fetch helpers).
     */
    public function getPdo(): ?PDO
    {
        return $this->pdo;
    }

    /**
     * Execute a prepared statement and return the PDOStatement.
     * Returns false when not connected.
     *
     * @param  string $sql    SQL with named or positional placeholders
     * @param  array  $params Bound parameter values
     * @return \PDOStatement|false
     */
    public function query(string $sql, array $params = []): \PDOStatement|false
    {
        if (!$this->connected || $this->pdo === null) {
            return false;
        }

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log('DB Query Error: ' . $e->getMessage() . ' | SQL: ' . $sql);
            return false;
        }
    }

    /**
     * Fetch all rows matching the query.
     *
     * @return array<int, array<string, mixed>>
     */
    public function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->query($sql, $params);
        if ($stmt === false) {
            return [];
        }
        return $stmt->fetchAll() ?: [];
    }

    /**
     * Fetch a single row matching the query.
     *
     * @return array<string, mixed>|null
     */
    public function fetchOne(string $sql, array $params = []): ?array
    {
        $stmt = $this->query($sql, $params);
        if ($stmt === false) {
            return null;
        }
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Fetch a single column value from the first row.
     *
     * @return mixed
     */
    public function fetchColumn(string $sql, array $params = []): mixed
    {
        $stmt = $this->query($sql, $params);
        if ($stmt === false) {
            return null;
        }
        return $stmt->fetchColumn();
    }

    /**
     * Execute an INSERT/UPDATE/DELETE and return the number of affected rows.
     */
    public function execute(string $sql, array $params = []): int
    {
        $stmt = $this->query($sql, $params);
        if ($stmt === false) {
            return 0;
        }
        return $stmt->rowCount();
    }

    /**
     * Insert a row into a table and return the inserted ID or false.
     *
     * @param string $table
     * @param array<string, mixed> $data
     * @return int|false
     */
    public function insert(string $table, array $data): int|false
    {
        if (empty($data)) {
            return false;
        }

        $columns = array_keys($data);
        $fields  = implode('`, `', $columns);
        $placeholders = implode(', ', array_fill(0, count($columns), '?'));

        $sql = "INSERT INTO `{$table}` (`{$fields}`) VALUES ({$placeholders})";
        $stmt = $this->query($sql, array_values($data));

        if ($stmt === false) {
            return false;
        }

        return $this->lastInsertId();
    }

    /**
     * Update rows in a table matching conditions.
     *
     * @param string $table
     * @param array<string, mixed> $data
     * @param array<string, mixed> $where
     * @return int|false
     */
    public function update(string $table, array $data, array $where): int|false
    {
        if (empty($data) || empty($where)) {
            return false;
        }

        $setParts = [];
        $params   = [];

        foreach ($data as $col => $val) {
            $setParts[] = "`{$col}` = ?";
            $params[]   = $val;
        }

        $whereParts = [];
        foreach ($where as $col => $val) {
            $whereParts[] = "`{$col}` = ?";
            $params[]     = $val;
        }

        $sql = "UPDATE `{$table}` SET " . implode(', ', $setParts) . " WHERE " . implode(' AND ', $whereParts);
        $stmt = $this->query($sql, $params);

        if ($stmt === false) {
            return false;
        }

        return $stmt->rowCount();
    }

    /**
     * Delete rows from a table matching conditions.
     *
     * @param string $table
     * @param array<string, mixed> $where
     * @return int|false
     */
    public function delete(string $table, array $where): int|false
    {
        if (empty($where)) {
            return false;
        }

        $whereParts = [];
        $params     = [];

        foreach ($where as $col => $val) {
            $whereParts[] = "`{$col}` = ?";
            $params[]     = $val;
        }

        $sql = "DELETE FROM `{$table}` WHERE " . implode(' AND ', $whereParts);
        $stmt = $this->query($sql, $params);

        if ($stmt === false) {
            return false;
        }

        return $stmt->rowCount();
    }

    /**
     * Return the last inserted auto-increment ID.
     */
    public function lastInsertId(): int
    {
        if ($this->pdo === null) {
            return 0;
        }
        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Begin a transaction.
     */
    public function beginTransaction(): bool
    {
        if ($this->pdo === null) {
            return false;
        }
        return $this->pdo->beginTransaction();
    }

    /**
     * Commit a transaction.
     */
    public function commit(): bool
    {
        if ($this->pdo === null) {
            return false;
        }
        return $this->pdo->commit();
    }

    /**
     * Roll back a transaction.
     */
    public function rollBack(): bool
    {
        if ($this->pdo === null) {
            return false;
        }
        return $this->pdo->rollBack();
    }

    /**
     * Prevent cloning of the singleton.
     */
    private function __clone(): void {}

    /**
     * Prevent unserialization of the singleton.
     */
    public function __wakeup(): never
    {
        throw new \RuntimeException('Cannot unserialize a singleton.');
    }
}
