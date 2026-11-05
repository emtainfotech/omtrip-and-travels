<?php
/**
 * Admin Banners Management View.
 */
?>
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <h2 class="h4 fw-bold mb-0">Homepage Hero Banners</h2>
    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addBannerModal">
        <i class="bi bi-plus-lg me-1"></i> Add Banner
    </button>
</div>

<div class="row g-4">
    <?php foreach ($banners as $b): ?>
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm border-0 overflow-hidden">
            <img src="<?= htmlspecialchars($b['image_path']) ?>" style="height:170px; object-fit:cover;" class="card-img-top">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h3 class="h6 fw-bold mb-0"><?= htmlspecialchars($b['title'] ?? 'Banner') ?></h3>
                    <span class="badge <?= $b['is_active'] ? 'bg-success' : 'bg-danger' ?>"><?= $b['is_active'] ? 'Active' : 'Disabled' ?></span>
                </div>
                <p class="text-muted small mb-3"><?= htmlspecialchars($b['subtitle'] ?? '') ?></p>
                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                    <small class="text-muted">Order: <?= (int)$b['sort_order'] ?></small>
                    <form action="/admin/banners/<?= $b['id'] ?>/delete" method="POST" onsubmit="return confirm('Delete banner?');">
                        <?= csrfField() ?>
                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash me-1"></i>Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- ADD BANNER MODAL -->
<div class="modal fade" id="addBannerModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="/admin/banners/create" method="POST" enctype="multipart/form-data" class="modal-content">
            <?= csrfField() ?>
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Add Hero Slide</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Headline / Title *</label>
                    <input type="text" name="title" class="form-control" required placeholder="e.g. Urbania &amp; Tempo Traveller Specialists in Indore">
                </div>
                <div class="mb-3">
                    <label class="form-label">Subtitle / Description</label>
                    <textarea name="subtitle" class="form-control" rows="2" placeholder="Luxury group travel for pilgrimages, tours &amp; weddings."></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Banner Image (JPG, PNG, WebP) *</label>
                    <input type="file" name="image" class="form-control" required accept="image/*">
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label">Call to Action Type</label>
                        <select name="cta_type" class="form-select form-select-sm">
                            <option value="call">Call Now Button</option>
                            <option value="whatsapp">WhatsApp Button</option>
                            <option value="link">Page Link</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control form-control-sm" value="0">
                    </div>
                </div>
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="bActive" checked>
                    <label class="form-check-label small fw-bold" for="bActive">Active</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm">Upload &amp; Save</button>
            </div>
        </form>
    </div>
</div>

