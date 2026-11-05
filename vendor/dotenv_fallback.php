<?php
declare(strict_types=1);

namespace Dotenv;

/**
 * Lightweight fallback for Dotenv\Dotenv when vendor is not installed via Composer.
 */
class Dotenv
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public static function createImmutable(string $path): self
    {
        return new self($path);
    }

    public function safeLoad(): array
    {
        $file = rtrim($this->path, '/\\') . '/.env';
        if (!is_file($file)) {
            return [];
        }

        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $loaded = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
                continue;
            }

            [$name, $value] = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value, " \t\n\r\0\x0B\"'");

            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
            putenv("{$name}={$value}");
            $loaded[$name] = $value;
        }

        return $loaded;
    }

    public function load(): array
    {
        return $this->safeLoad();
    }
}

