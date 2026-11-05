<?php
declare(strict_types=1);

/**
 * Global helper functions for OM Trip and Travels.
 *
 * Loaded via Composer's "files" autoload — available everywhere.
 * All helpers are wrapped in function_exists() guards.
 */

use App\Core\Database;
use App\Core\Session;

// =============================================================================
// Environment helpers
// =============================================================================

if (!function_exists('env')) {
    /**
     * Retrieve a value from $_ENV / getenv(), with an optional default.
     */
    function env(string $key, string $default = ''): string
    {
        $value = $_ENV[$key] ?? getenv($key);
        return ($value !== false && $value !== null) ? (string) $value : $default;
    }
}

// =============================================================================
// Settings helper (DB-backed)
// =============================================================================

if (!function_exists('setting')) {
    /**
     * Retrieve a site setting from the database (cached in a static array).
     * Falls back to $default if DB is unavailable or key doesn't exist.
     */
    function setting(string $key, string $default = ''): string
    {
        static $cache = [];
        static $loaded = false;

        if (!$loaded) {
            $db = Database::getInstance();
            if ($db->isConnected()) {
                $rows = $db->fetchAll('SELECT `key`, `value` FROM `settings`');
                foreach ($rows as $row) {
                    $cache[$row['key']] = $row['value'] ?? '';
                }
            }
            $loaded = true;
        }

        return array_key_exists($key, $cache) ? (string) $cache[$key] : $default;
    }
}

// =============================================================================
// HTML output helpers
// =============================================================================

if (!function_exists('e')) {
    /**
     * HTML-encode a value for safe output in templates.
     */
    function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('raw')) {
    /**
     * Return a value as-is (use only for already-sanitized HTML from the DB).
     * Signals intentional bypass of escaping — do not use with user input.
     */
    function raw(mixed $value): string
    {
        return (string) $value;
    }
}

// =============================================================================
// URL helpers
// =============================================================================

if (!function_exists('baseUrl')) {
    /**
     * Return the application base URL from APP_URL env.
     */
    function baseUrl(string $path = ''): string
    {
        $base = rtrim(env('APP_URL', 'http://localhost'), '/');
        return $base . '/' . ltrim($path, '/');
    }
}

if (!function_exists('currentUrl')) {
    /**
     * Return the full current URL including query string.
     */
    function currentUrl(): string
    {
        $scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
        $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $uri    = $_SERVER['REQUEST_URI'] ?? '/';
        return "{$scheme}://{$host}{$uri}";
    }
}

if (!function_exists('asset')) {
    /**
     * Return a versioned asset URL to bust caches.
     * e.g. asset('css/app.css') → /assets/css/app.css?v=1700000000
     */
    function asset(string $path): string
    {
        $filePath = dirname(__DIR__, 2) . '/public/assets/' . ltrim($path, '/');
        $version  = is_file($filePath) ? filemtime($filePath) : time();
        return '/assets/' . ltrim($path, '/') . '?v=' . $version;
    }
}

if (!function_exists('redirect')) {
    /**
     * Redirect to a URL immediately.
     */
    function redirect(string $url, int $code = 302): never
    {
        http_response_code($code);
        header('Location: ' . $url);
        exit();
    }
}

// =============================================================================
// String helpers
// =============================================================================

if (!function_exists('slug')) {
    /**
     * Convert a string to a URL-safe slug.
     */
    function slug(string $text): string
    {
        $text = mb_strtolower(trim($text));
        $text = preg_replace('/[^\p{L}\p{N}\s-]/u', '', $text) ?? $text;
        $text = preg_replace('/[\s_]+/', '-', $text) ?? $text;
        $text = preg_replace('/-+/', '-', $text) ?? $text;
        return trim($text, '-');
    }
}

if (!function_exists('excerpt')) {
    /**
     * Truncate text to a word boundary at $limit characters.
     */
    function excerpt(string $text, int $limit = 150, string $suffix = '…'): string
    {
        $text = strip_tags($text);
        if (mb_strlen($text) <= $limit) {
            return $text;
        }
        return rtrim(mb_substr($text, 0, $limit)) . $suffix;
    }
}

if (!function_exists('formatDate')) {
    /**
     * Format a date string or timestamp for display.
     */
    function formatDate(string|int $date, string $format = 'd M Y'): string
    {
        if (is_int($date)) {
            return date($format, $date);
        }
        $ts = strtotime($date);
        return $ts !== false ? date($format, $ts) : $date;
    }
}

if (!function_exists('numberFormat')) {
    /**
     * Format a number with Indian locale grouping (₹ friendly).
     */
    function numberFormat(float $number, int $decimals = 0): string
    {
        return number_format($number, $decimals, '.', ',');
    }
}

// =============================================================================
// WhatsApp helpers
// =============================================================================

if (!function_exists('whatsappUrl')) {
    /**
     * Build a WhatsApp API URL with a pre-filled message.
     * Uses the WHATSAPP_NUMBER env or business_name setting number.
     *
     * @param array<string,string> $placeholders Key/value pairs to replace {key} in template
     */
    function whatsappUrl(string $templateKey = 'wa_message_general', array $placeholders = []): string
    {
        $number  = env('WHATSAPP_NUMBER', setting('whatsapp_number', '919876543210'));
        $message = setting($templateKey, 'Hello OM Trip and Travels!');

        $placeholders['{page_url}'] = currentUrl();
        foreach ($placeholders as $placeholder => $value) {
            $message = str_replace($placeholder, $value, $message);
        }

        return 'https://wa.me/' . preg_replace('/\D/', '', $number) . '?text=' . rawurlencode($message);
    }
}

if (!function_exists('phoneUrl')) {
    /**
     * Build a tel: URL for the business phone number.
     */
    function phoneUrl(): string
    {
        $phone = env('PHONE_NUMBER', setting('phone', '+919876543210'));
        return 'tel:' . preg_replace('/\s+/', '', $phone);
    }
}

// =============================================================================
// Security helpers
// =============================================================================

if (!function_exists('csrfField')) {
    /**
     * Output a hidden CSRF input field.
     */
    function csrfField(): string
    {
        return Session::csrfField();
    }
}

if (!function_exists('csrfToken')) {
    /**
     * Return the current CSRF token string.
     */
    function csrfToken(): string
    {
        return Session::csrfToken();
    }
}

if (!function_exists('clientIp')) {
    /**
     * Get the best-guess real client IP address.
     */
    function clientIp(): string
    {
        $keys = [
            'HTTP_CF_CONNECTING_IP',   // Cloudflare
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_REAL_IP',
            'REMOTE_ADDR',
        ];
        foreach ($keys as $key) {
            if (!empty($_SERVER[$key])) {
                // X-Forwarded-For may be a comma-separated list
                $ip = trim(explode(',', $_SERVER[$key])[0]);
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }
        return '0.0.0.0';
    }
}

if (!function_exists('sanitizeFilename')) {
    /**
     * Strip path traversal and unsafe characters from an upload filename.
     */
    function sanitizeFilename(string $filename): string
    {
        $filename = basename($filename);
        $filename = preg_replace('/[^\w.\-]/', '_', $filename) ?? $filename;
        return ltrim($filename, '.');
    }
}

// =============================================================================
// Pagination helper
// =============================================================================

if (!function_exists('paginate')) {
    /**
     * Calculate pagination metadata.
     *
     * @return array{total:int, per_page:int, current_page:int, last_page:int, offset:int, has_prev:bool, has_next:bool}
     */
    function paginate(int $total, int $perPage = 10, ?int $currentPage = null): array
    {
        $currentPage = max(1, $currentPage ?? (int) ($_GET['page'] ?? 1));
        $lastPage    = max(1, (int) ceil($total / $perPage));
        $currentPage = min($currentPage, $lastPage);
        $offset      = ($currentPage - 1) * $perPage;

        return [
            'total'        => $total,
            'per_page'     => $perPage,
            'current_page' => $currentPage,
            'last_page'    => $lastPage,
            'offset'       => $offset,
            'has_prev'     => $currentPage > 1,
            'has_next'     => $currentPage < $lastPage,
        ];
    }
}

// =============================================================================
// Flash helpers (shortcut)
// =============================================================================

if (!function_exists('flashSuccess')) {
    function flashSuccess(string $message): void
    {
        Session::flash('success', $message);
    }
}

if (!function_exists('flashError')) {
    function flashError(string $message): void
    {
        Session::flash('error', $message);
    }
}

if (!function_exists('flashInfo')) {
    function flashInfo(string $message): void
    {
        Session::flash('info', $message);
    }
}

// =============================================================================
// Debug helper (dev only)
// =============================================================================

if (!function_exists('sanitize')) {
    /**
     * Sanitize general string input.
     */
    function sanitize(mixed $input): string
    {
        return trim(strip_tags((string) $input));
    }
}

if (!function_exists('is_active_page')) {
    /**
     * Check if current URI matches a path.
     */
    function is_active_page(string $path): string
    {
        $uri = strtok($_SERVER['REQUEST_URI'] ?? '/', '?');
        if ($path === '/') {
            return $uri === '/' ? 'active' : '';
        }
        return str_starts_with($uri, $path) ? 'active' : '';
    }
}

if (!function_exists('indianPhone')) {
    /**
     * Validate Indian phone number (10 digits, optional +91 or 0 prefix).
     */
    function indianPhone(string $phone): bool
    {
        $cleaned = preg_replace('/[^\d]/', '', $phone);
        if (str_starts_with($cleaned, '91') && strlen($cleaned) === 12) {
            $cleaned = substr($cleaned, 2);
        } elseif (str_starts_with($cleaned, '0') && strlen($cleaned) === 11) {
            $cleaned = substr($cleaned, 1);
        }
        return (bool) preg_match('/^[6-9]\d{9}$/', $cleaned);
    }
}

if (!function_exists('logActivity')) {
    /**
     * Log an admin action to activity_logs table.
     */
    function logActivity(?int $adminId, string $action, string $entityType = '', int $entityId = 0, string $desc = ''): void
    {
        try {
            $db = Database::getInstance();
            if ($db->isConnected()) {
                $db->insert('activity_logs', [
                    'admin_id'    => $adminId,
                    'action'      => $action,
                    'entity_type' => $entityType ?: null,
                    'entity_id'   => $entityId ?: null,
                    'description' => $desc ?: null,
                    'ip'          => clientIp(),
                ]);
            }
        } catch (\Throwable $e) {
            error_log('Failed to log activity: ' . $e->getMessage());
        }
    }
}

if (!function_exists('rateLimit')) {
    /**
     * File-based rate limiter per IP or custom key.
     */
    function rateLimit(string $key, int $maxAttempts = 5, int $decaySeconds = 60): bool
    {
        $storageDir = ROOT_PATH . '/storage/rate_limits';
        if (!is_dir($storageDir)) {
            @mkdir($storageDir, 0755, true);
        }
        $file = $storageDir . '/' . md5($key) . '.json';
        $now = time();
        $data = ['attempts' => 0, 'first_attempt' => $now];

        if (file_exists($file)) {
            $content = @file_get_contents($file);
            $parsed = $content ? json_decode($content, true) : null;
            if (is_array($parsed)) {
                $data = $parsed;
            }
        }

        if (($now - ($data['first_attempt'] ?? $now)) > $decaySeconds) {
            $data = ['attempts' => 1, 'first_attempt' => $now];
        } else {
            $data['attempts'] = ($data['attempts'] ?? 0) + 1;
        }

        @file_put_contents($file, json_encode($data));

        return $data['attempts'] <= $maxAttempts;
    }
}

if (!function_exists('dd')) {
    /**
     * Dump and die — only active when APP_DEBUG=true.
     */
    function dd(mixed ...$vars): never
    {
        if (env('APP_DEBUG', 'false') === 'true') {
            echo '<pre style="background:#1e1e1e;color:#d4d4d4;padding:1rem;font-size:13px;">';
            foreach ($vars as $var) {
                var_dump($var);
            }
            echo '</pre>';
        }
        exit();
    }
}
