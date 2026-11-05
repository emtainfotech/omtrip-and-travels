<?php
/**
 * Contact Us view.
 */
$phone = $settings['phone'] ?? env('PHONE_NUMBER', '+91 98765 43210');
$wa    = $settings['whatsapp_number'] ?? env('WHATSAPP_NUMBER', '919876543210');
$waUrl = 'https://wa.me/' . $wa . '?text=' . rawurlencode('Hello OM Trip and Travels, I want to enquire about your services.');
?>
<!-- PAGE HERO -->
<div class="page-hero">
    <div class="container text-center">
        <h1>Contact OM Trip and Travels</h1>
        <p class="text-white-50 max-w-600 mx-auto">Get in touch for instant cab bookings, vehicle rental quotes, or custom outstation packages in Indore.</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Contact</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <div class="row g-5">
        <!-- CONTACT INFO -->
        <div class="col-lg-5">
            <div class="contact-info-card card p-4 border-0 shadow-sm h-100">
                <h2 class="h4 fw-bold mb-4 border-bottom pb-3">Get In Touch</h2>

                <div class="d-flex align-items-start mb-4">
                    <div class="contact-icon me-3"><i class="bi bi-geo-alt-fill"></i></div>
                    <div>
                        <h3 class="h6 fw-bold mb-1">Our Office</h3>
                        <p class="text-muted small mb-0"><?= nl2br(htmlspecialchars($settings['address'] ?? 'Indore, Madhya Pradesh - 452010')) ?></p>
                    </div>
                </div>

                <div class="d-flex align-items-start mb-4">
                    <div class="contact-icon me-3"><i class="bi bi-telephone-fill"></i></div>
                    <div>
                        <h3 class="h6 fw-bold mb-1">Phone Number</h3>
                        <p class="text-muted small mb-1"><a href="tel:<?= htmlspecialchars($phone) ?>" class="text-decoration-none text-secondary fw-bold"><?= htmlspecialchars($phone) ?></a></p>
                        <small class="text-success"><i class="bi bi-check-circle me-1"></i>Available 24/7 for immediate dispatch</small>
                    </div>
                </div>

                <div class="d-flex align-items-start mb-4">
                    <div class="contact-icon me-3" style="background:#25D366;"><i class="bi bi-whatsapp"></i></div>
                    <div>
                        <h3 class="h6 fw-bold mb-1">WhatsApp Chat</h3>
                        <p class="text-muted small mb-1"><a href="<?= htmlspecialchars($waUrl) ?>" target="_blank" class="text-decoration-none text-secondary fw-bold">+<?= htmlspecialchars($wa) ?></a></p>
                        <small class="text-muted">Instant response with vehicle photos &amp; fare details</small>
                    </div>
                </div>

                <div class="d-flex align-items-start mb-4">
                    <div class="contact-icon me-3"><i class="bi bi-envelope-fill"></i></div>
                    <div>
                        <h3 class="h6 fw-bold mb-1">Email Address</h3>
                        <p class="text-muted small mb-0"><a href="mailto:<?= htmlspecialchars($settings['email'] ?? 'info@omtripandtravels.com') ?>" class="text-decoration-none text-secondary"><?= htmlspecialchars($settings['email'] ?? 'info@omtripandtravels.com') ?></a></p>
                    </div>
                </div>

                <div class="d-flex align-items-start">
                    <div class="contact-icon me-3"><i class="bi bi-clock-fill"></i></div>
                    <div>
                        <h3 class="h6 fw-bold mb-1">Operating Hours</h3>
                        <p class="text-muted small mb-0"><?= htmlspecialchars($settings['working_hours'] ?? 'Monday to Sunday: Open 24 Hours') ?></p>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex gap-2">
                    <a href="tel:<?= htmlspecialchars($phone) ?>" class="btn btn-primary flex-fill" data-log-call="1">
                        <i class="bi bi-telephone-fill me-1"></i>Call Now
                    </a>
                    <a href="<?= htmlspecialchars($waUrl) ?>" target="_blank" rel="noopener" class="btn btn-whatsapp flex-fill" data-log-whatsapp="1">
                        <i class="bi bi-whatsapp me-1"></i>WhatsApp
                    </a>
                </div>
            </div>
        </div>

        <!-- CONTACT FORM -->
        <div class="col-lg-7">
            <div class="card p-4 p-md-5 border-0 shadow-sm">
                <h2 class="h4 fw-bold mb-1">Send Us a Message</h2>
                <p class="text-muted small mb-4">Have an enquiry about vehicle availability, custom tour route, or long-term lease? Fill this form and we'll reply promptly.</p>

                <form action="/contact" method="POST" class="ajax-form">
                    <?= csrfField() ?>
                    <!-- Honeypot -->
                    <div style="display:none;"><input type="text" name="website_hp"></div>

                    <div class="form-result mb-3" style="display:none;"></div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="name" class="form-control" required placeholder="e.g. Rajesh Kumar">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mobile Number (10 digits) *</label>
                            <div class="input-group">
                                <span class="input-group-text">+91</span>
                                <input type="tel" name="phone" class="form-control" required placeholder="9876543210" maxlength="10">
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Email Address (Optional)</label>
                            <input type="email" name="email" class="form-control" placeholder="your@email.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Vehicle You are Interested In</label>
                            <select name="vehicle_id" class="form-select">
                                <option value="">Select a vehicle (optional)</option>
                                <?php foreach ($vehicles as $v): ?>
                                <option value="<?= $v['id'] ?>"><?= htmlspecialchars($v['name']) ?> (<?= (int)$v['seats'] ?> Seater)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Your Message / Query *</label>
                        <textarea name="message" class="form-control" rows="4" required placeholder="Tell us about your trip: destination, dates, number of passengers, etc."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg px-4 fw-bold">
                        <i class="bi bi-send-fill me-2"></i>Send Enquiry
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- GOOGLE MAPS EMBED IF CONFIGURED -->
    <?php if (!empty($settings['google_maps_embed'])): ?>
    <div class="mt-5 rounded overflow-hidden shadow-sm">
        <?= raw($settings['google_maps_embed']) ?>
    </div>
    <?php endif; ?>
</div>

