<?php
/**
 * Admin Vehicles Listing View.
 */
?>
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <h2 class="h4 fw-bold mb-0">Vehicle Fleet Management</h2>
    <a href="/admin/vehicles/create" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Add New Vehicle
    </a>
</div>

<!-- FILTER BAR -->
<div class="admin-card card mb-4">
    <div class="card-body p-3">
        <form action="/admin/vehicles" method="GET" class="row g-2 align-items-center">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by name..." value="<?= htmlspecialchars($search ?? '') ?>">
            </div>
            <div class="col-md-4">
                <select name="category_id" class="form-select form-select-sm">
                    <option value="0">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $categoryId === (int)$cat['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm flex-grow-1"><i class="bi bi-search me-1"></i>Search</button>
                <a href="/admin/vehicles" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-counterclockwise"></i></a>
            </div>
        </form>
    </div>
</div>

<!-- VEHICLES TABLE -->
<div class="admin-card card">
    <div class="table-responsive">
        <table class="table admin-table mb-0">
            <thead>
                <tr>
                    <th style="width:70px;">Image</th>
                    <th>Vehicle Name</th>
                    <th>Category</th>
                    <th>Seats</th>
                    <th>Starting Price</th>
                    <th>Featured</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($vehicles)): ?>
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">No vehicles found. Click "Add New Vehicle" to add one.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($vehicles as $v): ?>
                <tr>
                    <td>
                        <img src="<?= htmlspecialchars($v['primary_image'] ?: 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=100&q=80') ?>"
                             style="width:60px; height:45px; object-fit:cover; border-radius:6px;" alt="<?= htmlspecialchars($v['name']) ?>">
                    </td>
                    <td>
                        <strong><?= htmlspecialchars($v['name']) ?></strong>
                        <small class="d-block text-muted">/fleet/<?= htmlspecialchars($v['slug']) ?></small>
                    </td>
                    <td><span class="badge bg-secondary"><?= htmlspecialchars($v['category_name']) ?></span></td>
                    <td><i class="bi bi-people-fill text-muted me-1"></i><?= (int)$v['seats'] ?> Seater</td>
                    <td>
                        <span class="fw-bold text-primary">
                            <?= $v['starting_price'] ? '₹' . numberFormat((float)$v['starting_price']) : 'On Request' ?>
                        </span>
                    </td>
                    <td>
                        <?= $v['is_featured'] ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-light text-muted border">No</span>' ?>
                    </td>
                    <td>
                        <form action="/admin/vehicles/<?= $v['id'] ?>/toggle" method="POST" class="d-inline">
                            <?= csrfField() ?>
                            <button type="submit" class="btn btn-sm <?= $v['is_active'] ? 'btn-success' : 'btn-outline-danger' ?> py-0 px-2" style="font-size:0.75rem;">
                                <?= $v['is_active'] ? 'Active' : 'Disabled' ?>
                            </button>
                        </form>
                    </td>
                    <td class="text-end">
                        <div class="btn-group btn-group-sm">
                            <a href="/fleet/<?= htmlspecialchars($v['slug']) ?>" target="_blank" class="btn btn-outline-secondary" title="View on site"><i class="bi bi-box-arrow-up-right"></i></a>
                            <a href="/admin/vehicles/<?= $v['id'] ?>/edit" class="btn btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                            <form action="/admin/vehicles/<?= $v['id'] ?>/delete" method="POST" class="d-inline" onsubmit="return confirm('Delete <?= htmlspecialchars($v['name']) ?> permanently?');">
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

    <!-- PAGINATION -->
    <?php if (($pagination['pages'] ?? 1) > 1): ?>
    <div class="card-footer bg-white d-flex justify-content-between align-items-center py-3">
        <small class="text-muted">Showing page <?= $pagination['current'] ?> of <?= $pagination['pages'] ?> (<?= $pagination['total'] ?> total)</small>
        <ul class="pagination pagination-sm mb-0">
            <?php for ($p = 1; $p <= $pagination['pages']; $p++): ?>
            <li class="page-item <?= $p === $pagination['current'] ? 'active' : '' ?>">
                <a class="page-link" href="/admin/vehicles?page=<?= $p ?>"><?= $p ?></a>
            </li>
            <?php endfor; ?>
        </ul>
    </div>
    <?php endif; ?>
</div>

