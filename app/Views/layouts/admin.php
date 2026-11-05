<?php
/**
 * Admin panel layout.
 * Variables: $content (page HTML), $pageTitle, $adminName, $adminRole
 */
if (session_status() === PHP_SESSION_NONE && !headers_sent()) session_start();
$adminData = $_SESSION['admin'] ?? [];
$adminName = $adminData['name'] ?? $_SESSION['admin_name'] ?? 'Admin';
$adminRole = $adminData['role'] ?? $_SESSION['admin_role'] ?? 'staff';
$adminId   = $adminData['id']   ?? $_SESSION['admin_id']   ?? 0;

// Count unread enquiries for badge
$unreadCount = 0;
try {
    $db = App\Core\Database::getInstance();
    if ($db->isConnected()) {
        $unreadCount = (int) $db->fetchColumn("SELECT COUNT(*) FROM enquiries WHERE is_read = 0");
    }
} catch (\Throwable $e) {}

$flash = App\Core\Session::allFlash();
$currentUri = $_SERVER['REQUEST_URI'] ?? '';

if (!function_exists('adminMenuActive')) {
    function adminMenuActive(string $path): string {
        global $currentUri;
        return str_starts_with((string)($currentUri ?? ''), $path) ? 'active' : '';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle ?? 'Admin Panel') ?> | OM Trip & Travels</title>
    <meta name="robots" content="noindex,nofollow">
    <link rel="icon" href="/assets/images/favicon.png" type="image/png">

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="/assets/vendor/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="/assets/vendor/bootstrap-icons.min.css">
    <!-- Admin CSS -->
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body>

<!-- Sidebar Backdrop (mobile) -->
<div class="sidebar-backdrop"></div>

<!-- ========== SIDEBAR ========== -->
<aside id="admin-sidebar">
    <div class="sidebar-brand">
        <i class="bi bi-geo-alt-fill" style="color:#ff7820"></i>
        <div>
            <span>OM <span style="color:#ff7820">Trip</span> Admin</span>
            <small><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $adminRole))) ?></small>
        </div>
    </div>

    <nav class="sidebar-nav">
        <a href="/admin/dashboard" class="<?= adminMenuActive('/admin/dashboard') ?>">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <div class="sidebar-label">Enquiries</div>
        <a href="/admin/enquiries" class="<?= adminMenuActive('/admin/enquiries') ?>">
            <i class="bi bi-inbox-fill"></i> Enquiries
            <?php if ($unreadCount > 0): ?>
            <span class="badge bg-danger"><?= $unreadCount ?></span>
            <?php endif; ?>
        </a>

        <?php if ($adminRole === 'super_admin'): ?>
        <div class="sidebar-label">Fleet</div>
        <a href="/admin/vehicles" class="<?= adminMenuActive('/admin/vehicles') ?>">
            <i class="bi bi-truck-front"></i> Vehicles
        </a>
        <a href="/admin/categories" class="<?= adminMenuActive('/admin/categories') ?>">
            <i class="bi bi-tags-fill"></i> Categories
        </a>
        <a href="/admin/packages" class="<?= adminMenuActive('/admin/packages') ?>">
            <i class="bi bi-map-fill"></i> Tour Packages
        </a>
        <?php endif; ?>

        <div class="sidebar-label">Content</div>
        <a href="/admin/blog" class="<?= adminMenuActive('/admin/blog') ?>">
            <i class="bi bi-journal-richtext"></i> Blog Posts
        </a>
        <a href="/admin/blog-categories" class="<?= adminMenuActive('/admin/blog-categories') ?>">
            <i class="bi bi-folder-fill"></i> Blog Categories
        </a>

        <?php if ($adminRole === 'super_admin'): ?>
        <a href="/admin/testimonials" class="<?= adminMenuActive('/admin/testimonials') ?>">
            <i class="bi bi-stars"></i> Testimonials
        </a>
        <a href="/admin/gallery" class="<?= adminMenuActive('/admin/gallery') ?>">
            <i class="bi bi-images"></i> Gallery
        </a>
        <a href="/admin/faqs" class="<?= adminMenuActive('/admin/faqs') ?>">
            <i class="bi bi-question-circle-fill"></i> FAQs
        </a>
        <a href="/admin/banners" class="<?= adminMenuActive('/admin/banners') ?>">
            <i class="bi bi-image-fill"></i> Banners
        </a>

        <div class="sidebar-label">System</div>
        <a href="/admin/settings" class="<?= adminMenuActive('/admin/settings') ?>">
            <i class="bi bi-gear-fill"></i> Settings
        </a>
        <a href="/admin/activity-log" class="<?= adminMenuActive('/admin/activity-log') ?>">
            <i class="bi bi-clock-history"></i> Activity Log
        </a>
        <?php endif; ?>

        <div class="sidebar-label">Account</div>
        <a href="/admin/change-password" class="<?= adminMenuActive('/admin/change-password') ?>">
            <i class="bi bi-key-fill"></i> Change Password
        </a>
        <a href="/admin/logout">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </nav>

    <div style="padding:1rem 1.4rem; border-top:1px solid rgba(255,255,255,.08);">
        <small style="color:rgba(255,255,255,.35);">OM Trip and Travels &copy; <?= date('Y') ?></small>
    </div>
</aside>

<!-- ========== MAIN ========== -->
<main id="admin-main">

    <!-- Topbar -->
    <div class="admin-topbar">
        <div class="d-flex align-items-center gap-2">
            <button class="sidebar-toggle" aria-label="Toggle sidebar">
                <i class="bi bi-list"></i>
            </button>
            <h1 class="page-title"><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?></h1>
        </div>
        <div class="topbar-actions">
            <a href="/" target="_blank" class="btn btn-sm btn-outline-secondary" title="View site">
                <i class="bi bi-box-arrow-up-right"></i>
            </a>
            <div class="dropdown">
                <a href="#" class="admin-avatar dropdown-toggle text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false">
                    <?= strtoupper(substr($adminName, 0, 1)) ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><h6 class="dropdown-header"><?= htmlspecialchars($adminName) ?></h6></li>
                    <li><a class="dropdown-item" href="/admin/change-password"><i class="bi bi-key me-2"></i>Change Password</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="/admin/logout"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (!empty($flash)): ?>
    <div class="flash-container">
        <?php foreach ($flash as $type => $msg): ?>
        <div class="alert alert-<?= htmlspecialchars($type) ?> flash-msg alert-dismissible" role="alert">
            <?= htmlspecialchars($msg) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Page Content -->
    <div class="admin-content">
        <?= $content ?>
    </div>
</main>

<!-- Bootstrap JS -->
<script src="/assets/vendor/bootstrap.bundle.min.js"></script>
<!-- Chart.js -->
<script src="/assets/vendor/chart.umd.min.js"></script>
<!-- Admin JS -->
<script src="/assets/js/admin.js"></script>
</body>
</html>

