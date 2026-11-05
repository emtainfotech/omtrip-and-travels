<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Controller — base class for all application controllers.
 *
 * Provides convenience methods for:
 *   - Rendering views
 *   - Redirecting
 *   - Sending JSON responses
 *   - Accessing the DB and Session
 *   - Validating CSRF tokens
 */
abstract class Controller
{
    protected Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // -------------------------------------------------------------------------
    // View rendering
    // -------------------------------------------------------------------------

    /**
     * Render a view template inside the default layout.
     *
     * @param string              $template  e.g. 'home/index'
     * @param array<string,mixed> $data      Variables available in the template
     * @param string|false        $layout    Override layout or FALSE for no layout
     */
    protected function view(
        string $template,
        array $data = [],
        string|false $layout = ''
    ): void {
        // Merge global data available to all views
        $data = array_merge($this->globalViewData(), $data);
        View::render($template, $data, $layout);
    }

    /**
     * Alias for view() to support both calling conventions.
     */
    protected function render(
        string $template,
        array $data = [],
        string|false $layout = ''
    ): void {
        $this->view($template, $data, $layout);
    }

    /**
     * Abort request with an HTTP error code.
     */
    protected function abort(int $code = 404, string $message = ''): never
    {
        http_response_code($code);
        $this->view("errors/{$code}", ['title' => "Error {$code}", 'message' => $message]);
        exit();
    }

    /**
     * Data injected into every view automatically.
     *
     * @return array<string,mixed>
     */
    protected function globalViewData(): array
    {
        return [
            'flash_success' => Session::getFlash('success'),
            'flash_error'   => Session::getFlash('error'),
            'flash_info'    => Session::getFlash('info'),
            'flash_warning' => Session::getFlash('warning'),
            'current_admin' => Session::getAdmin(),
            'current_url'   => currentUrl(),
        ];
    }

    // -------------------------------------------------------------------------
    // Redirects
    // -------------------------------------------------------------------------

    /**
     * Redirect to a URL and terminate execution.
     */
    protected function redirect(string $url, int $statusCode = 302): never
    {
        http_response_code($statusCode);
        header('Location: ' . $url);
        exit();
    }

    /**
     * Redirect back to the HTTP referrer (or fallback URL).
     */
    protected function back(string $fallback = '/'): never
    {
        $ref = $_SERVER['HTTP_REFERER'] ?? $fallback;
        $this->redirect($ref);
    }

    // -------------------------------------------------------------------------
    // JSON responses
    // -------------------------------------------------------------------------

    /**
     * Send a JSON response and terminate.
     *
     * @param array<string,mixed> $data
     */
    protected function json(array $data, int $statusCode = 200): never
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit();
    }

    /**
     * Send a JSON success response.
     *
     * @param array<string,mixed> $extra Additional keys to merge into the response
     */
    protected function jsonSuccess(string $message = 'OK', array $extra = []): never
    {
        $this->json(array_merge(['success' => true, 'message' => $message], $extra));
    }

    /**
     * Send a JSON error response.
     *
     * @param array<string,mixed> $extra Additional keys to merge into the response
     */
    protected function jsonError(string $message, array $extra = [], int $status = 400): never
    {
        $this->json(array_merge(['success' => false, 'message' => $message], $extra), $status);
    }

    // -------------------------------------------------------------------------
    // CSRF verification
    // -------------------------------------------------------------------------

    /**
     * Verify the CSRF token from $_POST. Sends 403 JSON on failure.
     */
    protected function verifyCsrf(): void
    {
        $token = $_POST['_csrf_token'] ?? null;
        try {
            Session::verifyCsrf($token);
        } catch (\RuntimeException $e) {
            if ($this->isAjax()) {
                $this->jsonError('Invalid or expired form token. Please refresh and try again.', [], 403);
            }
            http_response_code(403);
            echo '<h1>403 — Forbidden</h1><p>Invalid CSRF token. Please go back and try again.</p>';
            exit();
        }
    }

    // -------------------------------------------------------------------------
    // Input helpers
    // -------------------------------------------------------------------------

    /**
     * Get a sanitized POST value.
     */
    protected function post(string $key, string $default = ''): string
    {
        return trim((string) ($_POST[$key] ?? $default));
    }

    /**
     * Get a sanitized GET value.
     */
    protected function get(string $key, string $default = ''): string
    {
        return trim((string) ($_GET[$key] ?? $default));
    }

    /**
     * Get an integer POST value.
     */
    protected function postInt(string $key, int $default = 0): int
    {
        return (int) ($_POST[$key] ?? $default);
    }

    /**
     * Get an integer GET value.
     */
    protected function getInt(string $key, int $default = 0): int
    {
        return (int) ($_GET[$key] ?? $default);
    }

    // -------------------------------------------------------------------------
    // Request helpers
    // -------------------------------------------------------------------------

    protected function isPost(): bool
    {
        return ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST';
    }

    protected function isGet(): bool
    {
        return ($_SERVER['REQUEST_METHOD'] ?? '') === 'GET';
    }

    protected function isAjax(): bool
    {
        return ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest';
    }

    // -------------------------------------------------------------------------
    // Auth guard (admin panel)
    // -------------------------------------------------------------------------

    /**
     * Require admin to be logged in; redirect to login page otherwise.
     */
    protected function requireAdmin(): void
    {
        if (!Session::isAdminLoggedIn()) {
            Session::flash('error', 'Please log in to access the admin panel.');
            $this->redirect('/admin/login');
        }
    }

    /**
     * Require the logged-in admin to have super_admin role.
     */
    protected function requireSuperAdmin(): void
    {
        $this->requireAdmin();
        $admin = Session::getAdmin();
        if (($admin['role'] ?? '') !== 'super_admin') {
            http_response_code(403);
            $this->view('errors/403', ['title' => '403 Forbidden']);
            exit();
        }
    }
}
