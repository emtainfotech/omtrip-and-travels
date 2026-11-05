<?php
/**
 * Admin Activity Logs View.
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 fw-bold mb-0">System Activity &amp; Audit Logs</h2>
</div>

<div class="admin-card card">
    <div class="table-responsive">
        <table class="table admin-table mb-0">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Entity Type</th>
                    <th>Description</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs)): ?>
                <tr><td colspan="6" class="text-center py-4 text-muted">No activity logs recorded yet.</td></tr>
                <?php else: ?>
                <?php foreach ($logs as $log): ?>
                <tr>
                    <td><small class="text-muted"><?= formatDate($log['created_at'], 'd M Y, h:i:s A') ?></small></td>
                    <td>
                        <strong><?= htmlspecialchars($log['admin_name'] ?? 'System') ?></strong>
                        <small class="d-block text-muted" style="font-size:0.75rem;"><?= htmlspecialchars($log['admin_email'] ?? '') ?></small>
                    </td>
                    <td>
                        <span class="badge bg-secondary"><?= htmlspecialchars($log['action']) ?></span>
                    </td>
                    <td><small><?= htmlspecialchars($log['entity_type'] ?? '-') ?> <?= $log['entity_id'] ? '#' . (int)$log['entity_id'] : '' ?></small></td>
                    <td><small class="text-secondary"><?= htmlspecialchars($log['description'] ?? '') ?></small></td>
                    <td><small class="font-monospace text-muted"><?= htmlspecialchars($log['ip'] ?? '') ?></small></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

