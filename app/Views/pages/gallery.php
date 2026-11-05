<?php
/**
 * Gallery view.
 */
?>
<!-- PAGE HERO -->
<div class="page-hero">
    <div class="container text-center">
        <h1>Photo Gallery</h1>
        <p class="text-white-50 max-w-600 mx-auto">Take a visual tour of our clean Force Urbania, Tempo Travellers, SUVs, and happy traveler groups.</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Gallery</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <!-- Category filter buttons -->
    <?php if (!empty($categories)): ?>
    <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
        <a href="/gallery" class="btn btn-sm <?= empty($activeCategory) ? 'btn-primary' : 'btn-outline-secondary' ?>">All Photos</a>
        <?php foreach ($categories as $cat): ?>
        <a href="/gallery?category=<?= urlencode($cat) ?>" class="btn btn-sm <?= $activeCategory === $cat ? 'btn-primary' : 'btn-outline-secondary' ?>">
            <?= htmlspecialchars($cat) ?>
        </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (empty($items)): ?>
    <div class="alert alert-info text-center py-5">
        <h5>No gallery photos currently displayed.</h5>
        <p class="text-muted">Our fleet photos are being updated. Call us for instant photos via WhatsApp!</p>
        <a href="https://wa.me/<?= htmlspecialchars($settings['whatsapp_number'] ?? '919876543210') ?>" target="_blank" class="btn btn-whatsapp"><i class="bi bi-whatsapp me-1"></i>Request Photos on WhatsApp</a>
    </div>
    <?php else: ?>
    <div class="row g-3">
        <?php foreach ($items as $item): ?>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="gallery-item shadow-sm">
                <img src="<?= htmlspecialchars($item['image_path']) ?>" alt="<?= htmlspecialchars($item['alt_text'] ?: ($item['title'] ?? 'OM Trip and Travels')) ?>" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=600&q=80'">
            </div>
            <?php if (!empty($item['title'])): ?>
            <p class="text-muted small mt-1 mb-0 text-center"><?= htmlspecialchars($item['title']) ?></p>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if (($pagination['pages'] ?? 1) > 1): ?>
    <nav class="mt-5" aria-label="Gallery pages">
        <ul class="pagination justify-content-center">
            <?php for ($p = 1; $p <= $pagination['pages']; $p++): ?>
            <li class="page-item <?= $p === $pagination['current'] ? 'active' : '' ?>">
                <a class="page-link" href="/gallery?page=<?= $p ?><?= $activeCategory ? '&category='.urlencode($activeCategory) : '' ?>"><?= $p ?></a>
            </li>
            <?php endfor; ?>
        </ul>
    </nav>
    <?php endif; ?>

    <?php endif; ?>
</div>

