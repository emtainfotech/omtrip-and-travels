<?php
declare(strict_types=1);

/**
 * public/index.php — Application Entry Point
 *
 * All HTTP requests are routed through this file via .htaccess.
 * Bootstraps the application and dispatches to the appropriate controller.
 */

// ---------------------------------------------------------------------------
// 1. Define root path constants
// ---------------------------------------------------------------------------
define('ROOT_PATH',   dirname(__DIR__));                 // e:\EMTA INFOTECH\OmTripAndTravels
define('APP_PATH',    ROOT_PATH . '/app');
define('PUBLIC_PATH', __DIR__);
define('VIEW_PATH',   APP_PATH . '/Views');
define('UPLOAD_PATH', PUBLIC_PATH . '/uploads');

// ---------------------------------------------------------------------------
// 2. Load Composer autoloader
// ---------------------------------------------------------------------------
$autoloader = ROOT_PATH . '/vendor/autoload.php';
if (!is_file($autoloader)) {
    http_response_code(500);
    echo '<h1>Application Error</h1><p>Dependencies not installed. Run <code>composer install</code>.</p>';
    exit(1);
}
require_once $autoloader;

// ---------------------------------------------------------------------------
// 3. Load .env file
// ---------------------------------------------------------------------------
use Dotenv\Dotenv;

$dotenvPath = ROOT_PATH;
if (is_file($dotenvPath . '/.env')) {
    $dotenv = Dotenv::createImmutable($dotenvPath);
    $dotenv->safeLoad();
}

// ---------------------------------------------------------------------------
// 4. Configure error reporting based on environment
// ---------------------------------------------------------------------------
if (env('APP_DEBUG', 'false') === 'true') {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(E_ALL);
    ini_set('log_errors', '1');
    ini_set('error_log', ROOT_PATH . '/storage/logs/php_errors.log');
}

// ---------------------------------------------------------------------------
// 5. Set default timezone
// ---------------------------------------------------------------------------
date_default_timezone_set('Asia/Kolkata');

// ---------------------------------------------------------------------------
// 6. Start session
// ---------------------------------------------------------------------------
use App\Core\Session;
Session::start();

// ---------------------------------------------------------------------------
// 7. Configure View path
// ---------------------------------------------------------------------------
use App\Core\View;
View::setViewPath(VIEW_PATH);
View::setDefaultLayout('layouts/main');

// ---------------------------------------------------------------------------
// 8. Register routes and dispatch
// ---------------------------------------------------------------------------
use App\Core\Router;

$router = new Router();

// ── Public routes ──────────────────────────────────────────────────────────

// Home
$router->get('/', 'App\Controllers\HomeController', 'index');

// Fleet / Vehicles
$router->get('/fleet', 'App\Controllers\FleetController', 'index');
$router->get('/fleet/{slug}', 'App\Controllers\FleetController', 'show');

// Tour Packages
$router->get('/packages', 'App\Controllers\PackageController', 'index');
$router->get('/packages/{slug}', 'App\Controllers\PackageController', 'show');

// Blog
$router->get('/blog', 'App\Controllers\BlogController', 'index');
$router->get('/blog/category/{slug}', 'App\Controllers\BlogController', 'category');
$router->get('/blog/tag/{slug}', 'App\Controllers\BlogController', 'tag');
$router->get('/blog/{slug}', 'App\Controllers\BlogController', 'show');

// Static pages
$router->get('/about', 'App\Controllers\PageController', 'about');
$router->get('/contact', 'App\Controllers\PageController', 'contact');
$router->get('/gallery', 'App\Controllers\PageController', 'gallery');
$router->get('/testimonials', 'App\Controllers\PageController', 'testimonials');
$router->get('/faq', 'App\Controllers\PageController', 'faq');
$router->get('/booking', 'App\Controllers\PageController', 'booking');
$router->get('/airport-transfers', 'App\Controllers\PageController', 'airportTransfers');
$router->get('/privacy', 'App\Controllers\PageController', 'privacy');
$router->get('/terms', 'App\Controllers\PageController', 'terms');
$router->get('/sitemap.xml', 'App\Controllers\SeoController', 'sitemap');
$router->get('/robots.txt', 'App\Controllers\SeoController', 'robots');

// Form submissions (POST only)
$router->post('/contact', 'App\Controllers\EnquiryController', 'contactSubmit');
$router->post('/booking', 'App\Controllers\EnquiryController', 'bookingSubmit');

// AJAX / API endpoints
$router->post('/api/click-log', 'App\Controllers\ApiController', 'clickLog');
$router->post('/api/enquiry',   'App\Controllers\ApiController', 'quickEnquiry');

// ── Admin routes ────────────────────────────────────────────────────────────

// Auth
$router->any('/admin/login',   'App\Controllers\Admin\AuthController', 'login');
$router->get('/admin/logout',  'App\Controllers\Admin\AuthController', 'logout');
$router->any('/admin/forgot',  'App\Controllers\Admin\AuthController', 'forgot');
$router->any('/admin/reset',   'App\Controllers\Admin\AuthController', 'reset');

// Dashboard
$router->get('/admin',           'App\Controllers\Admin\DashboardController', 'index');
$router->get('/admin/dashboard', 'App\Controllers\Admin\DashboardController', 'index');

// Enquiries
$router->get('/admin/enquiries',              'App\Controllers\Admin\EnquiryController', 'index');
$router->get('/admin/enquiries/{id}',         'App\Controllers\Admin\EnquiryController', 'show');
$router->post('/admin/enquiries/{id}/status', 'App\Controllers\Admin\EnquiryController', 'updateStatus');
$router->post('/admin/enquiries/{id}/note',   'App\Controllers\Admin\EnquiryController', 'addNote');
$router->post('/admin/enquiries/{id}/delete', 'App\Controllers\Admin\EnquiryController', 'delete');

// Vehicles
$router->get('/admin/vehicles',               'App\Controllers\Admin\VehicleController', 'index');
$router->any('/admin/vehicles/create',        'App\Controllers\Admin\VehicleController', 'create');
$router->any('/admin/vehicles/{id}/edit',     'App\Controllers\Admin\VehicleController', 'edit');
$router->post('/admin/vehicles/{id}/delete',  'App\Controllers\Admin\VehicleController', 'delete');
$router->post('/admin/vehicles/{id}/toggle',  'App\Controllers\Admin\VehicleController', 'toggle');

// Packages
$router->get('/admin/packages',               'App\Controllers\Admin\PackageController', 'index');
$router->any('/admin/packages/create',        'App\Controllers\Admin\PackageController', 'create');
$router->any('/admin/packages/{id}/edit',     'App\Controllers\Admin\PackageController', 'edit');
$router->post('/admin/packages/{id}/delete',  'App\Controllers\Admin\PackageController', 'delete');

// Blog
$router->get('/admin/blog',                   'App\Controllers\Admin\BlogController', 'index');
$router->get('/admin/blogs',                  'App\Controllers\Admin\BlogController', 'index');
$router->any('/admin/blog/create',            'App\Controllers\Admin\BlogController', 'create');
$router->any('/admin/blog/{id}/edit',         'App\Controllers\Admin\BlogController', 'edit');
$router->post('/admin/blog/{id}/delete',      'App\Controllers\Admin\BlogController', 'delete');

// Testimonials
$router->get('/admin/testimonials',               'App\Controllers\Admin\TestimonialController', 'index');
$router->any('/admin/testimonials/create',        'App\Controllers\Admin\TestimonialController', 'create');
$router->any('/admin/testimonials/{id}/edit',     'App\Controllers\Admin\TestimonialController', 'edit');
$router->post('/admin/testimonials/{id}/delete',  'App\Controllers\Admin\TestimonialController', 'delete');

// Gallery
$router->get('/admin/gallery',                'App\Controllers\Admin\GalleryController', 'index');
$router->any('/admin/gallery/upload',         'App\Controllers\Admin\GalleryController', 'upload');
$router->post('/admin/gallery/{id}/delete',   'App\Controllers\Admin\GalleryController', 'delete');

// Banners
$router->get('/admin/banners',                'App\Controllers\Admin\BannerController', 'index');
$router->any('/admin/banners/create',         'App\Controllers\Admin\BannerController', 'create');
$router->any('/admin/banners/{id}/edit',      'App\Controllers\Admin\BannerController', 'edit');
$router->post('/admin/banners/{id}/delete',   'App\Controllers\Admin\BannerController', 'delete');

// FAQs
$router->get('/admin/faqs',                   'App\Controllers\Admin\FaqController', 'index');
$router->any('/admin/faqs/create',            'App\Controllers\Admin\FaqController', 'create');
$router->any('/admin/faqs/{id}/edit',         'App\Controllers\Admin\FaqController', 'edit');
$router->post('/admin/faqs/{id}/delete',      'App\Controllers\Admin\FaqController', 'delete');

// Settings
$router->any('/admin/settings',               'App\Controllers\Admin\SettingsController', 'index');
$router->any('/admin/settings/seo',           'App\Controllers\Admin\SettingsController', 'seo');
$router->any('/admin/settings/whatsapp',      'App\Controllers\Admin\SettingsController', 'whatsapp');
$router->any('/admin/settings/scripts',       'App\Controllers\Admin\SettingsController', 'scripts');

// SEO
$router->get('/admin/seo',                    'App\Controllers\Admin\SeoController', 'index');
$router->any('/admin/seo/{page_key}',         'App\Controllers\Admin\SeoController', 'edit');

// Admins (super_admin only)
$router->get('/admin/admins',                 'App\Controllers\Admin\AdminUserController', 'index');
$router->any('/admin/admins/create',          'App\Controllers\Admin\AdminUserController', 'create');
$router->any('/admin/admins/{id}/edit',       'App\Controllers\Admin\AdminUserController', 'edit');
$router->post('/admin/admins/{id}/delete',    'App\Controllers\Admin\AdminUserController', 'delete');
$router->any('/admin/change-password',        'App\Controllers\Admin\AdminUserController', 'changePassword');
$router->get('/admin/activity-log',           'App\Controllers\Admin\AdminUserController', 'activityLog');

// Media / Image upload (AJAX)
$router->post('/admin/media/upload',          'App\Controllers\Admin\MediaController', 'upload');
$router->post('/admin/media/delete',          'App\Controllers\Admin\MediaController', 'delete');

// ── Error handlers ───────────────────────────────────────────────────────────

$router->setNotFound(function () {
    http_response_code(404);
    View::render('errors/404', ['title' => '404 — Page Not Found'], 'layouts/main');
});

$router->setMethodNotAllowed(function () {
    http_response_code(405);
    echo '<h1>405 — Method Not Allowed</h1>';
});

// ── Go ───────────────────────────────────────────────────────────────────────
$router->dispatch();
