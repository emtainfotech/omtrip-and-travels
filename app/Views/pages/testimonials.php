<?php
/**
 * Testimonials view.
 */
?>
<!-- PAGE HERO -->
<div class="page-hero">
    <div class="container text-center">
        <h1>Traveler Reviews &amp; Testimonials</h1>
        <p class="text-white-50 max-w-600 mx-auto">Read real experiences from families, pilgrims, and corporate clients who traveled with OM Trip and Travels.</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Reviews</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <?php foreach ($testimonials as $t): ?>
        <div class="col-lg-4 col-md-6">
            <div class="testimonial-card card h-100 p-4 border-0 shadow-sm">
                <div class="stars mb-2">
                    <?php for ($s = 1; $s <= 5; $s++): ?>
                    <i class="bi <?= $s <= (int)$t['rating'] ? 'bi-star-fill' : 'bi-star' ?>"></i>
                    <?php endfor; ?>
                </div>
                <p class="mb-3 text-secondary small fst-italic">"<?= htmlspecialchars($t['review']) ?>"</p>
                <div class="d-flex align-items-center mt-auto pt-2 border-top">
                    <div class="avatar-circle me-3 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:44px; height:44px;">
                        <?= strtoupper(substr($t['name'], 0, 1)) ?>
                    </div>
                    <div>
                        <h2 class="h6 fw-bold mb-0"><?= htmlspecialchars($t['name']) ?></h2>
                        <small class="text-muted"><?= htmlspecialchars($t['location'] ?? 'Indore') ?> &bull; Booked <?= htmlspecialchars($t['vehicle_booked'] ?? 'Travel') ?></small>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if (($pagination['pages'] ?? 1) > 1): ?>
    <nav class="mt-5" aria-label="Reviews pages">
        <ul class="pagination justify-content-center">
            <?php for ($p = 1; $p <= $pagination['pages']; $p++): ?>
            <li class="page-item <?= $p === $pagination['current'] ? 'active' : '' ?>">
                <a class="page-link" href="/testimonials?page=<?= $p ?>"><?= $p ?></a>
            </li>
            <?php endfor; ?>
        </ul>
    </nav>
    <?php endif; ?>
</div>

