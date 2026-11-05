<?php
/**
 * Admin Testimonials Management View.
 */
?>
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <h2 class="h4 fw-bold mb-0">Customer Testimonials &amp; Reviews</h2>
    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addTestimonialModal">
        <i class="bi bi-plus-lg me-1"></i> Add Review
    </button>
</div>

<div class="admin-card card">
    <div class="table-responsive">
        <table class="table admin-table mb-0">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Location</th>
                    <th>Vehicle Booked</th>
                    <th>Rating</th>
                    <th>Review Text</th>
                    <th>Featured</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($testimonials)): ?>
                <tr><td colspan="8" class="text-center py-4 text-muted">No testimonials added yet.</td></tr>
                <?php else: ?>
                <?php foreach ($testimonials as $t): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($t['name']) ?></strong></td>
                    <td><small class="text-muted"><?= htmlspecialchars($t['location'] ?? 'Indore') ?></small></td>
                    <td><small><?= htmlspecialchars($t['vehicle_booked'] ?? '-') ?></small></td>
                    <td>
                        <span class="text-warning">
                            <?php for ($s=1; $s<=(int)$t['rating']; $s++): ?>★<?php endfor; ?>
                        </span>
                    </td>
                    <td>
                        <small class="text-secondary" style="max-width:260px; display:inline-block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                            <?= htmlspecialchars($t['review']) ?>
                        </small>
                    </td>
                    <td><?= $t['is_featured'] ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-light text-muted border">No</span>' ?></td>
                    <td><span class="badge <?= $t['is_active'] ? 'bg-success' : 'bg-danger' ?>"><?= $t['is_active'] ? 'Active' : 'Hidden' ?></span></td>
                    <td class="text-end">
                        <form action="/admin/testimonials/<?= $t['id'] ?>/delete" method="POST" class="d-inline" onsubmit="return confirm('Delete this testimonial?');">
                            <?= csrfField() ?>
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ADD TESTIMONIAL MODAL -->
<div class="modal fade" id="addTestimonialModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="/admin/testimonials/create" method="POST" class="modal-content">
            <?= csrfField() ?>
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Add Customer Review</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Customer Name *</label>
                    <input type="text" name="name" class="form-control" required placeholder="e.g. Ramesh Patel">
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" class="form-control" placeholder="e.g. Indore">
                    </div>
                    <div class="col-6">
                        <label class="form-label">Vehicle Booked</label>
                        <input type="text" name="vehicle_booked" class="form-control" placeholder="e.g. Force Urbania 17 Seater">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Star Rating (1 to 5)</label>
                    <select name="rating" class="form-select">
                        <option value="5">5 Stars (Excellent)</option>
                        <option value="4">4 Stars (Good)</option>
                        <option value="3">3 Stars (Average)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Review / Feedback *</label>
                    <textarea name="review" class="form-control" rows="3" required placeholder="Write customer feedback here..."></textarea>
                </div>
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="tFeatured" checked>
                    <label class="form-check-label fw-bold small" for="tFeatured">Display on Homepage</label>
                </div>
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="tActive" checked>
                    <label class="form-check-label fw-bold small" for="tActive">Active</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm">Save Review</button>
            </div>
        </form>
    </div>
</div>

