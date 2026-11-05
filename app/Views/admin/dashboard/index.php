<?php
/**
 * Admin Dashboard View.
 */
?>
<!-- STAT CARDS ROW -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="bi bi-bell-fill"></i>
            </div>
            <div>
                <div class="stat-value"><?= (int)$stats['new_today'] ?></div>
                <div class="stat-label">New Enquiries Today</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-calendar-week-fill"></i>
            </div>
            <div>
                <div class="stat-value"><?= (int)$stats['new_week'] ?></div>
                <div class="stat-label">Past 7 Days Enquiries</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="bi bi-calendar3"></i>
            </div>
            <div>
                <div class="stat-value"><?= (int)$stats['new_month'] ?></div>
                <div class="stat-label">Past 30 Days</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="bi bi-envelope-open-fill"></i>
            </div>
            <div>
                <div class="stat-value"><?= (int)$stats['total_unread'] ?></div>
                <div class="stat-label">Unread Enquiries</div>
            </div>
        </div>
    </div>
</div>

<!-- CALL & WHATSAPP CLICK LOGS STATS ROW -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="p-3 bg-white rounded border shadow-sm d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="p-3 bg-primary-subtle text-primary rounded-circle me-3">
                    <i class="bi bi-telephone-outbound-fill fs-4"></i>
                </div>
                <div>
                    <h3 class="h4 fw-bold mb-0"><?= (int)$clicksSummary['call'] ?></h3>
                    <small class="text-muted">Total Website "Call Now" Clicks</small>
                </div>
            </div>
            <span class="badge bg-primary px-3 py-2">Phone Leads</span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="p-3 bg-white rounded border shadow-sm d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="p-3 rounded-circle me-3" style="background:#e8fdf0; color:#25D366;">
                    <i class="bi bi-whatsapp fs-4"></i>
                </div>
                <div>
                    <h3 class="h4 fw-bold mb-0"><?= (int)$clicksSummary['whatsapp'] ?></h3>
                    <small class="text-muted">Total Website "WhatsApp" Clicks</small>
                </div>
            </div>
            <span class="badge px-3 py-2 text-white" style="background:#25D366;">WhatsApp Leads</span>
        </div>
    </div>
</div>

<!-- CHARTS ROW -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="admin-card card h-100">
            <div class="card-header">
                <span><i class="bi bi-bar-chart-line-fill text-primary me-2"></i>Enquiry Trends (Last 6 Months)</span>
            </div>
            <div class="card-body">
                <canvas id="enquiry-chart" height="110" data-labels='<?= $chartLabels ?>' data-values='<?= $chartValues ?>'></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="admin-card card h-100">
            <div class="card-header">
                <span><i class="bi bi-pie-chart-fill text-primary me-2"></i>Enquiry by Status</span>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <div style="max-width:240px; width:100%;">
                    <canvas id="status-chart" data-labels='<?= $statusLabels ?>' data-values='<?= $statusValues ?>'></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- RECENT ENQUIRIES TABLE & TOP VEHICLES -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="admin-card card">
            <div class="card-header">
                <span><i class="bi bi-inbox-fill text-primary me-2"></i>Recent Enquiries</span>
                <a href="/admin/enquiries" class="btn btn-sm btn-outline-primary">View All &rarr;</a>
            </div>
            <div class="table-responsive">
                <table class="table admin-table mb-0">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th>Vehicle / Route</th>
                            <th>Travel Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($stats['recent'])): ?>
                        <tr><td colspan="6" class="text-center py-4 text-muted">No enquiries recorded yet.</td></tr>
                        <?php else: ?>
                        <?php foreach ($stats['recent'] as $enq): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($enq['name']) ?></strong>
                                <?php if (!$enq['is_read']): ?><span class="badge bg-danger ms-1" style="font-size:0.65rem;">NEW</span><?php endif; ?>
                                <small class="d-block text-muted" style="font-size:0.75rem;"><?= htmlspecialchars(ucfirst($enq['type'])) ?></small>
                            </td>
                            <td>
                                <a href="tel:<?= htmlspecialchars($enq['phone']) ?>" class="text-decoration-none fw-bold"><?= htmlspecialchars($enq['phone']) ?></a>
                            </td>
                            <td>
                                <small><?= htmlspecialchars($enq['vehicle_name'] ?: ($enq['pickup_location'] ? $enq['pickup_location'] . ' → ' . $enq['drop_location'] : 'General Enquiry')) ?></small>
                            </td>
                            <td>
                                <small class="text-muted"><?= $enq['travel_date'] ? formatDate($enq['travel_date']) : 'Flexible' ?></small>
                            </td>
                            <td>
                                <span class="status-badge status-<?= htmlspecialchars($enq['status']) ?>">
                                    <?= ucfirst($enq['status']) ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="/admin/enquiries/<?= $enq['id'] ?>" class="btn btn-outline-secondary" title="View details"><i class="bi bi-eye"></i></a>
                                    <a href="https://wa.me/91<?= preg_replace('/\D/', '', $enq['phone']) ?>?text=Hello%20<?= urlencode($enq['name']) ?>%2C%20thank%20you%20for%20contacting%20OM%20Trip%20and%20Travels." target="_blank" class="btn btn-outline-success" title="WhatsApp customer"><i class="bi bi-whatsapp"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TOP ENQUIRED VEHICLES & SHORTCUTS -->
    <div class="col-lg-4">
        <div class="admin-card card mb-4">
            <div class="card-header">
                <span><i class="bi bi-trophy-fill text-warning me-2"></i>Top Enquired Vehicles</span>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush small">
                    <?php if (empty($stats['top_vehicles'])): ?>
                    <li class="list-group-item text-muted py-3">No vehicle enquiries yet.</li>
                    <?php else: ?>
                    <?php foreach ($stats['top_vehicles'] as $v): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <span class="fw-bold"><?= htmlspecialchars($v['name']) ?></span>
                        <span class="badge bg-primary rounded-pill"><?= (int)$v['enquiry_count'] ?> requests</span>
                    </li>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <div class="admin-card card">
            <div class="card-header">
                <span><i class="bi bi-lightning-charge-fill text-primary me-2"></i>Quick Management</span>
            </div>
            <div class="card-body d-grid gap-2">
                <a href="/admin/enquiries" class="btn btn-outline-primary text-start"><i class="bi bi-inbox me-2"></i>Process Enquiries</a>
                <a href="/admin/vehicles/create" class="btn btn-outline-secondary text-start"><i class="bi bi-truck me-2"></i>Add New Vehicle</a>
                <a href="/admin/packages/create" class="btn btn-outline-secondary text-start"><i class="bi bi-map me-2"></i>Add Tour Package</a>
                <a href="/admin/blog/create" class="btn btn-outline-secondary text-start"><i class="bi bi-pencil-square me-2"></i>Write Blog Post</a>
                <a href="/admin/settings" class="btn btn-outline-secondary text-start"><i class="bi bi-gear me-2"></i>Site Settings</a>
            </div>
        </div>
    </div>
</div>

