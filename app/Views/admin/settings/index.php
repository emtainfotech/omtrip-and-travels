<?php
/**
 * Admin Site Settings View.
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 fw-bold mb-0">Website Configuration &amp; Settings</h2>
    <button type="button" class="btn btn-outline-primary btn-sm" id="smtp-test-btn">
        <i class="bi bi-envelope-check me-1"></i> Test SMTP Connection
    </button>
</div>

<form action="/admin/settings" method="POST" enctype="multipart/form-data" class="admin-form">
    <?= csrfField() ?>
    <div class="row g-4">
        <!-- GENERAL BUSINESS SETTINGS -->
        <div class="col-lg-6">
            <div class="admin-card card mb-4">
                <div class="card-header">
                    <span><i class="bi bi-building text-primary me-2"></i>Business Profile</span>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Business Name</label>
                        <input type="text" name="settings[business_name]" class="form-control" value="<?= htmlspecialchars($settings['business_name'] ?? 'OM Trip and Travels') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tagline</label>
                        <input type="text" name="settings[tagline]" class="form-control" value="<?= htmlspecialchars($settings['tagline'] ?? '') ?>">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label">Phone Number (Call Now)</label>
                            <input type="text" name="settings[phone]" class="form-control" value="<?= htmlspecialchars($settings['phone'] ?? '+91 98765 43210') ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label">WhatsApp Number (e.g. 919876543210)</label>
                            <input type="text" name="settings[whatsapp_number]" class="form-control" value="<?= htmlspecialchars($settings['whatsapp_number'] ?? '919876543210') ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Official Email</label>
                        <input type="email" name="settings[email]" class="form-control" value="<?= htmlspecialchars($settings['email'] ?? 'info@omtripandtravels.com') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Full Address</label>
                        <textarea name="settings[address]" class="form-control" rows="2"><?= htmlspecialchars($settings['address'] ?? '') ?></textarea>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label">Working Hours</label>
                            <input type="text" name="settings[working_hours]" class="form-control" value="<?= htmlspecialchars($settings['working_hours'] ?? '24 Hours / 7 Days a week') ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Pincode</label>
                            <input type="text" name="settings[pincode]" class="form-control" value="<?= htmlspecialchars($settings['pincode'] ?? '452010') ?>">
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Footer About Text</label>
                        <textarea name="settings[footer_about]" class="form-control" rows="3"><?= htmlspecialchars($settings['footer_about'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- LOGO & FAVICON -->
            <div class="admin-card card mb-4">
                <div class="card-header">
                    <span><i class="bi bi-image text-primary me-2"></i>Branding &amp; Logos</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6 text-center">
                            <label class="form-label d-block">Website Logo</label>
                            <?php if (!empty($settings['logo'])): ?>
                            <img src="<?= htmlspecialchars($settings['logo']) ?>" class="p-2 border rounded mb-2 bg-light" style="max-height:50px;">
                            <?php endif; ?>
                            <input type="file" name="logo" class="form-control form-control-sm" accept="image/*">
                        </div>
                        <div class="col-6 text-center">
                            <label class="form-label d-block">Favicon</label>
                            <?php if (!empty($settings['favicon'])): ?>
                            <img src="<?= htmlspecialchars($settings['favicon']) ?>" class="p-2 border rounded mb-2 bg-light" style="max-height:36px;">
                            <?php endif; ?>
                            <input type="file" name="favicon" class="form-control form-control-sm" accept="image/*">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- WHATSAPP TEMPLATES & SCRIPTS -->
        <div class="col-lg-6">
            <!-- WHATSAPP TEMPLATES -->
            <div class="admin-card card mb-4">
                <div class="card-header">
                    <span><i class="bi bi-whatsapp text-success me-2"></i>WhatsApp Prefilled Templates</span>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Vehicle Page WhatsApp Message</label>
                        <textarea name="settings[wa_message_vehicle]" class="form-control" rows="2"><?= htmlspecialchars($settings['wa_message_vehicle'] ?? 'Hello, I am interested in booking {vehicle_name}. Page: {page_url}') ?></textarea>
                        <small class="text-muted">Placeholders: <code>{vehicle_name}</code>, <code>{page_url}</code></small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tour Package WhatsApp Message</label>
                        <textarea name="settings[wa_message_package]" class="form-control" rows="2"><?= htmlspecialchars($settings['wa_message_package'] ?? 'Hello, I want to enquire about the {package_name} package. Page: {page_url}') ?></textarea>
                        <small class="text-muted">Placeholders: <code>{package_name}</code>, <code>{page_url}</code></small>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">General Enquiries Message</label>
                        <textarea name="settings[wa_message_general]" class="form-control" rows="2"><?= htmlspecialchars($settings['wa_message_general'] ?? 'Hello OM Trip and Travels, I would like to enquire. Page: {page_url}') ?></textarea>
                        <small class="text-muted">Placeholders: <code>{page_url}</code></small>
                    </div>
                </div>
            </div>

            <!-- SOCIAL LINKS -->
            <div class="admin-card card mb-4">
                <div class="card-header">
                    <span><i class="bi bi-share text-primary me-2"></i>Social Profiles</span>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-6 mb-2">
                            <label class="form-label small">Facebook URL</label>
                            <input type="url" name="settings[facebook_url]" class="form-control form-control-sm" value="<?= htmlspecialchars($settings['facebook_url'] ?? '') ?>">
                        </div>
                        <div class="col-6 mb-2">
                            <label class="form-label small">Instagram URL</label>
                            <input type="url" name="settings[instagram_url]" class="form-control form-control-sm" value="<?= htmlspecialchars($settings['instagram_url'] ?? '') ?>">
                        </div>
                        <div class="col-6 mb-2">
                            <label class="form-label small">YouTube URL</label>
                            <input type="url" name="settings[youtube_url]" class="form-control form-control-sm" value="<?= htmlspecialchars($settings['youtube_url'] ?? '') ?>">
                        </div>
                        <div class="col-6 mb-2">
                            <label class="form-label small">Twitter / X URL</label>
                            <input type="url" name="settings[twitter_url]" class="form-control form-control-sm" value="<?= htmlspecialchars($settings['twitter_url'] ?? '') ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- ANALYTICS & SCRIPTS -->
            <div class="admin-card card mb-4">
                <div class="card-header">
                    <span><i class="bi bi-code-slash text-primary me-2"></i>Analytics &amp; Custom Scripts</span>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Google Maps Embed iframe code</label>
                        <textarea name="settings[google_maps_embed]" class="form-control font-monospace" rows="2"><?= htmlspecialchars($settings['google_maps_embed'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Analytics Code (Google Analytics, GTM, Meta Pixel)</label>
                        <textarea name="settings[analytics_code]" class="form-control font-monospace" rows="2"><?= htmlspecialchars($settings['analytics_code'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Custom Header Scripts</label>
                        <textarea name="settings[header_scripts]" class="form-control font-monospace" rows="2"><?= htmlspecialchars($settings['header_scripts'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary btn-lg px-5 fw-bold shadow-sm">
                <i class="bi bi-check-circle-fill me-2"></i>Save All Settings
            </button>
        </div>
    </div>
</form>

