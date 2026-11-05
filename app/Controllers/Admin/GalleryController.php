<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Models\GalleryItem;
use App\Helpers\ImageUploader;

/**
 * GalleryController — Admin photo gallery management.
 */
class GalleryController extends Controller
{
    public function index(array $params = []): void
    {
        $this->requireSuperAdmin();
        $galleryModel = new GalleryItem();
        $items = $galleryModel->all([], 'sort_order ASC, id DESC');
        $categories = $galleryModel->getCategories();

        $this->view('admin/gallery/index', [
            'pageTitle'  => 'Photo Gallery Management',
            'items'      => $items,
            'categories' => $categories,
        ], 'layouts/admin');
    }

    public function upload(array $params = []): void
    {
        $this->requireSuperAdmin();
        if ($this->isPost()) {
            $this->verifyCsrf();
            $title    = sanitize($this->post('title'));
            $category = sanitize($this->post('category', 'Fleet'));
            $altText  = sanitize($this->post('alt_text', $title));

            if (!empty($_FILES['image']['name'])) {
                $upload = ImageUploader::upload($_FILES['image'], 'gallery');
                if ($upload['success']) {
                    (new GalleryItem())->create([
                        'title'      => $title ?: null,
                        'image_path' => $upload['path'],
                        'alt_text'   => $altText ?: null,
                        'category'   => $category ?: 'Fleet',
                        'is_active'  => 1,
                        'sort_order' => 0,
                    ]);
                    Session::flash('success', 'Photo added to gallery.');
                } else {
                    Session::flash('error', $upload['error']);
                }
            } else {
                Session::flash('error', 'Please choose an image file.');
            }
        }
        $this->redirect('/admin/gallery');
    }

    public function delete(array $params = []): void
    {
        $this->requireSuperAdmin();
        $this->verifyCsrf();
        $id = (int) ($params['id'] ?? 0);
        $galleryModel = new GalleryItem();
        $item = $galleryModel->find($id);

        if ($item) {
            $path = ROOT_PATH . '/public' . $item['image_path'];
            if (file_exists($path)) @unlink($path);
            $galleryModel->delete($id);
            Session::flash('success', 'Photo deleted.');
        }

        $this->redirect('/admin/gallery');
    }
}

