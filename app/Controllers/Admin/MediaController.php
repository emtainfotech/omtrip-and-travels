<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Helpers\ImageUploader;

/**
 * MediaController — Handles asynchronous image uploads from admin editors & forms.
 */
class MediaController extends Controller
{
    public function upload(array $params = []): void
    {
        $this->requireAdmin();

        if (empty($_FILES['file']['name'])) {
            $this->jsonError('No file received.');
        }

        $folder = sanitize($_POST['folder'] ?? 'general');
        $upload = ImageUploader::upload($_FILES['file'], $folder);

        if ($upload['success']) {
            // Format response for TinyMCE / custom uploaders
            $this->json([
                'success'  => true,
                'location' => $upload['path'],
                'url'      => $upload['path'],
            ]);
        } else {
            $this->jsonError($upload['error']);
        }
    }

    public function delete(array $params = []): void
    {
        $this->requireAdmin();
        $this->verifyCsrf();

        $path = sanitize($_POST['path'] ?? '');
        if ($path && str_starts_with($path, '/uploads/')) {
            $fullPath = ROOT_PATH . '/public' . $path;
            if (file_exists($fullPath)) {
                @unlink($fullPath);
                $this->jsonSuccess('File deleted');
            }
        }

        $this->jsonError('File not found or invalid path');
    }
}

