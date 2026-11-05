<?php
declare(strict_types=1);

namespace App\Helpers;

/**
 * Image upload and WebP processing helper.
 */
class ImageUploader
{
    /**
     * Upload and optimize an image file.
     *
     * @param array $file $_FILES['input_name']
     * @param string $dir Subdirectory in public/uploads (e.g. 'vehicles', 'banners', 'blog')
     * @param int $maxSize Max size in bytes (default 5MB)
     * @param array $allowedMimes Allowed MIME types
     * @return array{success: bool, path: string, error: string}
     */
    public static function upload(
        array $file,
        string $dir = 'general',
        int $maxSize = 5242880,
        array $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif']
    ): array {
        if (!isset($file['error']) || is_array($file['error'])) {
            return ['success' => false, 'path' => '', 'error' => 'Invalid file parameter.'];
        }

        switch ($file['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                return ['success' => false, 'path' => '', 'error' => 'No file uploaded.'];
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return ['success' => false, 'path' => '', 'error' => 'File exceeds maximum upload limit.'];
            default:
                return ['success' => false, 'path' => '', 'error' => 'Unknown upload error.'];
        }

        if ($file['size'] > $maxSize) {
            $mb = round($maxSize / (1024 * 1024), 1);
            return ['success' => false, 'path' => '', 'error' => "File size cannot exceed {$mb}MB."];
        }

        // Verify MIME type using finfo
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        if (!in_array($mime, $allowedMimes, true)) {
            return ['success' => false, 'path' => '', 'error' => 'Invalid image format. Allowed: JPG, PNG, WEBP, GIF.'];
        }

        // Validate extension
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $validExts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        if (!in_array($ext, $validExts, true)) {
            return ['success' => false, 'path' => '', 'error' => 'Invalid file extension.'];
        }

        $baseUploadDir = dirname(__DIR__, 2) . '/public/uploads/' . trim($dir, '/');
        if (!is_dir($baseUploadDir)) {
            @mkdir($baseUploadDir, 0755, true);
        }

        $randomName = uniqid('', true) . '_' . bin2hex(random_bytes(4));
        $targetWebpPath = $baseUploadDir . '/' . $randomName . '.webp';
        $relativeWebPath = '/uploads/' . trim($dir, '/') . '/' . $randomName . '.webp';

        // Convert to WebP and resize if GD is loaded
        if (extension_loaded('gd')) {
            $converted = self::convertToWebP($file['tmp_name'], $targetWebpPath, $mime);
            if ($converted) {
                return ['success' => true, 'path' => $relativeWebPath, 'error' => ''];
            }
        }

        // Fallback: copy original file if GD failed or not loaded
        $fallbackTarget = $baseUploadDir . '/' . $randomName . '.' . $ext;
        if (move_uploaded_file($file['tmp_name'], $fallbackTarget)) {
            return [
                'success' => true,
                'path' => '/uploads/' . trim($dir, '/') . '/' . $randomName . '.' . $ext,
                'error' => ''
            ];
        }

        return ['success' => false, 'path' => '', 'error' => 'Failed to save uploaded file.'];
    }

    /**
     * Convert and resize image to WebP with max 1920x1080 dimensions.
     */
    private static function convertToWebP(string $sourcePath, string $destinationPath, string $mime): bool
    {
        $image = match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($sourcePath),
            'image/png'  => @imagecreatefrompng($sourcePath),
            'image/webp' => @imagecreatefromwebp($sourcePath),
            'image/gif'  => @imagecreatefromgif($sourcePath),
            default      => false,
        };

        if (!$image) {
            return false;
        }

        // Preserve transparency for PNG/GIF/WEBP
        imagepalettetotruecolor($image);
        imagealphablending($image, true);
        imagesavealpha($image, true);

        $width  = imagesx($image);
        $height = imagesy($image);

        $maxWidth  = 1920;
        $maxHeight = 1080;

        if ($width > $maxWidth || $height > $maxHeight) {
            $ratio = min($maxWidth / $width, $maxHeight / $height);
            $newWidth  = (int) round($width * $ratio);
            $newHeight = (int) round($height * $ratio);

            $resized = imagecreatetruecolor($newWidth, $newHeight);
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resized;
        }

        $result = imagewebp($image, $destinationPath, 85);
        imagedestroy($image);

        return $result;
    }
}

/**
 * Functional wrapper for ImageUploader::upload
 */
function handleImageUpload(array $file, string $dir = 'general', int $maxSize = 5242880): array
{
    return ImageUploader::upload($file, $dir, $maxSize);
}

