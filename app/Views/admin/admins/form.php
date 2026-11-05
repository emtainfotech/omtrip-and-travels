<?php
/**
 * Admin User Form (Add / Edit).
 */
$isEdit = !empty($user);
$action = $isEdit ? "/admin/admins/{$user['id']}/edit" : "/admin/admins/create";
?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="admin-card card">
            <div class="card-header">
                <span><i class="bi bi-person-fill text-primary me-2"></i><?= htmlspecialchars($pageTitle) ?></span>
                <a href="/admin/admins" class="btn btn-outline-secondary btn-sm">Cancel</a>
            </div>
            <div class="card-body p-4">
                <form action="<?= $action ?>" method="POST">
                    <?= csrfField() ?>
                    <div class="mb-3">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($user['name'] ?? '') ?>" placeholder="e.g. Rahul Sharma">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email Address *</label>
                        <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($user['email'] ?? '') ?>" placeholder="rahul@omtripandtravels.com">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password <?= $isEdit ? '(Leave blank to keep unchanged)' : '*' ?></label>
                        <input type="password" name="password" class="form-control" <?= $isEdit ? '' : 'required' ?> minlength="8" placeholder="••••••••">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">User Role</label>
                        <select name="role" class="form-select">
                            <option value="staff" <?= ($user['role'] ?? '') === 'staff' ? 'selected' : '' ?>>Staff (Enquiries &amp; Blog only)</option>
                            <option value="super_admin" <?= ($user['role'] ?? '') === 'super_admin' ? 'selected' : '' ?>>Super Admin (Full Access)</option>
                        </select>
                    </div>

                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="uActive" <?= ($user['is_active'] ?? 1) ? 'checked' : '' ?>>
                        <label class="form-check-label fw-bold small" for="uActive">Account Active</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2">
                        <i class="bi bi-check-circle-fill me-1"></i> <?= $isEdit ? 'Save Changes' : 'Create Admin' ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

