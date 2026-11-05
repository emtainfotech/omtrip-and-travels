<?php
/**
 * FAQ view.
 */
?>
<!-- PAGE HERO -->
<div class="page-hero">
    <div class="container text-center">
        <h1>Frequently Asked Questions</h1>
        <p class="text-white-50 max-w-600 mx-auto">Everything you need to know about renting Force Urbania, Tempo Travellers, and cabs in Indore.</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">FAQ</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <?php foreach ($groupedFaqs as $category => $items): ?>
            <div class="mb-5">
                <h2 class="h5 fw-bold text-primary mb-3 border-bottom pb-2">
                    <i class="bi bi-question-circle-fill me-2"></i><?= htmlspecialchars($category) ?> Questions
                </h2>
                <div class="accordion faq-accordion" id="faqGroup_<?= md5($category) ?>">
                    <?php foreach ($items as $idx => $faq):
                        $accId = md5($category) . '_' . $idx;
                    ?>
                    <div class="accordion-item shadow-sm border-0 mb-2">
                        <h3 class="accordion-header" id="heading_<?= $accId ?>">
                            <button class="accordion-button <?= $idx === 0 ? '' : 'collapsed' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_<?= $accId ?>">
                                <?= htmlspecialchars($faq['question']) ?>
                            </button>
                        </h3>
                        <div id="collapse_<?= $accId ?>" class="accordion-collapse collapse <?= $idx === 0 ? 'show' : '' ?>" data-bs-parent="#faqGroup_<?= md5($category) ?>">
                            <div class="accordion-body text-secondary small">
                                <?= nl2br(htmlspecialchars($faq['answer'])) ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>

            <!-- Still Have Questions CTA -->
            <div class="card p-4 p-md-5 border-0 shadow-sm bg-light text-center mt-4">
                <h3 class="h5 fw-bold mb-2">Still have questions about your trip?</h3>
                <p class="text-muted small mb-3">Our helpline is active 24/7. Speak to our Indore travel coordinator right now.</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="tel:<?= htmlspecialchars($settings['phone'] ?? '+919876543210') ?>" class="btn btn-primary" data-log-call="1">
                        <i class="bi bi-telephone-fill me-1"></i>Call Support
                    </a>
                    <a href="https://wa.me/<?= htmlspecialchars($settings['whatsapp_number'] ?? '919876543210') ?>?text=Hello%20I%20have%20a%20question%20about%20booking" target="_blank" class="btn btn-whatsapp" data-log-whatsapp="1">
                        <i class="bi bi-whatsapp me-1"></i>Ask on WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

