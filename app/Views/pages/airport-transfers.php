<?php
/**
 * Airport and local transfers view.
 */
$phone = $settings['phone'] ?? env('PHONE_NUMBER', '+91 98765 43210');
$wa    = $settings['whatsapp_number'] ?? env('WHATSAPP_NUMBER', '919876543210');
$waUrl = 'https://wa.me/' . $wa . '?text=' . rawurlencode('Hello OM Trip and Travels, I need an airport taxi in Indore.');
?>
<!-- PAGE HERO -->
<div class="page-hero">
    <div class="container text-center">
        <h1>Indore Airport Taxi &amp; Local Transfers</h1>
        <p class="text-white-50 max-w-600 mx-auto">Guaranteed on-time pickup &amp; drop for Devi Ahilyabai Holkar Airport (IDR). AC sedans, Innova, and luxury vans 24/7.</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Airport Taxi</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <!-- AIRPORT SERVICE FEATURES -->
    <div class="row align-items-center g-5 mb-5">
        <div class="col-lg-6">
            <span class="text-primary fw-bold text-uppercase fs-7">Devi Ahilyabai Holkar Airport Transfers</span>
            <h2 class="display-6 fw-bold mb-3">Never Miss a Flight with OM Trip and Travels</h2>
            <p class="text-secondary mb-3">Whether you have an early morning 5:00 AM flight or a midnight arrival at Indore Airport (IDR), our dedicated airport cab service guarantees punctuality, clean AC interiors, and flight tracking.</p>
            <p class="text-secondary mb-4">We cater to solo corporate flyers in comfortable sedans, families in Toyota Innova Crysta/Hycross, as well as large conference and wedding delegations in Force Urbania &amp; Tempo Travellers.</p>

            <ul class="list-unstyled mb-4">
                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i><strong>Free 45-minute flight delay waiting</strong> at arrival terminal</li>
                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i><strong>Doorstep pickup across Indore</strong> (Vijay Nagar, Palasia, Silicon City, Rau, Super Corridor)</li>
                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i><strong>Direct Airport to Ujjain Mahakaleshwar</strong> corridor service (60 mins)</li>
                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i><strong>Professional drivers</strong> assisting with your luggage</li>
            </ul>

            <div class="d-flex flex-wrap gap-3">
                <a href="tel:<?= htmlspecialchars($phone) ?>" class="btn btn-primary btn-lg" data-log-call="1">
                    <i class="bi bi-telephone-fill me-2"></i>Call: <?= htmlspecialchars($phone) ?>
                </a>
                <a href="<?= htmlspecialchars($waUrl) ?>" target="_blank" class="btn btn-whatsapp btn-lg" data-log-whatsapp="1">
                    <i class="bi bi-whatsapp me-2"></i>Book on WhatsApp
                </a>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card p-4 p-md-5 border-0 shadow">
                <h3 class="h5 fw-bold mb-1">Book Your Airport Cab Now</h3>
                <p class="text-muted small mb-3">Instant booking confirmation over call / WhatsApp.</p>

                <form action="/booking" method="POST" class="ajax-form">
                    <?= csrfField() ?>
                    <div style="display:none;"><input type="text" name="website_hp"></div>
                    <input type="hidden" name="source_page" value="<?= htmlspecialchars(currentUrl()) ?>">

                    <div class="form-result mb-3" style="display:none;"></div>

                    <div class="mb-3">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="name" class="form-control form-control-sm" required placeholder="Your name">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mobile Number *</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">+91</span>
                            <input type="tel" name="phone" class="form-control" required placeholder="10-digit number" maxlength="10">
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label">Pickup Location *</label>
                            <input type="text" name="pickup_location" class="form-control form-control-sm" required placeholder="e.g. Indore Airport / Hotel">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Drop Location *</label>
                            <input type="text" name="drop_location" class="form-control form-control-sm" required placeholder="e.g. Vijay Nagar / Ujjain">
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label">Date &amp; Time *</label>
                            <input type="date" name="travel_date" class="form-control form-control-sm" required min="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Vehicle Type</label>
                            <select name="vehicle_id" class="form-select form-select-sm">
                                <option value="">Any AC Vehicle</option>
                                <?php foreach ($vehicles as $v): ?>
                                <option value="<?= $v['id'] ?>"><?= htmlspecialchars($v['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold">
                        <i class="bi bi-airplane-fill me-1"></i>Confirm Airport Ride
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

