<?php
/**
 * Admin Users List View.
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 fw-bold mb-0">Admin Users Management</h2>
    <a href="/admin/admins/create" class="btn btn-primary btn-sm">
        <i class="bi bi-person-plus-fill me-1"></i> Add Admin User
    </a>
</div>

<div class="admin-card card">
    <div class="table-responsive">
        <table class="table admin-table mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email Address</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Last Login</th>
                    <th>Created At</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admins as $adm): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($adm['name']) ?></strong></td>
                    <td><?= htmlspecialchars($adm['email']) ?></td>
                    <td>
                        <span class="badge bg-<?= $adm['role'] === 'super_admin' ? 'danger' : 'info text-dark' ?>">
                            <?= $adm['role'] === 'super_admin' ? 'Super Admin' : 'Staff' ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge <?= $adm['is_active'] ? 'bg-success' : 'bg-danger' ?>">
                            <?= $adm['is_active'] ? 'Active' : 'Disabled' ?>
                        </span>
                    </td>
                    <td><small class="text-muted"><?= $adm['last_login'] ? formatDate($adm['last_login'], 'd M Y, h:i A') : 'Never' ?></small></td>
                    <td><small class="text-muted"><?= formatDate($adm['created_at']) ?></small></td>
                    <td class="text-end">
                        <div class="btn-group btn-group-sm">
                            <a href="/admin/admins/<?= $adm['id'] ?>/edit" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <?php if ((int)$adm['id'] !== (int)($_SESSION['admin_id'] ?? 0)): ?>
                            <form action="/admin/admins/<?= $adm['id'] ?>/delete" method="POST" class="d-inline" onsubmit="return confirm('Delete this admin user?');">
                                <?= csrfField() ?>
                                <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

