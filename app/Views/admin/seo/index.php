<?php
/**
 * Admin SEO Pages Index View.
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 fw-bold mb-0">Page SEO &amp; Meta Tags</h2>
</div>

<div class="admin-card card">
    <div class="table-responsive">
        <table class="table admin-table mb-0">
            <thead>
                <tr>
                    <th>Page Key</th>
                    <th>Meta Title</th>
                    <th>Meta Description</th>
                    <th>Last Updated</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pages as $p): ?>
                <tr>
                    <td><code><?= htmlspecialchars($p['page_key']) ?></code></td>
                    <td><strong><?= htmlspecialchars($p['title'] ?? '') ?></strong></td>
                    <td><small class="text-muted" style="max-width:320px; display:inline-block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"><?= htmlspecialchars($p['meta_description'] ?? '') ?></small></td>
                    <td><small class="text-muted"><?= formatDate($p['updated_at']) ?></small></td>
                    <td class="text-end">
                        <a href="/admin/seo/<?= htmlspecialchars($p['page_key']) ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil me-1"></i>Edit SEO</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

