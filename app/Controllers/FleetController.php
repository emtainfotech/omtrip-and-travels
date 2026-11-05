<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\{Vehicle, VehicleCategory, Setting};

/**
 * FleetController — vehicle listing and detail pages.
 */
class FleetController extends Controller
{
    public function index(array $params = []): void
    {
        $vehicleModel   = new Vehicle();
        $categoryModel  = new VehicleCategory();
        $settings       = (new Setting())->getAllFlat();

        $categorySlug = sanitize($_GET['category'] ?? '');
        $seats        = (int)($_GET['seats'] ?? 0);
        $categoryId   = 0;
        $activeCategory = null;

        $categories = $categoryModel->getWithVehicleCount();

        if ($categorySlug) {
            $activeCategory = $categoryModel->findBySlug($categorySlug);
            if ($activeCategory) {
                $categoryId = (int)$activeCategory['id'];
            }
        }

        $vehicles = $vehicleModel->getActiveVehicles($categoryId, $seats);

        $appUrl = rtrim(env('APP_URL', ''), '/');
        $wa     = $settings['whatsapp_number'] ?? env('WHATSAPP_NUMBER', '919876543210');
        $waTpl  = $settings['wa_message_general'] ?? 'Hello, I want to enquire about your vehicles. Page: {page_url}';
        $waUrl  = 'https://wa.me/' . $wa . '?text=' . rawurlencode(str_replace('{page_url}', $appUrl . '/fleet', $waTpl));

        $pageTitle       = ($activeCategory ? e($activeCategory['name']) . ' on Rent in Indore | ' : '') . 'Vehicle Fleet | OM Trip and Travels';
        $metaDescription = 'Explore our fleet of Urbania, Tempo Traveller, Innova, Fortuner on rent in Indore. Best rates for group travel.';

        $this->render('fleet/index', compact(
            'vehicles', 'categories', 'activeCategory', 'categorySlug',
            'seats', 'appUrl', 'wa', 'waUrl', 'pageTitle', 'metaDescription', 'settings'
        ));
    }

    public function show(array $params = []): void
    {
        $slug    = $params['slug'] ?? '';
        $vehicle = (new Vehicle())->findBySlug($slug);

        if (!$vehicle) {
            $this->abort(404);
            return;
        }

        $vehicleModel = new Vehicle();
        $images   = $vehicleModel->getImages((int)$vehicle['id']);
        $related  = $vehicleModel->getRelated((int)$vehicle['id'], (int)$vehicle['category_id']);
        $settings = (new Setting())->getAllFlat();

        $appUrl  = rtrim(env('APP_URL', ''), '/');
        $wa      = $settings['whatsapp_number'] ?? env('WHATSAPP_NUMBER', '919876543210');
        $phone   = $settings['phone']           ?? env('PHONE_NUMBER', '+919876543210');
        $waTpl   = $settings['wa_message_vehicle'] ?? 'Hello, I am interested in {vehicle_name}. Page: {page_url}';
        $pageUrl = $appUrl . '/fleet/' . $slug;
        $waMsg   = str_replace(['{vehicle_name}', '{page_url}'], [$vehicle['name'], $pageUrl], $waTpl);
        $waUrl   = 'https://wa.me/' . $wa . '?text=' . rawurlencode($waMsg);

        // Decode JSON features
        $features  = json_decode($vehicle['features']  ?? '[]', true) ?: [];
        $idealFor  = json_decode($vehicle['ideal_for'] ?? '[]', true) ?: [];

        $pageTitle       = $vehicle['meta_title'] ?: ($vehicle['name'] . ' on Rent in Indore | OM Trip and Travels');
        $metaDescription = $vehicle['meta_description'] ?: ('Book ' . $vehicle['name'] . ' on rent in Indore. Best rates, AC, experienced driver. Call now.');
        $ogImage         = $vehicle['og_image'] ?: ($vehicle['primary_image'] ?: $appUrl . '/assets/images/og-default.jpg');
        $canonical       = $pageUrl;

        // JSON-LD: Vehicle/Product
        $schemaMarkup = json_encode([
            '@context' => 'https://schema.org',
            '@type'    => 'Product',
            'name'     => $vehicle['name'],
            'description' => $vehicle['short_description'],
            'image'    => $appUrl . ($vehicle['primary_image'] ?? ''),
            'brand'    => ['@type' => 'Brand', 'name' => 'OM Trip and Travels'],
            'offers'   => [
                '@type'           => 'Offer',
                'priceCurrency'   => 'INR',
                'price'           => $vehicle['starting_price'] ?? 0,
                'priceSpecification' => $vehicle['price_note'] ?? 'On Request',
                'seller'          => ['@type' => 'LocalBusiness', 'name' => 'OM Trip and Travels'],
                'availability'    => 'https://schema.org/InStock',
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $this->render('fleet/show', compact(
            'vehicle', 'images', 'related', 'features', 'idealFor',
            'phone', 'wa', 'waUrl', 'appUrl', 'pageUrl',
            'pageTitle', 'metaDescription', 'ogImage', 'canonical',
            'schemaMarkup', 'settings'
        ));
    }
}

