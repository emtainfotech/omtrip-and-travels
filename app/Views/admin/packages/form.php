<?php
/**
 * Admin Tour Package Form.
 */
$isEdit = !empty($package);
$action = $isEdit ? "/admin/packages/{$package['id']}/edit" : "/admin/packages/create";
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="/admin/packages" class="text-decoration-none text-muted small"><i class="bi bi-arrow-left me-1"></i> Back to Packages</a>
        <h2 class="h4 fw-bold mb-0 mt-1"><?= htmlspecialchars($pageTitle) ?></h2>
    </div>
</div>

<form action="<?= $action ?>" method="POST" enctype="multipart/form-data" class="admin-form">
    <?= csrfField() ?>
    <div class="row g-4">
        <!-- LEFT COLUMN: MAIN FIELDS -->
        <div class="col-lg-8">
            <div class="admin-card card mb-4">
                <div class="card-header">
                    <span><i class="bi bi-map-fill text-primary me-2"></i>Package Details</span>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-7">
                            <label class="form-label">Package Name *</label>
                            <input type="text" name="name" class="form-control" required data-slug-source
                                   value="<?= htmlspecialchars($package['name'] ?? '') ?>" placeholder="e.g. Indore to Ujjain Darshan">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">URL Slug *</label>
                            <input type="text" name="slug" class="form-control" required data-slug-target
                                   value="<?= htmlspecialchars($package['slug'] ?? '') ?>" placeholder="indore-to-ujjain">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Origin City</label>
                            <input type="text" name="origin" class="form-control" value="<?= htmlspecialchars($package['origin'] ?? 'Indore') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Destination</label>
                            <input type="text" name="destination" class="form-control"
                                   value="<?= htmlspecialchars($package['destination'] ?? '') ?>" placeholder="e.g. Ujjain">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Route Summary</label>
                            <input type="text" name="route" class="form-control"
                                   value="<?= htmlspecialchars($package['route'] ?? '') ?>" placeholder="Indore → Ujjain → Indore">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Duration (Days)</label>
                            <input type="number" name="duration_days" class="form-control" min="1" max="30"
                                   value="<?= (int)($package['duration_days'] ?? 1) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Duration (Nights)</label>
                            <input type="number" name="duration_nights" class="form-control" min="0" max="30"
                                   value="<?= (int)($package['duration_nights'] ?? 0) ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Short Description</label>
                        <textarea name="short_description" class="form-control" rows="2"
                                  placeholder="One or two sentences summarizing the tour"><?= htmlspecialchars($package['short_description'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Overview &amp; Highlights</label>
                        <textarea name="description" class="form-control tinymce-editor" rows="5"><?= htmlspecialchars($package['description'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Detailed Day-by-Day Itinerary</label>
                        <textarea name="itinerary" class="form-control tinymce-editor" rows="6"><?= htmlspecialchars($package['itinerary'] ?? '') ?></textarea>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Inclusions (one item per line)</label>
                            <textarea name="inclusions" class="form-control" rows="4" placeholder="AC vehicle with driver&#10;Fuel charges&#10;Driver allowance&#10;Tolls &amp; parking"><?= htmlspecialchars($package['inclusions'] ?? '') ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Exclusions (one item per line)</label>
                            <textarea name="exclusions" class="form-control" rows="4" placeholder="Monument entry tickets&#10;Hotel accommodation&#10;Meals &amp; snacks"><?= htmlspecialchars($package['exclusions'] ?? '') ?></textarea>
                        </div>
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
                        <input type="text" name="meta_title" class="form-control" value="<?= htmlspecialchars($package['meta_title'] ?? '') ?>">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Meta Description</label>
                        <textarea name="meta_description" class="form-control" rows="2"><?= htmlspecialchars($package['meta_description'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: IMAGE, PRICING & VEHICLES -->
        <div class="col-lg-4">
            <!-- PRICING -->
            <div class="admin-card card mb-4">
                <div class="card-header">
                    <span><i class="bi bi-cash text-primary me-2"></i>Pricing</span>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Starting Price in ₹ (optional)</label>
                        <input type="number" step="0.01" name="starting_price" class="form-control"
                               value="<?= htmlspecialchars($package['starting_price'] ?? '') ?>" placeholder="Leave blank for 'On Request'">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Price Note</label>
                        <input type="text" name="price_note" class="form-control"
                               value="<?= htmlspecialchars($package['price_note'] ?? 'Includes vehicle, fuel, driver') ?>"
                               placeholder="e.g. Starting from ₹2,500">
                    </div>
                </div>
            </div>

            <!-- PRIMARY IMAGE -->
            <div class="admin-card card mb-4">
                <div class="card-header">
                    <span><i class="bi bi-image text-primary me-2"></i>Featured Banner Image</span>
                </div>
                <div class="card-body">
                    <?php if (!empty($package['primary_image'])): ?>
                    <div class="mb-3 text-center">
                        <img src="<?= htmlspecialchars($package['primary_image']) ?>" class="img-fluid rounded border shadow-sm" style="max-height:160px; object-fit:cover;">
                    </div>
                    <?php endif; ?>
                    <label class="form-label">Upload Banner Image</label>
                    <input type="file" name="primary_image" class="form-control form-control-sm" accept="image/*">
                </div>
            </div>

            <!-- LINKED VEHICLES -->
            <div class="admin-card card mb-4">
                <div class="card-header">
                    <span><i class="bi bi-truck-front text-primary me-2"></i>Available Fleet for this Tour</span>
                </div>
                <div class="card-body" style="max-height:220px; overflow-y:auto;">
                    <?php foreach ($allVehicles as $v): ?>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="vehicles[]" value="<?= $v['id'] ?>" id="veh_<?= $v['id'] ?>"
                               <?= in_array((int)$v['id'], $linkedVehicles, true) ? 'checked' : '' ?>>
                        <label class="form-check-label small" for="veh_<?= $v['id'] ?>">
                            <strong><?= htmlspecialchars($v['name']) ?></strong> (<?= (int)$v['seats'] ?> seats)
                        </label>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- STATUS -->
            <div class="admin-card card mb-4">
                <div class="card-header">
                    <span><i class="bi bi-sliders text-primary me-2"></i>Visibility &amp; Order</span>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="isFeatured"
                               <?= !empty($package['is_featured']) ? 'checked' : '' ?>>
                        <label class="form-check-label fw-bold" for="isFeatured">Featured on Homepage</label>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive"
                               <?= ($package['is_active'] ?? 1) ? 'checked' : '' ?>>
                        <label class="form-check-label fw-bold" for="isActive">Active (Visible)</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Display Sort Order</label>
                        <input type="number" name="sort_order" class="form-control form-control-sm"
                               value="<?= (int)($package['sort_order'] ?? 0) ?>">
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2">
                        <i class="bi bi-check-lg me-1"></i> <?= $isEdit ? 'Save Changes' : 'Create Package' ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

