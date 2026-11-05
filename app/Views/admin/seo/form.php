<?php
/**
 * Admin SEO Page Edit Form.
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="/admin/seo" class="text-decoration-none text-muted small"><i class="bi bi-arrow-left me-1"></i> Back to SEO list</a>
        <h2 class="h4 fw-bold mb-0 mt-1"><?= htmlspecialchars($pageTitle) ?></h2>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="admin-card card">
            <div class="card-header">
                <span><i class="bi bi-search text-primary me-2"></i>SEO &amp; Open Graph Configuration</span>
            </div>
            <div class="card-body p-4">
                <form action="/admin/seo/<?= htmlspecialchars($page['page_key']) ?>" method="POST">
                    <?= csrfField() ?>
                    <div class="mb-3">
                        <label class="form-label">Meta Title *</label>
                        <input type="text" name="title" class="form-control" required value="<?= htmlspecialchars($page['title'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Meta Description *</label>
                        <textarea name="meta_description" class="form-control" rows="3" required><?= htmlspecialchars($page['meta_description'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Canonical URL</label>
                        <input type="url" name="canonical" class="form-control" value="<?= htmlspecialchars($page['canonical'] ?? '') ?>" placeholder="https://omtripandtravels.com/fleet">
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label">OG Title</label>
                            <input type="text" name="og_title" class="form-control" value="<?= htmlspecialchars($page['og_title'] ?? '') ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label">OG Description</label>
                            <input type="text" name="og_description" class="form-control" value="<?= htmlspecialchars($page['og_description'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Custom JSON-LD Schema (optional, e.g. LocalBusiness, FAQPage)</label>
                        <textarea name="schema_json" class="form-control font-monospace" rows="5" placeholder='{"@context": "https://schema.org", ...}'><?= htmlspecialchars($page['schema_json'] ?? '') ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary fw-bold px-4 py-2">
                        <i class="bi bi-check-circle-fill me-1"></i> Save SEO Settings
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

