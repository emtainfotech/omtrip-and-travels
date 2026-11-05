<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Database;

$db = Database::getInstance()->getPdo();

$packages = [
    [
        'name' => 'Indore to Shirdi Tour',
        'slug' => 'indore-to-shirdi',
        'short_description' => 'Sai Baba darshan at Shirdi — popular overnight pilgrimage tour from Indore.',
        'description' => '<p>Shirdi, the sacred abode of Sai Baba, is a revered pilgrimage destination approximately 500 km from Indore. Our comfortable Urbania and Tempo Travellers make overnight group travel easy, restful and safe.</p>',
        'inclusions' => "AC Vehicle with driver\nFuel charges\nDriver allowance\nAll toll and parking",
        'exclusions' => "Hotel accommodation\nTemple VIP Darshan Pass\nFood & Meals",
        'duration_days' => 2,
        'duration_nights' => 1,
        'route' => 'Indore → Shirdi → Indore',
        'origin' => 'Indore',
        'destination' => 'Shirdi',
        'starting_price' => null,
        'price_note' => 'On Request',
        'primary_image' => '/assets/images/packages/shirdi.jpg',
        'is_featured' => 1,
        'sort_order' => 5,
    ],
    [
        'name' => 'Indore to Khajuraho Tour',
        'slug' => 'indore-to-khajuraho',
        'short_description' => 'Explore the UNESCO World Heritage Khajuraho temples on a 2-day tour from Indore.',
        'description' => '<p>Khajuraho Group of Monuments is a UNESCO World Heritage Site famous for its stunning Nagara-style temples adorned with intricate carvings. Located 370 km from Indore.</p>',
        'inclusions' => "AC Vehicle with driver\nFuel charges\nDriver allowance\nAll toll and parking",
        'exclusions' => "Monument entry tickets\nGuide fees\nMeals & Hotel",
        'duration_days' => 2,
        'duration_nights' => 1,
        'route' => 'Indore → Khajuraho → Indore',
        'origin' => 'Indore',
        'destination' => 'Khajuraho',
        'starting_price' => null,
        'price_note' => 'On Request',
        'primary_image' => '/assets/images/packages/khajuraho.jpg',
        'is_featured' => 1,
        'sort_order' => 6,
    ],
    [
        'name' => 'Dwarka & Somnath Yatra',
        'slug' => 'dwarka-somnath-yatra',
        'short_description' => 'Sacred Dwarka and Somnath pilgrimage from Indore — visit two of India\'s most revered temples.',
        'description' => '<p>Embark on a divine journey to Dwarka (Lord Krishna\'s kingdom) and Somnath (the first Jyotirlinga). Both temples are located in Gujarat, approximately 700-750 km from Indore, making this a 3-4 night pilgrimage tour.</p>',
        'inclusions' => "AC Vehicle with driver\nFuel charges\nDriver allowance\nAll toll and parking",
        'exclusions' => "Hotel stays\nFood & Meals\nPersonal expenses",
        'duration_days' => 4,
        'duration_nights' => 3,
        'route' => 'Indore → Dwarka → Somnath → Indore',
        'origin' => 'Indore',
        'destination' => 'Dwarka & Somnath',
        'starting_price' => null,
        'price_note' => 'On Request',
        'primary_image' => '/assets/images/packages/dwarka.jpg',
        'is_featured' => 1,
        'sort_order' => 7,
    ],
];

$stmt = $db->prepare("INSERT OR IGNORE INTO tour_packages (name, slug, short_description, description, inclusions, exclusions, duration_days, duration_nights, route, origin, destination, starting_price, price_note, primary_image, is_featured, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

foreach ($packages as $p) {
    $stmt->execute([
        $p['name'], $p['slug'], $p['short_description'], $p['description'],
        $p['inclusions'], $p['exclusions'], $p['duration_days'], $p['duration_nights'],
        $p['route'], $p['origin'], $p['destination'], $p['starting_price'],
        $p['price_note'], $p['primary_image'], $p['is_featured'], $p['sort_order'],
    ]);
}

echo "✅ All 7 tour packages seeded successfully!\n";

