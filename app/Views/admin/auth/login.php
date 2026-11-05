<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle ?? 'Admin Login') ?></title>
    <meta name="robots" content="noindex,nofollow">
    <link rel="icon" href="/assets/images/favicon.png" type="image/png">
    <link rel="stylesheet" href="/assets/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/vendor/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="login-page">

<div class="login-card">
    <div class="login-logo">
        <i class="bi bi-geo-alt-fill text-primary display-5 mb-2"></i>
        <h2>OM <span>Trip</span> Admin</h2>
        <p>Sign in to manage fleet, bookings &amp; enquiries</p>
    </div>

    <?php $flash = App\Core\Session::allFlash(); ?>
    <?php foreach ($flash as $type => $msg): ?>
    <div class="alert alert-<?= $type === 'error' ? 'danger' : htmlspecialchars($type) ?> alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($msg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endforeach; ?>

    <form action="/admin/login" method="POST">
        <?= csrfField() ?>
        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                <input type="email" name="email" class="form-control" required autofocus placeholder="admin@omtripandtravels.com">
            </div>
        </div>

        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <label class="form-label mb-0">Password</label>
                <a href="/admin/forgot" class="small text-decoration-none">Forgot password?</a>
            </div>
            <div class="input-group mt-1">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input type="password" name="password" class="form-control" required placeholder="••••••••">
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold mt-2">
            <i class="bi bi-box-arrow-in-right me-1"></i> Sign In to Dashboard
        </button>
    </form>

    <div class="text-center mt-4">
        <a href="/" class="small text-muted text-decoration-none"><i class="bi bi-arrow-left me-1"></i>Back to Website</a>
    </div>
</div>

<script src="/assets/vendor/bootstrap.bundle.min.js"></script>
</body>
</html>

