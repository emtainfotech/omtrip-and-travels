<?php
declare(strict_types=1);

/**
 * Autoloader for OM Trip and Travels.
 *
 * Implements PSR-4 autoloading for the App\ namespace and loads helpers.
 * When Composer is run (`composer install`), Composer replaces this file with its own.
 */

// 1. PSR-4 Autoloader
spl_autoload_register(function (string $class) {
    $prefix = 'App\\';
    $baseDir = dirname(__DIR__) . '/app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

// 2. Load Global Helper Files
$helpers = [
    dirname(__DIR__) . '/app/Helpers/functions.php',
    dirname(__DIR__) . '/app/Helpers/upload.php',
    dirname(__DIR__) . '/app/Helpers/mailer.php',
];

foreach ($helpers as $helper) {
    if (file_exists($helper)) {
        require_once $helper;
    }
}

// 3. Fallback Dotenv loader if vlucas/phpdotenv is not installed
if (!class_exists('Dotenv\\Dotenv', false)) {
    require_once __DIR__ . '/dotenv_fallback.php';
}
