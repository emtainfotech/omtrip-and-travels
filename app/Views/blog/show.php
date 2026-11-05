<?php
/**
 * Blog detail view.
 */
?>
<!-- BREADCRUMB -->
<div class="breadcrumb-nav">
    <div class="container">
        <ol class="breadcrumb mb-0 small">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="/blog">Blog</a></li>
            <?php if (!empty($post['category_name'])): ?>
            <li class="breadcrumb-item"><a href="/blog/category/<?= htmlspecialchars($post['category_slug']) ?>"><?= htmlspecialchars($post['category_name']) ?></a></li>
            <?php endif; ?>
            <li class="breadcrumb-item active"><?= htmlspecialchars($post['title']) ?></li>
        </ol>
    </div>
</div>

<div class="container py-4">
    <div class="row g-4">
        <!-- LEFT MAIN ARTICLE -->
        <div class="col-lg-8">
            <article class="card p-4 p-md-5 border-0 shadow-sm mb-4">
                <?php if (!empty($post['category_name'])): ?>
                <span class="blog-category-badge align-self-start mb-3"><?= htmlspecialchars($post['category_name']) ?></span>
                <?php endif; ?>

                <h1 class="h2 fw-extrabold mb-3"><?= htmlspecialchars($post['title']) ?></h1>

                <div class="d-flex align-items-center text-muted small mb-4 pb-3 border-bottom">
                    <span class="me-3"><i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($post['author_name'] ?: 'OM Trip and Travels Team') ?></span>
                    <span class="me-3"><i class="bi bi-calendar3 me-1"></i><?= formatDate($post['published_at'] ?? $post['created_at']) ?></span>
                    <span><i class="bi bi-eye-fill me-1"></i><?= (int)$post['views'] ?> views</span>
                </div>

                <?php if (!empty($post['featured_image'])): ?>
                <div class="mb-4">
                    <img src="<?= htmlspecialchars($post['featured_image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>"
                         class="img-fluid rounded shadow-sm w-100" style="max-height:420px; object-fit:cover;">
                </div>
                <?php endif; ?>

                <!-- CONTENT -->
                <div class="blog-content mb-4 text-secondary">
                    <?= raw($post['content']) ?>
                </div>

                <!-- TAGS -->
                <?php if (!empty($tags)): ?>
                <div class="pt-3 border-top d-flex align-items-center flex-wrap gap-2">
                    <span class="small fw-bold text-muted me-1">Tags:</span>
                    <?php foreach ($tags as $tag): ?>
                    <a href="/blog/tag/<?= htmlspecialchars($tag['slug']) ?>" class="badge bg-light text-secondary border text-decoration-none py-2 px-3">
                        #<?= htmlspecialchars($tag['name']) ?>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </article>

            <!-- CALL TO ACTION BOX -->
            <div class="card p-4 border-0 shadow-sm bg-light text-center mb-4">
                <h3 class="h5 fw-bold mb-2">Need a Cab or Tempo Traveller for this Trip?</h3>
                <p class="text-muted small mb-3">OM Trip and Travels offers comfortable vehicles with professional drivers at the most competitive rates in Indore.</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="tel:<?= htmlspecialchars($settings['phone'] ?? '+919876543210') ?>" class="btn btn-primary" data-log-call="1">
                        <i class="bi bi-telephone-fill me-1"></i>Call Now
                    </a>
                    <a href="/booking" class="btn btn-outline-secondary">
                        <i class="bi bi-calendar-check me-1"></i>Book Online
                    </a>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDEBAR -->
        <div class="col-lg-4">
            <!-- Related Articles -->
            <?php if (!empty($related)): ?>
            <div class="card p-4 border-0 shadow-sm mb-4">
                <h3 class="h6 fw-bold mb-3 border-bottom pb-2">Related Articles</h3>
                <div class="d-flex flex-column gap-3">
                    <?php foreach ($related as $rel): ?>
                    <div class="d-flex gap-3 align-items-center">
                        <img src="<?= htmlspecialchars($rel['featured_image'] ?: 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=150&q=80') ?>"
                             style="width:70px; height:60px; object-fit:cover; border-radius:6px;" alt="<?= htmlspecialchars($rel['title']) ?>">
                        <div>
                            <h4 class="small fw-bold mb-1"><a href="/blog/<?= htmlspecialchars($rel['slug']) ?>" class="text-dark text-decoration-none"><?= htmlspecialchars($rel['title']) ?></a></h4>
                            <small class="text-muted"><?= formatDate($rel['published_at'] ?? $rel['created_at']) ?></small>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Categories -->
            <div class="card p-4 border-0 shadow-sm mb-4">
                <h3 class="h6 fw-bold mb-3 border-bottom pb-2">Categories</h3>
                <ul class="list-unstyled mb-0">
                    <?php foreach ($categories as $cat): ?>
                    <li class="py-2 border-bottom">
                        <a href="/blog/category/<?= htmlspecialchars($cat['slug']) ?>" class="text-decoration-none text-secondary small d-flex justify-content-between">
                            <span><i class="bi bi-chevron-right text-primary me-2"></i><?= htmlspecialchars($cat['name']) ?></span>
                            <span class="text-muted">(<?= (int)$cat['post_count'] ?>)</span>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

