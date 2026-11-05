<?php
declare(strict_types=1);

namespace App\Core;

/**
 * View — simple PHP template renderer.
 *
 * Templates live in app/Views/
 * Layout files live in app/Views/layouts/
 *
 * Usage inside a controller:
 *   View::render('home/index', ['title' => 'Home', 'vehicles' => $data]);
 *   View::render('home/index', $data, 'layouts/main');  // with custom layout
 *
 * Inside a template, $this is the View instance and all $data keys are available
 * as local variables via extract().
 *
 * Layouts receive a $content variable containing the rendered template output.
 */
class View
{
    /** Base path to all view files */
    private static string $viewPath = '';

    /** Default layout (relative to viewPath, without .php) */
    private static string $defaultLayout = 'layouts/main';

    /** Data currently being rendered (available as $key inside templates) */
    private array $data = [];

    // -------------------------------------------------------------------------
    // Static bootstrap
    // -------------------------------------------------------------------------

    public static function setViewPath(string $path): void
    {
        self::$viewPath = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    public static function setDefaultLayout(string $layout): void
    {
        self::$defaultLayout = $layout;
    }

    // -------------------------------------------------------------------------
    // Static entry point (called from controllers)
    // -------------------------------------------------------------------------

    /**
     * Render a template inside a layout.
     *
     * @param string            $template  Dot-notation or slash path, e.g. 'home/index'
     * @param array<string,mixed> $data    Variables passed to the template
     * @param string|false      $layout    Layout to wrap, or FALSE for standalone
     */
    public static function render(
        string $template,
        array $data = [],
        string|false $layout = ''
    ): void {
        $view = new self();
        $view->data = $data;

        // Resolve layout
        $useLayout = $layout === '' ? self::$defaultLayout : $layout;

        // Render template to string
        $content = $view->renderTemplate($template, $data);

        if ($useLayout === false) {
            echo $content;
            return;
        }

        // Wrap in layout
        $data['content'] = $content;
        echo $view->renderTemplate($useLayout, $data);
    }

    /**
     * Render a partial template (no layout) and return its output.
     *
     * @param string            $template
     * @param array<string,mixed> $data
     */
    public static function partial(string $template, array $data = []): string
    {
        $view = new self();
        $view->data = $data;
        return $view->renderTemplate($template, $data);
    }

    // -------------------------------------------------------------------------
    // Internal renderer
    // -------------------------------------------------------------------------

    /**
     * Render a template file and return its output as a string.
     *
     * @throws \RuntimeException if template file not found
     */
    private function renderTemplate(string $template, array $data = []): string
    {
        $file = self::$viewPath . str_replace('.', DIRECTORY_SEPARATOR, $template) . '.php';

        if (!is_file($file)) {
            throw new \RuntimeException("View template not found: {$file}");
        }

        // Extract data into local scope
        extract($data, EXTR_SKIP);

        ob_start();
        require $file;
        return (string) ob_get_clean();
    }

    // -------------------------------------------------------------------------
    // Template helper methods (callable as $this->e() etc. inside templates)
    // -------------------------------------------------------------------------

    /**
     * HTML-escape a value for safe output.
     */
    public function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * Include a partial sub-template from within a template.
     */
    public function include(string $template, array $data = []): void
    {
        echo self::partial($template, array_merge($this->data, $data));
    }

    /**
     * Return a setting value by key (shortcut for templates).
     */
    public function setting(string $key, string $default = ''): string
    {
        // Uses the global helper defined in functions.php
        if (function_exists('setting')) {
            return setting($key, $default);
        }
        return $default;
    }
}
