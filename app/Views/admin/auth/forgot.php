<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle ?? 'Forgot Password') ?></title>
    <meta name="robots" content="noindex,nofollow">
    <link rel="icon" href="/assets/images/favicon.png" type="image/png">
    <link rel="stylesheet" href="/assets/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/vendor/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="login-page">

<div class="login-card">
    <div class="login-logo">
        <i class="bi bi-key-fill text-primary display-5 mb-2"></i>
        <h2>Reset Password</h2>
        <p>Enter your admin email to receive a recovery link</p>
    </div>

    <?php $flash = App\Core\Session::allFlash(); ?>
    <?php foreach ($flash as $type => $msg): ?>
    <div class="alert alert-<?= $type === 'error' ? 'danger' : htmlspecialchars($type) ?> alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($msg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endforeach; ?>

    <form action="/admin/forgot" method="POST">
        <?= csrfField() ?>
        <div class="mb-4">
            <label class="form-label">Registered Admin Email</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                <input type="email" name="email" class="form-control" required autofocus placeholder="admin@omtripandtravels.com">
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
            <i class="bi bi-send-fill me-1"></i> Send Reset Link
        </button>
    </form>

    <div class="text-center mt-4">
        <a href="/admin/login" class="small text-muted text-decoration-none"><i class="bi bi-arrow-left me-1"></i>Back to Login</a>
    </div>
</div>

<script src="/assets/vendor/bootstrap.bundle.min.js"></script>
</body>
</html>

