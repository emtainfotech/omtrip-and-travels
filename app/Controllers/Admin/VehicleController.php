<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Models\{Vehicle, VehicleCategory};
use App\Helpers\ImageUploader;

/**
 * VehicleController — Admin CRUD for vehicles and vehicle categories.
 */
class VehicleController extends Controller
{
    public function index(array $params = []): void
    {
        $this->requireAdmin();

        $vehicleModel = new Vehicle();
        $catModel     = new VehicleCategory();

        $search     = $this->get('search');
        $categoryId = $this->getInt('category_id');
        $page       = max(1, $this->getInt('page', 1));

        $result     = $vehicleModel->getAllForAdmin($search, $categoryId, $page, 20);
        $categories = $catModel->getActive();

        $this->view('admin/vehicles/index', [
            'pageTitle'  => 'Vehicle Fleet Management',
            'vehicles'   => $result['data'],
            'pagination' => $result,
            'categories' => $categories,
            'search'     => $search,
            'categoryId' => $categoryId,
        ], 'layouts/admin');
    }

    public function create(array $params = []): void
    {
        $this->requireSuperAdmin();
        $vehicleModel = new Vehicle();
        $catModel     = new VehicleCategory();

        if ($this->isPost()) {
            $this->verifyCsrf();

            $name        = sanitize($this->post('name'));
            $slug        = slug($this->post('slug') ?: $name);
            $categoryId  = $this->postInt('category_id');
            $seats       = $this->postInt('seats', 4);
            $hasAc       = $this->postInt('has_ac', 1);
            $luggage     = sanitize($this->post('luggage_capacity'));
            $shortDesc   = sanitize($this->post('short_description'));
            $description = $this->post('description'); // HTML allowed for rich text
            $startingPrice = $this->post('starting_price') !== '' ? (float)$this->post('starting_price') : null;
            $priceNote   = sanitize($this->post('price_note'));
            $isFeatured  = $this->postInt('is_featured', 0);
            $isActive    = $this->postInt('is_active', 1);
            $sortOrder   = $this->postInt('sort_order', 0);
            $metaTitle   = sanitize($this->post('meta_title'));
            $metaDesc    = sanitize($this->post('meta_description'));

            // Features and Ideal For (comma-separated converted to JSON)
            $featuresRaw = explode(',', $this->post('features'));
            $features = array_values(array_filter(array_map('trim', $featuresRaw)));

            $idealForRaw = explode(',', $this->post('ideal_for'));
            $idealFor = array_values(array_filter(array_map('trim', $idealForRaw)));

            // Handle primary image upload
            $primaryImage = null;
            if (!empty($_FILES['primary_image']['name'])) {
                $upload = ImageUploader::upload($_FILES['primary_image'], 'vehicles');
                if ($upload['success']) {
                    $primaryImage = $upload['path'];
                }
            }

            $id = $vehicleModel->create([
                'category_id'       => $categoryId,
                'name'              => $name,
                'slug'              => $slug,
                'short_description' => $shortDesc ?: null,
                'description'       => $description ?: null,
                'seats'             => $seats,
                'luggage_capacity'  => $luggage ?: null,
                'has_ac'            => $hasAc,
                'features'          => json_encode($features),
                'ideal_for'         => json_encode($idealFor),
                'starting_price'    => $startingPrice,
                'price_note'        => $priceNote ?: null,
                'primary_image'     => $primaryImage,
                'is_featured'       => $isFeatured,
                'sort_order'        => $sortOrder,
                'is_active'         => $isActive,
                'meta_title'        => $metaTitle ?: null,
                'meta_description'  => $metaDesc ?: null,
                'og_image'          => $primaryImage,
            ]);

            // Handle additional gallery images
            if (!empty($_FILES['gallery_images']['name'][0])) {
                $this->uploadGalleryImages((int)$id, $_FILES['gallery_images']);
            }

            logActivity(Session::getAdmin()['id'] ?? 0, 'create', 'vehicle', (int)$id, "Created vehicle '{$name}'");
            Session::flash('success', "Vehicle '{$name}' created successfully.");
            $this->redirect('/admin/vehicles');
        }

        $categories = $catModel->getActive();
        $this->view('admin/vehicles/form', [
            'pageTitle'  => 'Add New Vehicle',
            'vehicle'    => null,
            'categories' => $categories,
            'images'     => [],
        ], 'layouts/admin');
    }

    public function edit(array $params = []): void
    {
        $this->requireSuperAdmin();
        $id = (int) ($params['id'] ?? 0);
        $vehicleModel = new Vehicle();
        $vehicle = $vehicleModel->find($id);

        if (!$vehicle) {
            Session::flash('error', 'Vehicle not found.');
            $this->redirect('/admin/vehicles');
        }

        if ($this->isPost()) {
            $this->verifyCsrf();

            $name        = sanitize($this->post('name'));
            $slug        = slug($this->post('slug') ?: $name);
            $categoryId  = $this->postInt('category_id');
            $seats       = $this->postInt('seats', 4);
            $hasAc       = $this->postInt('has_ac', 1);
            $luggage     = sanitize($this->post('luggage_capacity'));
            $shortDesc   = sanitize($this->post('short_description'));
            $description = $this->post('description');
            $startingPrice = $this->post('starting_price') !== '' ? (float)$this->post('starting_price') : null;
            $priceNote   = sanitize($this->post('price_note'));
            $isFeatured  = $this->postInt('is_featured', 0);
            $isActive    = $this->postInt('is_active', 1);
            $sortOrder   = $this->postInt('sort_order', 0);
            $metaTitle   = sanitize($this->post('meta_title'));
            $metaDesc    = sanitize($this->post('meta_description'));

            $featuresRaw = explode(',', $this->post('features'));
            $features = array_values(array_filter(array_map('trim', $featuresRaw)));

            $idealForRaw = explode(',', $this->post('ideal_for'));
            $idealFor = array_values(array_filter(array_map('trim', $idealForRaw)));

            $data = [
                'category_id'       => $categoryId,
                'name'              => $name,
                'slug'              => $slug,
                'short_description' => $shortDesc ?: null,
                'description'       => $description ?: null,
                'seats'             => $seats,
                'luggage_capacity'  => $luggage ?: null,
                'has_ac'            => $hasAc,
                'features'          => json_encode($features),
                'ideal_for'         => json_encode($idealFor),
                'starting_price'    => $startingPrice,
                'price_note'        => $priceNote ?: null,
                'is_featured'       => $isFeatured,
                'sort_order'        => $sortOrder,
                'is_active'         => $isActive,
                'meta_title'        => $metaTitle ?: null,
                'meta_description'  => $metaDesc ?: null,
            ];

            // Primary image update
            if (!empty($_FILES['primary_image']['name'])) {
                $upload = ImageUploader::upload($_FILES['primary_image'], 'vehicles');
                if ($upload['success']) {
                    $data['primary_image'] = $upload['path'];
                    $data['og_image']      = $upload['path'];
                }
            }

            $vehicleModel->update($id, $data);

            // Handle additional gallery images
            if (!empty($_FILES['gallery_images']['name'][0])) {
                $this->uploadGalleryImages($id, $_FILES['gallery_images']);
            }

            logActivity(Session::getAdmin()['id'] ?? 0, 'update', 'vehicle', $id, "Updated vehicle '{$name}'");
            Session::flash('success', "Vehicle '{$name}' updated successfully.");
            $this->redirect('/admin/vehicles');
        }

        $catModel   = new VehicleCategory();
        $categories = $catModel->getActive();
        $images     = $vehicleModel->getImages($id);

        $this->view('admin/vehicles/form', [
            'pageTitle'  => "Edit Vehicle: {$vehicle['name']}",
            'vehicle'    => $vehicle,
            'categories' => $categories,
            'images'     => $images,
        ], 'layouts/admin');
    }

    public function delete(array $params = []): void
    {
        $this->requireSuperAdmin();
        $this->verifyCsrf();

        $id = (int) ($params['id'] ?? 0);
        $vehicleModel = new Vehicle();
        $vehicle = $vehicleModel->find($id);

        if ($vehicle) {
            $vehicleModel->deleteImages($id);
            $vehicleModel->delete($id);
            logActivity(Session::getAdmin()['id'] ?? 0, 'delete', 'vehicle', $id, "Deleted vehicle '{$vehicle['name']}'");
            Session::flash('success', 'Vehicle deleted.');
        }

        $this->redirect('/admin/vehicles');
    }

    public function toggle(array $params = []): void
    {
        $this->requireSuperAdmin();
        $this->verifyCsrf();

        $id = (int) ($params['id'] ?? 0);
        $vehicleModel = new Vehicle();
        $vehicle = $vehicleModel->find($id);

        if ($vehicle) {
            $newStatus = $vehicle['is_active'] ? 0 : 1;
            $vehicleModel->update($id, ['is_active' => $newStatus]);
            Session::flash('success', 'Vehicle status updated.');
        }

        $this->redirect('/admin/vehicles');
    }

    private function uploadGalleryImages(int $vehicleId, array $files): void
    {
        $vehicleModel = new Vehicle();
        $count = count($files['name']);
        for ($i = 0; $i < $count; $i++) {
            if (empty($files['name'][$i])) continue;
            $singleFile = [
                'name'     => $files['name'][$i],
                'type'     => $files['type'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'error'    => $files['error'][$i],
                'size'     => $files['size'][$i],
            ];
            $upload = ImageUploader::upload($singleFile, 'vehicles');
            if ($upload['success']) {
                $vehicleModel->addImage($vehicleId, $upload['path'], '', $i);
            }
        }
    }
}

