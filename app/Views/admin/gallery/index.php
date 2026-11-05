<?php
/**
 * Admin Gallery Management View.
 */
?>
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <h2 class="h4 fw-bold mb-0">Photo Gallery Management</h2>
    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadPhotoModal">
        <i class="bi bi-cloud-arrow-up-fill me-1"></i> Upload Photo
    </button>
</div>

<!-- GALLERY GRID -->
<div class="row g-3">
    <?php if (empty($items)): ?>
    <div class="col-12">
        <div class="alert alert-info text-center py-5">
            <h5>No photos in gallery yet.</h5>
            <p class="text-muted">Upload photos of your Urbania, Tempo Travellers, customer tours, and drivers.</p>
        </div>
    </div>
    <?php else: ?>
    <?php foreach ($items as $item): ?>
    <div class="col-xl-2 col-lg-3 col-md-4 col-6">
        <div class="card h-100 shadow-sm border-0 position-relative overflow-hidden">
            <img src="<?= htmlspecialchars($item['image_path']) ?>" style="height:140px; object-fit:cover;" class="card-img-top">
            <div class="card-body p-2 d-flex justify-content-between align-items-center">
                <small class="text-truncate" style="max-width:110px;"><?= htmlspecialchars($item['title'] ?: ($item['category'] ?? 'Photo')) ?></small>
                <form action="/admin/gallery/<?= $item['id'] ?>/delete" method="POST" onsubmit="return confirm('Delete photo?');">
                    <?= csrfField() ?>
                    <button type="submit" class="btn btn-sm btn-outline-danger p-1" style="line-height:1;"><i class="bi bi-trash"></i></button>
                </form>
            </div>
            <span class="badge bg-secondary position-absolute top-0 start-0 m-2" style="font-size:0.65rem;"><?= htmlspecialchars($item['category'] ?? 'Fleet') ?></span>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- UPLOAD MODAL -->
<div class="modal fade" id="uploadPhotoModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="/admin/gallery/upload" method="POST" enctype="multipart/form-data" class="modal-content">
            <?= csrfField() ?>
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Upload Gallery Photo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Photo Title</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. Force Urbania Captain Seats Interior">
                </div>
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <input type="text" name="category" class="form-control" list="catList" placeholder="e.g. Fleet / Tours / Interior">
                    <datalist id="catList">
                        <option value="Fleet">
                        <option value="Interior">
                        <option value="Tour Groups">
                        <option value="Pilgrimages">
                    </datalist>
                </div>
                <div class="mb-3">
                    <label class="form-label">Select Image File (JPG, PNG, WebP) *</label>
                    <input type="file" name="image" class="form-control" required accept="image/*">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm">Upload Photo</button>
            </div>
        </form>
    </div>
</div>

