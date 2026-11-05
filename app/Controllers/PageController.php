<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\{Faq, GalleryItem, Testimonial, Vehicle, Setting};

/**
 * PageController — static and informative public pages.
 */
class PageController extends Controller
{
    public function about(array $params = []): void
    {
        $settings = (new Setting())->getAllFlat();
        $pageTitle = 'About Us | Urbania & Tempo Traveller Specialists | OM Trip and Travels Indore';
        $metaDescription = 'Learn about OM Trip and Travels, Indore\'s premier car rental and travel agency. Specializing in Force Urbania, Tempo Traveller, Innova, and Fortuner rentals for group tours.';

        $this->view('pages/about', compact('settings', 'pageTitle', 'metaDescription'));
    }

    public function contact(array $params = []): void
    {
        $settings = (new Setting())->getAllFlat();
        $vehicles = (new Vehicle())->getActiveVehicles();
        $pageTitle = 'Contact Us | Cab & Tempo Traveller Booking Indore | OM Trip and Travels';
        $metaDescription = 'Contact OM Trip and Travels for taxi, car rental, Urbania, and Tempo Traveller bookings in Indore. 24/7 customer support via phone, WhatsApp, and email.';

        $this->view('pages/contact', compact('settings', 'vehicles', 'pageTitle', 'metaDescription'));
    }

    public function gallery(array $params = []): void
    {
        $galleryModel = new GalleryItem();
        $category = $this->get('category', '');
        $page     = max(1, $this->getInt('page', 1));

        $result     = $galleryModel->getActive($category, $page, 24);
        $categories = $galleryModel->getCategories();
        $settings   = (new Setting())->getAllFlat();

        $pageTitle = 'Fleet & Tour Photo Gallery | OM Trip and Travels Indore';
        $metaDescription = 'Browse our photo gallery showing Force Urbania, Tempo Travellers, Toyota Fortuner, Innova Crysta, and memorable customer tours from Indore.';

        $this->view('pages/gallery', [
            'items'          => $result['data'],
            'pagination'     => $result,
            'categories'     => $categories,
            'activeCategory' => $category,
            'settings'       => $settings,
            'pageTitle'      => $pageTitle,
            'metaDescription'=> $metaDescription,
        ]);
    }

    public function testimonials(array $params = []): void
    {
        $testModel = new Testimonial();
        $page      = max(1, $this->getInt('page', 1));
        $result    = $testModel->getActive($page, 12);
        $settings  = (new Setting())->getAllFlat();

        $pageTitle = 'Customer Reviews & Testimonials | OM Trip and Travels Indore';
        $metaDescription = 'Read genuine reviews from travelers who rented Urbania, Tempo Travellers, and luxury cars from OM Trip and Travels for pilgrimages and family vacations.';

        $this->view('pages/testimonials', [
            'testimonials'   => $result['data'],
            'pagination'     => $result,
            'settings'       => $settings,
            'pageTitle'      => $pageTitle,
            'metaDescription'=> $metaDescription,
        ]);
    }

    public function faq(array $params = []): void
    {
        $faqModel = new Faq();
        $groupedFaqs = $faqModel->getGrouped();
        $allFaqs     = $faqModel->getActive();
        $settings    = (new Setting())->getAllFlat();

        $pageTitle = 'Frequently Asked Questions (FAQ) | OM Trip and Travels Indore';
        $metaDescription = 'Find answers to common questions about hiring Urbania, Tempo Traveller, taxi rates, booking process, driver allowances, and cancellation policies in Indore.';

        // JSON-LD: FAQPage
        $faqEntities = [];
        foreach ($allFaqs as $faq) {
            $faqEntities[] = [
                '@type' => 'Question',
                'name'  => $faq['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => strip_tags($faq['answer']),
                ]
            ];
        }
        $schemaMarkup = json_encode([
            '@context'   => 'https://schema.org',
            '@type'      => 'FAQPage',
            'mainEntity' => $faqEntities,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $this->view('pages/faq', compact('groupedFaqs', 'settings', 'pageTitle', 'metaDescription', 'schemaMarkup'));
    }

    public function booking(array $params = []): void
    {
        $vehicles = (new Vehicle())->getActiveVehicles();
        $settings = (new Setting())->getAllFlat();
        $selectedVehicleId = $this->getInt('vehicle_id', 0);
        $selectedPackageId = $this->getInt('package_id', 0);

        $pageTitle = 'Booking Request | Hire Urbania & Tempo Traveller in Indore';
        $metaDescription = 'Submit a quick booking request for Urbania, Tempo Traveller, Innova Crysta, or Fortuner in Indore. No online payment required — our team will call with instant quotes.';

        $this->view('pages/booking', compact(
            'vehicles', 'settings', 'selectedVehicleId', 'selectedPackageId',
            'pageTitle', 'metaDescription'
        ));
    }

    public function airportTransfers(array $params = []): void
    {
        $vehicles = (new Vehicle())->getActiveVehicles();
        $settings = (new Setting())->getAllFlat();

        $pageTitle = 'Indore Airport Taxi & Transfers | Devi Ahilyabai Holkar Airport Cab';
        $metaDescription = 'Book reliable 24/7 airport taxi in Indore for Devi Ahilyabai Holkar Airport (IDR). AC sedans, Innova, Urbania, and Tempo Travellers for flights, arrivals & departures.';

        $this->view('pages/airport-transfers', compact('vehicles', 'settings', 'pageTitle', 'metaDescription'));
    }

    public function privacy(array $params = []): void
    {
        $settings  = (new Setting())->getAllFlat();
        $pageTitle = 'Privacy Policy | OM Trip and Travels';
        $metaDescription = 'Privacy Policy of OM Trip and Travels. Read how we protect your personal information and enquiry data.';

        $this->view('pages/privacy', compact('settings', 'pageTitle', 'metaDescription'));
    }

    public function terms(array $params = []): void
    {
        $settings  = (new Setting())->getAllFlat();
        $pageTitle = 'Terms and Conditions | OM Trip and Travels';
        $metaDescription = 'Terms and conditions for vehicle rentals, driver duties, toll/parking policies, and booking terms with OM Trip and Travels, Indore.';

        $this->view('pages/terms', compact('settings', 'pageTitle', 'metaDescription'));
    }
}

