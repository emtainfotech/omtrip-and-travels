<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\{TourPackage, Vehicle, Setting};

/**
 * PackageController — tour packages listing and detail pages.
 */
class PackageController extends Controller
{
    public function index(array $params = []): void
    {
        $packageModel = new TourPackage();
        $packages     = $packageModel->getActive();
        $settings     = (new Setting())->getAllFlat();

        $appUrl = rtrim(env('APP_URL', ''), '/');
        $wa     = $settings['whatsapp_number'] ?? env('WHATSAPP_NUMBER', '919876543210');
        $phone  = $settings['phone']           ?? env('PHONE_NUMBER', '+919876543210');
        $waTpl  = $settings['wa_message_package'] ?? 'Hello, I want to enquire about tour packages from Indore. Page: {page_url}';
        $waMsg  = str_replace(['{package_name}', '{page_url}'], ['Tour Packages', $appUrl . '/packages'], $waTpl);
        $waUrl  = 'https://wa.me/' . $wa . '?text=' . rawurlencode($waMsg);

        $pageTitle       = 'Tour Packages from Indore | Ujjain, Omkareshwar, Pachmarhi | OM Trip and Travels';
        $metaDescription = 'Explore popular tour packages from Indore: Mahakaleshwar Ujjain, Omkareshwar Jyotirlinga, Maheshwar-Mandu, Pachmarhi, Shirdi & more. Group travel with Urbania & Tempo Traveller.';

        $this->view('packages/index', compact(
            'packages', 'phone', 'wa', 'waUrl', 'appUrl',
            'pageTitle', 'metaDescription', 'settings'
        ));
    }

    public function show(array $params = []): void
    {
        $slug = $params['slug'] ?? '';

        $aliases = [
            'maheshwar-mandu-heritage-tour'     => 'maheshwar-mandu-tour',
            'indore-to-ujjain-mahakal-darshan'  => 'indore-to-ujjain',
            'indore-to-omkareshwar-jyotirlinga' => 'indore-to-omkareshwar',
            'pachmarhi-hill-station-tour'       => 'indore-to-pachmarhi',
            'shirdi-sai-baba-darshan'           => 'indore-to-shirdi',
            'khajuraho-heritage-tour'           => 'indore-to-khajuraho',
        ];
        if (isset($aliases[$slug])) {
            $slug = $aliases[$slug];
        }

        $packageModel = new TourPackage();
        $package = $packageModel->findBySlug($slug);

        if (!$package) {
            $this->abort(404, 'Tour package not found.');
        }

        $vehicles = $packageModel->getVehicles((int)$package['id']);
        $images   = $packageModel->getImages((int)$package['id']);
        $settings = (new Setting())->getAllFlat();

        $appUrl  = rtrim(env('APP_URL', ''), '/');
        $wa      = $settings['whatsapp_number'] ?? env('WHATSAPP_NUMBER', '919876543210');
        $phone   = $settings['phone']           ?? env('PHONE_NUMBER', '+919876543210');
        $pageUrl = $appUrl . '/packages/' . $slug;

        $waTpl = $settings['wa_message_package'] ?? 'Hello, I want to enquire about the {package_name} package. Page: {page_url}';
        $waMsg = str_replace(['{package_name}', '{page_url}'], [$package['name'], $pageUrl], $waTpl);
        $waUrl = 'https://wa.me/' . $wa . '?text=' . rawurlencode($waMsg);

        $pageTitle       = $package['meta_title'] ?: ($package['name'] . ' Tour Package from Indore | OM Trip and Travels');
        $metaDescription = $package['meta_description'] ?: ($package['short_description'] ?? 'Book ' . $package['name'] . ' from Indore. Affordable group travel with AC cab, Urbania, Tempo Traveller.');
        $ogImage         = $package['og_image'] ?: ($package['primary_image'] ?: $appUrl . '/assets/images/og-default.jpg');
        $canonical       = $pageUrl;

        // JSON-LD: TouristTrip / Product
        $schemaMarkup = json_encode([
            '@context'    => 'https://schema.org',
            '@type'       => 'TouristTrip',
            'name'        => $package['name'],
            'description' => $package['short_description'] ?? '',
            'touristType' => ['Family', 'Pilgrim', 'Group'],
            'provider'    => [
                '@type' => 'TravelAgency',
                'name'  => 'OM Trip and Travels',
                'url'   => $appUrl,
            ],
            'itinerary'   => [
                '@type' => 'ItemList',
                'name'  => $package['route'] ?? $package['name'],
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $this->view('packages/show', compact(
            'package', 'vehicles', 'images', 'phone', 'wa', 'waUrl',
            'appUrl', 'pageUrl', 'pageTitle', 'metaDescription',
            'ogImage', 'canonical', 'schemaMarkup', 'settings'
        ));
    }
}

