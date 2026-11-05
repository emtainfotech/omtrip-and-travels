<?php
/**
 * Vehicle detail view.
 */
?>
<!-- BREADCRUMB -->
<div class="breadcrumb-nav">
    <div class="container">
        <ol class="breadcrumb mb-0 small">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="/fleet">Fleet</a></li>
            <li class="breadcrumb-item"><a href="/fleet?category=<?= htmlspecialchars($vehicle['category_slug']) ?>"><?= htmlspecialchars($vehicle['category_name']) ?></a></li>
            <li class="breadcrumb-item active"><?= htmlspecialchars($vehicle['name']) ?></li>
        </ol>
    </div>
</div>

<div class="container py-4">
    <div class="row g-4">
        <!-- LEFT COLUMN: IMAGES & SPECS -->
        <div class="col-lg-7">
            <!-- Main Image -->
            <div class="vehicle-gallery-main mb-3 shadow-sm">
                <img id="vehicle-main-img"
                     src="<?= htmlspecialchars($vehicle['primary_image'] ?: 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=800&q=80') ?>"
                     alt="<?= htmlspecialchars($vehicle['name']) ?>"
                     onerror="this.src='https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=800&q=80'">
            </div>

            <!-- Thumbnails if multiple images exist -->
            <?php if (!empty($images) && count($images) > 1): ?>
            <div class="vehicle-gallery-thumbs mb-4">
                <?php foreach ($images as $idx => $img): ?>
                <img src="<?= htmlspecialchars($img['image_path']) ?>"
                     data-full="<?= htmlspecialchars($img['image_path']) ?>"
                     alt="<?= htmlspecialchars($img['alt_text'] ?: $vehicle['name']) ?>"
                     class="<?= $idx === 0 ? 'active' : '' ?>">
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- DESCRIPTION -->
            <div class="card p-4 border-0 shadow-sm mb-4">
                <h2 class="h5 fw-bold mb-3 border-bottom pb-2">About <?= htmlspecialchars($vehicle['name']) ?></h2>
                <div class="vehicle-long-description text-secondary mb-4">
                    <?= raw($vehicle['description'] ?: nl2br(htmlspecialchars($vehicle['short_description'] ?? ''))) ?>
                </div>

                <!-- SPECIFICATIONS TABLE -->
                <h3 class="h6 fw-bold mb-3">Vehicle Specifications</h3>
                <table class="table table-bordered vehicle-spec-table small mb-4">
                    <tbody>
                        <tr>
                            <td class="fw-bold w-40"><i class="bi bi-people-fill text-primary me-2"></i>Seating Capacity</td>
                            <td><?= (int)$vehicle['seats'] ?> Passengers + 1 Driver</td>
                        </tr>
                        <tr>
                            <td class="fw-bold"><i class="bi bi-snow text-info me-2"></i>Air Conditioning</td>
                            <td><?= $vehicle['has_ac'] ? 'Fully Air-Conditioned (Front & Rear Vents)' : 'Non-AC' ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold"><i class="bi bi-briefcase-fill text-secondary me-2"></i>Luggage Capacity</td>
                            <td><?= htmlspecialchars($vehicle['luggage_capacity'] ?? 'Ample boot space for luggage') ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold"><i class="bi bi-tag-fill text-success me-2"></i>Category</td>
                            <td><?= htmlspecialchars($vehicle['category_name']) ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold"><i class="bi bi-geo-alt-fill text-danger me-2"></i>Base Location</td>
                            <td>Indore, Madhya Pradesh (Available for Pan-India &amp; MP Tours)</td>
                        </tr>
                    </tbody>
                </table>

                <!-- FEATURES LIST -->
                <?php if (!empty($features)): ?>
                <h3 class="h6 fw-bold mb-2">Key Amenities &amp; Features</h3>
                <div class="row g-2 mb-4">
                    <?php foreach ($features as $f): ?>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center p-2 rounded bg-light border small">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            <span><?= htmlspecialchars($f) ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- IDEAL FOR -->
                <?php if (!empty($idealFor)): ?>
                <h3 class="h6 fw-bold mb-2">Ideal Travel Use Cases</h3>
                <div class="d-flex flex-wrap gap-2 mb-2">
                    <?php foreach ($idealFor as $use): ?>
                    <span class="badge bg-secondary-subtle text-secondary border px-3 py-2 fs-7"><?= htmlspecialchars($use) ?></span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- RIGHT COLUMN: PRICING & ENQUIRY / BOOKING FORM -->
        <div class="col-lg-5">
            <div class="action-btns-sticky">
                <!-- Vehicle Card Header -->
                <div class="card p-4 border-0 shadow-sm mb-4">
                    <span class="badge bg-primary-subtle text-primary border border-primary align-self-start mb-2"><?= htmlspecialchars($vehicle['category_name']) ?></span>
                    <h1 class="h3 fw-bold mb-2"><?= htmlspecialchars($vehicle['name']) ?></h1>
                    <p class="text-muted small mb-3"><?= htmlspecialchars($vehicle['short_description'] ?? '') ?></p>

                    <div class="p-3 bg-light rounded mb-3">
                        <div class="small text-muted mb-1">Rental Fare / Starting Price:</div>
                        <div class="d-flex align-items-baseline gap-2">
                            <span class="h3 fw-bold text-primary mb-0">
                                <?= $vehicle['starting_price'] ? '₹' . numberFormat((float)$vehicle['starting_price']) : 'On Request' ?>
                            </span>
                            <small class="text-muted"><?= htmlspecialchars($vehicle['price_note'] ?: 'Best price guaranteed') ?></small>
                        </div>
                    </div>

                    <!-- DIRECT CONTACT BUTTONS (USP) -->
                    <div class="d-grid gap-2 mb-3">
                        <a href="tel:<?= htmlspecialchars($phone) ?>" class="btn btn-primary btn-lg shadow-sm" data-log-call="1" data-vehicle-id="<?= $vehicle['id'] ?>">
                            <i class="bi bi-telephone-fill me-2"></i>Call Now: <?= htmlspecialchars($phone) ?>
                        </a>
                        <a href="<?= htmlspecialchars($waUrl) ?>" target="_blank" rel="noopener" class="btn btn-whatsapp btn-lg shadow-sm" data-log-whatsapp="1" data-vehicle-id="<?= $vehicle['id'] ?>">
                            <i class="bi bi-whatsapp me-2"></i>WhatsApp Booking
                        </a>
                    </div>
                    <small class="text-center text-muted d-block"><i class="bi bi-shield-check text-success me-1"></i>No advance payment online. Call or WhatsApp to block your vehicle.</small>
                </div>

                <!-- INSTANT BOOKING REQUEST FORM -->
                <div class="card p-4 border-0 shadow-sm">
                    <h3 class="h5 fw-bold mb-1">Instant Booking Request</h3>
                    <p class="text-muted small mb-3">Fill this form and our Indore dispatch team will call you within 15 minutes.</p>

                    <form action="/booking" method="POST" class="ajax-form">
                        <?= csrfField() ?>
                        <!-- Honeypot -->
                        <div style="display:none;"><input type="text" name="website_hp"></div>
                        <input type="hidden" name="vehicle_id" value="<?= $vehicle['id'] ?>">
                        <input type="hidden" name="source_page" value="<?= htmlspecialchars($pageUrl) ?>">

                        <div class="form-result mb-3" style="display:none;"></div>

                        <div class="mb-3">
                            <label class="form-label">Your Name *</label>
                            <input type="text" name="name" class="form-control form-control-sm" required placeholder="e.g. Ramesh Patel">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone Number *</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">+91</span>
                                <input type="tel" name="phone" class="form-control" required placeholder="10-digit mobile number" maxlength="10">
                            </div>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label">Pickup City/Place *</label>
                                <input type="text" name="pickup_location" class="form-control form-control-sm" required placeholder="e.g. Indore Airport / Vijay Nagar">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Drop Destination</label>
                                <input type="text" name="drop_location" class="form-control form-control-sm" placeholder="e.g. Ujjain / Omkareshwar">
                            </div>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label">Travel Date *</label>
                                <input type="date" name="travel_date" class="form-control form-control-sm" required min="<?= date('Y-m-d') ?>">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Passengers</label>
                                <input type="number" name="passengers" class="form-control form-control-sm" placeholder="<?= (int)$vehicle['seats'] ?>" max="<?= (int)$vehicle['seats'] + 2 ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Trip Notes / Special Requests</label>
                            <textarea name="message" class="form-control form-control-sm" rows="2" placeholder="e.g. Need vehicle for 2 days outstation with luggage"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold">
                            <i class="bi bi-send-fill me-1"></i>Send Booking Request
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- RELATED VEHICLES -->
    <?php if (!empty($related)): ?>
    <div class="pt-5 mt-4 border-top">
        <h3 class="h4 fw-bold mb-4">Other Vehicles in <?= htmlspecialchars($vehicle['category_name']) ?></h3>
        <div class="row g-4">
            <?php foreach ($related as $rel):
                $rUrl = '/fleet/' . htmlspecialchars($rel['slug']);
                $rPageUrl = $appUrl . $rUrl;
                $rWaUrl = "https://wa.me/{$wa}?text=" . rawurlencode("Hello OM Trip and Travels, I am interested in " . $rel['name'] . ". Page: " . $rPageUrl);
            ?>
            <div class="col-md-4">
                <div class="vehicle-card card">
                    <div class="card-img-wrap">
                        <img src="<?= htmlspecialchars($rel['primary_image'] ?: 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=600&q=80') ?>" alt="<?= htmlspecialchars($rel['name']) ?>" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=600&q=80'">
                        <span class="badge-seats"><?= (int)$rel['seats'] ?> Seater</span>
                    </div>
                    <div class="card-body">
                        <h4 class="card-title h6"><a href="<?= $rUrl ?>" class="text-dark"><?= htmlspecialchars($rel['name']) ?></a></h4>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="price-tag"><?= $rel['starting_price'] ? '₹' . numberFormat((float)$rel['starting_price']) : 'On Request' ?></span>
                            <a href="<?= $rUrl ?>" class="btn btn-sm btn-outline-primary">Details &rarr;</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

