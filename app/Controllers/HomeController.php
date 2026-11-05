<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\{Banner, Vehicle, TourPackage, Testimonial, BlogPost, Faq, Setting, VehicleCategory};

/**
 * HomeController — renders the homepage.
 */
class HomeController extends Controller
{
    public function index(array $params = []): void
    {
        $banners      = (new Banner())->getActive();
        $featured     = (new Vehicle())->getFeatured(6);
        $packages     = (new TourPackage())->getFeatured(4);
        $testimonials = (new Testimonial())->getFeatured(6);
        $blogs        = (new BlogPost())->getLatest(3);
        $faqs         = (new Faq())->getActive();
        $faqs         = array_slice($faqs, 0, 6);
        $categories   = (new VehicleCategory())->getWithVehicleCount();

        $settings     = (new Setting())->getAllFlat();
        $phone        = $settings['phone']            ?? env('PHONE_NUMBER', '+919876543210');
        $wa           = $settings['whatsapp_number']  ?? env('WHATSAPP_NUMBER', '919876543210');
        $waMsgTpl     = $settings['wa_message_general'] ?? 'Hello OM Trip and Travels, I would like to enquire. Page: {page_url}';
        $appUrl       = rtrim(env('APP_URL', ''), '/');
        $waMsg        = str_replace('{page_url}', $appUrl . '/', $waMsgTpl);
        $waUrl        = 'https://wa.me/' . $wa . '?text=' . rawurlencode($waMsg);

        $this->render('home/index', compact(
            'banners', 'featured', 'packages', 'testimonials',
            'blogs', 'faqs', 'categories', 'phone', 'wa',
            'waUrl', 'waMsgTpl', 'appUrl', 'settings'
        ));
    }
}

