<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Models\{TourPackage, Vehicle};
use App\Helpers\ImageUploader;

/**
 * PackageController — Admin CRUD for tour packages.
 */
class PackageController extends Controller
{
    public function index(array $params = []): void
    {
        $this->requireAdmin();

        $packageModel = new TourPackage();
        $search       = $this->get('search');
        $page         = max(1, $this->getInt('page', 1));

        $result = $packageModel->getAllForAdmin($search, $page, 20);

        $this->view('admin/packages/index', [
            'pageTitle'  => 'Tour Packages Management',
            'packages'   => $result['data'],
            'pagination' => $result,
            'search'     => $search,
        ], 'layouts/admin');
    }

    public function create(array $params = []): void
    {
        $this->requireSuperAdmin();
        $packageModel = new TourPackage();
        $vehicleModel = new Vehicle();

        if ($this->isPost()) {
            $this->verifyCsrf();

            $name           = sanitize($this->post('name'));
            $slug           = slug($this->post('slug') ?: $name);
            $shortDesc      = sanitize($this->post('short_description'));
            $description    = $this->post('description');
            $itinerary      = $this->post('itinerary');
            $inclusions     = sanitize($this->post('inclusions'));
            $exclusions     = sanitize($this->post('exclusions'));
            $durationDays   = $this->postInt('duration_days', 1);
            $durationNights = $this->postInt('duration_nights', 0);
            $route          = sanitize($this->post('route'));
            $origin         = sanitize($this->post('origin', 'Indore'));
            $destination    = sanitize($this->post('destination'));
            $startingPrice  = $this->post('starting_price') !== '' ? (float)$this->post('starting_price') : null;
            $priceNote      = sanitize($this->post('price_note'));
            $isFeatured     = $this->postInt('is_featured', 0);
            $isActive       = $this->postInt('is_active', 1);
            $sortOrder      = $this->postInt('sort_order', 0);
            $metaTitle      = sanitize($this->post('meta_title'));
            $metaDesc       = sanitize($this->post('meta_description'));
            $linkedVehicles = $_POST['vehicles'] ?? [];

            $primaryImage = null;
            if (!empty($_FILES['primary_image']['name'])) {
                $upload = ImageUploader::upload($_FILES['primary_image'], 'packages');
                if ($upload['success']) {
                    $primaryImage = $upload['path'];
                }
            }

            $id = $packageModel->create([
                'name'              => $name,
                'slug'              => $slug,
                'short_description' => $shortDesc ?: null,
                'description'       => $description ?: null,
                'itinerary'         => $itinerary ?: null,
                'inclusions'        => $inclusions ?: null,
                'exclusions'        => $exclusions ?: null,
                'duration_days'     => $durationDays,
                'duration_nights'   => $durationNights,
                'route'             => $route ?: null,
                'origin'            => $origin ?: 'Indore',
                'destination'       => $destination ?: null,
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

            // Sync linked vehicles
            if (!empty($linkedVehicles)) {
                $packageModel->syncVehicles((int)$id, $linkedVehicles);
            }

            logActivity(Session::getAdmin()['id'] ?? 0, 'create', 'package', (int)$id, "Created package '{$name}'");
            Session::flash('success', "Package '{$name}' created successfully.");
            $this->redirect('/admin/packages');
        }

        $allVehicles = $vehicleModel->all(['is_active' => 1], 'name ASC');

        $this->view('admin/packages/form', [
            'pageTitle'      => 'Add Tour Package',
            'package'        => null,
            'allVehicles'    => $allVehicles,
            'linkedVehicles' => [],
        ], 'layouts/admin');
    }

    public function edit(array $params = []): void
    {
        $this->requireSuperAdmin();
        $id = (int) ($params['id'] ?? 0);
        $packageModel = new TourPackage();
        $package = $packageModel->find($id);

        if (!$package) {
            Session::flash('error', 'Tour package not found.');
            $this->redirect('/admin/packages');
        }

        if ($this->isPost()) {
            $this->verifyCsrf();

            $name           = sanitize($this->post('name'));
            $slug           = slug($this->post('slug') ?: $name);
            $shortDesc      = sanitize($this->post('short_description'));
            $description    = $this->post('description');
            $itinerary      = $this->post('itinerary');
            $inclusions     = sanitize($this->post('inclusions'));
            $exclusions     = sanitize($this->post('exclusions'));
            $durationDays   = $this->postInt('duration_days', 1);
            $durationNights = $this->postInt('duration_nights', 0);
            $route          = sanitize($this->post('route'));
            $origin         = sanitize($this->post('origin', 'Indore'));
            $destination    = sanitize($this->post('destination'));
            $startingPrice  = $this->post('starting_price') !== '' ? (float)$this->post('starting_price') : null;
            $priceNote      = sanitize($this->post('price_note'));
            $isFeatured     = $this->postInt('is_featured', 0);
            $isActive       = $this->postInt('is_active', 1);
            $sortOrder      = $this->postInt('sort_order', 0);
            $metaTitle      = sanitize($this->post('meta_title'));
            $metaDesc       = sanitize($this->post('meta_description'));
            $linkedVehicles = $_POST['vehicles'] ?? [];

            $data = [
                'name'              => $name,
                'slug'              => $slug,
                'short_description' => $shortDesc ?: null,
                'description'       => $description ?: null,
                'itinerary'         => $itinerary ?: null,
                'inclusions'        => $inclusions ?: null,
                'exclusions'        => $exclusions ?: null,
                'duration_days'     => $durationDays,
                'duration_nights'   => $durationNights,
                'route'             => $route ?: null,
                'origin'            => $origin ?: 'Indore',
                'destination'       => $destination ?: null,
                'starting_price'    => $startingPrice,
                'price_note'        => $priceNote ?: null,
                'is_featured'       => $isFeatured,
                'sort_order'        => $sortOrder,
                'is_active'         => $isActive,
                'meta_title'        => $metaTitle ?: null,
                'meta_description'  => $metaDesc ?: null,
            ];

            if (!empty($_FILES['primary_image']['name'])) {
                $upload = ImageUploader::upload($_FILES['primary_image'], 'packages');
                if ($upload['success']) {
                    $data['primary_image'] = $upload['path'];
                    $data['og_image']      = $upload['path'];
                }
            }

            $packageModel->update($id, $data);
            $packageModel->syncVehicles($id, $linkedVehicles);

            logActivity(Session::getAdmin()['id'] ?? 0, 'update', 'package', $id, "Updated package '{$name}'");
            Session::flash('success', "Package '{$name}' updated successfully.");
            $this->redirect('/admin/packages');
        }

        $vehicleModel   = new Vehicle();
        $allVehicles    = $vehicleModel->all(['is_active' => 1], 'name ASC');
        $linkedVehicles = array_column($packageModel->getVehicles($id), 'id');

        $this->view('admin/packages/form', [
            'pageTitle'      => "Edit Package: {$package['name']}",
            'package'        => $package,
            'allVehicles'    => $allVehicles,
            'linkedVehicles' => $linkedVehicles,
        ], 'layouts/admin');
    }

    public function delete(array $params = []): void
    {
        $this->requireSuperAdmin();
        $this->verifyCsrf();

        $id = (int) ($params['id'] ?? 0);
        $packageModel = new TourPackage();
        $package = $packageModel->find($id);

        if ($package) {
            $packageModel->delete($id);
            logActivity(Session::getAdmin()['id'] ?? 0, 'delete', 'package', $id, "Deleted package '{$package['name']}'");
            Session::flash('success', 'Tour package deleted.');
        }

        $this->redirect('/admin/packages');
    }
}

