<?php
declare(strict_types=1);

/**
 * Generates branded SVG / PNG / JPG placeholder images for OM Trip and Travels.
 */

$root = dirname(__DIR__);

// Helper to create an image with GD
function createPlaceholderImage(string $filePath, int $width, int $height, string $text, array $bgRgb, array $textRgb = [255, 255, 255]) {
    $img = imagecreatetruecolor($width, $height);
    $bgColor = imagecolorallocate($img, $bgRgb[0], $bgRgb[1], $bgRgb[2]);
    $textColor = imagecolorallocate($img, $textRgb[0], $textRgb[1], $textRgb[2]);
    $subColor = imagecolorallocate($img, 240, 240, 240);

    imagefill($img, 0, 0, $bgColor);

    // Draw decorative border / accents
    $accentColor = imagecolorallocate($img, 230, 92, 0); // #e65c00
    imagesetthickness($img, 4);
    imagerectangle($img, 10, 10, $width - 10, $height - 10, $accentColor);

    // Text rendering
    $font = 5; // Built-in GD font
    $textWidth = imagefontwidth($font) * strlen($text);
    $textHeight = imagefontheight($font);
    $x = (int) max(20, ($width - $textWidth) / 2);
    $y = (int) max(20, ($height - $textHeight) / 2);

    imagestring($img, $font, $x, $y, $text, $textColor);

    $sub = "OM Trip & Travels - Indore";
    $subX = (int) max(20, ($width - (imagefontwidth(3) * strlen($sub))) / 2);
    imagestring($img, 3, $subX, $y + 30, $sub, $subColor);

    $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    if ($ext === 'png') {
        imagepng($img, $filePath);
    } elseif ($ext === 'webp') {
        imagewebp($img, $filePath, 90);
    } else {
        imagejpeg($img, $filePath, 90);
    }
    imagedestroy($img);
}

// 1. Logo
$img = imagecreatetruecolor(300, 80);
imagealphablending($img, false);
imagesavealpha($img, true);
$transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);
imagefill($img, 0, 0, $transparent);
$colorOrange = imagecolorallocate($img, 230, 92, 0);
$colorWhite  = imagecolorallocate($img, 26, 42, 74);
imagestring($img, 5, 20, 25, "OM TRIP & TRAVELS", $colorOrange);
imagestring($img, 3, 20, 50, "Urbania & Tempo Specialists", $colorWhite);
imagepng($img, $root . '/public/assets/images/logo.png');
imagedestroy($img);

// 2. Favicon
$img = imagecreatetruecolor(64, 64);
$orange = imagecolorallocate($img, 230, 92, 0);
$white = imagecolorallocate($img, 255, 255, 255);
imagefill($img, 0, 0, $orange);
imagestring($img, 5, 22, 24, "OM", $white);
imagepng($img, $root . '/public/assets/images/favicon.png');
imagedestroy($img);

// 3. OG Default
createPlaceholderImage($root . '/public/assets/images/og-default.jpg', 1200, 630, "OM Trip & Travels - Indore Fleet", [26, 42, 74]);

// 4. Banners
createPlaceholderImage($root . '/public/assets/images/banners/banner-1.jpg', 1600, 700, "Force Urbania & Tempo Traveller in Indore", [26, 42, 74]);
createPlaceholderImage($root . '/public/assets/images/banners/banner-2.jpg', 1600, 700, "24/7 Airport Taxi & Cab Transfers", [30, 50, 90]);
createPlaceholderImage($root . '/public/assets/images/banners/banner-3.jpg', 1600, 700, "Indore Pilgrimage & Group Tour Packages", [40, 30, 70]);

echo "✅ All placeholder assets generated successfully.\n";

