<?php
declare(strict_types=1);

/**
 * CLI script to create or reset an admin user.
 *
 * Usage:
 *   php bin/create-admin.php
 *   php bin/create-admin.php "Admin Name" admin@omtripandtravels.com "SecretPassword123!" super_admin
 */

if (php_sapi_name() !== 'cli') {
    http_response_code(403);
    echo "This script can only be executed via the command line.\n";
    exit(1);
}

define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/app/Core/Database.php';
require_once ROOT_PATH . '/app/Helpers/functions.php';

// Load .env if present
if (is_file(ROOT_PATH . '/.env')) {
    $lines = file(ROOT_PATH . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }
        [$k, $v] = explode('=', $line, 2);
        $k = trim($k);
        $v = trim($v, " \t\n\r\0\x0B\"'");
        $_ENV[$k] = $v;
        putenv("{$k}={$v}");
    }
}

use App\Core\Database;

$db = Database::getInstance();
if (!$db->isConnected()) {
    echo "❌ Error: Could not connect to the database.\n";
    echo "Please configure your .env file with valid DB credentials.\n";
    exit(1);
}

echo "=====================================================\n";
echo "    OM Trip and Travels — Admin Account Setup        \n";
echo "=====================================================\n\n";

$name     = $argv[1] ?? '';
$email    = $argv[2] ?? '';
$password = $argv[3] ?? '';
$role     = $argv[4] ?? 'super_admin';

if (empty($name)) {
    echo "Enter Admin Full Name [Super Admin]: ";
    $input = trim(fgets(STDIN));
    $name = $input ?: 'Super Admin';
}

if (empty($email)) {
    echo "Enter Admin Email: ";
    $email = trim(fgets(STDIN));
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "❌ Error: Invalid email address.\n";
    exit(1);
}

if (empty($password)) {
    echo "Enter Password (min 8 chars): ";
    $password = trim(fgets(STDIN));
}

if (strlen($password) < 8) {
    echo "❌ Error: Password must be at least 8 characters long.\n";
    exit(1);
}

if (!in_array($role, ['super_admin', 'staff'], true)) {
    $role = 'super_admin';
}

$passwordHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

// Check if admin already exists with this email
$existing = $db->fetchOne("SELECT id FROM admins WHERE email = ? LIMIT 1", [$email]);

if ($existing) {
    $db->update('admins', [
        'name'       => $name,
        'password'   => $passwordHash,
        'role'       => $role,
        'is_active'  => 1,
    ], ['id' => (int) $existing['id']]);
    echo "\n✅ Admin account updated successfully!\n";
    echo "ID:       {$existing['id']}\n";
} else {
    $id = $db->insert('admins', [
        'name'      => $name,
        'email'     => $email,
        'password'  => $passwordHash,
        'role'      => $role,
        'is_active' => 1,
    ]);
    echo "\n✅ Admin account created successfully!\n";
    echo "ID:       {$id}\n";
}

echo "Name:     {$name}\n";
echo "Email:    {$email}\n";
echo "Role:     {$role}\n";
echo "Login at: " . env('APP_URL', 'http://localhost') . "/admin/login\n\n";

