<?php
declare(strict_types=1);

/**
 * router.php — Local PHP Development Server Router
 * Handles static asset streaming with correct MIME types and routes dynamic requests.
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH));
$publicDir = __DIR__ . '/public';
$filePath = $publicDir . $uri;

// 1. Static file handling: stream directly with appropriate Content-Type header
if ($uri !== '/' && file_exists($filePath) && !is_dir($filePath)) {
    $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    $mimes = [
        'css'   => 'text/css; charset=UTF-8',
        'js'    => 'application/javascript; charset=UTF-8',
        'svg'   => 'image/svg+xml',
        'png'   => 'image/png',
        'jpg'   => 'image/jpeg',
        'jpeg'  => 'image/jpeg',
        'webp'  => 'image/webp',
        'gif'   => 'image/gif',
        'ico'   => 'image/x-icon',
        'woff'  => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf'   => 'font/ttf',
        'otf'   => 'font/otf',
        'eot'   => 'application/vnd.ms-fontobject',
        'json'  => 'application/json; charset=UTF-8',
        'xml'   => 'application/xml; charset=UTF-8',
        'txt'   => 'text/plain; charset=UTF-8',
    ];

    $mime = $mimes[$ext] ?? (function_exists('mime_content_type') ? @mime_content_type($filePath) : 'application/octet-stream') ?: 'application/octet-stream';
    header('Content-Type: ' . $mime);
    header('Content-Length: ' . (string) filesize($filePath));
    header('Cache-Control: public, max-age=86400');
    readfile($filePath);
    exit;
}

// 2. Dispatch dynamic routes to public/index.php
$_SERVER['SCRIPT_NAME'] = '/index.php';
require_once $publicDir . '/index.php';
