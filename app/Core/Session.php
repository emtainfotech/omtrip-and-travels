<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Session — thin wrapper around PHP sessions with CSRF and flash support.
 *
 * Usage:
 *   Session::start();
 *   Session::set('key', $value);
 *   Session::get('key', $default);
 *   Session::csrfToken();
 *   Session::verifyCsrf($token);    // throws on failure
 *   Session::flash('success', 'Saved!');
 *   Session::getFlash('success');
 */
class Session
{
    private static bool $started = false;

    // -------------------------------------------------------------------------
    // Boot
    // -------------------------------------------------------------------------

    /**
     * Start the session once with secure settings.
     */
    public static function start(): void
    {
        if (self::$started || session_status() === PHP_SESSION_ACTIVE) {
            self::$started = true;
            return;
        }

        $secure   = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
        $lifetime = 0; // expire on browser close

        session_set_cookie_params([
            'lifetime' => $lifetime,
            'path'     => '/',
            'domain'   => '',
            'secure'   => $secure,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        session_name('omtrip_sess');
        session_start();

        // Regenerate session ID periodically to prevent fixation
        if (!isset($_SESSION['_last_regenerated'])) {
            session_regenerate_id(true);
            $_SESSION['_last_regenerated'] = time();
        } elseif (time() - $_SESSION['_last_regenerated'] > 1800) {
            session_regenerate_id(true);
            $_SESSION['_last_regenerated'] = time();
        }

        self::$started = true;
    }

    // -------------------------------------------------------------------------
    // Basic get / set / forget
    // -------------------------------------------------------------------------

    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public static function forget(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function destroy(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        session_destroy();
        self::$started = false;
    }

    // -------------------------------------------------------------------------
    // CSRF
    // -------------------------------------------------------------------------

    /**
     * Return (and generate if needed) the CSRF token for the current session.
     */
    public static function csrfToken(): string
    {
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf_token'];
    }

    /**
     * Verify a submitted CSRF token.
     * Throws \RuntimeException on failure (caller should respond 403).
     */
    public static function verifyCsrf(?string $token): void
    {
        $expected = $_SESSION['_csrf_token'] ?? '';
        if (
            empty($token)
            || empty($expected)
            || !hash_equals($expected, $token)
        ) {
            http_response_code(403);
            throw new \RuntimeException('CSRF token mismatch.');
        }
    }

    /**
     * Render a hidden CSRF input field (for use in blade/plain PHP templates).
     */
    public static function csrfField(): string
    {
        $token = htmlspecialchars(self::csrfToken(), ENT_QUOTES, 'UTF-8');
        return "<input type=\"hidden\" name=\"_csrf_token\" value=\"{$token}\">";
    }

    // -------------------------------------------------------------------------
    // Flash messages
    // -------------------------------------------------------------------------

    /**
     * Store a one-time flash message (available on next request only).
     */
    public static function flash(string $key, string $message): void
    {
        $_SESSION['_flash'][$key] = $message;
    }

    /**
     * Retrieve and immediately remove a flash message.
     */
    public static function getFlash(string $key, string $default = ''): string
    {
        $value = $_SESSION['_flash'][$key] ?? $default;
        unset($_SESSION['_flash'][$key]);
        return $value;
    }

    /**
     * Check whether a flash message exists.
     */
    public static function hasFlash(string $key): bool
    {
        return isset($_SESSION['_flash'][$key]);
    }

    /**
     * Retrieve all flash messages (and clear them).
     *
     * @return array<string, string>
     */
    public static function allFlash(): array
    {
        $flash = $_SESSION['_flash'] ?? [];
        unset($_SESSION['_flash']);
        return $flash;
    }

    // -------------------------------------------------------------------------
    // Admin auth helpers
    // -------------------------------------------------------------------------

    public static function setAdmin(array $admin): void
    {
        self::set('admin', $admin);
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function getAdmin(): ?array
    {
        $admin = self::get('admin');
        return is_array($admin) ? $admin : null;
    }

    public static function isAdminLoggedIn(): bool
    {
        return self::getAdmin() !== null;
    }

    public static function logoutAdmin(): void
    {
        self::forget('admin');
    }
}
