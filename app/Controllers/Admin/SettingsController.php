<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Setting;
use App\Helpers\ImageUploader;
use App\Helpers\Mailer;

/**
 * SettingsController — Admin site configuration management & SMTP test.
 */
class SettingsController extends Controller
{
    public function index(array $params = []): void
    {
        $this->requireSuperAdmin();
        $settingModel = new Setting();

        if ($this->isPost()) {
            $this->verifyCsrf();

            $data = $_POST['settings'] ?? [];
            foreach ($data as $key => $val) {
                $settingModel->set((string)$key, (string)$val);
            }

            // Handle logo / favicon upload if provided
            if (!empty($_FILES['logo']['name'])) {
                $upload = ImageUploader::upload($_FILES['logo'], 'general');
                if ($upload['success']) {
                    $settingModel->set('logo', $upload['path']);
                }
            }

            if (!empty($_FILES['favicon']['name'])) {
                $upload = ImageUploader::upload($_FILES['favicon'], 'general');
                if ($upload['success']) {
                    $settingModel->set('favicon', $upload['path']);
                }
            }

            logActivity(Session::getAdmin()['id'] ?? 0, 'update_settings', 'settings', 0, 'Updated site settings');
            Session::flash('success', 'Site settings saved successfully.');
            $this->redirect('/admin/settings');
        }

        $allSettings = $settingModel->getAllFlat();

        $this->view('admin/settings/index', [
            'pageTitle' => 'Site Settings &amp; Configuration',
            'settings'  => $allSettings,
        ], 'layouts/admin');
    }

    public function smtpTest(array $params = []): void
    {
        $this->requireSuperAdmin();
        $this->verifyCsrf();

        $admin = Session::getAdmin();
        $testEmail = $admin['email'] ?? env('ADMIN_NOTIFY_EMAIL', 'test@example.com');

        $subject = "SMTP Test — OM Trip & Travels";
        $body = "<p>Congratulations! Your SMTP email server configuration is working correctly.</p><p>Sent from OM Trip and Travels admin panel on " . date('Y-m-d H:i:s') . "</p>";

        $sent = Mailer::send($testEmail, $subject, $body);

        if ($sent) {
            $this->jsonSuccess("SMTP test email was sent successfully to: {$testEmail}");
        } else {
            $this->jsonError("SMTP connection failed. Please check your SMTP credentials in .env file.");
        }
    }
}

