<?php
declare(strict_types=1);

/**
 * Database Initializer & Demo Seeder
 * Sets up SQLite (for instant local preview) or MySQL (for production Hostinger).
 */

define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/app/Core/Database.php';
require_once ROOT_PATH . '/app/Helpers/functions.php';

// Load .env
if (is_file(ROOT_PATH . '/.env')) {
    $lines = file(ROOT_PATH . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) continue;
        [$k, $v] = explode('=', $line, 2);
        $_ENV[trim($k)] = trim($v, " \t\n\r\0\x0B\"'");
        putenv(trim($k) . "=" . trim($v, " \t\n\r\0\x0B\"'"));
    }
}

$sqliteFile = ROOT_PATH . '/storage/database.sqlite';
$storageDir = ROOT_PATH . '/storage';
if (!is_dir($storageDir)) {
    @mkdir($storageDir, 0755, true);
}

// Create or connect to SQLite PDO
$pdo = new PDO("sqlite:" . $sqliteFile);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

echo "Creating tables in {$sqliteFile}...\n";

// Execute SQLite Schema
$pdo->exec("
CREATE TABLE IF NOT EXISTS `admins` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `name` TEXT NOT NULL,
  `email` TEXT UNIQUE NOT NULL,
  `password` TEXT NOT NULL,
  `role` TEXT NOT NULL DEFAULT 'staff',
  `is_active` INTEGER NOT NULL DEFAULT 1,
  `last_login` DATETIME NULL,
  `remember_token` TEXT NULL,
  `password_reset_token` TEXT NULL,
  `password_reset_expires` DATETIME NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `settings` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `key` TEXT UNIQUE NOT NULL,
  `value` TEXT NULL,
  `type` TEXT NOT NULL DEFAULT 'text',
  `group` TEXT NOT NULL DEFAULT 'general',
  `label` TEXT NOT NULL,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `vehicle_categories` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `name` TEXT NOT NULL,
  `slug` TEXT UNIQUE NOT NULL,
  `description` TEXT NULL,
  `icon` TEXT NULL,
  `image` TEXT NULL,
  `sort_order` INTEGER NOT NULL DEFAULT 0,
  `is_active` INTEGER NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `vehicles` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `category_id` INTEGER NOT NULL,
  `name` TEXT NOT NULL,
  `slug` TEXT UNIQUE NOT NULL,
  `short_description` TEXT NULL,
  `description` TEXT NULL,
  `seats` INTEGER NOT NULL DEFAULT 4,
  `luggage_capacity` TEXT NULL,
  `has_ac` INTEGER NOT NULL DEFAULT 1,
  `features` TEXT NULL,
  `ideal_for` TEXT NULL,
  `starting_price` REAL NULL,
  `price_note` TEXT NULL,
  `primary_image` TEXT NULL,
  `is_featured` INTEGER NOT NULL DEFAULT 0,
  `sort_order` INTEGER NOT NULL DEFAULT 0,
  `is_active` INTEGER NOT NULL DEFAULT 1,
  `meta_title` TEXT NULL,
  `meta_description` TEXT NULL,
  `og_image` TEXT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `vehicle_images` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `vehicle_id` INTEGER NOT NULL,
  `image_path` TEXT NOT NULL,
  `alt_text` TEXT NULL,
  `sort_order` INTEGER NOT NULL DEFAULT 0,
  `is_primary` INTEGER NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `tour_packages` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `name` TEXT NOT NULL,
  `slug` TEXT UNIQUE NOT NULL,
  `short_description` TEXT NULL,
  `description` TEXT NULL,
  `itinerary` TEXT NULL,
  `inclusions` TEXT NULL,
  `exclusions` TEXT NULL,
  `duration_days` INTEGER NULL,
  `duration_nights` INTEGER NULL,
  `route` TEXT NULL,
  `origin` TEXT NULL DEFAULT 'Indore',
  `destination` TEXT NULL,
  `starting_price` REAL NULL,
  `price_note` TEXT NULL,
  `primary_image` TEXT NULL,
  `is_featured` INTEGER NOT NULL DEFAULT 0,
  `sort_order` INTEGER NOT NULL DEFAULT 0,
  `is_active` INTEGER NOT NULL DEFAULT 1,
  `meta_title` TEXT NULL,
  `meta_description` TEXT NULL,
  `og_image` TEXT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `package_vehicles` (
  `package_id` INTEGER NOT NULL,
  `vehicle_id` INTEGER NOT NULL,
  PRIMARY KEY (`package_id`, `vehicle_id`)
);

CREATE TABLE IF NOT EXISTS `blog_categories` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `name` TEXT NOT NULL,
  `slug` TEXT UNIQUE NOT NULL,
  `description` TEXT NULL,
  `sort_order` INTEGER NOT NULL DEFAULT 0,
  `is_active` INTEGER NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `blog_posts` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `category_id` INTEGER NULL,
  `author_id` INTEGER NULL,
  `title` TEXT NOT NULL,
  `slug` TEXT UNIQUE NOT NULL,
  `excerpt` TEXT NULL,
  `content` TEXT NOT NULL,
  `featured_image` TEXT NULL,
  `og_image` TEXT NULL,
  `status` TEXT NOT NULL DEFAULT 'draft',
  `published_at` DATETIME NULL,
  `meta_title` TEXT NULL,
  `meta_description` TEXT NULL,
  `views` INTEGER NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `blog_tags` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `name` TEXT NOT NULL,
  `slug` TEXT UNIQUE NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `blog_post_tags` (
  `post_id` INTEGER NOT NULL,
  `tag_id` INTEGER NOT NULL,
  PRIMARY KEY (`post_id`, `tag_id`)
);

CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `name` TEXT NOT NULL,
  `designation` TEXT NULL,
  `location` TEXT NULL,
  `photo` TEXT NULL,
  `rating` INTEGER NOT NULL DEFAULT 5,
  `review` TEXT NOT NULL,
  `vehicle_booked` TEXT NULL,
  `is_featured` INTEGER NOT NULL DEFAULT 0,
  `sort_order` INTEGER NOT NULL DEFAULT 0,
  `is_active` INTEGER NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `gallery_items` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `title` TEXT NULL,
  `image_path` TEXT NOT NULL,
  `alt_text` TEXT NULL,
  `category` TEXT NULL,
  `sort_order` INTEGER NOT NULL DEFAULT 0,
  `is_active` INTEGER NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `faqs` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `question` TEXT NOT NULL,
  `answer` TEXT NOT NULL,
  `category` TEXT NULL DEFAULT 'General',
  `sort_order` INTEGER NOT NULL DEFAULT 0,
  `is_active` INTEGER NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `banners` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `title` TEXT NULL,
  `subtitle` TEXT NULL,
  `image_path` TEXT NOT NULL,
  `mobile_image_path` TEXT NULL,
  `cta_text` TEXT NULL,
  `cta_url` TEXT NULL,
  `cta_type` TEXT NOT NULL DEFAULT 'link',
  `sort_order` INTEGER NOT NULL DEFAULT 0,
  `is_active` INTEGER NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `enquiries` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `type` TEXT NOT NULL DEFAULT 'contact',
  `name` TEXT NOT NULL,
  `phone` TEXT NOT NULL,
  `email` TEXT NULL,
  `pickup_location` TEXT NULL,
  `drop_location` TEXT NULL,
  `travel_date` DATE NULL,
  `return_date` DATE NULL,
  `passengers` INTEGER NULL,
  `vehicle_id` INTEGER NULL,
  `package_id` INTEGER NULL,
  `message` TEXT NULL,
  `status` TEXT NOT NULL DEFAULT 'new',
  `source_page` TEXT NULL,
  `ip` TEXT NULL,
  `user_agent` TEXT NULL,
  `is_read` INTEGER NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `enquiry_notes` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `enquiry_id` INTEGER NOT NULL,
  `admin_id` INTEGER NOT NULL,
  `note` TEXT NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `click_logs` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `type` TEXT NOT NULL,
  `vehicle_id` INTEGER NULL,
  `package_id` INTEGER NULL,
  `page` TEXT NULL,
  `ip` TEXT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `admin_id` INTEGER NULL,
  `action` TEXT NOT NULL,
  `entity_type` TEXT NULL,
  `entity_id` INTEGER NULL,
  `description` TEXT NULL,
  `ip` TEXT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `ip` TEXT NOT NULL,
  `email` TEXT NULL,
  `attempts` INTEGER NOT NULL DEFAULT 1,
  `locked_until` DATETIME NULL,
  `last_attempt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `pages_seo` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `page_key` TEXT UNIQUE NOT NULL,
  `title` TEXT NULL,
  `meta_description` TEXT NULL,
  `canonical` TEXT NULL,
  `og_title` TEXT NULL,
  `og_description` TEXT NULL,
  `og_image` TEXT NULL,
  `schema_json` TEXT NULL,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);
");

// Check if admin exists; if not, create demo admin
$hasAdmin = $pdo->query("SELECT COUNT(*) FROM admins")->fetchColumn();
if (!$hasAdmin) {
    $demoPass = 'Admin@123456';
    $demoHash = password_hash($demoPass, PASSWORD_BCRYPT, ['cost' => 12]);
    $stmt = $pdo->prepare("INSERT INTO admins (name, email, password, role, is_active) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute(['Super Admin', 'admin@omtripandtravels.com', $demoHash, 'super_admin', 1]);
    echo "✅ Demo Admin created:\n   Email: admin@omtripandtravels.com\n   Password: {$demoPass}\n";
}

// Seed default settings
$hasSettings = $pdo->query("SELECT COUNT(*) FROM settings")->fetchColumn();
if (!$hasSettings) {
    $settings = [
        ['business_name', 'OM Trip and Travels', 'text', 'general', 'Business Name'],
        ['tagline', 'Tempo Traveller • Urbania • SUV • Car Rental for Group Travel', 'text', 'general', 'Tagline'],
        ['phone', '+91 98260 12345', 'text', 'general', 'Phone Number'],
        ['whatsapp_number', '919826012345', 'text', 'general', 'WhatsApp Number'],
        ['email', 'info@omtripandtravels.com', 'text', 'general', 'Email Address'],
        ['address', '123, Scheme 54, Vijay Nagar, Indore, Madhya Pradesh - 452010', 'textarea', 'general', 'Full Address'],
        ['city', 'Indore', 'text', 'general', 'City'],
        ['state', 'Madhya Pradesh', 'text', 'general', 'State'],
        ['pincode', '452010', 'text', 'general', 'Pincode'],
        ['working_hours', 'Mon – Sun, 6:00 AM – 10:00 PM', 'text', 'general', 'Working Hours'],
        ['logo', '/assets/images/logo.png', 'image', 'general', 'Logo'],
        ['favicon', '/assets/images/favicon.png', 'image', 'general', 'Favicon'],
        ['footer_about', 'OM Trip and Travels is Indore\'s leading group travel specialist. Providing Force Urbania, Luxury Tempo Travellers, Innova Crysta & Fortuner for pilgrimages, weddings, and corporate tours.', 'textarea', 'general', 'Footer About Text'],
        ['wa_message_vehicle', 'Hello OM Trip and Travels, I am interested in booking {vehicle_name}. Page: {page_url}', 'textarea', 'whatsapp', 'WhatsApp Message - Vehicle'],
        ['wa_message_package', 'Hello OM Trip and Travels, I want to enquire about the {package_name} package. Page: {page_url}', 'textarea', 'whatsapp', 'WhatsApp Message - Package'],
        ['wa_message_general', 'Hello OM Trip and Travels, I would like to enquire about your vehicles in Indore. Page: {page_url}', 'textarea', 'whatsapp', 'WhatsApp Message - General'],
    ];
    $stmt = $pdo->prepare("INSERT INTO settings (`key`, `value`, `type`, `group`, `label`) VALUES (?, ?, ?, ?, ?)");
    foreach ($settings as $s) {
        $stmt->execute($s);
    }
}

// Seed categories
$hasCats = $pdo->query("SELECT COUNT(*) FROM vehicle_categories")->fetchColumn();
if (!$hasCats) {
    $cats = [
        ['Urbania', 'urbania', 'Force Urbania luxury vans with executive captain seats.', 'bi-minecart-loaded', 1],
        ['Tempo Traveller', 'tempo-traveller', '12, 14, and 17 seater luxury tempo travellers.', 'bi-truck-front', 2],
        ['SUV', 'suv', 'Toyota Fortuner, Innova Crysta, and Innova Hycross.', 'bi-car-front-fill', 3],
        ['Sedan', 'sedan', 'Comfortable AC sedans for airport & city transfers.', 'bi-car-front', 4],
        ['Others', 'others', 'Buses and coaches for large wedding delegations.', 'bi-bus-front', 5],
    ];
    $stmt = $pdo->prepare("INSERT INTO vehicle_categories (name, slug, description, icon, sort_order) VALUES (?, ?, ?, ?, ?)");
    foreach ($cats as $c) $stmt->execute($c);
}

// Seed Vehicles
$hasVehicles = $pdo->query("SELECT COUNT(*) FROM vehicles")->fetchColumn();
if (!$hasVehicles) {
    $vehicles = [
        [
            1, 'Force Urbania', 'force-urbania',
            'Comfortable and modern Force Urbania with 17 reclining captain seats, ambient lighting, and panoramic windows.',
            '<p>The Force Urbania is Indore\'s most demanded luxury group transport van. Designed with world-class safety, individual AC vents, USB charging sockets at each seat, and silent cabin acoustics.</p>',
            17, '15-18 Suitcases + Boot Space', 1,
            json_encode(['17 Reclining Seats', 'AC Vents', 'Spacious Legroom', 'USB Charging', 'Music System', 'LED Lighting']),
            json_encode(['Family Trips', 'Corporate Tours', 'Weddings', 'Pilgrimage']),
            null, 'On Request — Best Price Guaranteed', '/assets/images/vehicles/urbania.png', 1, 1
        ],
        [
            2, 'Tempo Traveller', 'tempo-traveller',
            'Comfortable 12 to 17 seater AC Tempo Traveller with pushback seating and high-roof cabin.',
            '<p>Our Force Tempo Travellers are meticulously maintained and sanitized. Featuring pushback executive seats, separate luggage compartment, and entertainment screen.</p>',
            17, '12-16 Bags + Carrier', 1,
            json_encode(['12 / 17 Seats', 'Dual AC', 'Pushback Seats', 'Luggage Space', 'Music System']),
            json_encode(['Group Tours', 'School/College Trips', 'Religious Yatra', 'Family Outstation']),
            null, 'On Request — Best Price Guaranteed', '/assets/images/vehicles/tempo-traveller.png', 1, 2
        ],
        [
            3, 'Innova Crysta', 'innova-crysta',
            'Toyota Innova Crysta 7/8 seater — the gold standard for executive and family road trips from Indore.',
            '<p>Experience effortless long journeys to Ujjain, Omkareshwar, or Pachmarhi in the spacious Toyota Innova Crysta with premium captain seats.</p>',
            7, '5-6 Large Bags', 1,
            json_encode(['7 / 8 Seats', 'Rear AC Vents', 'Captain Seats', 'Airbags', 'GPS Tracked']),
            json_encode(['Family Travel', 'Outstation Trips', 'Business Travel', 'Airport Taxi']),
            null, 'On Request — Best Price Guaranteed', '/assets/images/vehicles/innova-crysta.png', 1, 3
        ],
        [
            3, 'Toyota Fortuner', 'toyota-fortuner',
            'Dominant luxury SUV Toyota Fortuner 4x4 for VIP arrivals, executive travel, and wedding processions.',
            '<p>Premium Toyota Fortuner in pristine white, offering plush leather seats, powerful climate control, and unmatched road presence.</p>',
            7, '5 Suitcases', 1,
            json_encode(['7 Seats', 'Climate Control', 'Luxury Interior', '4WD', 'Sunroof']),
            json_encode(['VIP Travel', 'Weddings', 'Corporate & Long Tours', 'Airport VIP']),
            null, 'On Request — Best Price Guaranteed', '/assets/images/vehicles/fortuner.png', 1, 4
        ],
    ];

    $stmt = $pdo->prepare("INSERT INTO vehicles (category_id, name, slug, short_description, description, seats, luggage_capacity, has_ac, features, ideal_for, starting_price, price_note, primary_image, is_featured, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($vehicles as $v) $stmt->execute($v);
}

// Seed Packages
$hasPackages = $pdo->query("SELECT COUNT(*) FROM tour_packages")->fetchColumn();
if (!$hasPackages) {
    $packages = [
        ['Indore to Ujjain Darshan', 'indore-to-ujjain', 'One-day Mahakaleshwar Jyotirlinga darshan with Harsiddhi & Kal Bhairav temples.', '<p>Experience the divine Mahakaleshwar Jyotirlinga in Ujjain, just 55 km from Indore.</p>', "AC Vehicle with Driver\nFuel Charges\nTolls and Parking", "Temple VIP Entry\nMeals", 1, 0, 'Indore → Ujjain → Indore', 'Indore', 'Ujjain', null, 'On Request', '/assets/images/packages/ujjain.jpg', 1, 1],
        ['Indore to Omkareshwar Darshan', 'indore-to-omkareshwar', 'Sacred Jyotirlinga on the island of Mandhata on Narmada river.', '<p>Peaceful spiritual pilgrimage to Omkareshwar and Mamleshwar Jyotirlinga temples.</p>', "AC Vehicle with Driver\nFuel Charges\nTolls and Parking", "Boat Rides\nFood & Puja", 1, 0, 'Indore → Omkareshwar → Indore', 'Indore', 'Omkareshwar', null, 'On Request', '/assets/images/packages/omkareshwar.jpg', 1, 2],
        ['Maheshwar & Mandu Heritage Tour', 'maheshwar-mandu-tour', 'Ahilya Bai Holkar Fort, Narmada Ghats, Jahaz Mahal and Rani Roopmati Pavilion.', '<p>The ultimate heritage circuit of Central India.</p>', "AC Vehicle with Driver\nFuel Charges\nTolls and Parking", "Monument Entry\nMeals", 1, 0, 'Indore → Maheshwar → Mandu → Indore', 'Indore', 'Maheshwar & Mandu', null, 'On Request', '/assets/images/packages/mandu.jpg', 1, 3],
        ['Indore to Pachmarhi Hill Tour', 'indore-to-pachmarhi', 'Queen of Satpura — waterfalls, viewpoints, and pine forests in MP.', '<p>Scenic 3-day getaway to the only hill station in Madhya Pradesh.</p>', "AC Vehicle with Driver\nFuel Charges\nDriver Allowance", "Hotel Booking\nForest Permits", 3, 2, 'Indore → Pachmarhi → Indore', 'Indore', 'Pachmarhi', null, 'On Request', '/assets/images/packages/pachmarhi.jpg', 1, 4],
    ];
    $stmt = $pdo->prepare("INSERT INTO tour_packages (name, slug, short_description, description, inclusions, exclusions, duration_days, duration_nights, route, origin, destination, starting_price, price_note, primary_image, is_featured, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($packages as $pkg) $stmt->execute($pkg);
}

// Seed Testimonials
$hasTests = $pdo->query("SELECT COUNT(*) FROM testimonials")->fetchColumn();
if (!$hasTests) {
    $tests = [
        ['Rajesh Sharma', 'Business Owner', 'Indore', 5, 'We booked Force Urbania 17 seater for our family Mahakaleshwar and Omkareshwar darshan. The van was spotlessly clean, very comfortable, and driver was polite and punctual. Highly recommended in Indore!', 'Force Urbania 17 Seater', 1, 1],
        ['Priya Patel', 'Software Engineer', 'Vijay Nagar, Indore', 5, 'Hired Innova Crysta for an airport drop and outstation trip. Driver reached 15 minutes before time. Completely stress-free travel.', 'Toyota Innova Crysta', 1, 2],
        ['Amitabh Verma', 'Tour Coordinator', 'Bhopal', 5, 'Arranged two Tempo Travellers for a 3-day corporate trip to Pachmarhi. Both vehicles performed smoothly on ghat roads. Great service from OM Trip and Travels!', 'Tempo Traveller 17 Seater', 1, 3],
    ];
    $stmt = $pdo->prepare("INSERT INTO testimonials (name, designation, location, rating, review, vehicle_booked, is_featured, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($tests as $t) $stmt->execute($t);
}

// Seed FAQs
$hasFaqs = $pdo->query("SELECT COUNT(*) FROM faqs")->fetchColumn();
if (!$hasFaqs) {
    $faqs = [
        ['How do I confirm my vehicle booking with OM Trip and Travels?', 'Simply call us at +91 98260 12345 or send a WhatsApp message with your travel dates and passenger count. No online advance payment or card details are required.', 'Booking', 1],
        ['Are all vehicles air-conditioned?', 'Yes, 100% of our fleet including Force Urbania, Tempo Travellers, and SUVs have powerful front and rear air-conditioning systems.', 'Fleet', 2],
        ['Do you provide pickup from Indore Airport or Railway Station?', 'Yes, we provide 24/7 doorstep pickup anywhere in Indore, including Devi Ahilyabai Holkar Airport, Indore Junction, and all major residential areas.', 'Services', 3],
        ['Can we rent Urbania or Tempo Traveller for outstation trips outside MP?', 'Yes, all our commercial vehicles have All-India tourist permits. We frequently operate trips to Shirdi, Dwarka, Somnath, Rajasthan, and across India.', 'Booking', 4],
    ];
    $stmt = $pdo->prepare("INSERT INTO faqs (question, answer, category, sort_order) VALUES (?, ?, ?, ?)");
    foreach ($faqs as $f) $stmt->execute($f);
}

echo "✅ Database initialized and seeded successfully!\n";

