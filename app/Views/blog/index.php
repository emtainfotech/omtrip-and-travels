<?php
/**
 * Blog listing view.
 */
?>
<!-- PAGE HERO -->
<div class="page-hero">
    <div class="container text-center">
        <h1><?= htmlspecialchars($pageTitle ?? 'Travel Blog') ?></h1>
        <p class="text-white-50 max-w-600 mx-auto">Helpful travel tips, road trip routes, pilgrimage advice &amp; vehicle guides from Indore.</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Blog</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <!-- MAIN BLOG POSTS -->
        <div class="col-lg-8">
            <!-- Active filter badge -->
            <?php if (!empty($activeCategory) || !empty($activeTag) || !empty($search)): ?>
            <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded mb-4">
                <span class="small">
                    Filtering by:
                    <?php if ($activeCategory): ?><strong>Category: <?= htmlspecialchars($activeCategory['name']) ?></strong><?php endif; ?>
                    <?php if ($activeTag): ?><strong>Tag: #<?= htmlspecialchars($activeTag['name']) ?></strong><?php endif; ?>
                    <?php if ($search): ?><strong>Search: "<?= htmlspecialchars($search) ?>"</strong><?php endif; ?>
                </span>
                <a href="/blog" class="btn btn-sm btn-outline-danger">Reset</a>
            </div>
            <?php endif; ?>

            <?php if (empty($posts)): ?>
            <div class="alert alert-warning py-5 text-center">
                <h5>No articles found.</h5>
                <p class="text-muted">Check back later or browse other categories.</p>
                <a href="/blog" class="btn btn-primary btn-sm">All Articles</a>
            </div>
            <?php else: ?>
            <div class="row g-4">
                <?php foreach ($posts as $post):
                    $pUrl = '/blog/' . htmlspecialchars($post['slug']);
                ?>
                <div class="col-md-6">
                    <div class="blog-card card">
                        <img src="<?= htmlspecialchars($post['featured_image'] ?: 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=600&q=80') ?>"
                             class="blog-img" alt="<?= htmlspecialchars($post['title']) ?>" loading="lazy"
                             onerror="this.src='https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=600&q=80'">
                        <div class="card-body d-flex flex-column">
                            <span class="blog-category-badge align-self-start mb-2"><?= htmlspecialchars($post['category_name'] ?? 'Travel Guide') ?></span>
                            <h2 class="h5 fw-bold mb-2"><a href="<?= $pUrl ?>" class="text-dark text-decoration-none"><?= htmlspecialchars($post['title']) ?></a></h2>
                            <p class="text-muted small mb-3 flex-grow-1"><?= htmlspecialchars($post['excerpt'] ?? '') ?></p>
                            <div class="d-flex justify-content-between align-items-center text-muted small pt-2 border-top">
                                <span><i class="bi bi-calendar3 me-1"></i><?= formatDate($post['published_at'] ?? $post['created_at']) ?></span>
                                <a href="<?= $pUrl ?>" class="text-primary fw-bold text-decoration-none">Read More &rarr;</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- PAGINATION -->
            <?php if (($pagination['pages'] ?? 1) > 1): ?>
            <nav class="mt-5" aria-label="Blog pages">
                <ul class="pagination justify-content-center">
                    <?php for ($p = 1; $p <= $pagination['pages']; $p++): ?>
                    <li class="page-item <?= $p === $pagination['current'] ? 'active' : '' ?>">
                        <a class="page-link" href="/blog?page=<?= $p ?><?= $search ? '&q='.urlencode($search) : '' ?>"><?= $p ?></a>
                    </li>
                    <?php endfor; ?>
                </ul>
            </nav>
            <?php endif; ?>

            <?php endif; ?>
        </div>

        <!-- SIDEBAR -->
        <div class="col-lg-4">
            <!-- Search Widget -->
            <div class="card p-4 border-0 shadow-sm mb-4">
                <h3 class="h6 fw-bold mb-3">Search Articles</h3>
                <form action="/blog" method="GET">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control" placeholder="Search guides..." value="<?= htmlspecialchars($search ?? '') ?>">
                        <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </form>
            </div>

            <!-- Categories Widget -->
            <div class="card p-4 border-0 shadow-sm mb-4">
                <h3 class="h6 fw-bold mb-3">Blog Categories</h3>
                <ul class="list-unstyled mb-0">
                    <?php foreach ($categories as $cat): ?>
                    <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <a href="/blog/category/<?= htmlspecialchars($cat['slug']) ?>" class="text-decoration-none text-secondary">
                            <i class="bi bi-folder2-open text-primary me-2"></i><?= htmlspecialchars($cat['name']) ?>
                        </a>
                        <span class="badge bg-light text-muted border"><?= (int)$cat['post_count'] ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Popular Tags -->
            <?php if (!empty($tags)): ?>
            <div class="card p-4 border-0 shadow-sm mb-4">
                <h3 class="h6 fw-bold mb-3">Popular Tags</h3>
                <div class="d-flex flex-wrap gap-2 tag-cloud">
                    <?php foreach ($tags as $tag): ?>
                    <a href="/blog/tag/<?= htmlspecialchars($tag['slug']) ?>" class="badge bg-light text-secondary border text-decoration-none py-2 px-3">
                        #<?= htmlspecialchars($tag['name']) ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Need a Cab CTA Widget -->
            <div class="card p-4 border-0 shadow-sm bg-primary text-white text-center">
                <i class="bi bi-truck-front display-4 mb-2"></i>
                <h3 class="h5 fw-bold mb-2">Hire Tempo &amp; Urbania</h3>
                <p class="small text-white-50 mb-3">Traveling to these destinations with family or friends? Book your group van with driver today.</p>
                <a href="tel:<?= htmlspecialchars($phone ?? '+919876543210') ?>" class="btn btn-light fw-bold">
                    <i class="bi bi-telephone-fill me-1 text-primary"></i>Call Now
                </a>
            </div>
        </div>
    </div>
</div>

