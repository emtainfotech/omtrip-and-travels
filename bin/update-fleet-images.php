<?php
declare(strict_types=1);

$db = new PDO('sqlite:' . __DIR__ . '/../storage/database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$vehicles = $db->query("SELECT id, name, slug FROM vehicles")->fetchAll(PDO::FETCH_ASSOC);

foreach ($vehicles as $v) {
    $slug = $v['slug'];
    $img = '/assets/images/vehicles/urbania.jpg';
    if (str_contains($slug, 'urbania')) {
        $img = '/assets/images/vehicles/urbania.jpg';
    } elseif (str_contains($slug, 'tempo') || str_contains($slug, 'traveller')) {
        $img = '/assets/images/vehicles/tempo-traveller.jpg';
    } elseif (str_contains($slug, 'innova') || str_contains($slug, 'crysta') || str_contains($slug, 'hycross')) {
        $img = '/assets/images/vehicles/innova-crysta.jpg';
    } elseif (str_contains($slug, 'fortuner')) {
        $img = '/assets/images/vehicles/fortuner.jpg';
    }
    
    $stmt = $db->prepare("UPDATE vehicles SET primary_image = :img WHERE id = :id");
    $stmt->execute([':img' => $img, ':id' => $v['id']]);
    echo "ID {$v['id']}: {$v['name']} ({$v['slug']}) -> $img\n";
}
