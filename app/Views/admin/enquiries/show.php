<?php
/**
 * Admin Enquiry Detail View.
 */
$cleanPhone = preg_replace('/\D/', '', $enquiry['phone']);
$waLink = "https://wa.me/91{$cleanPhone}?text=" . rawurlencode("Hello " . $enquiry['name'] . ", greetings from OM Trip and Travels Indore. We are contacting you regarding your travel enquiry.");
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="/admin/enquiries" class="text-decoration-none text-muted small"><i class="bi bi-arrow-left me-1"></i> Back to all enquiries</a>
        <h2 class="h4 fw-bold mb-0 mt-1">Enquiry #<?= $enquiry['id'] ?> &mdash; <?= htmlspecialchars($enquiry['name']) ?></h2>
    </div>
    <div class="d-flex gap-2">
        <a href="tel:<?= htmlspecialchars($enquiry['phone']) ?>" class="btn btn-primary btn-sm">
            <i class="bi bi-telephone-fill me-1"></i> Call Customer
        </a>
        <a href="<?= htmlspecialchars($waLink) ?>" target="_blank" class="btn btn-success btn-sm">
            <i class="bi bi-whatsapp me-1"></i> WhatsApp Message
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- LEFT: CUSTOMER & TRIP DETAILS -->
    <div class="col-lg-7">
        <div class="admin-card card mb-4">
            <div class="card-header">
                <span><i class="bi bi-person-fill text-primary me-2"></i>Customer &amp; Trip Information</span>
                <span class="status-badge status-<?= htmlspecialchars($enquiry['status']) ?>"><?= ucfirst($enquiry['status']) ?></span>
            </div>
            <div class="card-body">
                <table class="table table-bordered small mb-0">
                    <tbody>
                        <tr>
                            <td class="fw-bold w-35 bg-light">Customer Name</td>
                            <td><strong><?= htmlspecialchars($enquiry['name']) ?></strong></td>
                        </tr>
                        <tr>
                            <td class="fw-bold bg-light">Phone Number</td>
                            <td>
                                <a href="tel:<?= htmlspecialchars($enquiry['phone']) ?>" class="fw-bold text-dark"><?= htmlspecialchars($enquiry['phone']) ?></a>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold bg-light">Email Address</td>
                            <td><?= htmlspecialchars($enquiry['email'] ?: 'Not provided') ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold bg-light">Enquiry Type</td>
                            <td><span class="badge bg-<?= $enquiry['type'] === 'booking' ? 'primary' : 'secondary' ?>"><?= ucfirst($enquiry['type']) ?></span></td>
                        </tr>
                        <tr>
                            <td class="fw-bold bg-light">Vehicle Requested</td>
                            <td><?= htmlspecialchars($enquiry['vehicle_name'] ?: 'Not selected') ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold bg-light">Package Requested</td>
                            <td><?= htmlspecialchars($enquiry['package_name'] ?: 'Not selected') ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold bg-light">Pickup Location</td>
                            <td><?= htmlspecialchars($enquiry['pickup_location'] ?: 'Not specified') ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold bg-light">Drop Destination</td>
                            <td><?= htmlspecialchars($enquiry['drop_location'] ?: 'Not specified') ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold bg-light">Travel Departure Date</td>
                            <td><?= $enquiry['travel_date'] ? formatDate($enquiry['travel_date']) : 'Flexible' ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold bg-light">Return Date</td>
                            <td><?= $enquiry['return_date'] ? formatDate($enquiry['return_date']) : 'One-way / Not specified' ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold bg-light">Passengers Count</td>
                            <td><?= $enquiry['passengers'] ? (int)$enquiry['passengers'] . ' Passengers' : 'Not specified' ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold bg-light">Customer Message / Notes</td>
                            <td class="text-secondary"><?= nl2br(htmlspecialchars($enquiry['message'] ?? 'None provided')) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TECHNICAL / METADATA -->
        <div class="admin-card card">
            <div class="card-header">
                <span><i class="bi bi-info-circle text-muted me-2"></i>Lead Source &amp; Metadata</span>
            </div>
            <div class="card-body small text-muted">
                <div class="row g-2">
                    <div class="col-sm-6"><strong>Submitted At:</strong> <?= formatDate($enquiry['created_at'], 'd M Y, h:i:s A') ?></div>
                    <div class="col-sm-6"><strong>IP Address:</strong> <?= htmlspecialchars($enquiry['ip'] ?? 'Unknown') ?></div>
                    <div class="col-12"><strong>Source Webpage:</strong> <a href="<?= htmlspecialchars($enquiry['source_page'] ?? '#') ?>" target="_blank" class="text-muted"><?= htmlspecialchars($enquiry['source_page'] ?? '') ?></a></div>
                    <div class="col-12"><strong>User Agent:</strong> <?= htmlspecialchars($enquiry['user_agent'] ?? 'Unknown') ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT: STATUS CHANGER & INTERNAL NOTES -->
    <div class="col-lg-5">
        <!-- STATUS UPDATE CARD -->
        <div class="admin-card card mb-4">
            <div class="card-header">
                <span><i class="bi bi-arrow-repeat text-primary me-2"></i>Update Lead Status</span>
            </div>
            <div class="card-body">
                <form action="/admin/enquiries/<?= $enquiry['id'] ?>/status" method="POST">
                    <?= csrfField() ?>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Current Status</label>
                        <select name="status" class="form-select">
                            <option value="new"       <?= $enquiry['status'] === 'new' ? 'selected' : '' ?>>New (Unattended)</option>
                            <option value="contacted" <?= $enquiry['status'] === 'contacted' ? 'selected' : '' ?>>Contacted (Spoke on call/WA)</option>
                            <option value="followup"  <?= $enquiry['status'] === 'followup' ? 'selected' : '' ?>>Follow-up required</option>
                            <option value="confirmed" <?= $enquiry['status'] === 'confirmed' ? 'selected' : '' ?>>Confirmed Booking</option>
                            <option value="closed"    <?= $enquiry['status'] === 'closed' ? 'selected' : '' ?>>Closed / Cancelled</option>
                            <option value="spam"      <?= $enquiry['status'] === 'spam' ? 'selected' : '' ?>>Spam</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold">Update Status</button>
                </form>
            </div>
        </div>

        <!-- INTERNAL STAFF NOTES -->
        <div class="admin-card card">
            <div class="card-header">
                <span><i class="bi bi-chat-left-text-fill text-primary me-2"></i>Internal Staff Notes</span>
            </div>
            <div class="card-body">
                <div id="notes-list" class="mb-3" style="max-height:280px; overflow-y:auto;">
                    <?php if (empty($notes)): ?>
                    <p class="text-muted small text-center py-3 mb-0" id="no-notes-msg">No internal notes added yet.</p>
                    <?php else: ?>
                    <?php foreach ($notes as $n): ?>
                    <div class="border rounded p-3 mb-2 bg-light">
                        <p class="mb-1 small text-dark"><?= nl2br(htmlspecialchars($n['note'])) ?></p>
                        <small class="text-muted" style="font-size:0.72rem;">By <strong><?= htmlspecialchars($n['admin_name']) ?></strong> &bull; <?= formatDate($n['created_at'], 'd M, h:i A') ?></small>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <form id="add-note-form" action="/admin/enquiries/<?= $enquiry['id'] ?>/note" method="POST">
                    <?= csrfField() ?>
                    <div class="mb-2">
                        <textarea name="note" class="form-control form-control-sm" rows="3" required placeholder="Add follow-up notes (e.g. Quoted ₹18/km for Urbania 17 seater, waiting for confirmation)..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-outline-primary btn-sm w-100">
                        <i class="bi bi-plus-circle me-1"></i> Add Internal Note
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

