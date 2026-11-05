<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Enquiry;

/**
 * ApiController — AJAX and beacon endpoints.
 */
class ApiController extends Controller
{
    /**
     * Record call / whatsapp clicks.
     */
    public function clickLog(array $params = []): void
    {
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

        $type      = ($input['type'] ?? '') === 'whatsapp' ? 'whatsapp' : 'call';
        $vehicleId = !empty($input['vehicle_id']) ? (int) $input['vehicle_id'] : null;
        $packageId = !empty($input['package_id']) ? (int) $input['package_id'] : null;
        $page      = sanitize($input['page'] ?? ($_SERVER['HTTP_REFERER'] ?? '/'));
        $ip        = clientIp();

        $db = Database::getInstance();
        if ($db->isConnected()) {
            $db->insert('click_logs', [
                'type'       => $type,
                'vehicle_id' => $vehicleId,
                'package_id' => $packageId,
                'page'       => substr($page, 0, 500),
                'ip'         => $ip,
            ]);
        }

        $this->jsonSuccess('Logged');
    }

    /**
     * Handle quick enquiry modal via AJAX.
     */
    public function quickEnquiry(array $params = []): void
    {
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

        // Anti-spam honeypot
        if (!empty($input['website_hp'])) {
            $this->jsonSuccess('Thank you!');
        }

        $ip = clientIp();
        if (!rateLimit('quick_enquiry_' . $ip, 6, 600)) {
            $this->jsonError('Too many requests. Please wait a few moments.', [], 429);
        }

        $name       = sanitize($input['name'] ?? '');
        $phone      = sanitize($input['phone'] ?? '');
        $vehicleId  = !empty($input['vehicle_id']) ? (int)$input['vehicle_id'] : null;
        $packageId  = !empty($input['package_id']) ? (int)$input['package_id'] : null;
        $travelDate = sanitize($input['travel_date'] ?? '');
        $sourcePage = sanitize($input['source_page'] ?? ($_SERVER['HTTP_REFERER'] ?? '/'));

        if (empty($name) || strlen($name) < 2) {
            $this->jsonError('Please enter your name.', [], 422);
        }

        if (empty($phone) || !indianPhone($phone)) {
            $this->jsonError('Please enter a valid 10-digit Indian phone number.', [], 422);
        }

        $pickup     = sanitize($input['pickup_location'] ?? 'Indore');
        $drop       = sanitize($input['drop_location'] ?? '');
        $passengers = !empty($input['passengers']) ? (int)$input['passengers'] : null;

        $enquiryModel = new Enquiry();
        $id = $enquiryModel->create([
            'type'            => 'booking',
            'name'            => $name,
            'phone'           => $phone,
            'pickup_location' => $pickup ?: 'Indore',
            'drop_location'   => $drop ?: null,
            'passengers'      => $passengers,
            'vehicle_id'      => $vehicleId,
            'package_id'      => $packageId,
            'travel_date'     => $travelDate ?: null,
            'status'          => 'new',
            'source_page'     => $sourcePage,
            'ip'              => $ip,
            'user_agent'      => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 500),
            'is_read'         => 0,
        ]);

        $this->jsonSuccess('Thank you! We will call you back within 15 minutes.', ['id' => $id]);
    }
}
