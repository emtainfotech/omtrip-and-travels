<?php
/**
 * Main public layout — wraps all public pages.
 * Variables available: $content (rendered page HTML), $pageTitle, $metaDescription, etc.
 */
$settings = function(string $k, string $d='') {
    static $m = null;
    if ($m === null) {
        try { $m = (new App\Models\Setting())->getAllFlat(); } catch(\Throwable $e) { $m = []; }
    }
    return htmlspecialchars($m[$k] ?? $d, ENT_QUOTES, 'UTF-8');
};

$phone   = $settings('phone', env('PHONE_NUMBER', '+91 98765 43210'));
$wa      = $settings('whatsapp_number', env('WHATSAPP_NUMBER', '919876543210'));
$bizName = $settings('business_name', 'OM Trip and Travels');
$appUrl  = rtrim(env('APP_URL', ''), '/');

// SEO defaults
$pageTitle       = $pageTitle       ?? $bizName . ' | Urbania & Tempo Traveller in Indore';
$metaDescription = $metaDescription ?? 'Book Urbania, Tempo Traveller, Innova, Fortuner on rent in Indore. Best group travel packages.';
$canonical       = $canonical       ?? $appUrl . $_SERVER['REQUEST_URI'];
$ogImage         = $ogImage         ?? $appUrl . '/assets/images/og-default.jpg';

$waMsg = $waMsg ?? $settings('wa_message_general', 'Hello OM Trip and Travels, I would like to enquire. Page: {page_url}');
$waMsg = str_replace('{page_url}', $canonical, $waMsg);
$waUrl = 'https://wa.me/' . $wa . '?text=' . rawurlencode($waMsg);

// Flash messages
$flash = App\Core\Session::allFlash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES) ?></title>
    <meta name="description" content="<?= htmlspecialchars($metaDescription, ENT_QUOTES) ?>">
    <link rel="canonical" href="<?= htmlspecialchars($canonical, ENT_QUOTES) ?>">

    <!-- Open Graph -->
    <meta property="og:type"        content="website">
    <meta property="og:title"       content="<?= htmlspecialchars($ogTitle ?? $pageTitle, ENT_QUOTES) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($ogDescription ?? $metaDescription, ENT_QUOTES) ?>">
    <meta property="og:image"       content="<?= htmlspecialchars($ogImage, ENT_QUOTES) ?>">
    <meta property="og:url"         content="<?= htmlspecialchars($canonical, ENT_QUOTES) ?>">
    <meta property="og:site_name"   content="<?= htmlspecialchars($bizName, ENT_QUOTES) ?>">

    <!-- Twitter Card -->
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="<?= htmlspecialchars($pageTitle, ENT_QUOTES) ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($metaDescription, ENT_QUOTES) ?>">
    <meta name="twitter:image"       content="<?= htmlspecialchars($ogImage, ENT_QUOTES) ?>">

    <!-- Favicon -->
    <link rel="icon" href="/assets/images/favicon.png" type="image/png">

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="/assets/vendor/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="/assets/vendor/bootstrap-icons.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">

    <!-- JSON-LD: LocalBusiness -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": ["TravelAgency","LocalBusiness"],
        "name": "<?= htmlspecialchars($bizName) ?>",
        "url": "<?= $appUrl ?>",
        "logo": "<?= $appUrl ?>/assets/images/logo.png",
        "image": "<?= $appUrl ?>/assets/images/og-default.jpg",
        "telephone": "<?= $settings('phone', '+919876543210') ?>",
        "email": "<?= $settings('email', '') ?>",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "<?= $settings('address', 'Indore') ?>",
            "addressLocality": "Indore",
            "addressRegion": "Madhya Pradesh",
            "postalCode": "<?= $settings('pincode', '452010') ?>",
            "addressCountry": "IN"
        },
        "openingHours": "Mo-Su 00:00-23:59",
        "priceRange": "₹₹",
        "description": "<?= $settings('tagline', 'Urbania & Tempo Traveller Specialists in Indore') ?>"
    }
    </script>

    <?php if ($schemaMarkup ?? null): ?>
    <script type="application/ld+json"><?= $schemaMarkup ?></script>
    <?php endif; ?>

    <?= $settings('header_scripts', '') ?>
</head>
<body>

<!-- ========== TOP UTILITY BAR ========== -->
<div class="top-bar">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="top-bar-item">
            <i class="bi bi-geo-alt-fill text-primary me-1"></i>
            <span>Indore, Madhya Pradesh</span>
        </div>
        <div class="top-bar-item d-none d-md-block fw-medium">
            <span>Tempo Traveller | Urbania | SUV | Car Rental for Group Travel</span>
        </div>
        <div class="top-bar-item">
            <i class="bi bi-clock me-1 text-primary"></i>
            <span>Mon – Sun, 6:00 AM – 10:00 PM</span>
        </div>
    </div>
</div>

<!-- ========== NAVBAR ========== -->
<nav class="navbar navbar-expand-lg sticky-top bg-white site-navbar">
    <div class="container">
        <!-- Mobile Left: Hamburger Toggler -->
        <button class="navbar-toggler border-0 p-0 me-2 d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" aria-label="Toggle navigation">
            <i class="bi bi-list fs-1 text-dark"></i>
        </button>

        <!-- Brand Logo -->
        <a class="navbar-brand d-flex align-items-center py-1" href="/">
            <img src="/assets/images/logo.svg" alt="OM Trip and Travels" height="42" class="me-2" onerror="this.src='/assets/images/logo.png'">
        </a>

        <!-- Mobile Right: WhatsApp direct icon -->
        <div class="d-flex align-items-center d-lg-none ms-auto">
            <a href="<?= htmlspecialchars($waUrl) ?>" target="_blank" rel="noopener" class="btn-wa-icon-sm" aria-label="WhatsApp" data-log-whatsapp="1">
                <i class="bi bi-whatsapp"></i>
            </a>
        </div>

        <!-- Desktop Nav Items -->
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav mx-auto align-items-center">
                <li class="nav-item"><a class="nav-link <?= is_active_page('/') ? 'nav-pill-active' : '' ?>" href="/">Home</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= is_active_page('/fleet') ?>" href="/fleet" data-bs-toggle="dropdown">Our Fleet</a>
                    <ul class="dropdown-menu border-0 shadow">
                        <li><a class="dropdown-item" href="/fleet">All Vehicles</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/fleet/force-urbania">Force Urbania (17 Seater)</a></li>
                        <li><a class="dropdown-item" href="/fleet/tempo-traveller">Tempo Traveller (12/17 Seater)</a></li>
                        <li><a class="dropdown-item" href="/fleet/innova-crysta">Innova Crysta</a></li>
                        <li><a class="dropdown-item" href="/fleet/toyota-fortuner">Toyota Fortuner</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= is_active_page('/packages') ?>" href="/packages" data-bs-toggle="dropdown">Tour Packages</a>
                    <ul class="dropdown-menu border-0 shadow">
                        <li><a class="dropdown-item" href="/packages">All Tour Packages</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/packages/indore-to-ujjain-mahakal-darshan">Indore to Ujjain Mahakal</a></li>
                        <li><a class="dropdown-item" href="/packages/indore-to-omkareshwar-jyotirlinga">Indore to Omkareshwar</a></li>
                        <li><a class="dropdown-item" href="/packages/maheshwar-mandu-heritage-tour">Maheshwar &amp; Mandu</a></li>
                        <li><a class="dropdown-item" href="/packages/pachmarhi-hill-station-tour">Pachmarhi Hill Station</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Routes</a>
                    <ul class="dropdown-menu border-0 shadow">
                        <li><a class="dropdown-item" href="/packages/indore-to-ujjain-mahakal-darshan">Indore to Ujjain Taxi</a></li>
                        <li><a class="dropdown-item" href="/packages/indore-to-omkareshwar-jyotirlinga">Indore to Omkareshwar</a></li>
                        <li><a class="dropdown-item" href="/airport-transfers">Indore Airport Taxi</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link <?= is_active_page('/about') ?>" href="/about">About Us</a></li>
                <li class="nav-item"><a class="nav-link <?= is_active_page('/gallery') ?>" href="/gallery">Gallery</a></li>
                <li class="nav-item"><a class="nav-link <?= is_active_page('/blog') ?>" href="/blog">Blog</a></li>
                <li class="nav-item"><a class="nav-link <?= is_active_page('/contact') ?>" href="/contact">Contact</a></li>
            </ul>

            <div class="d-none d-lg-flex align-items-center gap-2">
                <a href="tel:<?= htmlspecialchars($phone) ?>" class="nav-phone-pill" data-log-call="1">
                    <i class="bi bi-telephone-fill me-1"></i><?= htmlspecialchars($phone) ?>
                </a>
                <a href="<?= htmlspecialchars($waUrl) ?>" target="_blank" rel="noopener" class="nav-wa-square" title="Chat on WhatsApp" data-log-whatsapp="1">
                    <i class="bi bi-whatsapp"></i>
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- ========== FLASH MESSAGES ========== -->
<?php if (!empty($flash)): ?>
<div class="container mt-2">
    <?php foreach ($flash as $type => $msg): ?>
    <div class="alert alert-<?= htmlspecialchars($type) ?> alert-dismissible auto-dismiss" role="alert">
        <?= htmlspecialchars($msg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- ========== PAGE CONTENT ========== -->
<?= $content ?>

<!-- ========== FOOTER ========== -->
<footer class="pt-5 pb-0 mt-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="footer-logo mb-3"><i class="bi bi-geo-alt-fill"></i> OM <span>Trip</span> &amp; Travels</div>
                <p class="small" style="color:rgba(255,255,255,.65);"><?= nl2br(htmlspecialchars($settings('footer_about', 'OM Trip and Travels — Indore\'s trusted name for group travel.'))) ?></p>
                <div class="social-links mt-3">
                    <?php if ($settings('facebook_url')): ?><a href="<?= htmlspecialchars($settings('facebook_url')) ?>" target="_blank" rel="noopener"><i class="bi bi-facebook"></i></a><?php endif; ?>
                    <?php if ($settings('instagram_url')): ?><a href="<?= htmlspecialchars($settings('instagram_url')) ?>" target="_blank" rel="noopener"><i class="bi bi-instagram"></i></a><?php endif; ?>
                    <?php if ($settings('youtube_url')): ?><a href="<?= htmlspecialchars($settings('youtube_url')) ?>" target="_blank" rel="noopener"><i class="bi bi-youtube"></i></a><?php endif; ?>
                    <?php if ($settings('twitter_url')): ?><a href="<?= htmlspecialchars($settings('twitter_url')) ?>" target="_blank" rel="noopener"><i class="bi bi-twitter-x"></i></a><?php endif; ?>
                </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <h6>Quick Links</h6>
                <ul>
                    <li><a href="/fleet">Our Fleet</a></li>
                    <li><a href="/packages">Tour Packages</a></li>
                    <li><a href="/airport-transfers">Airport Taxi</a></li>
                    <li><a href="/gallery">Gallery</a></li>
                    <li><a href="/testimonials">Reviews</a></li>
                    <li><a href="/faq">FAQ</a></li>
                    <li><a href="/blog">Blog</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-6">
                <h6>Fleet</h6>
                <ul>
                    <li><a href="/fleet?category=urbania">Urbania</a></li>
                    <li><a href="/fleet?category=tempo-traveller">Tempo Traveller</a></li>
                    <li><a href="/fleet?category=suv">Toyota Fortuner</a></li>
                    <li><a href="/fleet?category=suv">Innova Crysta</a></li>
                    <li><a href="/fleet?category=suv">Innova Hycross</a></li>
                    <li><a href="/fleet?category=sedan">Sedan</a></li>
                </ul>
            </div>
            <div class="col-lg-4 col-md-6">
                <h6>Contact Us</h6>
                <div class="footer-contact">
                    <div class="d-flex align-items-start mb-2">
                        <span class="contact-icon"><i class="bi bi-geo-alt-fill"></i></span>
                        <span><?= nl2br(htmlspecialchars($settings('address', 'Indore, Madhya Pradesh'))) ?></span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <span class="contact-icon"><i class="bi bi-telephone-fill"></i></span>
                        <a href="tel:<?= htmlspecialchars($phone) ?>" class="text-white-50 hover-primary"><?= htmlspecialchars($phone) ?></a>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <span class="contact-icon"><i class="bi bi-whatsapp"></i></span>
                        <a href="<?= htmlspecialchars($waUrl) ?>" target="_blank" class="text-white-50"><?= htmlspecialchars($phone) ?></a>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <span class="contact-icon"><i class="bi bi-envelope-fill"></i></span>
                        <a href="mailto:<?= htmlspecialchars($settings('email')) ?>" class="text-white-50"><?= htmlspecialchars($settings('email')) ?></a>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="contact-icon"><i class="bi bi-clock-fill"></i></span>
                        <span><?= htmlspecialchars($settings('working_hours', 'Mon-Sun: 24 Hours')) ?></span>
                    </div>
                </div>
                <div class="mt-3 d-flex gap-2">
                    <a href="tel:<?= htmlspecialchars($phone) ?>" class="btn btn-call btn-sm flex-fill" data-log-call="1">
                        <i class="bi bi-telephone-fill me-1"></i>Call Now
                    </a>
                    <a href="<?= htmlspecialchars($waUrl) ?>" target="_blank" class="btn btn-whatsapp btn-sm flex-fill" data-log-whatsapp="1">
                        <i class="bi bi-whatsapp me-1"></i>WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom mt-4">
        <div class="container d-flex flex-wrap justify-content-between align-items-center gap-2">
            <span>&copy; <?= date('Y') ?> <?= htmlspecialchars($bizName) ?>. All rights reserved.</span>
            <span>
                <a href="/privacy" class="text-white-50 me-3">Privacy Policy</a>
                <a href="/terms"   class="text-white-50">Terms &amp; Conditions</a>
            </span>
        </div>
    </div>
</footer>

<!-- ========== FLOATING WHATSAPP ========== -->
<a href="<?= htmlspecialchars($waUrl) ?>" target="_blank" rel="noopener"
   class="floating-whatsapp" title="Chat on WhatsApp" data-log-whatsapp="1" aria-label="WhatsApp Chat">
    <i class="bi bi-whatsapp"></i>
</a>

<!-- ========== BACK TO TOP ========== -->
<div id="back-to-top" title="Back to top" style="display:none;">
    <i class="bi bi-chevron-up"></i>
</div>

<!-- ========== MOBILE BOTTOM ACTION BAR ========== -->
<div class="mobile-action-bar">
    <a href="tel:<?= htmlspecialchars($phone) ?>" class="call-btn" data-log-call="1">
        <i class="bi bi-telephone-fill"></i>Call
    </a>
    <a href="<?= htmlspecialchars($waUrl) ?>" target="_blank" class="wa-btn" data-log-whatsapp="1">
        <i class="bi bi-whatsapp"></i>WhatsApp
    </a>
    <a href="/booking" class="enq-btn">
        <i class="bi bi-calendar-check-fill"></i>Book Now
    </a>
</div>

<!-- Bootstrap JS -->
<script src="/assets/vendor/bootstrap.bundle.min.js"></script>
<!-- Main JS -->
<script src="/assets/js/main.js"></script>

<?= $settings('footer_scripts', '') ?>
<?= $settings('analytics_code', '') ?>
</body>
</html>

