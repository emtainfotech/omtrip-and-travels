<?php
/**
 * About Us view.
 */
$phone = $settings['phone'] ?? env('PHONE_NUMBER', '+91 98765 43210');
$wa    = $settings['whatsapp_number'] ?? env('WHATSAPP_NUMBER', '919876543210');
?>
<!-- PAGE HERO -->
<div class="page-hero">
    <div class="container text-center">
        <h1>About OM Trip and Travels</h1>
        <p class="text-white-50 max-w-600 mx-auto">Indore's most reliable and professional car rental &amp; group travel partner since years.</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">About Us</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <!-- STORY & MISSION -->
    <div class="row align-items-center g-5 mb-5">
        <div class="col-lg-6">
            <span class="text-primary fw-bold text-uppercase fs-7">Who We Are</span>
            <h2 class="display-6 fw-bold mb-3">Group Travel &amp; Van Rental Specialists in Indore</h2>
            <p class="text-secondary mb-3">OM Trip and Travels was founded with a singular mission: to make group travel and pilgrimage tours from Indore hassle-free, comfortable, and affordable. We recognized that while standard cabs are easily available, finding clean, spacious, and reliable 12 to 17-seater vehicles with trustworthy drivers was a major pain point for families and tour organizers.</p>
            <p class="text-secondary mb-4">Today, we own and manage Indore's finest fleet of <strong>Force Urbania luxury vans, Executive Tempo Travellers (12, 14, 17 seater)</strong>, and premium SUVs like <strong>Toyota Fortuner, Innova Crysta, and Innova Hycross</strong>.</p>
            
            <div class="row g-3">
                <div class="col-6">
                    <div class="p-3 border rounded bg-light text-center">
                        <div class="h3 fw-bold text-primary mb-0">10,000+</div>
                        <small class="text-muted">Trips Completed</small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-3 border rounded bg-light text-center">
                        <div class="h3 fw-bold text-primary mb-0">99.8%</div>
                        <small class="text-muted">On-Time Punctuality</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <img src="https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=800&q=80"
                 alt="OM Trip and Travels Fleet Indore" class="img-fluid rounded shadow-lg">
        </div>
    </div>

    <!-- CORE VALUES -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="p-4 border rounded shadow-sm h-100 bg-white">
                <div class="display-6 text-primary mb-3"><i class="bi bi-shield-check"></i></div>
                <h3 class="h5 fw-bold mb-2">Safety &amp; Reliability</h3>
                <p class="text-muted small mb-0">All our vehicles undergo regular mechanical inspection and GPS tracking. Our drivers are background-checked and have extensive hill and night-driving experience.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4 border rounded shadow-sm h-100 bg-white">
                <div class="display-6 text-primary mb-3"><i class="bi bi-cash-stack"></i></div>
                <h3 class="h5 fw-bold mb-2">Clear &amp; Transparent Rates</h3>
                <p class="text-muted small mb-0">No sudden surcharges or unexpected driver bhatta disputes. Everything agreed upon during booking is strictly honored.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4 border rounded shadow-sm h-100 bg-white">
                <div class="display-6 text-primary mb-3"><i class="bi bi-heart-pulse-fill"></i></div>
                <h3 class="h5 fw-bold mb-2">Pilgrim-Centric Care</h3>
                <p class="text-muted small mb-0">We specialize in religious yatras to Mahakaleshwar, Omkareshwar, Shirdi, and Dwarka, providing elder-friendly stops and VIP guidance.</p>
            </div>
        </div>
    </div>

    <!-- OFFICE ADDRESS & DETAILS -->
    <div class="p-4 p-md-5 bg-light rounded shadow-sm">
        <div class="row align-items-center g-4">
            <div class="col-md-8">
                <h3 class="h4 fw-bold mb-2">Visit Our Indore Office</h3>
                <p class="text-muted mb-2"><i class="bi bi-geo-alt-fill text-danger me-2"></i><?= nl2br(htmlspecialchars($settings['address'] ?? 'Indore, Madhya Pradesh - 452010')) ?></p>
                <p class="text-muted mb-0"><i class="bi bi-clock-fill text-primary me-2"></i><strong>Working Hours:</strong> <?= htmlspecialchars($settings['working_hours'] ?? '24 Hours / 7 Days a week') ?></p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="tel:<?= htmlspecialchars($phone) ?>" class="btn btn-primary px-4 py-2 me-2 mb-2">
                    <i class="bi bi-telephone-fill me-1"></i>Call Office
                </a>
                <a href="/contact" class="btn btn-outline-secondary px-4 py-2 mb-2">Get Directions</a>
            </div>
        </div>
    </div>
</div>

