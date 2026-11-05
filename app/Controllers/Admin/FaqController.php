<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Faq;

/**
 * FaqController — Admin FAQ management.
 */
class FaqController extends Controller
{
    public function index(array $params = []): void
    {
        $this->requireSuperAdmin();
        $faqs = (new Faq())->all([], 'category ASC, sort_order ASC');

        $this->view('admin/faqs/index', [
            'pageTitle' => 'Frequently Asked Questions (FAQ)',
            'faqs'      => $faqs,
        ], 'layouts/admin');
    }

    public function create(array $params = []): void
    {
        $this->requireSuperAdmin();
        if ($this->isPost()) {
            $this->verifyCsrf();
            (new Faq())->create([
                'question'   => sanitize($this->post('question')),
                'answer'     => $this->post('answer'),
                'category'   => sanitize($this->post('category', 'General')),
                'sort_order' => $this->postInt('sort_order', 0),
                'is_active'  => $this->postInt('is_active', 1),
            ]);
            Session::flash('success', 'FAQ question added.');
        }
        $this->redirect('/admin/faqs');
    }

    public function edit(array $params = []): void
    {
        $this->requireSuperAdmin();
        $id = (int) ($params['id'] ?? 0);
        $faqModel = new Faq();
        if ($this->isPost()) {
            $this->verifyCsrf();
            $faqModel->update($id, [
                'question'   => sanitize($this->post('question')),
                'answer'     => $this->post('answer'),
                'category'   => sanitize($this->post('category', 'General')),
                'sort_order' => $this->postInt('sort_order', 0),
                'is_active'  => $this->postInt('is_active', 1),
            ]);
            Session::flash('success', 'FAQ updated.');
        }
        $this->redirect('/admin/faqs');
    }

    public function delete(array $params = []): void
    {
        $this->requireSuperAdmin();
        $this->verifyCsrf();
        $id = (int) ($params['id'] ?? 0);
        (new Faq())->delete($id);
        Session::flash('success', 'FAQ deleted.');
        $this->redirect('/admin/faqs');
    }
}

