<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Database;

$db = Database::getInstance()->getPdo();

$updates = [
    'force-urbania'   => ['img' => '/assets/images/vehicles/urbania.svg', 'badge' => 'Premium', 'ideal' => 'Family Trips, Corporate Tours, Weddings, Pilgrimage'],
    'tempo-traveller' => ['img' => '/assets/images/vehicles/tempo-traveller.svg', 'badge' => 'Group Favourite', 'ideal' => 'Group Tours, School/College Trips, Religious Yatra'],
    'innova-crysta'   => ['img' => '/assets/images/vehicles/innova-crysta.svg', 'badge' => 'Family Choice', 'ideal' => 'Family Travel, Outstation Trips, Business Travel'],
    'toyota-fortuner' => ['img' => '/assets/images/vehicles/fortuner.svg', 'badge' => 'Luxury', 'ideal' => 'VIP Travel, Weddings, Corporate & Long Tours'],
];

foreach ($updates as $slug => $data) {
    $stmt = $db->prepare("UPDATE vehicles SET primary_image = ?, ideal_for = ? WHERE slug = ?");
    $stmt->execute([$data['img'], $data['ideal'], $slug]);
}

echo "✅ Vehicle images and ideal_for updated successfully!\n";

