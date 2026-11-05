<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Banner;
use App\Helpers\ImageUploader;

/**
 * BannerController — Admin homepage slider & hero banner management.
 */
class BannerController extends Controller
{
    public function index(array $params = []): void
    {
        $this->requireSuperAdmin();
        $banners = (new Banner())->all([], 'sort_order ASC, id ASC');

        $this->view('admin/banners/index', [
            'pageTitle' => 'Hero Banners & Slider',
            'banners'   => $banners,
        ], 'layouts/admin');
    }

    public function create(array $params = []): void
    {
        $this->requireSuperAdmin();
        if ($this->isPost()) {
            $this->verifyCsrf();
            $title    = sanitize($this->post('title'));
            $subtitle = sanitize($this->post('subtitle'));
            $ctaText  = sanitize($this->post('cta_text'));
            $ctaUrl   = sanitize($this->post('cta_url', '#'));
            $ctaType  = in_array($this->post('cta_type'), ['call', 'whatsapp', 'link', 'none']) ? $this->post('cta_type') : 'call';
            $sortOrder= $this->postInt('sort_order', 0);
            $isActive = $this->postInt('is_active', 1);

            $imagePath = '';
            if (!empty($_FILES['image']['name'])) {
                $upload = ImageUploader::upload($_FILES['image'], 'banners');
                if ($upload['success']) {
                    $imagePath = $upload['path'];
                }
            }

            if ($imagePath) {
                (new Banner())->create([
                    'title'       => $title ?: null,
                    'subtitle'    => $subtitle ?: null,
                    'image_path'  => $imagePath,
                    'cta_text'    => $ctaText ?: null,
                    'cta_url'     => $ctaUrl,
                    'cta_type'    => $ctaType,
                    'sort_order'  => $sortOrder,
                    'is_active'   => $isActive,
                ]);
                Session::flash('success', 'Banner created.');
            } else {
                Session::flash('error', 'Please choose a banner image.');
            }
        }
        $this->redirect('/admin/banners');
    }

    public function delete(array $params = []): void
    {
        $this->requireSuperAdmin();
        $this->verifyCsrf();
        $id = (int) ($params['id'] ?? 0);
        $bannerModel = new Banner();
        $b = $bannerModel->find($id);
        if ($b) {
            $path = ROOT_PATH . '/public' . $b['image_path'];
            if (file_exists($path)) @unlink($path);
            $bannerModel->delete($id);
            Session::flash('success', 'Banner deleted.');
        }
        $this->redirect('/admin/banners');
    }
}

