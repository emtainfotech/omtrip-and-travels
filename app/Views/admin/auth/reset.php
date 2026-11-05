<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle ?? 'Set New Password') ?></title>
    <meta name="robots" content="noindex,nofollow">
    <link rel="icon" href="/assets/images/favicon.png" type="image/png">
    <link rel="stylesheet" href="/assets/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/vendor/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="login-page">

<div class="login-card">
    <div class="login-logo">
        <i class="bi bi-shield-lock-fill text-primary display-5 mb-2"></i>
        <h2>Set New Password</h2>
        <p>Choose a secure password for your account</p>
    </div>

    <?php $flash = App\Core\Session::allFlash(); ?>
    <?php foreach ($flash as $type => $msg): ?>
    <div class="alert alert-<?= $type === 'error' ? 'danger' : htmlspecialchars($type) ?> alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($msg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endforeach; ?>

    <form action="/admin/reset" method="POST">
        <?= csrfField() ?>
        <input type="hidden" name="token" value="<?= htmlspecialchars($token ?? '') ?>">

        <div class="mb-3">
            <label class="form-label">New Password (min 8 characters)</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input type="password" name="password" class="form-control" required minlength="8" placeholder="••••••••">
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label">Confirm New Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input type="password" name="password_confirm" class="form-control" required minlength="8" placeholder="••••••••">
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
            <i class="bi bi-check-circle-fill me-1"></i> Update Password
        </button>
    </form>
</div>

<script src="/assets/vendor/bootstrap.bundle.min.js"></script>
</body>
</html>

