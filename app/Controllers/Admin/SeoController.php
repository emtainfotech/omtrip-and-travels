<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Database;

/**
 * SeoController — Per-page meta tags & SEO configuration.
 */
class SeoController extends Controller
{
    public function index(array $params = []): void
    {
        $this->requireSuperAdmin();
        $db = Database::getInstance();
        $pages = [];
        if ($db->isConnected()) {
            $pages = $db->fetchAll("SELECT * FROM pages_seo ORDER BY page_key ASC");
        }

        $this->view('admin/seo/index', [
            'pageTitle' => 'Page SEO & Meta Tags',
            'pages'     => $pages,
        ], 'layouts/admin');
    }

    public function edit(array $params = []): void
    {
        $this->requireSuperAdmin();
        $pageKey = sanitize($params['page_key'] ?? '');
        $db = Database::getInstance();
        $page = $db->fetchOne("SELECT * FROM pages_seo WHERE page_key = ? LIMIT 1", [$pageKey]);

        if (!$page) {
            Session::flash('error', 'SEO entry not found.');
            $this->redirect('/admin/seo');
        }

        if ($this->isPost()) {
            $this->verifyCsrf();
            $title       = sanitize($this->post('title'));
            $metaDesc    = sanitize($this->post('meta_description'));
            $canonical   = sanitize($this->post('canonical'));
            $ogTitle     = sanitize($this->post('og_title'));
            $ogDesc      = sanitize($this->post('og_description'));
            $schemaJson  = $this->post('schema_json');

            $db->update('pages_seo', [
                'title'            => $title ?: null,
                'meta_description' => $metaDesc ?: null,
                'canonical'        => $canonical ?: null,
                'og_title'         => $ogTitle ?: null,
                'og_description'   => $ogDesc ?: null,
                'schema_json'      => $schemaJson ?: null,
            ], ['page_key' => $pageKey]);

            Session::flash('success', "SEO settings for '{$pageKey}' saved.");
            $this->redirect('/admin/seo');
        }

        $this->view('admin/seo/form', [
            'pageTitle' => "Edit SEO: {$pageKey}",
            'page'      => $page,
        ], 'layouts/admin');
    }
}

