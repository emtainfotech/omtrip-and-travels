<?php
/**
 * Fleet listing view.
 */
$phone = $settings['phone'] ?? env('PHONE_NUMBER', '+91 98765 43210');
$wa    = $settings['whatsapp_number'] ?? env('WHATSAPP_NUMBER', '919876543210');
?>

<!-- PAGE HERO -->
<div class="page-hero">
    <div class="container text-center">
        <h1><?= $activeCategory ? htmlspecialchars($activeCategory['name']) . ' on Rent in Indore' : 'Our Vehicle Fleet in Indore' ?></h1>
        <p class="text-white-50 max-w-600 mx-auto">Specialists in Force Urbania, Luxury Tempo Travellers (12, 14, 17 seater), Toyota Fortuner &amp; Innova Crysta for group journeys.</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Fleet</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <!-- FILTERS BAR -->
    <div class="card p-3 mb-4 shadow-sm border-0 bg-light">
        <form method="GET" action="/fleet" class="row g-3 align-items-center">
            <div class="col-md-5">
                <label class="form-label small fw-bold mb-1">Filter by Category</label>
                <div class="d-flex flex-wrap gap-2">
                    <a href="/fleet" class="btn btn-sm <?= empty($categorySlug) ? 'btn-primary' : 'btn-outline-secondary' ?>">All Vehicles</a>
                    <?php foreach ($categories as $cat): ?>
                    <a href="/fleet?category=<?= htmlspecialchars($cat['slug']) ?><?= $seats ? '&seats='.$seats : '' ?>"
                       class="btn btn-sm <?= $categorySlug === $cat['slug'] ? 'btn-primary' : 'btn-outline-secondary' ?>">
                        <?= htmlspecialchars($cat['name']) ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <label for="seatSelect" class="form-label small fw-bold mb-1">Passenger Capacity</label>
                <select name="seats" id="seatSelect" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="0">All Seating Capacities</option>
                    <option value="7"  <?= $seats === 7 ? 'selected' : '' ?>>7 Seater (Innova / Fortuner)</option>
                    <option value="12" <?= $seats === 12 ? 'selected' : '' ?>>12 Seater (Urbania / Tempo)</option>
                    <option value="14" <?= $seats === 14 ? 'selected' : '' ?>>14 Seater (Tempo Traveller)</option>
                    <option value="17" <?= $seats === 17 ? 'selected' : '' ?>>17 Seater (Large Urbania / Tempo)</option>
                </select>
                <?php if ($categorySlug): ?>
                <input type="hidden" name="category" value="<?= htmlspecialchars($categorySlug) ?>">
                <?php endif; ?>
            </div>
            <div class="col-md-3 col-sm-6 text-md-end mt-4">
                <a href="/fleet" class="btn btn-sm btn-link text-muted">Clear Filters</a>
            </div>
        </form>
    </div>

    <!-- VEHICLES GRID -->
    <?php if (empty($vehicles)): ?>
    <div class="alert alert-info text-center py-5">
        <i class="bi bi-info-circle display-4 d-block mb-3 text-primary"></i>
        <h5>No vehicles found matching your criteria.</h5>
        <p class="text-muted">We may have custom vans available. Please call us directly for availability.</p>
        <a href="tel:<?= htmlspecialchars($phone) ?>" class="btn btn-primary mt-2"><i class="bi bi-telephone-fill me-1"></i>Call Now: <?= htmlspecialchars($phone) ?></a>
    </div>
    <?php else: ?>
    <div class="row g-4">
        <?php foreach ($vehicles as $v):
            $vUrl = '/fleet/' . htmlspecialchars($v['slug']);
            $vPageUrl = $appUrl . $vUrl;
            $vWaMsg = "Hello OM Trip and Travels, I am interested in booking the " . $v['name'] . ". Page: " . $vPageUrl;
            $vWaUrl = "https://wa.me/{$wa}?text=" . rawurlencode($vWaMsg);
            $features = json_decode($v['features'] ?? '[]', true) ?: [];
            $idealFor = json_decode($v['ideal_for'] ?? '[]', true) ?: [];
        ?>
        <div class="col-lg-4 col-md-6">
            <div class="vehicle-card card">
                <div class="card-img-wrap">
                    <img src="<?= htmlspecialchars($v['primary_image'] ?: 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=600&q=80') ?>" alt="<?= htmlspecialchars($v['name']) ?>" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=600&q=80'">
                    <span class="badge-seats"><i class="bi bi-people-fill me-1"></i><?= (int)$v['seats'] ?> Seater</span>
                    <span class="badge-category"><?= htmlspecialchars($v['category_name']) ?></span>
                </div>
                <div class="card-body d-flex flex-column">
                    <h3 class="card-title"><a href="<?= $vUrl ?>" class="text-dark text-decoration-none"><?= htmlspecialchars($v['name']) ?></a></h3>
                    <p class="text-muted small mb-2 flex-grow-1"><?= htmlspecialchars($v['short_description'] ?? 'Comfortable AC vehicle with captain seats and ample luggage space.') ?></p>
                    
                    <?php if (!empty($features)): ?>
                    <ul class="vehicle-features mb-3">
                        <?php foreach (array_slice($features, 0, 4) as $feat): ?>
                        <li><i class="bi bi-check2 text-primary"></i> <?= htmlspecialchars($feat) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between align-items-center pt-2 border-top mt-auto mb-3">
                        <div>
                            <small class="text-muted d-block" style="font-size:0.75rem;">Rent / Pricing</small>
                            <span class="price-tag"><?= $v['starting_price'] ? '₹' . numberFormat((float)$v['starting_price']) : 'On Request' ?></span>
                        </div>
                        <a href="<?= $vUrl ?>" class="btn btn-sm btn-outline-secondary">View Details</a>
                    </div>

                    <div class="row g-2">
                        <div class="col-6">
                            <a href="tel:<?= htmlspecialchars($phone) ?>" class="btn btn-call btn-sm w-100" data-log-call="1" data-vehicle-id="<?= $v['id'] ?>">
                                <i class="bi bi-telephone-fill me-1"></i>Call Now
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="<?= htmlspecialchars($vWaUrl) ?>" target="_blank" rel="noopener" class="btn btn-whatsapp btn-sm w-100" data-log-whatsapp="1" data-vehicle-id="<?= $v['id'] ?>">
                                <i class="bi bi-whatsapp me-1"></i>WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

