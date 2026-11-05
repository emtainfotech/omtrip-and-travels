<?php
/**
 * Admin Blog Posts Listing View.
 */
?>
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <h2 class="h4 fw-bold mb-0">Blog Articles Management</h2>
    <a href="/admin/blog/create" class="btn btn-primary btn-sm">
        <i class="bi bi-pencil-square me-1"></i> Write New Article
    </a>
</div>

<!-- FILTER CARD -->
<div class="admin-card card mb-4">
    <div class="card-body p-3">
        <form action="/admin/blog" method="GET" class="row g-2 align-items-center">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by title..." value="<?= htmlspecialchars($search ?? '') ?>">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    <option value="published" <?= ($status ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
                    <option value="draft" <?= ($status ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                    <option value="scheduled" <?= ($status ?? '') === 'scheduled' ? 'selected' : '' ?>>Scheduled</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="category_id" class="form-select form-select-sm">
                    <option value="0">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $categoryId === (int)$cat['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-search"></i></button>
            </div>
        </form>
    </div>
</div>

<!-- POSTS TABLE -->
<div class="admin-card card">
    <div class="table-responsive">
        <table class="table admin-table mb-0">
            <thead>
                <tr>
                    <th style="width:70px;">Image</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Views</th>
                    <th>Published At</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($posts)): ?>
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">No blog articles found.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($posts as $post): ?>
                <tr>
                    <td>
                        <img src="<?= htmlspecialchars($post['featured_image'] ?: 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=100&q=80') ?>"
                             style="width:60px; height:45px; object-fit:cover; border-radius:6px;" alt="<?= htmlspecialchars($post['title']) ?>">
                    </td>
                    <td>
                        <strong><?= htmlspecialchars($post['title']) ?></strong>
                        <small class="d-block text-muted">/blog/<?= htmlspecialchars($post['slug']) ?></small>
                    </td>
                    <td><span class="badge bg-secondary"><?= htmlspecialchars($post['category_name'] ?? 'Uncategorized') ?></span></td>
                    <td>
                        <span class="badge <?= $post['status'] === 'published' ? 'bg-success' : 'bg-warning text-dark' ?>">
                            <?= ucfirst($post['status']) ?>
                        </span>
                    </td>
                    <td><i class="bi bi-eye text-muted me-1"></i><?= (int)$post['views'] ?></td>
                    <td><small class="text-muted"><?= $post['published_at'] ? formatDate($post['published_at']) : 'Not published' ?></small></td>
                    <td class="text-end">
                        <div class="btn-group btn-group-sm">
                            <a href="/blog/<?= htmlspecialchars($post['slug']) ?>" target="_blank" class="btn btn-outline-secondary" title="View on website"><i class="bi bi-box-arrow-up-right"></i></a>
                            <a href="/admin/blog/<?= $post['id'] ?>/edit" class="btn btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                            <form action="/admin/blog/<?= $post['id'] ?>/delete" method="POST" class="d-inline" onsubmit="return confirm('Delete this post?');">
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
                <a class="page-link" href="/admin/blog?page=<?= $p ?>"><?= $p ?></a>
            </li>
            <?php endfor; ?>
        </ul>
    </div>
    <?php endif; ?>
</div>

