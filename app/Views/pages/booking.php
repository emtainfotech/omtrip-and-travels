<?php
/**
 * Booking Request view.
 */
$phone = $settings['phone'] ?? env('PHONE_NUMBER', '+91 98765 43210');
$wa    = $settings['whatsapp_number'] ?? env('WHATSAPP_NUMBER', '919876543210');
?>
<!-- PAGE HERO -->
<div class="page-hero">
    <div class="container text-center">
        <h1>Booking Request</h1>
        <p class="text-white-50 max-w-600 mx-auto">Book Force Urbania, Tempo Traveller, Innova Crysta or Fortuner in Indore. No advance online payment required!</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Booking Request</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <!-- IMPORTANT NOTICE -->
            <div class="alert alert-success d-flex align-items-center mb-4 shadow-sm" role="alert">
                <i class="bi bi-shield-check fs-2 me-3 text-success"></i>
                <div>
                    <h5 class="alert-heading fw-bold mb-1">Zero Advance Online Payment Required!</h5>
                    <p class="mb-0 small text-muted">We respect your peace of mind. Simply submit your travel details below and our team will call you to confirm vehicle availability, provide a fixed quote, and block your vehicle.</p>
                </div>
            </div>

            <div class="card p-4 p-md-5 border-0 shadow-sm">
                <h2 class="h4 fw-bold mb-1">Trip &amp; Passenger Details</h2>
                <p class="text-muted small mb-4">Fields marked with <span class="text-danger">*</span> are required.</p>

                <form action="/booking" method="POST" class="ajax-form">
                    <?= csrfField() ?>
                    <!-- Honeypot -->
                    <div style="display:none;"><input type="text" name="website_hp"></div>
                    <input type="hidden" name="source_page" value="<?= htmlspecialchars(currentUrl()) ?>">

                    <div class="form-result mb-4" style="display:none;"></div>

                    <!-- 1. PERSONAL INFORMATION -->
                    <h3 class="h6 fw-bold text-primary text-uppercase mb-3 border-bottom pb-2">1. Contact Information</h3>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required placeholder="e.g. Vikram Singhania">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">WhatsApp / Mobile Number <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">+91</span>
                                <input type="tel" name="phone" class="form-control" required placeholder="10-digit number" maxlength="10">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address (Optional)</label>
                            <input type="email" name="email" class="form-control" placeholder="yourname@gmail.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Preferred Vehicle Choice</label>
                            <select name="vehicle_id" class="form-select">
                                <option value="">Select Vehicle (or discuss on call)</option>
                                <?php foreach ($vehicles as $v): ?>
                                <option value="<?= $v['id'] ?>" <?= $selectedVehicleId === (int)$v['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($v['name']) ?> (<?= (int)$v['seats'] ?> Seater)
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- 2. TRAVEL ROUTE & DATES -->
                    <h3 class="h6 fw-bold text-primary text-uppercase mb-3 border-bottom pb-2">2. Route &amp; Travel Dates</h3>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Pickup Location in Indore <span class="text-danger">*</span></label>
                            <input type="text" name="pickup_location" class="form-control" required placeholder="e.g. Vijay Nagar / Airport / Railway Station">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Drop Destination</label>
                            <input type="text" name="drop_location" class="form-control" placeholder="e.g. Ujjain / Omkareshwar / Pachmarhi / Local Indore">
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label class="form-label">Travel / Departure Date <span class="text-danger">*</span></label>
                            <input type="date" name="travel_date" class="form-control" required min="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <label class="form-label">Return Date (if Round Trip)</label>
                            <input type="date" name="return_date" class="form-control" min="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <label class="form-label">Expected Number of Passengers</label>
                            <input type="number" name="passengers" class="form-control" placeholder="e.g. 12" min="1" max="50">
                        </div>
                    </div>

                    <!-- 3. ADDITIONAL NOTES -->
                    <h3 class="h6 fw-bold text-primary text-uppercase mb-3 border-bottom pb-2">3. Additional Trip Requirements</h3>
                    <div class="mb-4">
                        <label class="form-label">Notes, Itinerary or Special Instructions</label>
                        <textarea name="message" class="form-control" rows="3" placeholder="e.g. Traveling with senior citizens, need pushback seats and extra luggage space."></textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold py-3">
                            <i class="bi bi-calendar-check-fill me-2"></i>Submit Booking Request
                        </button>
                    </div>

                    <p class="text-muted small text-center mt-3 mb-0">
                        Prefer to talk directly? Call our 24/7 hotline:
                        <a href="tel:<?= htmlspecialchars($phone) ?>" class="text-primary fw-bold"><?= htmlspecialchars($phone) ?></a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

