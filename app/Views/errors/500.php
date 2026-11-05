<div class="container py-5 text-center my-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="display-1 fw-bold text-warning mb-3">500</div>
            <h1 class="h2 mb-3">Server Error</h1>
            <p class="text-muted lead mb-4"><?= htmlspecialchars($message ?? 'An unexpected error occurred while processing your request. Please try again later or contact us directly.') ?></p>
            <div class="d-flex justify-content-center gap-3">
                <a href="/" class="btn btn-primary px-4 py-2"><i class="bi bi-house-door-fill me-2"></i>Go to Homepage</a>
                <a href="tel:<?= htmlspecialchars(env('PHONE_NUMBER', '+919876543210')) ?>" class="btn btn-outline-secondary px-4 py-2"><i class="bi bi-telephone-fill me-2"></i>Call Support</a>
            </div>
        </div>
    </div>
</div>

