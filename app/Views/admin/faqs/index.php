<?php
/**
 * Admin FAQs Management View.
 */
?>
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <h2 class="h4 fw-bold mb-0">Frequently Asked Questions (FAQ)</h2>
    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addFaqModal">
        <i class="bi bi-plus-lg me-1"></i> Add Question
    </button>
</div>

<div class="admin-card card">
    <div class="table-responsive">
        <table class="table admin-table mb-0">
            <thead>
                <tr>
                    <th>Question</th>
                    <th>Category</th>
                    <th>Answer Preview</th>
                    <th>Sort Order</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($faqs)): ?>
                <tr><td colspan="6" class="text-center py-4 text-muted">No FAQs created yet.</td></tr>
                <?php else: ?>
                <?php foreach ($faqs as $f): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($f['question']) ?></strong></td>
                    <td><span class="badge bg-secondary"><?= htmlspecialchars($f['category'] ?? 'General') ?></span></td>
                    <td>
                        <small class="text-muted" style="max-width:300px; display:inline-block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                            <?= strip_tags($f['answer']) ?>
                        </small>
                    </td>
                    <td><?= (int)$f['sort_order'] ?></td>
                    <td><span class="badge <?= $f['is_active'] ? 'bg-success' : 'bg-danger' ?>"><?= $f['is_active'] ? 'Active' : 'Disabled' ?></span></td>
                    <td class="text-end">
                        <form action="/admin/faqs/<?= $f['id'] ?>/delete" method="POST" class="d-inline" onsubmit="return confirm('Delete this FAQ?');">
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

<!-- ADD FAQ MODAL -->
<div class="modal fade" id="addFaqModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="/admin/faqs/create" method="POST" class="modal-content">
            <?= csrfField() ?>
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Add FAQ Question</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Question *</label>
                    <input type="text" name="question" class="form-control" required placeholder="e.g. Can I hire a vehicle for outstation trips from Indore?">
                </div>
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <input type="text" name="category" class="form-control" list="faqCats" placeholder="e.g. Booking / Pricing / Fleet / General">
                    <datalist id="faqCats">
                        <option value="Booking">
                        <option value="Pricing">
                        <option value="Fleet">
                        <option value="General">
                        <option value="Services">
                    </datalist>
                </div>
                <div class="mb-3">
                    <label class="form-label">Answer *</label>
                    <textarea name="answer" class="form-control" rows="4" required placeholder="Detailed answer..."></textarea>
                </div>
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control form-control-sm" value="0">
                    </div>
                    <div class="col-6 d-flex align-items-end mb-1">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="fActive" checked>
                            <label class="form-check-label small fw-bold" for="fActive">Active</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm">Save FAQ</button>
            </div>
        </form>
    </div>
</div>

