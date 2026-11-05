<?php
/**
 * Admin Blog Post Form.
 */
$isEdit = !empty($post);
$action = $isEdit ? "/admin/blog/{$post['id']}/edit" : "/admin/blog/create";
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="/admin/blog" class="text-decoration-none text-muted small"><i class="bi bi-arrow-left me-1"></i> Back to Blog</a>
        <h2 class="h4 fw-bold mb-0 mt-1"><?= htmlspecialchars($pageTitle) ?></h2>
    </div>
</div>

<form action="<?= $action ?>" method="POST" enctype="multipart/form-data" class="admin-form">
    <?= csrfField() ?>
    <div class="row g-4">
        <!-- LEFT COLUMN: ARTICLE CONTENT -->
        <div class="col-lg-8">
            <div class="admin-card card mb-4">
                <div class="card-header">
                    <span><i class="bi bi-pencil-fill text-primary me-2"></i>Post Content</span>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Article Title *</label>
                        <input type="text" name="title" class="form-control" required data-slug-source
                               value="<?= htmlspecialchars($post['title'] ?? '') ?>" placeholder="e.g. Complete Guide to Mahakaleshwar Darshan from Indore">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">URL Slug *</label>
                        <input type="text" name="slug" class="form-control" required data-slug-target
                               value="<?= htmlspecialchars($post['slug'] ?? '') ?>" placeholder="mahakaleshwar-darshan-guide-from-indore">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Short Excerpt (shows in blog cards &amp; social shares)</label>
                        <textarea name="excerpt" class="form-control" rows="2" placeholder="Brief summary of the article"><?= htmlspecialchars($post['excerpt'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Article Body *</label>
                        <textarea name="content" class="form-control tinymce-editor" rows="12"><?= htmlspecialchars($post['content'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Tags (comma separated)</label>
                        <input type="text" name="tags" class="form-control" value="<?= htmlspecialchars($tagsString ?? '') ?>" placeholder="Indore, Ujjain, Pilgrimage, Urbania">
                    </div>
                </div>
            </div>

            <!-- SEO FIELDS -->
            <div class="admin-card card mb-4">
                <div class="card-header">
                    <span><i class="bi bi-search text-primary me-2"></i>SEO Metadata</span>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Meta Title</label>
                        <input type="text" name="meta_title" class="form-control" value="<?= htmlspecialchars($post['meta_title'] ?? '') ?>">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Meta Description</label>
                        <textarea name="meta_description" class="form-control" rows="2"><?= htmlspecialchars($post['meta_description'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: PUBLISHING & FEATURED IMAGE -->
        <div class="col-lg-4">
            <!-- PUBLISH STATUS -->
            <div class="admin-card card mb-4">
                <div class="card-header">
                    <span><i class="bi bi-send text-primary me-2"></i>Publishing</span>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select">
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= ($post['category_id'] ?? 0) === (int)$cat['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Publish Status</label>
                        <select name="status" class="form-select">
                            <option value="published" <?= ($post['status'] ?? 'published') === 'published' ? 'selected' : '' ?>>Published (Live)</option>
                            <option value="draft"     <?= ($post['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                            <option value="scheduled" <?= ($post['status'] ?? '') === 'scheduled' ? 'selected' : '' ?>>Scheduled</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Publish Date &amp; Time</label>
                        <input type="datetime-local" name="published_at" class="form-control form-control-sm"
                               value="<?= !empty($post['published_at']) ? date('Y-m-d\TH:i', strtotime($post['published_at'])) : date('Y-m-d\TH:i') ?>">
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2">
                        <i class="bi bi-check-lg me-1"></i> <?= $isEdit ? 'Update Article' : 'Publish Article' ?>
                    </button>
                </div>
            </div>

            <!-- FEATURED IMAGE -->
            <div class="admin-card card mb-4">
                <div class="card-header">
                    <span><i class="bi bi-image text-primary me-2"></i>Featured Image</span>
                </div>
                <div class="card-body">
                    <?php if (!empty($post['featured_image'])): ?>
                    <div class="mb-3 text-center">
                        <img src="<?= htmlspecialchars($post['featured_image']) ?>" class="img-fluid rounded border shadow-sm" style="max-height:160px; object-fit:cover;">
                    </div>
                    <?php endif; ?>
                    <label class="form-label">Upload Image</label>
                    <input type="file" name="featured_image" class="form-control form-control-sm" accept="image/*">
                </div>
            </div>
        </div>
    </div>
</form>

<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

