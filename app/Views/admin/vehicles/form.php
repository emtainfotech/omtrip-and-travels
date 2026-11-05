<?php
/**
 * Admin Vehicle Add / Edit Form.
 */
$isEdit = !empty($vehicle);
$action = $isEdit ? "/admin/vehicles/{$vehicle['id']}/edit" : "/admin/vehicles/create";

// Features and Ideal For
$features = $isEdit ? implode(', ', json_decode($vehicle['features'] ?? '[]', true) ?: []) : 'Reclining Seats, Pushback, AC, Charging Points, Music System';
$idealFor = $isEdit ? implode(', ', json_decode($vehicle['ideal_for'] ?? '[]', true) ?: []) : 'Pilgrimage Tours, Family Trips, Corporate Outings';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="/admin/vehicles" class="text-decoration-none text-muted small"><i class="bi bi-arrow-left me-1"></i> Back to Fleet</a>
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
                    <span><i class="bi bi-truck text-primary me-2"></i>Basic Details</span>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-7">
                            <label class="form-label">Vehicle Name *</label>
                            <input type="text" name="name" class="form-control" required data-slug-source
                                   value="<?= htmlspecialchars($vehicle['name'] ?? '') ?>" placeholder="e.g. Force Urbania 17 Seater">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">URL Slug *</label>
                            <input type="text" name="slug" class="form-control" required data-slug-target
                                   value="<?= htmlspecialchars($vehicle['slug'] ?? '') ?>" placeholder="force-urbania-17-seater">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Category *</label>
                            <select name="category_id" class="form-select" required>
                                <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= ($vehicle['category_id'] ?? 0) === (int)$cat['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['name']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Passenger Seats *</label>
                            <input type="number" name="seats" class="form-control" required min="1" max="60"
                                   value="<?= (int)($vehicle['seats'] ?? 12) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Air Conditioning *</label>
                            <select name="has_ac" class="form-select">
                                <option value="1" <?= ($vehicle['has_ac'] ?? 1) == 1 ? 'selected' : '' ?>>Yes (Air Conditioned)</option>
                                <option value="0" <?= ($vehicle['has_ac'] ?? 1) == 0 ? 'selected' : '' ?>>No (Non-AC)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Luggage Capacity Description</label>
                        <input type="text" name="luggage_capacity" class="form-control"
                               value="<?= htmlspecialchars($vehicle['luggage_capacity'] ?? '12-15 Medium Bags / Carrier on top') ?>"
                               placeholder="e.g. 10 Suitcases + Rooftop carrier">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Short Description (for listing cards)</label>
                        <textarea name="short_description" class="form-control" rows="2"
                                  placeholder="Brief 1-2 sentence description"><?= htmlspecialchars($vehicle['short_description'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Full Description &amp; Amenities</label>
                        <textarea name="description" class="form-control tinymce-editor" rows="7"><?= htmlspecialchars($vehicle['description'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- FEATURES & TAGS -->
            <div class="admin-card card mb-4">
                <div class="card-header">
                    <span><i class="bi bi-tags-fill text-primary me-2"></i>Features &amp; Ideal Use Cases</span>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Features / Amenities (comma separated)</label>
                        <input type="text" name="features" class="form-control" value="<?= htmlspecialchars($features) ?>"
                               placeholder="Reclining Captain Seats, Pushback, AC, Charging Points, Music System, Ice Box">
                        <small class="form-text">Separate features with commas. These will render as feature badges on the vehicle page.</small>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Ideal For (comma separated)</label>
                        <input type="text" name="ideal_for" class="form-control" value="<?= htmlspecialchars($idealFor) ?>"
                               placeholder="Pilgrimage Tours, Family Trips, Corporate Outings, Airport Transfers, Weddings">
                    </div>
                </div>
            </div>

            <!-- SEO FIELDS -->
            <div class="admin-card card mb-4">
                <div class="card-header">
                    <span><i class="bi bi-search text-primary me-2"></i>Search Engine Optimization (SEO)</span>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Meta Title</label>
                        <input type="text" name="meta_title" class="form-control"
                               value="<?= htmlspecialchars($vehicle['meta_title'] ?? '') ?>" placeholder="e.g. Force Urbania 17 Seater on Rent in Indore | OM Trip and Travels">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Meta Description</label>
                        <textarea name="meta_description" class="form-control" rows="2"
                                  placeholder="Keyword-rich summary for Google search results"><?= htmlspecialchars($vehicle['meta_description'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: IMAGES & PRICING -->
        <div class="col-lg-4">
            <!-- PRICING CARD -->
            <div class="admin-card card mb-4">
                <div class="card-header">
                    <span><i class="bi bi-cash-stack text-primary me-2"></i>Pricing</span>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Starting Price in ₹ (optional)</label>
                        <input type="number" step="0.01" name="starting_price" class="form-control"
                               value="<?= htmlspecialchars($vehicle['starting_price'] ?? '') ?>" placeholder="Leave blank for 'On Request'">
                        <small class="form-text">If blank, "On Request" is shown to encourage phone / WhatsApp enquiries.</small>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Price Note / Subtitle</label>
                        <input type="text" name="price_note" class="form-control"
                               value="<?= htmlspecialchars($vehicle['price_note'] ?? 'On Request — Call for Best Price') ?>"
                               placeholder="e.g. Per km basis / Call for package quote">
                    </div>
                </div>
            </div>

            <!-- PRIMARY IMAGE CARD -->
            <div class="admin-card card mb-4">
                <div class="card-header">
                    <span><i class="bi bi-image text-primary me-2"></i>Primary Image</span>
                </div>
                <div class="card-body">
                    <?php if (!empty($vehicle['primary_image'])): ?>
                    <div class="mb-3 text-center">
                        <img src="<?= htmlspecialchars($vehicle['primary_image']) ?>" class="img-fluid rounded border shadow-sm" style="max-height:160px; object-fit:cover;">
                        <small class="d-block text-muted mt-1">Current Primary Image</small>
                    </div>
                    <?php endif; ?>

                    <label class="form-label">Upload Image (JPG, PNG, WebP)</label>
                    <input type="file" name="primary_image" class="form-control form-control-sm" accept="image/*">
                    <small class="form-text">Auto-converted to WebP with aspect ratio preservation.</small>
                </div>
            </div>

            <!-- GALLERY IMAGES -->
            <div class="admin-card card mb-4">
                <div class="card-header">
                    <span><i class="bi bi-images text-primary me-2"></i>Additional Photos</span>
                </div>
                <div class="card-body">
                    <?php if (!empty($images)): ?>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <?php foreach ($images as $img): ?>
                        <div class="position-relative">
                            <img src="<?= htmlspecialchars($img['image_path']) ?>" style="width:60px; height:50px; object-fit:cover; border-radius:4px; border:1px solid #ddd;">
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <label class="form-label">Add More Photos (Multi-select)</label>
                    <input type="file" name="gallery_images[]" class="form-control form-control-sm" multiple accept="image/*">
                </div>
            </div>

            <!-- STATUS & PUBLISH CARD -->
            <div class="admin-card card mb-4">
                <div class="card-header">
                    <span><i class="bi bi-sliders text-primary me-2"></i>Visibility &amp; Order</span>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="isFeatured"
                               <?= !empty($vehicle['is_featured']) ? 'checked' : '' ?>>
                        <label class="form-check-label fw-bold" for="isFeatured">Featured on Homepage</label>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive"
                               <?= ($vehicle['is_active'] ?? 1) ? 'checked' : '' ?>>
                        <label class="form-check-label fw-bold" for="isActive">Active (Visible on Website)</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Display Sort Order</label>
                        <input type="number" name="sort_order" class="form-control form-control-sm"
                               value="<?= (int)($vehicle['sort_order'] ?? 0) ?>">
                        <small class="form-text">Lower number displays first (e.g. 1, 2, 3).</small>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2">
                        <i class="bi bi-check-lg me-1"></i> <?= $isEdit ? 'Save Changes' : 'Publish Vehicle' ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Include TinyMCE from CDN for rich descriptions -->
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

