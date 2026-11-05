<?php
/**
 * Home page view.
 */
$phone   = $settings['phone'] ?? env('PHONE_NUMBER', '+91 98765 43210');
$wa      = $settings['whatsapp_number'] ?? env('WHATSAPP_NUMBER', '919876543210');
$appUrl  = rtrim(env('APP_URL', ''), '/');
?>

<!-- 1. EXACT MOCKUP HERO SECTION -->
<section class="hero-section text-white">
    <div class="container">
        <div class="row align-items-center">
            <!-- Left: Headline, Subtitle, CTAs & Route Pills -->
            <div class="col-lg-6 pt-3 pb-3">
                <div class="badge-hero-pill mb-3">
                    <i class="bi bi-truck-front-fill me-2"></i>Group Travel Made Easy from Indore
                </div>
                <h1 class="hero-title mb-3">
                    Urbania &amp; Tempo Traveller<br>
                    on Rent in <span class="text-orange">Indore</span>
                </h1>
                <p class="hero-subtext mb-4">
                    Comfortable, Safe and Reliable Travel for Families, Weddings, Pilgrimages, Corporate Trips &amp; Group Tours.
                </p>

                <!-- 3 Action CTAs -->
                <div class="d-flex flex-wrap gap-2 gap-sm-3 mb-4">
                    <a href="tel:<?= htmlspecialchars($phone) ?>" class="btn btn-hero-call" data-log-call="1">
                        <i class="bi bi-telephone-fill me-1"></i> Call Now
                    </a>
                    <a href="<?= htmlspecialchars($waUrl) ?>" target="_blank" rel="noopener" class="btn btn-hero-wa" data-log-whatsapp="1">
                        <i class="bi bi-whatsapp me-1"></i> WhatsApp
                    </a>
                    <a href="#quick-quote-card" class="btn btn-hero-quote">
                        <i class="bi bi-card-checklist me-1"></i> Get a Quote
                    </a>
                </div>

                <!-- Quick Route Pills -->
                <div class="d-flex flex-wrap gap-2 hero-route-pills">
                    <a href="/packages/indore-to-ujjain-mahakal-darshan" class="route-pill"><i class="bi bi-geo-alt-fill text-primary me-1"></i>Ujjain</a>
                    <a href="/packages/indore-to-omkareshwar-jyotirlinga" class="route-pill"><i class="bi bi-geo-alt-fill text-primary me-1"></i>Omkareshwar</a>
                    <a href="/packages/maheshwar-mandu-heritage-tour" class="route-pill"><i class="bi bi-geo-alt-fill text-primary me-1"></i>Maheshwar</a>
                    <a href="/packages/maheshwar-mandu-heritage-tour" class="route-pill"><i class="bi bi-geo-alt-fill text-primary me-1"></i>Mandu</a>
                    <a href="/packages/pachmarhi-hill-station-tour" class="route-pill"><i class="bi bi-geo-alt-fill text-primary me-1"></i>Pachmarhi</a>
                    <a href="/packages/shirdi-sai-baba-darshan" class="route-pill"><i class="bi bi-geo-alt-fill text-primary me-1"></i>Shirdi</a>
                    <a href="/packages/khajuraho-heritage-tour" class="route-pill"><i class="bi bi-geo-alt-fill text-primary me-1"></i>Khajuraho</a>
                </div>
            </div>

            <!-- Right: 3-Vehicle Graphic with Mahakaleshwar sketch -->
            <div class="col-lg-6 text-center text-lg-end hero-graphic-wrap">
                <img src="/assets/images/hero-vehicles.svg" alt="Urbania &amp; Tempo Traveller rentals in Indore" class="img-fluid hero-graphic">
            </div>
        </div>
    </div>
</section>



<!-- 2. FLOATING QUICK QUOTE CARD -->
<div class="container quick-quote-wrapper" id="quick-quote-card">
    <div class="quick-quote-card">
        <form action="/enquiry/submit" method="POST" class="needs-validation ajax-quote-form" novalidate>
            <input type="hidden" name="csrf_token" value="<?= \App\Core\Session::csrfToken() ?>">
            <input type="hidden" name="type" value="booking">
            <input type="hidden" name="source_page" value="Home Quick Quote Card">
            <div style="display:none;"><input type="text" name="website_url"></div>

            <div class="row g-3 align-items-center">
                <!-- Title block -->
                <div class="col-lg-2 col-md-12">
                    <div class="quote-title-block">
                        <span class="quote-badge">Plan Your Trip</span>
                        <h4 class="quote-headline mb-0">Get a Quick Quote</h4>
                    </div>
                </div>

                <!-- Input: Pickup Location -->
                <div class="col-lg-2 col-md-6 col-sm-6">
                    <div class="quote-field-box">
                        <label class="form-label-micro"><i class="bi bi-geo-alt text-primary me-1"></i>Pickup Location</label>
                        <select name="pickup_location" class="form-select quote-input" required>
                            <option value="Indore" selected>Indore</option>
                            <option value="Indore Airport (IDR)">Indore Airport (IDR)</option>
                            <option value="Indore Railway Station">Indore Railway Station</option>
                            <option value="Ujjain">Ujjain</option>
                            <option value="Pithampur / Dewas">Pithampur / Dewas</option>
                        </select>
                    </div>
                </div>

                <!-- Input: Destination -->
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="quote-field-box">
                        <label class="form-label-micro"><i class="bi bi-geo-alt-fill text-primary me-1"></i>Destination</label>
                        <input type="text" name="drop_location" class="form-control quote-input" placeholder="e.g. Ujjain, Omkareshwar" required>
                    </div>
                </div>

                <!-- Input: Travel Date -->
                <div class="col-lg-2 col-md-6 col-sm-6">
                    <div class="quote-field-box">
                        <label class="form-label-micro"><i class="bi bi-calendar-event text-primary me-1"></i>Travel Date</label>
                        <input type="date" name="travel_date" class="form-control quote-input" value="<?= date('Y-m-d') ?>" min="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>

                <!-- Input: Passengers -->
                <div class="col-lg-1 col-md-6 col-sm-6">
                    <div class="quote-field-box">
                        <label class="form-label-micro"><i class="bi bi-people text-primary me-1"></i>Passengers</label>
                        <select name="passengers" class="form-select quote-input" required>
                            <option value="" disabled selected>Select</option>
                            <option value="4">4-6</option>
                            <option value="7">7-8</option>
                            <option value="12">9-12</option>
                            <option value="17">13-17</option>
                            <option value="20">17+</option>
                        </select>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="col-lg-2 col-md-12">
                    <button type="submit" class="btn btn-quote-submit w-100">
                        <i class="bi bi-send-fill me-1"></i> Send Enquiry
                    </button>
                </div>
            </div>

            <!-- Trust micro badges row inside card -->
            <div class="quick-quote-trust-row mt-3 pt-3 border-top d-flex flex-wrap justify-content-between align-items-center">
                <div class="trust-micro-item"><i class="bi bi-lightning-charge-fill text-warning me-1"></i>We reply within 15 minutes</div>
                <div class="trust-micro-divider d-none d-md-block">|</div>
                <div class="trust-micro-item"><i class="bi bi-shield-check text-primary me-1"></i>No Hidden Charges</div>
                <div class="trust-micro-divider d-none d-md-block">|</div>
                <div class="trust-micro-item"><i class="bi bi-droplet-fill text-info me-1"></i>Clean &amp; Sanitized Vehicles</div>
                <div class="trust-micro-divider d-none d-md-block">|</div>
                <div class="trust-micro-item"><i class="bi bi-headset text-success me-1"></i>24&times;7 Travel Support</div>
            </div>
        </form>
    </div>
</div>

<!-- 3. TRUST COUNTER STRIP (6 BADGES) -->
<section class="trust-badges-strip py-4">
    <div class="container">
        <div class="row g-3 g-lg-4 text-center">
            <div class="col-6 col-md-4 col-lg-2">
                <div class="trust-badge-card">
                    <div class="trust-badge-icon"><i class="bi bi-calendar4-week"></i></div>
                    <div class="trust-badge-title">10+ Years</div>
                    <div class="trust-badge-desc">of Experience</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="trust-badge-card">
                    <div class="trust-badge-icon"><i class="bi bi-people-fill"></i></div>
                    <div class="trust-badge-title">5,000+</div>
                    <div class="trust-badge-desc">Happy Customers</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="trust-badge-card">
                    <div class="trust-badge-icon"><i class="bi bi-person-badge-fill"></i></div>
                    <div class="trust-badge-title">Verified</div>
                    <div class="trust-badge-desc">&amp; Experienced Drivers</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="trust-badge-card">
                    <div class="trust-badge-icon"><i class="bi bi-geo-alt-fill"></i></div>
                    <div class="trust-badge-title">GPS Tracked</div>
                    <div class="trust-badge-desc">Vehicles</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="trust-badge-card">
                    <div class="trust-badge-icon"><i class="bi bi-shield-check"></i></div>
                    <div class="trust-badge-title">Clean &amp;</div>
                    <div class="trust-badge-desc">Sanitized Fleet</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="trust-badge-card">
                    <div class="trust-badge-icon"><i class="bi bi-currency-rupee"></i></div>
                    <div class="trust-badge-title">Best Value</div>
                    <div class="trust-badge-desc">&amp; Transparent Service</div>
                </div>
            </div>
        </div>
    </div>
</section>



<!-- 4. OUR FLEET SHOWCASE -->
<section class="py-5 fleet-showcase-section">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h2 class="section-heading mb-1">Our Fleet &ndash; Choose the Right Vehicle for Your Trip</h2>
                <p class="text-muted mb-0">Well-maintained, comfortable and AC vehicles for every group size and travel need.</p>
            </div>
            <a href="/fleet" class="btn btn-outline-primary btn-pill mt-3 mt-md-0">
                View All Vehicles <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-4">
            <?php
            $fleetCards = [
                'force-urbania' => [
                    'name' => 'Force Urbania',
                    'badge' => 'Premium',
                    'badge_class' => 'badge-pill-orange',
                    'img' => '/assets/images/vehicles/urbania.svg',
                    'specs' => [
                        ['icon' => 'bi-people-fill', 'text' => '17 Seats'],
                        ['icon' => 'bi-snow', 'text' => 'AC'],
                        ['icon' => 'bi-briefcase-fill', 'text' => 'Spacious'],
                    ],
                    'ideal' => 'Family Trips, Corporate Tours, Weddings, Pilgrimage'
                ],
                'tempo-traveller' => [
                    'name' => 'Tempo Traveller',
                    'badge' => 'Group Favourite',
                    'badge_class' => 'badge-pill-amber',
                    'img' => '/assets/images/vehicles/tempo-traveller.svg',
                    'specs' => [
                        ['icon' => 'bi-people-fill', 'text' => '12 / 17 Seats'],
                        ['icon' => 'bi-snow', 'text' => 'AC'],
                        ['icon' => 'bi-briefcase-fill', 'text' => 'Comfortable'],
                    ],
                    'ideal' => 'Group Tours, School/College Trips, Religious Yatra'
                ],
                'innova-crysta' => [
                    'name' => 'Innova Crysta',
                    'badge' => 'Family Choice',
                    'badge_class' => 'badge-pill-orange',
                    'img' => '/assets/images/vehicles/innova-crysta.svg',
                    'specs' => [
                        ['icon' => 'bi-people-fill', 'text' => '7 / 8 Seats'],
                        ['icon' => 'bi-snow', 'text' => 'AC'],
                        ['icon' => 'bi-briefcase-fill', 'text' => 'Premium'],
                    ],
                    'ideal' => 'Family Travel, Outstation Trips, Business Travel'
                ],
                'toyota-fortuner' => [
                    'name' => 'Toyota Fortuner',
                    'badge' => 'Luxury',
                    'badge_class' => 'badge-pill-amber',
                    'img' => '/assets/images/vehicles/fortuner.svg',
                    'specs' => [
                        ['icon' => 'bi-people-fill', 'text' => '7 Seats'],
                        ['icon' => 'bi-snow', 'text' => 'AC'],
                        ['icon' => 'bi-briefcase-fill', 'text' => 'SUV'],
                    ],
                    'ideal' => 'VIP Travel, Weddings, Corporate & Long Tours'
                ]
            ];

            foreach ($fleetCards as $slug => $meta):
                $vPage = "/fleet/{$slug}";
                $vWaMsg = "Hello OM Trip and Travels, I am interested in booking the " . $meta['name'] . " on rent in Indore. Page: " . $appUrl . $vPage;
                $vWaUrl = "https://wa.me/{$wa}?text=" . rawurlencode($vWaMsg);
            ?>
            <div class="col-lg-3 col-md-6">
                <div class="card mockup-vehicle-card h-100">
                    <div class="mockup-card-img-wrap">
                        <span class="mockup-corner-badge <?= $meta['badge_class'] ?>"><?= $meta['badge'] ?></span>
                        <a href="<?= $vPage ?>" class="w-100 text-center">
                            <img src="<?= htmlspecialchars($meta['img']) ?>" alt="<?= htmlspecialchars($meta['name']) ?>" class="vehicle-mockup-img" loading="lazy">
                        </a>
                    </div>
                    <div class="card-body d-flex flex-column p-3">
                        <h3 class="mockup-vehicle-name mb-2">
                            <a href="<?= $vPage ?>"><?= htmlspecialchars($meta['name']) ?></a>
                        </h3>

                        <!-- Specs row -->
                        <div class="mockup-specs-row mb-2">
                            <?php foreach ($meta['specs'] as $sp): ?>
                            <span class="spec-tag"><i class="bi <?= $sp['icon'] ?> me-1"></i><?= $sp['text'] ?></span>
                            <?php endforeach; ?>
                        </div>

                        <!-- Ideal for -->
                        <p class="mockup-ideal-text text-muted mb-3 flex-grow-1">
                            <strong>Ideal for:</strong> <?= htmlspecialchars($meta['ideal']) ?>
                        </p>

                        <!-- Dual Action Buttons -->
                        <div class="row g-2 pt-2 border-top">
                            <div class="col-6">
                                <a href="tel:<?= htmlspecialchars($phone) ?>" class="btn btn-call btn-sm w-100" data-log-call="1">
                                    <i class="bi bi-telephone-fill me-1"></i>Call Now
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="<?= htmlspecialchars($vWaUrl) ?>" target="_blank" rel="noopener" class="btn btn-whatsapp btn-sm w-100" data-log-whatsapp="1">
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
</section>



<!-- 5. CHOOSE BY GROUP SIZE BANNER -->
<section class="choose-group-size-strip text-white">
    <div class="container">
        <div class="row align-items-center g-3">
            <div class="col-lg-5 d-flex align-items-center">
                <div class="temple-icon-wrap me-3">
                    <img src="/assets/images/logo.svg" alt="OM" height="38" class="filter-white">
                </div>
                <div>
                    <h4 class="mb-0 fw-bold">Choose by Group Size</h4>
                    <small class="text-white-50">Find the perfect vehicle as per your travel group.</small>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="row g-2">
                    <div class="col-md-4 col-sm-4">
                        <a href="/fleet?category=suv" class="group-size-card">
                            <div class="group-size-icon"><i class="bi bi-car-front-fill"></i></div>
                            <div class="group-size-content">
                                <div class="group-size-seats">5 &ndash; 7 Seats</div>
                                <div class="group-size-names">Cars &amp; SUVs <span class="sub">(Innova, Crysta, Fortuner)</span></div>
                            </div>
                            <i class="bi bi-chevron-right ms-auto"></i>
                        </a>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <a href="/fleet/tempo-traveller" class="group-size-card">
                            <div class="group-size-icon"><i class="bi bi-truck-front-fill"></i></div>
                            <div class="group-size-content">
                                <div class="group-size-seats">8 &ndash; 12 Seats</div>
                                <div class="group-size-names">Tempo Traveller <span class="sub">(12 Seater)</span></div>
                            </div>
                            <i class="bi bi-chevron-right ms-auto"></i>
                        </a>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <a href="/fleet/force-urbania" class="group-size-card">
                            <div class="group-size-icon"><i class="bi bi-bus-front-fill"></i></div>
                            <div class="group-size-content">
                                <div class="group-size-seats">13 &ndash; 17 Seats</div>
                                <div class="group-size-names">Urbania / Tempo <span class="sub">(17 Seater)</span></div>
                            </div>
                            <i class="bi bi-chevron-right ms-auto"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<!-- 6. POPULAR ROUTES & PILGRIMAGE PACKAGES FROM INDORE -->
<section class="py-5 bg-light">
    <div class="container py-3">
        <div class="text-center mb-5">
            <span class="text-primary fw-bold text-uppercase fs-7">Spiritual &amp; Leisure Getaways</span>
            <h2 class="section-title">Popular Tour Routes from Indore</h2>
            <div class="section-divider"></div>
            <p class="section-subtitle">Comfortable group travel packages from Indore to Jyotirlingas, hill stations, and royal palaces of Central India.</p>
        </div>
        <div class="row g-4">
            <?php foreach ($packages as $pkg):
                $pkgUrl = '/packages/' . htmlspecialchars($pkg['slug']);
                $pkgPageUrl = $appUrl . $pkgUrl;
                $pkgWaMsg = "Hello OM Trip and Travels, I want to enquire about the " . $pkg['name'] . " tour package. Page: " . $pkgPageUrl;
                $pkgWaUrl = "https://wa.me/{$wa}?text=" . rawurlencode($pkgWaMsg);
            ?>
            <div class="col-lg-3 col-md-6">
                <div class="package-card card">
                    <img src="<?= htmlspecialchars($pkg['primary_image'] ?: 'https://images.unsplash.com/photo-1590050752117-238cb0fb12b1?auto=format&fit=crop&w=600&q=80') ?>" class="pkg-img" alt="<?= htmlspecialchars($pkg['name']) ?>" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1590050752117-238cb0fb12b1?auto=format&fit=crop&w=600&q=80'">
                    <div class="card-body d-flex flex-column">
                        <span class="package-badge"><?= (int)$pkg['duration_days'] ?> Days / <?= (int)$pkg['duration_nights'] ?> Nights</span>
                        <h3 class="h6 fw-bold mb-2"><a href="<?= $pkgUrl ?>" class="text-dark text-decoration-none"><?= htmlspecialchars($pkg['name']) ?></a></h3>
                        <p class="text-muted small mb-3 flex-grow-1"><i class="bi bi-pin-map-fill text-danger me-1"></i><?= htmlspecialchars($pkg['route'] ?? 'Indore Outstation Route') ?></p>
                        
                        <div class="row g-2 pt-2 border-top">
                            <div class="col-6">
                                <a href="tel:<?= htmlspecialchars($phone) ?>" class="btn btn-outline-primary btn-sm w-100" data-log-call="1" data-package-id="<?= $pkg['id'] ?>">
                                    <i class="bi bi-telephone-fill me-1"></i>Call
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="<?= htmlspecialchars($pkgWaUrl) ?>" target="_blank" rel="noopener" class="btn btn-whatsapp btn-sm w-100" data-log-whatsapp="1" data-package-id="<?= $pkg['id'] ?>">
                                    <i class="bi bi-whatsapp me-1"></i>Enquire
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="/packages" class="btn btn-primary px-4 py-2">Explore All Tour Packages <i class="bi bi-arrow-right ms-1"></i></a>
        </div>
    </div>
</section>



<!-- 6. WHY CHOOSE OM TRIP AND TRAVELS -->
<section class="py-5">
    <div class="container py-3">
        <div class="text-center mb-5">
            <span class="text-primary fw-bold text-uppercase fs-7">Our Promise</span>
            <h2 class="section-title">Why Choose OM Trip and Travels?</h2>
            <div class="section-divider"></div>
            <p class="section-subtitle">We have served thousands of happy pilgrims, families, and corporates across Madhya Pradesh with highest safety and punctuality.</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="why-card">
                    <div class="why-icon"><i class="bi bi-person-badge-fill"></i></div>
                    <h3 class="h5 fw-bold mb-2">Expert Local Drivers</h3>
                    <p class="text-muted small mb-0">Polite, uniform-clad, police-verified drivers well-versed with MP road conditions and temple VIP routes.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="why-card">
                    <div class="why-icon"><i class="bi bi-truck"></i></div>
                    <h3 class="h5 fw-bold mb-2">Modern Clean Fleet</h3>
                    <p class="text-muted small mb-0">Force Urbania, Tempo Travellers, and Innova Crysta sanitized before every departure with ice box and pushback comfort.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="why-card">
                    <div class="why-icon"><i class="bi bi-wallet2"></i></div>
                    <h3 class="h5 fw-bold mb-2">No Hidden Charges</h3>
                    <p class="text-muted small mb-0">Transparent billing with upfront quotes including fuel, driver bhatta, and clear parking policies.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="why-card">
                    <div class="why-icon"><i class="bi bi-headset"></i></div>
                    <h3 class="h5 fw-bold mb-2">24/7 Helpline</h3>
                    <p class="text-muted small mb-0">Round-the-clock booking assistance, emergency backup vehicle dispatch, and live trip support.</p>
                </div>
            </div>
        </div>
    </div>
</section>



<!-- 7. TESTIMONIALS -->
<section class="py-5 bg-light">
    <div class="container py-3">
        <div class="text-center mb-5">
            <span class="text-primary fw-bold text-uppercase fs-7">Customer Voices</span>
            <h2 class="section-title">What Our Travelers Say</h2>
            <div class="section-divider"></div>
        </div>
        <div class="row g-4">
            <?php foreach ($testimonials as $t): ?>
            <div class="col-lg-4 col-md-6">
                <div class="testimonial-card">
                    <div class="stars mb-2">
                        <?php for ($s = 1; $s <= 5; $s++): ?>
                        <i class="bi <?= $s <= (int)$t['rating'] ? 'bi-star-fill' : 'bi-star' ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <p class="mb-3 text-secondary small fst-italic">"<?= htmlspecialchars($t['review']) ?>"</p>
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle me-3 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:44px; height:44px;">
                            <?= strtoupper(substr($t['name'], 0, 1)) ?>
                        </div>
                        <div>
                            <h4 class="h6 fw-bold mb-0"><?= htmlspecialchars($t['name']) ?></h4>
                            <small class="text-muted"><?= htmlspecialchars($t['location'] ?? 'Indore') ?> &bull; Booked <?= htmlspecialchars($t['vehicle_booked'] ?? 'Travel') ?></small>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>



<!-- 8. LATEST BLOG POSTS -->
<?php if (!empty($blogs)): ?>
<section class="py-5">
    <div class="container py-3">
        <div class="d-flex flex-wrap justify-content-between align-items-end mb-4">
            <div>
                <span class="text-primary fw-bold text-uppercase fs-7">Travel Advice &amp; Guides</span>
                <h2 class="section-title mb-0">Latest From Our Travel Blog</h2>
            </div>
            <a href="/blog" class="btn btn-outline-primary mt-3 mt-md-0">All Articles <i class="bi bi-arrow-right ms-1"></i></a>
        </div>
        <div class="row g-4">
            <?php foreach ($blogs as $b):
                $bUrl = '/blog/' . htmlspecialchars($b['slug']);
            ?>
            <div class="col-lg-4 col-md-6">
                <div class="blog-card card">
                    <img src="<?= htmlspecialchars($b['featured_image'] ?: 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=600&q=80') ?>" class="blog-img" alt="<?= htmlspecialchars($b['title']) ?>" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=600&q=80'">
                    <div class="card-body d-flex flex-column">
                        <span class="blog-category-badge align-self-start mb-2"><?= htmlspecialchars($b['category_name'] ?? 'Travel Guide') ?></span>
                        <h3 class="h6 fw-bold mb-2"><a href="<?= $bUrl ?>" class="text-dark text-decoration-none"><?= htmlspecialchars($b['title']) ?></a></h3>
                        <p class="text-muted small mb-3 flex-grow-1"><?= htmlspecialchars($b['excerpt'] ?? '') ?></p>
                        <div class="d-flex justify-content-between align-items-center text-muted small pt-2 border-top">
                            <span><i class="bi bi-calendar3 me-1"></i><?= formatDate($b['published_at'] ?? $b['created_at']) ?></span>
                            <a href="<?= $bUrl ?>" class="text-primary fw-bold text-decoration-none">Read More &rarr;</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>


<?php endif; ?>

<!-- 9. FAQ ACCORDION PREVIEW -->
<section class="py-5 bg-light">
    <div class="container py-3">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-4">
                    <span class="text-primary fw-bold text-uppercase fs-7">Help &amp; Questions</span>
                    <h2 class="section-title">Frequently Asked Questions</h2>
                    <div class="section-divider"></div>
                </div>
                <div class="accordion faq-accordion" id="homeFaq">
                    <?php foreach ($faqs as $i => $faq): ?>
                    <div class="accordion-item shadow-sm border-0 mb-2">
                        <h2 class="accordion-header" id="headingFaq<?= $i ?>">
                            <button class="accordion-button <?= $i === 0 ? '' : 'collapsed' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFaq<?= $i ?>">
                                <?= htmlspecialchars($faq['question']) ?>
                            </button>
                        </h2>
                        <div id="collapseFaq<?= $i ?>" class="accordion-collapse collapse <?= $i === 0 ? 'show' : '' ?>" data-bs-parent="#homeFaq">
                            <div class="accordion-body text-muted small">
                                <?= nl2br(htmlspecialchars($faq['answer'])) ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="text-center mt-3">
                    <a href="/faq" class="btn btn-outline-secondary btn-sm">View More Questions &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</section>



<!-- 10. FINAL CALL TO ACTION STRIP -->
<section class="cta-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <h2 class="display-6 fw-bold mb-3">Planning a Group Trip from Indore?</h2>
                <p class="lead mb-4 text-white-50">Call us now or send a WhatsApp message with your passenger count and destination for an instant quotation. No online payment required!</p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="tel:<?= htmlspecialchars($phone) ?>" class="btn btn-primary btn-lg px-4" data-log-call="1">
                        <i class="bi bi-telephone-fill me-2"></i>Call: <?= htmlspecialchars($phone) ?>
                    </a>
                    <a href="<?= htmlspecialchars($waUrl) ?>" target="_blank" rel="noopener" class="btn btn-whatsapp btn-lg px-4" data-log-whatsapp="1">
                        <i class="bi bi-whatsapp me-2"></i>WhatsApp Us Now
                    </a>
                    <a href="/booking" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-envelope-fill me-2"></i>Booking Form
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 11. QUICK QUOTE CONTACT MODAL -->
<div class="modal fade" id="quickQuoteModal" tabindex="-1" aria-labelledby="quickQuoteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <div>
                    <span class="badge bg-primary px-2 py-1 mb-1">Instant Travel Quote</span>
                    <h5 class="modal-title fw-bold" id="quickQuoteModalLabel">Where should we send your quote?</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 pt-3">
                <div class="p-3 mb-3 rounded-3 bg-light border text-muted small">
                    <div class="row g-2">
                        <div class="col-6"><strong>Route:</strong> <span id="modal-route-preview">Indore to Ujjain</span></div>
                        <div class="col-6"><strong>Date:</strong> <span id="modal-date-preview">-</span></div>
                        <div class="col-6"><strong>Passengers:</strong> <span id="modal-passengers-preview">-</span></div>
                        <div class="col-6"><strong>Suggested Fleet:</strong> <span class="text-primary fw-bold">Urbania / Tempo</span></div>
                    </div>
                </div>

                <form id="quickQuoteContactForm" class="needs-validation" novalidate>
                    <input type="hidden" name="type" value="booking">
                    <input type="hidden" name="pickup_location" id="modal-pickup">
                    <input type="hidden" name="drop_location" id="modal-drop">
                    <input type="hidden" name="travel_date" id="modal-date">
                    <input type="hidden" name="passengers" id="modal-passengers">
                    <input type="hidden" name="source_page" value="Home Quick Quote Modal">
                    <input type="hidden" name="_csrf" value="<?= \App\Core\Session::csrfToken() ?>">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Your Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Rajesh Sharma" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Mobile / WhatsApp Number <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">+91</span>
                            <input type="tel" name="phone" class="form-control" placeholder="98260 12345" pattern="[6-9][0-9]{9}" required>
                        </div>
                        <small class="text-muted">We will send fare options &amp; vehicle photos on WhatsApp.</small>
                    </div>

                    <div id="modal-quote-alert" class="alert d-none mb-3"></div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" id="btn-modal-quote-submit">
                        <i class="bi bi-whatsapp me-2"></i> Get Quote on WhatsApp / Call
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
