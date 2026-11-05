<?php
/**
 * Packages listing view.
 */
?>
<!-- PAGE HERO -->
<div class="page-hero">
    <div class="container text-center">
        <h1>Tour Packages from Indore</h1>
        <p class="text-white-50 max-w-600 mx-auto">Spiritual darshan tours to Jyotirlingas, scenic hill stations, and royal heritage spots with experienced drivers and clean vehicles.</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tour Packages</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <?php foreach ($packages as $pkg):
            $pkgUrl = '/packages/' . htmlspecialchars($pkg['slug']);
            $pkgPageUrl = $appUrl . $pkgUrl;
            $pkgWaMsg = "Hello OM Trip and Travels, I want to enquire about the " . $pkg['name'] . " tour package. Page: " . $pkgPageUrl;
            $pkgWaUrl = "https://wa.me/{$wa}?text=" . rawurlencode($pkgWaMsg);
        ?>
        <div class="col-lg-4 col-md-6">
            <div class="package-card card">
                <img src="<?= htmlspecialchars($pkg['primary_image'] ?: 'https://images.unsplash.com/photo-1590050752117-238cb0fb12b1?auto=format&fit=crop&w=600&q=80') ?>"
                     class="pkg-img" alt="<?= htmlspecialchars($pkg['name']) ?>" loading="lazy"
                     onerror="this.src='https://images.unsplash.com/photo-1590050752117-238cb0fb12b1?auto=format&fit=crop&w=600&q=80'">
                <div class="card-body d-flex flex-column">
                    <span class="package-badge align-self-start"><?= (int)$pkg['duration_days'] ?> Days / <?= (int)$pkg['duration_nights'] ?> Nights</span>
                    <h2 class="h5 fw-bold mb-2"><a href="<?= $pkgUrl ?>" class="text-dark text-decoration-none"><?= htmlspecialchars($pkg['name']) ?></a></h2>
                    <p class="text-muted small mb-2"><i class="bi bi-geo-alt-fill text-danger me-1"></i><?= htmlspecialchars($pkg['route'] ?? 'Indore Outstation Route') ?></p>
                    <p class="text-secondary small mb-3 flex-grow-1"><?= htmlspecialchars($pkg['short_description'] ?? '') ?></p>

                    <div class="d-flex justify-content-between align-items-center pt-2 border-top mb-3">
                        <div>
                            <small class="text-muted d-block" style="font-size:0.75rem;">Package Starts At</small>
                            <span class="price-tag"><?= $pkg['starting_price'] ? '₹' . numberFormat((float)$pkg['starting_price']) : 'On Request' ?></span>
                        </div>
                        <a href="<?= $pkgUrl ?>" class="btn btn-sm btn-outline-secondary">Itinerary &rarr;</a>
                    </div>

                    <div class="row g-2">
                        <div class="col-6">
                            <a href="tel:<?= htmlspecialchars($phone) ?>" class="btn btn-call btn-sm w-100" data-log-call="1" data-package-id="<?= $pkg['id'] ?>">
                                <i class="bi bi-telephone-fill me-1"></i>Call Now
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="<?= htmlspecialchars($pkgWaUrl) ?>" target="_blank" rel="noopener" class="btn btn-whatsapp btn-sm w-100" data-log-whatsapp="1" data-package-id="<?= $pkg['id'] ?>">
                                <i class="bi bi-whatsapp me-1"></i>WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

