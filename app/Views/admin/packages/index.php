<?php
/**
 * Admin Tour Packages Listing View.
 */
?>
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <h2 class="h4 fw-bold mb-0">Tour Packages Management</h2>
    <a href="/admin/packages/create" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Add Tour Package
    </a>
</div>

<div class="admin-card card">
    <div class="table-responsive">
        <table class="table admin-table mb-0">
            <thead>
                <tr>
                    <th style="width:70px;">Image</th>
                    <th>Package Name</th>
                    <th>Route</th>
                    <th>Duration</th>
                    <th>Price</th>
                    <th>Featured</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($packages)): ?>
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">No tour packages created yet.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($packages as $pkg): ?>
                <tr>
                    <td>
                        <img src="<?= htmlspecialchars($pkg['primary_image'] ?: 'https://images.unsplash.com/photo-1590050752117-238cb0fb12b1?auto=format&fit=crop&w=100&q=80') ?>"
                             style="width:60px; height:45px; object-fit:cover; border-radius:6px;" alt="<?= htmlspecialchars($pkg['name']) ?>">
                    </td>
                    <td>
                        <strong><?= htmlspecialchars($pkg['name']) ?></strong>
                        <small class="d-block text-muted">/packages/<?= htmlspecialchars($pkg['slug']) ?></small>
                    </td>
                    <td><small><?= htmlspecialchars($pkg['route'] ?? 'Indore Outstation') ?></small></td>
                    <td><span class="badge bg-secondary"><?= (int)$pkg['duration_days'] ?>D / <?= (int)$pkg['duration_nights'] ?>N</span></td>
                    <td>
                        <span class="fw-bold text-primary">
                            <?= $pkg['starting_price'] ? '₹' . numberFormat((float)$pkg['starting_price']) : 'On Request' ?>
                        </span>
                    </td>
                    <td>
                        <?= $pkg['is_featured'] ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-light text-muted border">No</span>' ?>
                    </td>
                    <td>
                        <span class="badge <?= $pkg['is_active'] ? 'bg-success' : 'bg-danger' ?>">
                            <?= $pkg['is_active'] ? 'Active' : 'Disabled' ?>
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="btn-group btn-group-sm">
                            <a href="/packages/<?= htmlspecialchars($pkg['slug']) ?>" target="_blank" class="btn btn-outline-secondary" title="View on site"><i class="bi bi-box-arrow-up-right"></i></a>
                            <a href="/admin/packages/<?= $pkg['id'] ?>/edit" class="btn btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                            <form action="/admin/packages/<?= $pkg['id'] ?>/delete" method="POST" class="d-inline" onsubmit="return confirm('Delete <?= htmlspecialchars($pkg['name']) ?>?');">
                                <?= csrfField() ?>
                                <button type="submit" class="btn btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

