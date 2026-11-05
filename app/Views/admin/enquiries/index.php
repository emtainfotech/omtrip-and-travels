<?php
/**
 * Admin Enquiries Listing View.
 */
?>
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <h2 class="h4 fw-bold mb-0">Enquiries &amp; Booking Leads</h2>
    <div>
        <a href="/admin/enquiries?<?= http_build_query(array_merge($filters, ['export' => 'csv'])) ?>" class="btn btn-outline-success btn-sm">
            <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export to CSV
        </a>
    </div>
</div>

<!-- FILTERS CARD -->
<div class="admin-card card mb-4">
    <div class="card-body p-3">
        <form action="/admin/enquiries" method="GET" class="row g-2 align-items-center">
            <div class="col-md-2 col-sm-6">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search name/phone..." value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
            </div>
            <div class="col-md-2 col-sm-6">
                <select name="type" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    <option value="booking" <?= ($filters['type'] ?? '') === 'booking' ? 'selected' : '' ?>>Booking Requests</option>
                    <option value="contact" <?= ($filters['type'] ?? '') === 'contact' ? 'selected' : '' ?>>General Contact</option>
                </select>
            </div>
            <div class="col-md-2 col-sm-6">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    <option value="new" <?= ($filters['status'] ?? '') === 'new' ? 'selected' : '' ?>>New</option>
                    <option value="contacted" <?= ($filters['status'] ?? '') === 'contacted' ? 'selected' : '' ?>>Contacted</option>
                    <option value="followup" <?= ($filters['status'] ?? '') === 'followup' ? 'selected' : '' ?>>Follow-up</option>
                    <option value="confirmed" <?= ($filters['status'] ?? '') === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                    <option value="closed" <?= ($filters['status'] ?? '') === 'closed' ? 'selected' : '' ?>>Closed</option>
                    <option value="spam" <?= ($filters['status'] ?? '') === 'spam' ? 'selected' : '' ?>>Spam</option>
                </select>
            </div>
            <div class="col-md-2 col-sm-6">
                <select name="vehicle_id" class="form-select form-select-sm">
                    <option value="">All Vehicles</option>
                    <?php foreach ($vehicles as $v): ?>
                    <option value="<?= $v['id'] ?>" <?= ($filters['vehicle_id'] ?? 0) === (int)$v['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($v['name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2 col-sm-6">
                <input type="date" name="date_from" class="form-control form-control-sm" title="From date" value="<?= htmlspecialchars($filters['date_from'] ?? '') ?>">
            </div>
            <div class="col-md-2 col-sm-6 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm flex-grow-1"><i class="bi bi-filter"></i> Filter</button>
                <a href="/admin/enquiries" class="btn btn-outline-secondary btn-sm" title="Reset filters"><i class="bi bi-arrow-counterclockwise"></i></a>
            </div>
        </form>
    </div>
</div>

<!-- ENQUIRIES TABLE -->
<div class="admin-card card">
    <div class="table-responsive">
        <table class="table admin-table mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer Name</th>
                    <th>Phone</th>
                    <th>Type</th>
                    <th>Vehicle / Package</th>
                    <th>Travel Date</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($enquiries)): ?>
                <tr>
                    <td colspan="9" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox display-5 d-block mb-2"></i>
                        No enquiries match the current filter criteria.
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($enquiries as $e):
                    $cleanPhone = preg_replace('/\D/', '', $e['phone']);
                    $waLink = "https://wa.me/91{$cleanPhone}?text=" . rawurlencode("Hello " . $e['name'] . ", greetings from OM Trip and Travels Indore. We received your travel enquiry.");
                ?>
                <tr class="<?= empty($e['is_read']) ? 'table-warning' : '' ?>">
                    <td>#<?= $e['id'] ?></td>
                    <td>
                        <strong><?= htmlspecialchars($e['name']) ?></strong>
                        <?php if (empty($e['is_read'])): ?>
                        <span class="badge bg-danger ms-1" style="font-size:0.65rem;">NEW</span>
                        <?php endif; ?>
                        <?php if (!empty($e['email'])): ?>
                        <small class="d-block text-muted"><?= htmlspecialchars($e['email']) ?></small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="tel:<?= htmlspecialchars($e['phone']) ?>" class="fw-bold text-decoration-none text-dark"><?= htmlspecialchars($e['phone']) ?></a>
                    </td>
                    <td>
                        <span class="badge bg-<?= $e['type'] === 'booking' ? 'primary' : 'secondary' ?>">
                            <?= ucfirst($e['type']) ?>
                        </span>
                    </td>
                    <td>
                        <small class="fw-bold"><?= htmlspecialchars($e['vehicle_name'] ?: ($e['package_name'] ?: 'Custom Route')) ?></small>
                        <?php if (!empty($e['pickup_location'])): ?>
                        <small class="d-block text-muted" style="font-size:0.75rem;"><?= htmlspecialchars($e['pickup_location']) ?> &rarr; <?= htmlspecialchars($e['drop_location'] ?? '') ?></small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <small><?= $e['travel_date'] ? formatDate($e['travel_date']) : '<span class="text-muted">Not specified</span>' ?></small>
                    </td>
                    <td>
                        <select class="form-select form-select-sm status-select status-<?= htmlspecialchars($e['status']) ?>" data-id="<?= $e['id'] ?>" style="width:120px; font-size:0.78rem; font-weight:600;">
                            <option value="new"       <?= $e['status'] === 'new' ? 'selected' : '' ?>>New</option>
                            <option value="contacted" <?= $e['status'] === 'contacted' ? 'selected' : '' ?>>Contacted</option>
                            <option value="followup"  <?= $e['status'] === 'followup' ? 'selected' : '' ?>>Follow-up</option>
                            <option value="confirmed" <?= $e['status'] === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                            <option value="closed"    <?= $e['status'] === 'closed' ? 'selected' : '' ?>>Closed</option>
                            <option value="spam"      <?= $e['status'] === 'spam' ? 'selected' : '' ?>>Spam</option>
                        </select>
                    </td>
                    <td>
                        <small class="text-muted"><?= formatDate($e['created_at'], 'd M, h:i A') ?></small>
                    </td>
                    <td class="text-end">
                        <div class="btn-group btn-group-sm">
                            <a href="/admin/enquiries/<?= $e['id'] ?>" class="btn btn-outline-secondary" title="View Details"><i class="bi bi-eye"></i></a>
                            <a href="tel:<?= htmlspecialchars($e['phone']) ?>" class="btn btn-outline-primary" title="Call Customer"><i class="bi bi-telephone-fill"></i></a>
                            <a href="<?= htmlspecialchars($waLink) ?>" target="_blank" class="btn btn-outline-success" title="WhatsApp Customer"><i class="bi bi-whatsapp"></i></a>
                            <form action="/admin/enquiries/<?= $e['id'] ?>/delete" method="POST" class="d-inline" onsubmit="return confirm('Delete this enquiry?');">
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
                <a class="page-link" href="/admin/enquiries?<?= http_build_query(array_merge($filters, ['page' => $p])) ?>"><?= $p ?></a>
            </li>
            <?php endfor; ?>
        </ul>
    </div>
    <?php endif; ?>
</div>

