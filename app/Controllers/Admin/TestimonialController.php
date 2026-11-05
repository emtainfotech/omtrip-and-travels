<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Testimonial;

/**
 * TestimonialController — Admin management for reviews.
 */
class TestimonialController extends Controller
{
    public function index(array $params = []): void
    {
        $this->requireSuperAdmin();
        $testModel = new Testimonial();
        $testimonials = $testModel->all([], 'sort_order ASC, id DESC');

        $this->view('admin/testimonials/index', [
            'pageTitle'    => 'Customer Reviews & Testimonials',
            'testimonials' => $testimonials,
        ], 'layouts/admin');
    }

    public function create(array $params = []): void
    {
        $this->requireSuperAdmin();
        if ($this->isPost()) {
            $this->verifyCsrf();
            $name     = sanitize($this->post('name'));
            $location = sanitize($this->post('location'));
            $designation = sanitize($this->post('designation'));
            $vehicle  = sanitize($this->post('vehicle_booked'));
            $rating   = max(1, min(5, $this->postInt('rating', 5)));
            $review   = sanitize($this->post('review'));
            $isFeatured = $this->postInt('is_featured', 0);
            $isActive   = $this->postInt('is_active', 1);
            $sortOrder  = $this->postInt('sort_order', 0);

            (new Testimonial())->create([
                'name'           => $name,
                'location'       => $location ?: null,
                'designation'    => $designation ?: null,
                'vehicle_booked' => $vehicle ?: null,
                'rating'         => $rating,
                'review'         => $review,
                'is_featured'    => $isFeatured,
                'is_active'      => $isActive,
                'sort_order'     => $sortOrder,
            ]);

            Session::flash('success', 'Testimonial added.');
        }
        $this->redirect('/admin/testimonials');
    }

    public function edit(array $params = []): void
    {
        $this->requireSuperAdmin();
        $id = (int) ($params['id'] ?? 0);
        $testModel = new Testimonial();
        $t = $testModel->find($id);

        if ($t && $this->isPost()) {
            $this->verifyCsrf();
            $testModel->update($id, [
                'name'           => sanitize($this->post('name')),
                'location'       => sanitize($this->post('location')),
                'designation'    => sanitize($this->post('designation')),
                'vehicle_booked' => sanitize($this->post('vehicle_booked')),
                'rating'         => max(1, min(5, $this->postInt('rating', 5))),
                'review'         => sanitize($this->post('review')),
                'is_featured'    => $this->postInt('is_featured', 0),
                'is_active'      => $this->postInt('is_active', 1),
                'sort_order'     => $this->postInt('sort_order', 0),
            ]);
            Session::flash('success', 'Testimonial updated.');
        }
        $this->redirect('/admin/testimonials');
    }

    public function delete(array $params = []): void
    {
        $this->requireSuperAdmin();
        $this->verifyCsrf();
        $id = (int) ($params['id'] ?? 0);
        (new Testimonial())->delete($id);
        Session::flash('success', 'Testimonial deleted.');
        $this->redirect('/admin/testimonials');
    }
}

