<?php
/**
 * Package detail view.
 */
?>
<!-- BREADCRUMB -->
<div class="breadcrumb-nav">
    <div class="container">
        <ol class="breadcrumb mb-0 small">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="/packages">Tour Packages</a></li>
            <li class="breadcrumb-item active"><?= htmlspecialchars($package['name']) ?></li>
        </ol>
    </div>
</div>

<div class="container py-4">
    <div class="row g-4">
        <!-- LEFT COLUMN: ITINERARY & DETAILS -->
        <div class="col-lg-7">
            <div class="card p-4 border-0 shadow-sm mb-4">
                <span class="badge bg-primary-subtle text-primary border border-primary align-self-start mb-2">
                    <?= (int)$package['duration_days'] ?> Days / <?= (int)$package['duration_nights'] ?> Nights Tour
                </span>
                <h1 class="h2 fw-bold mb-2"><?= htmlspecialchars($package['name']) ?></h1>
                <p class="text-muted mb-3"><i class="bi bi-signpost-2-fill text-danger me-2"></i><strong>Route:</strong> <?= htmlspecialchars($package['route'] ?? '') ?></p>

                <div class="mb-4">
                    <img src="<?= htmlspecialchars($package['primary_image'] ?: 'https://images.unsplash.com/photo-1590050752117-238cb0fb12b1?auto=format&fit=crop&w=800&q=80') ?>"
                         class="img-fluid rounded shadow-sm w-100" style="max-height:380px; object-fit:cover;"
                         alt="<?= htmlspecialchars($package['name']) ?>"
                         onerror="this.src='https://images.unsplash.com/photo-1590050752117-238cb0fb12b1?auto=format&fit=crop&w=800&q=80'">
                </div>

                <!-- OVERVIEW -->
                <h2 class="h5 fw-bold mb-3 border-bottom pb-2">Tour Overview</h2>
                <div class="text-secondary mb-4">
                    <?= raw($package['description'] ?: nl2br(htmlspecialchars($package['short_description'] ?? ''))) ?>
                </div>

                <!-- ITINERARY IF PRESENT -->
                <?php if (!empty($package['itinerary'])): ?>
                <h2 class="h5 fw-bold mb-3 border-bottom pb-2">Tour Itinerary</h2>
                <div class="text-secondary mb-4">
                    <?= raw($package['itinerary']) ?>
                </div>
                <?php endif; ?>

                <!-- INCLUSIONS & EXCLUSIONS -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded border h-100">
                            <h3 class="h6 fw-bold text-success mb-2"><i class="bi bi-check-circle-fill me-2"></i>Inclusions</h3>
                            <ul class="list-unstyled small mb-0">
                                <?php foreach (explode("\n", (string)$package['inclusions']) as $inc):
                                    if (!trim($inc)) continue; ?>
                                <li class="mb-1"><i class="bi bi-check2 text-success me-1"></i><?= htmlspecialchars(trim($inc)) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded border h-100">
                            <h3 class="h6 fw-bold text-danger mb-2"><i class="bi bi-x-circle-fill me-2"></i>Exclusions</h3>
                            <ul class="list-unstyled small mb-0">
                                <?php foreach (explode("\n", (string)$package['exclusions']) as $exc):
                                    if (!trim($exc)) continue; ?>
                                <li class="mb-1"><i class="bi bi-x text-danger me-1"></i><?= htmlspecialchars(trim($exc)) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- SUITABLE FLEET -->
                <?php if (!empty($vehicles)): ?>
                <h2 class="h5 fw-bold mb-3 border-bottom pb-2">Recommended Vehicles for this Tour</h2>
                <div class="row g-2 mb-2">
                    <?php foreach ($vehicles as $v): ?>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center p-2 rounded bg-light border small">
                            <i class="bi bi-truck-front-fill text-primary me-2 fs-5"></i>
                            <div>
                                <a href="/fleet/<?= htmlspecialchars($v['slug']) ?>" class="fw-bold text-dark text-decoration-none"><?= htmlspecialchars($v['name']) ?></a>
                                <div class="text-muted" style="font-size:0.75rem;"><?= (int)$v['seats'] ?> Seater &bull; <?= htmlspecialchars($v['category_name']) ?></div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- RIGHT COLUMN: CTA & ENQUIRY -->
        <div class="col-lg-5">
            <div class="action-btns-sticky">
                <!-- Action Card -->
                <div class="card p-4 border-0 shadow-sm mb-4">
                    <h2 class="h5 fw-bold mb-2">Package Quotation</h2>
                    <p class="text-muted small mb-3">Customized according to your group size and vehicle choice.</p>

                    <div class="p-3 bg-light rounded mb-3">
                        <div class="small text-muted mb-1">Price starts at:</div>
                        <div class="d-flex align-items-baseline gap-2">
                            <span class="h3 fw-bold text-primary mb-0">
                                <?= $package['starting_price'] ? '₹' . numberFormat((float)$package['starting_price']) : 'On Request' ?>
                            </span>
                            <small class="text-muted"><?= htmlspecialchars($package['price_note'] ?: 'Includes AC cab, driver, fuel') ?></small>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mb-3">
                        <a href="tel:<?= htmlspecialchars($phone) ?>" class="btn btn-primary btn-lg shadow-sm" data-log-call="1" data-package-id="<?= $package['id'] ?>">
                            <i class="bi bi-telephone-fill me-2"></i>Call: <?= htmlspecialchars($phone) ?>
                        </a>
                        <a href="<?= htmlspecialchars($waUrl) ?>" target="_blank" rel="noopener" class="btn btn-whatsapp btn-lg shadow-sm" data-log-whatsapp="1" data-package-id="<?= $package['id'] ?>">
                            <i class="bi bi-whatsapp me-2"></i>WhatsApp Enquiry
                        </a>
                    </div>
                    <small class="text-center text-muted d-block"><i class="bi bi-shield-check text-success me-1"></i>No online booking or payment. Instant booking over phone.</small>
                </div>

                <!-- ENQUIRY FORM -->
                <div class="card p-4 border-0 shadow-sm">
                    <h3 class="h5 fw-bold mb-1">Enquire for this Package</h3>
                    <p class="text-muted small mb-3">Leave your number and date for a callback within 15 minutes.</p>

                    <form action="/booking" method="POST" class="ajax-form">
                        <?= csrfField() ?>
                        <!-- Honeypot -->
                        <div style="display:none;"><input type="text" name="website_hp"></div>
                        <input type="hidden" name="package_id" value="<?= $package['id'] ?>">
                        <input type="hidden" name="source_page" value="<?= htmlspecialchars($pageUrl) ?>">

                        <div class="form-result mb-3" style="display:none;"></div>

                        <div class="mb-3">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="name" class="form-control form-control-sm" required placeholder="e.g. Anand Sharma">
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
                                <label class="form-label">Travel Date *</label>
                                <input type="date" name="travel_date" class="form-control form-control-sm" required min="<?= date('Y-m-d') ?>">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Passengers</label>
                                <input type="number" name="passengers" class="form-control form-control-sm" placeholder="e.g. 12" min="1">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Pickup Location in Indore</label>
                            <input type="text" name="pickup_location" class="form-control form-control-sm" placeholder="e.g. Vijay Nagar, Indore">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Any specific requirement?</label>
                            <textarea name="message" class="form-control form-control-sm" rows="2" placeholder="e.g. Prefer 17-seater Urbania"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold">
                            <i class="bi bi-send-fill me-1"></i>Submit Package Enquiry
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

