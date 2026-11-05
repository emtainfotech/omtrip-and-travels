<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Models\{BlogPost, BlogCategory, BlogTag};
use App\Helpers\ImageUploader;

/**
 * BlogController — Admin CRUD for blog articles.
 */
class BlogController extends Controller
{
    public function index(array $params = []): void
    {
        $this->requireAdmin();

        $blogModel = new BlogPost();
        $search    = $this->get('search');
        $status    = $this->get('status');
        $catId     = $this->getInt('category_id');
        $page      = max(1, $this->getInt('page', 1));

        $result     = $blogModel->getAllForAdmin($search, $status, $catId, $page, 20);
        $categories = (new BlogCategory())->getActive();

        $this->view('admin/blog/index', [
            'pageTitle'  => 'Blog Articles Management',
            'posts'      => $result['data'],
            'pagination' => $result,
            'categories' => $categories,
            'search'     => $search,
            'status'     => $status,
            'categoryId' => $catId,
        ], 'layouts/admin');
    }

    public function create(array $params = []): void
    {
        $this->requireAdmin();
        $blogModel = new BlogPost();
        $catModel  = new BlogCategory();

        if ($this->isPost()) {
            $this->verifyCsrf();

            $title       = sanitize($this->post('title'));
            $slug        = slug($this->post('slug') ?: $title);
            $categoryId  = $this->postInt('category_id') ?: null;
            $excerpt     = sanitize($this->post('excerpt'));
            $content     = $this->post('content');
            $status      = in_array($this->post('status'), ['draft', 'published', 'scheduled']) ? $this->post('status') : 'draft';
            $publishedAt = $status === 'published' ? ($this->post('published_at') ?: date('Y-m-d H:i:s')) : null;
            $metaTitle   = sanitize($this->post('meta_title'));
            $metaDesc    = sanitize($this->post('meta_description'));
            $tagsInput   = sanitize($this->post('tags'));

            $featuredImage = null;
            if (!empty($_FILES['featured_image']['name'])) {
                $upload = ImageUploader::upload($_FILES['featured_image'], 'blog');
                if ($upload['success']) {
                    $featuredImage = $upload['path'];
                }
            }

            $admin = Session::getAdmin();
            $id = $blogModel->create([
                'category_id'    => $categoryId,
                'author_id'      => (int)$admin['id'],
                'title'          => $title,
                'slug'           => $slug,
                'excerpt'        => $excerpt ?: null,
                'content'        => $content ?: '',
                'featured_image' => $featuredImage,
                'og_image'       => $featuredImage,
                'status'         => $status,
                'published_at'   => $publishedAt,
                'meta_title'     => $metaTitle ?: null,
                'meta_description'=> $metaDesc ?: null,
            ]);

            // Sync tags
            if (!empty($tagsInput)) {
                $this->syncTags((int)$id, $tagsInput);
            }

            logActivity((int)$admin['id'], 'create', 'blog', (int)$id, "Created blog post '{$title}'");
            Session::flash('success', "Article '{$title}' created.");
            $this->redirect('/admin/blog');
        }

        $categories = $catModel->getActive();
        $this->view('admin/blog/form', [
            'pageTitle'  => 'Write New Article',
            'post'       => null,
            'categories' => $categories,
            'tagsString' => '',
        ], 'layouts/admin');
    }

    public function edit(array $params = []): void
    {
        $this->requireAdmin();
        $id = (int) ($params['id'] ?? 0);
        $blogModel = new BlogPost();
        $post = $blogModel->find($id);

        if (!$post) {
            Session::flash('error', 'Article not found.');
            $this->redirect('/admin/blog');
        }

        if ($this->isPost()) {
            $this->verifyCsrf();

            $title       = sanitize($this->post('title'));
            $slug        = slug($this->post('slug') ?: $title);
            $categoryId  = $this->postInt('category_id') ?: null;
            $excerpt     = sanitize($this->post('excerpt'));
            $content     = $this->post('content');
            $status      = in_array($this->post('status'), ['draft', 'published', 'scheduled']) ? $this->post('status') : 'draft';
            $publishedAt = $status === 'published' ? ($this->post('published_at') ?: ($post['published_at'] ?: date('Y-m-d H:i:s'))) : null;
            $metaTitle   = sanitize($this->post('meta_title'));
            $metaDesc    = sanitize($this->post('meta_description'));
            $tagsInput   = sanitize($this->post('tags'));

            $data = [
                'category_id'    => $categoryId,
                'title'          => $title,
                'slug'           => $slug,
                'excerpt'        => $excerpt ?: null,
                'content'        => $content ?: '',
                'status'         => $status,
                'published_at'   => $publishedAt,
                'meta_title'     => $metaTitle ?: null,
                'meta_description'=> $metaDesc ?: null,
            ];

            if (!empty($_FILES['featured_image']['name'])) {
                $upload = ImageUploader::upload($_FILES['featured_image'], 'blog');
                if ($upload['success']) {
                    $data['featured_image'] = $upload['path'];
                    $data['og_image']       = $upload['path'];
                }
            }

            $blogModel->update($id, $data);
            $this->syncTags($id, $tagsInput);

            logActivity(Session::getAdmin()['id'] ?? 0, 'update', 'blog', $id, "Updated blog post '{$title}'");
            Session::flash('success', "Article '{$title}' updated.");
            $this->redirect('/admin/blog');
        }

        $categories = (new BlogCategory())->getActive();
        $tags       = $blogModel->getTags($id);
        $tagsString = implode(', ', array_column($tags, 'name'));

        $this->view('admin/blog/form', [
            'pageTitle'  => "Edit Article: {$post['title']}",
            'post'       => $post,
            'categories' => $categories,
            'tagsString' => $tagsString,
        ], 'layouts/admin');
    }

    public function delete(array $params = []): void
    {
        $this->requireAdmin();
        $this->verifyCsrf();

        $id = (int) ($params['id'] ?? 0);
        $blogModel = new BlogPost();
        $post = $blogModel->find($id);

        if ($post) {
            $blogModel->delete($id);
            logActivity(Session::getAdmin()['id'] ?? 0, 'delete', 'blog', $id, "Deleted post '{$post['title']}'");
            Session::flash('success', 'Article deleted.');
        }

        $this->redirect('/admin/blog');
    }

    private function syncTags(int $postId, string $tagsInput): void
    {
        $tagModel = new BlogTag();
        $tagNames = array_filter(array_map('trim', explode(',', $tagsInput)));
        $tagIds = [];
        foreach ($tagNames as $name) {
            if ($name !== '') {
                $tagIds[] = $tagModel->findOrCreate($name);
            }
        }
        (new BlogPost())->syncTags($postId, $tagIds);
    }
}

