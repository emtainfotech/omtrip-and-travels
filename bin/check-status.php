<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Database;
use App\Models\Vehicle;
use App\Models\Admin;

echo "--- DATABASE CHECK ---\n";
$db = Database::getInstance()->getPdo();
echo "Connected driver: " . $db->getAttribute(PDO::ATTR_DRIVER_NAME) . "\n";

echo "\n--- ADMINS ---\n";
$admins = (new Admin())->all();
foreach ($admins as $a) {
    $ok = password_verify('Admin@123456', $a['password']) ? 'PASSWORD_MATCH_OK' : 'MISMATCH';
    echo "ID: {$a['id']}, Email: {$a['email']}, Role: {$a['role']}, Hash: {$a['password']}, Pass Check: {$ok}\n";
}

echo "\n--- SETTINGS ---\n";
$settings = (new App\Models\Setting())->getAllFlat();
echo "Business Name: " . ($settings['business_name'] ?? 'N/A') . "\n";
echo "Phone: " . ($settings['phone'] ?? 'N/A') . "\n";
echo "WhatsApp: " . ($settings['whatsapp_number'] ?? 'N/A') . "\n";
echo "\n--- VIEW RENDER TEST ---\n";
try {
    $_SERVER['REQUEST_URI'] = '/';
    $_SERVER['HTTP_HOST'] = '127.0.0.1:8000';
    \App\Core\View::setViewPath(dirname(__DIR__) . '/app/Views');
    \App\Core\View::setDefaultLayout('layouts/main');
    ob_start();
    (new \App\Controllers\HomeController())->index();
    $out = ob_get_clean();
    echo "Rendered length: " . strlen($out) . " bytes\n";
    echo "First 200 chars:\n" . substr($out, 0, 200) . "\n";
    echo "Contains <!DOCTYPE html>: " . (str_contains($out, '<!DOCTYPE html>') ? 'YES' : 'NO') . "\n";

    echo "\n--- ADMIN DASHBOARD RENDER TEST ---\n";
    $_SESSION['admin'] = [
        'id'    => 1,
        'name'  => 'Admin User',
        'email' => 'admin@omtripandtravels.com',
        'role'  => 'super_admin',
    ];
    ob_start();
    (new \App\Controllers\Admin\DashboardController())->index();
    $adminOut = ob_get_clean();
    echo "Admin Dashboard length: " . strlen($adminOut) . " bytes\n";
    echo "Admin contains 'Dashboard Overview': " . (str_contains($adminOut, 'Dashboard Overview') ? 'YES' : 'NO') . "\n";

    echo "\n--- ENQUIRIES SAVED IN DB ---\n";
    $enquiries = (new \App\Models\Enquiry())->all([], 'id DESC', 3);
    foreach ($enquiries as $eq) {
        echo "ID: {$eq['id']}, Name: {$eq['name']}, Phone: {$eq['phone']}, Route: {$eq['pickup_location']} -> {$eq['drop_location']}, Created: {$eq['created_at']}\n";
    }

    echo "\n--- TOUR PACKAGES IN DB ---\n";
    $pkgs = (new \App\Models\TourPackage())->all();
    foreach ($pkgs as $p) {
        echo "ID: {$p['id']}, Name: {$p['name']}, Slug: {$p['slug']}, Active: {$p['is_active']}\n";
    }
} catch (\Throwable $e) {
    echo "RENDER ERROR: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo $e->getTraceAsString() . "\n";
}
