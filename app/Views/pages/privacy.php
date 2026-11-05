<?php
/**
 * Privacy Policy view.
 */
?>
<!-- PAGE HERO -->
<div class="page-hero">
    <div class="container text-center">
        <h1>Privacy Policy</h1>
        <p class="text-white-50 max-w-600 mx-auto">How OM Trip and Travels handles and protects your personal information.</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Privacy Policy</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card p-4 p-md-5 border-0 shadow-sm text-secondary">
                <p class="small text-muted mb-4">Last Updated: <?= date('F Y') ?></p>

                <h2 class="h5 fw-bold text-dark mb-3">1. Information We Collect</h2>
                <p>When you contact OM Trip and Travels through our website forms, phone, or WhatsApp, we collect details necessary to arrange your transport, including: your name, telephone number, email address, travel dates, pickup/drop locations, and passenger counts.</p>

                <h2 class="h5 fw-bold text-dark mb-3">2. How We Use Your Information</h2>
                <p>We use your contact information exclusively to:</p>
                <ul>
                    <li>Confirm vehicle availability and provide accurate quotations.</li>
                    <li>Coordinate with our assigned drivers for your timely pickup.</li>
                    <li>Respond to your enquiries and customer support requests.</li>
                </ul>
                <p>We <strong>never</strong> sell, rent, or trade your personal information to third-party marketing companies.</p>

                <h2 class="h5 fw-bold text-dark mb-3">3. Data Security &amp; Retention</h2>
                <p>All online forms are protected with SSL/TLS encryption and CSRF security measures. Enquiries are stored securely in our protected database accessible only by authorized administrative personnel.</p>

                <h2 class="h5 fw-bold text-dark mb-3">4. Contact Regarding Privacy</h2>
                <p>If you have any questions about our privacy practices or wish to have your contact details removed from our enquiry database, please email us at <a href="mailto:<?= htmlspecialchars($settings['email'] ?? 'info@omtripandtravels.com') ?>"><?= htmlspecialchars($settings['email'] ?? 'info@omtripandtravels.com') ?></a>.</p>
            </div>
        </div>
    </div>
</div>

