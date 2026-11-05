<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Router — maps URI paths to controller actions.
 *
 * Supports:
 *   - Static routes      GET /about  → [AboutController, 'index']
 *   - Named params       GET /fleet/{slug}
 *   - Optional segments  (append ? to segment)
 *   - 404 / 405 handlers
 */
class Router
{
    /** @var array<string, array<string, array{controller:string, action:string, middleware:string[]}>> */
    private array $routes = [];

    /** @var callable|null */
    private $notFoundHandler = null;

    /** @var callable|null */
    private $methodNotAllowedHandler = null;

    // -------------------------------------------------------------------------
    // Route registration helpers
    // -------------------------------------------------------------------------

    public function get(string $path, string $controller, string $action, array $middleware = []): void
    {
        $this->addRoute('GET', $path, $controller, $action, $middleware);
    }

    public function post(string $path, string $controller, string $action, array $middleware = []): void
    {
        $this->addRoute('POST', $path, $controller, $action, $middleware);
    }

    public function any(string $path, string $controller, string $action, array $middleware = []): void
    {
        foreach (['GET', 'POST'] as $method) {
            $this->addRoute($method, $path, $controller, $action, $middleware);
        }
    }

    private function addRoute(
        string $method,
        string $path,
        string $controller,
        string $action,
        array $middleware = []
    ): void {
        $this->routes[$method][$path] = [
            'controller' => $controller,
            'action'     => $action,
            'middleware' => $middleware,
        ];
    }

    // -------------------------------------------------------------------------
    // 404 / 405 handlers
    // -------------------------------------------------------------------------

    public function setNotFound(callable $handler): void
    {
        $this->notFoundHandler = $handler;
    }

    public function setMethodNotAllowed(callable $handler): void
    {
        $this->methodNotAllowedHandler = $handler;
    }

    // -------------------------------------------------------------------------
    // Dispatch
    // -------------------------------------------------------------------------

    /**
     * Dispatch the current HTTP request.
     */
    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri    = $this->parseUri();

        // Try to match against registered routes for this method
        $match = $this->match($method, $uri);

        if ($match !== null) {
            $this->runMiddleware($match['route']['middleware']);
            $this->callAction(
                $match['route']['controller'],
                $match['route']['action'],
                $match['params']
            );
            return;
        }

        // Check if the URI matches any OTHER method (→ 405)
        foreach ($this->routes as $m => $routes) {
            if ($m === $method) {
                continue;
            }
            if ($this->match($m, $uri) !== null) {
                $this->handleMethodNotAllowed();
                return;
            }
        }

        // 404
        $this->handleNotFound();
    }

    /**
     * Extract the clean URI path, strip query string, strip base path.
     */
    private function parseUri(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        // Strip query string
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);
        $uri = rtrim($uri, '/') ?: '/';
        return $uri;
    }

    /**
     * Try to match a URI against routes for the given method.
     * Returns ['route' => ..., 'params' => [...]] or null.
     *
     * @return array{route: array{controller:string,action:string,middleware:string[]}, params: array<string,string>}|null
     */
    private function match(string $method, string $uri): ?array
    {
        $routes = $this->routes[$method] ?? [];

        foreach ($routes as $pattern => $route) {
            $params = [];
            $regex  = $this->compilePattern($pattern);

            if (preg_match($regex, $uri, $matches)) {
                // Collect named captures
                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $params[$key] = $value;
                    }
                }
                return ['route' => $route, 'params' => $params];
            }
        }

        return null;
    }

    /**
     * Convert a route pattern like /fleet/{slug} into a regex.
     * Supports optional segments by appending ? to the segment name: {slug?}
     */
    private function compilePattern(string $pattern): string
    {
        // Escape forward slashes and dots
        $regex = preg_replace_callback('/\{(\w+)(\?)?\}/', function (array $m) {
            $name     = $m[1];
            $optional = isset($m[2]) && $m[2] === '?';
            $part     = "(?P<{$name}>[^/]+)";
            return $optional ? "(?:/{$part})?" : $part;
        }, $pattern);

        // Escape everything that isn't already regex
        $regex = str_replace('/', '\/', $regex ?? $pattern);
        return '/^' . $regex . '$/u';
    }

    // -------------------------------------------------------------------------
    // Middleware runner (simple callable-stack approach)
    // -------------------------------------------------------------------------

    private function runMiddleware(array $middleware): void
    {
        foreach ($middleware as $mw) {
            if (is_string($mw) && class_exists($mw)) {
                /** @var object $instance */
                $instance = new $mw();
                if (method_exists($instance, 'handle')) {
                    $instance->handle();
                }
            } elseif (is_callable($mw)) {
                $mw();
            }
        }
    }

    // -------------------------------------------------------------------------
    // Controller / action caller
    // -------------------------------------------------------------------------

    private function callAction(string $controller, string $action, array $params): void
    {
        if (!class_exists($controller)) {
            error_log("Router: Controller class not found: {$controller}");
            $this->handleNotFound();
            return;
        }

        $instance = new $controller();

        if (!method_exists($instance, $action)) {
            error_log("Router: Action not found: {$controller}::{$action}");
            $this->handleNotFound();
            return;
        }

        // Pass URL params array to the action method
        $instance->$action($params);
    }

    // -------------------------------------------------------------------------
    // Error handlers
    // -------------------------------------------------------------------------

    private function handleNotFound(): void
    {
        http_response_code(404);
        if ($this->notFoundHandler !== null) {
            ($this->notFoundHandler)();
        } else {
            echo '<h1>404 — Page Not Found</h1>';
        }
    }

    private function handleMethodNotAllowed(): void
    {
        http_response_code(405);
        if ($this->methodNotAllowedHandler !== null) {
            ($this->methodNotAllowedHandler)();
        } else {
            echo '<h1>405 — Method Not Allowed</h1>';
        }
    }
}
