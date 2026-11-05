<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\{BlogPost, BlogCategory, BlogTag, Setting};

/**
 * BlogController — public blog listing, categories, tags, and articles.
 */
class BlogController extends Controller
{
    public function index(array $params = []): void
    {
        $this->renderBlogList();
    }

    public function category(array $params = []): void
    {
        $slug = $params['slug'] ?? '';
        $cat  = (new BlogCategory())->findBySlug($slug);
        if (!$cat) {
            $this->abort(404, 'Category not found.');
        }
        $this->renderBlogList((int)$cat['id'], 0, '', $cat);
    }

    public function tag(array $params = []): void
    {
        $slug = $params['slug'] ?? '';
        $tag  = (new BlogTag())->findBySlug($slug);
        if (!$tag) {
            $this->abort(404, 'Tag not found.');
        }
        $this->renderBlogList(0, (int)$tag['id'], '', null, $tag);
    }

    private function renderBlogList(
        int $categoryId = 0,
        int $tagId = 0,
        string $search = '',
        ?array $activeCategory = null,
        ?array $activeTag = null
    ): void {
        $blogModel = new BlogPost();
        $catModel  = new BlogCategory();
        $tagModel  = new BlogTag();

        $page   = max(1, $this->getInt('page', 1));
        $search = $search ?: $this->get('q', '');

        $result     = $blogModel->getPublished($page, 9, $categoryId, $tagId, $search);
        $categories = $catModel->getActive();
        $tags       = $tagModel->getAll();
        $recent     = $blogModel->getLatest(4);
        $settings   = (new Setting())->getAllFlat();

        $pageTitle = 'Travel Blog & Guides | OM Trip and Travels Indore';
        if ($activeCategory) {
            $pageTitle = e($activeCategory['name']) . ' - Travel Blog | OM Trip and Travels';
        } elseif ($activeTag) {
            $pageTitle = 'Posts tagged with #' . e($activeTag['name']) . ' | OM Trip and Travels';
        } elseif ($search) {
            $pageTitle = 'Search results for "' . e($search) . '" | Travel Blog';
        }

        $metaDescription = 'Read expert travel tips, pilgrimage guides, and destination road trips from Indore. Guides for Ujjain, Omkareshwar, Pachmarhi, and MP tourism.';

        $this->view('blog/index', [
            'posts'          => $result['data'],
            'pagination'     => $result,
            'categories'     => $categories,
            'tags'           => $tags,
            'recent'         => $recent,
            'activeCategory' => $activeCategory,
            'activeTag'      => $activeTag,
            'search'         => $search,
            'pageTitle'      => $pageTitle,
            'metaDescription'=> $metaDescription,
            'settings'       => $settings,
        ]);
    }

    public function show(array $params = []): void
    {
        $slug = $params['slug'] ?? '';
        $blogModel = new BlogPost();
        $post = $blogModel->findBySlug($slug);

        if (!$post) {
            $this->abort(404, 'Blog article not found.');
        }

        // Increment views
        $blogModel->incrementViews((int)$post['id']);

        $tags       = $blogModel->getTags((int)$post['id']);
        $related    = $blogModel->getRelated((int)$post['id'], (int)($post['category_id'] ?? 0));
        $categories = (new BlogCategory())->getActive();
        $settings   = (new Setting())->getAllFlat();

        $appUrl  = rtrim(env('APP_URL', ''), '/');
        $pageUrl = $appUrl . '/blog/' . $slug;

        $pageTitle       = $post['meta_title'] ?: ($post['title'] . ' | OM Trip and Travels Blog');
        $metaDescription = $post['meta_description'] ?: ($post['excerpt'] ?? 'Read ' . $post['title'] . ' on OM Trip and Travels.');
        $ogImage         = $post['og_image'] ?: ($post['featured_image'] ?: $appUrl . '/assets/images/og-default.jpg');
        $canonical       = $pageUrl;

        // JSON-LD: BlogPosting
        $schemaMarkup = json_encode([
            '@context'         => 'https://schema.org',
            '@type'            => 'BlogPosting',
            'headline'         => $post['title'],
            'image'            => $appUrl . ($post['featured_image'] ?? ''),
            'datePublished'    => date('c', strtotime($post['published_at'] ?? $post['created_at'])),
            'dateModified'     => date('c', strtotime($post['updated_at'] ?? $post['created_at'])),
            'author'           => [
                '@type' => 'Person',
                'name'  => $post['author_name'] ?: 'OM Trip and Travels Team',
            ],
            'publisher'        => [
                '@type' => 'Organization',
                'name'  => 'OM Trip and Travels',
                'logo'  => ['@type' => 'ImageObject', 'url' => $appUrl . '/assets/images/logo.png'],
            ],
            'description'      => $metaDescription,
            'mainEntityOfPage' => $canonical,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $this->view('blog/show', compact(
            'post', 'tags', 'related', 'categories', 'pageUrl',
            'pageTitle', 'metaDescription', 'ogImage', 'canonical',
            'schemaMarkup', 'settings'
        ));
    }
}

