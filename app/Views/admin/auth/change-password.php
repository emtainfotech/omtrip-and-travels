<?php
/**
 * Admin Change Password View.
 */
?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="admin-card card">
            <div class="card-header">
                <span><i class="bi bi-shield-lock-fill text-primary me-2"></i>Change Your Password</span>
            </div>
            <div class="card-body p-4">
                <form action="/admin/change-password" method="POST">
                    <?= csrfField() ?>
                    <div class="mb-3">
                        <label class="form-label">Current Password *</label>
                        <input type="password" name="current_password" class="form-control" required placeholder="••••••••">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">New Password (min 8 characters) *</label>
                        <input type="password" name="new_password" class="form-control" required minlength="8" placeholder="••••••••">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Confirm New Password *</label>
                        <input type="password" name="confirm_password" class="form-control" required minlength="8" placeholder="••••••••">
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2">
                        <i class="bi bi-check-circle-fill me-1"></i> Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

