<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\{Enquiry, Setting, Vehicle, TourPackage};
use App\Helpers\Mailer;

/**
 * EnquiryController — handles public form submissions (Contact & Booking).
 */
class EnquiryController extends Controller
{
    /**
     * Handle Contact Us form submission.
     */
    public function contactSubmit(array $params = []): void
    {
        $this->processEnquiry('contact');
    }

    /**
     * Handle Booking Request form submission.
     */
    public function bookingSubmit(array $params = []): void
    {
        $this->processEnquiry('booking');
    }

    private function processEnquiry(string $type): void
    {
        $isAjax = $this->isAjax() || !empty($_POST['ajax']);

        // 1. Honeypot check (anti-spam)
        if (!empty($_POST['website_hp'])) {
            if ($isAjax) {
                $this->jsonSuccess('Thank you! Your request has been received.');
            }
            $this->redirect('/' . ($type === 'booking' ? 'booking' : 'contact'));
        }

        // 2. CSRF check
        $this->verifyCsrf();

        // 3. Rate limiting by IP (max 5 requests per 10 minutes)
        $ip = clientIp();
        if (!rateLimit('enquiry_' . $ip, 5, 600)) {
            $msg = 'Too many enquiries from your network. Please wait a few minutes or call us directly.';
            if ($isAjax) {
                $this->jsonError($msg, [], 429);
            }
            $this->redirectBackWith('error', $msg, $type);
        }

        // 4. Cloudflare Turnstile check (if configured)
        $turnstileSecret = env('TURNSTILE_SECRET_KEY', setting('turnstile_secret_key', ''));
        if (!empty($turnstileSecret) && !empty($_POST['cf-turnstile-response'])) {
            $response = $_POST['cf-turnstile-response'];
            $verifyRes = @file_get_contents("https://challenges.cloudflare.com/turnstile/v0/siteverify", false, stream_context_create([
                'http' => [
                    'method'  => 'POST',
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'content' => http_build_query([
                        'secret'   => $turnstileSecret,
                        'response' => $response,
                        'remoteip' => $ip,
                    ]),
                ]
            ]));
            $json = $verifyRes ? json_decode($verifyRes, true) : null;
            if (!$json || empty($json['success'])) {
                $msg = 'Security verification failed. Please try again.';
                if ($isAjax) $this->jsonError($msg, [], 400);
                $this->redirectBackWith('error', $msg, $type);
            }
        }

        // 5. Sanitize and validate inputs
        $name     = sanitize($this->post('name'));
        $phone    = sanitize($this->post('phone'));
        $email    = filter_var($this->post('email'), FILTER_VALIDATE_EMAIL) ? sanitize($this->post('email')) : null;
        $message  = sanitize($this->post('message'));

        $pickup   = sanitize($this->post('pickup_location'));
        $drop     = sanitize($this->post('drop_location'));
        $travelDate = sanitize($this->post('travel_date'));
        $returnDate = sanitize($this->post('return_date'));
        $passengers = $this->postInt('passengers', 0);
        $vehicleId  = $this->postInt('vehicle_id', 0) ?: null;
        $packageId  = $this->postInt('package_id', 0) ?: null;
        $sourcePage = sanitize($this->post('source_page', currentUrl()));

        $errors = [];

        if (empty($name) || strlen($name) < 2) {
            $errors[] = 'Please enter your full name.';
        }

        if (empty($phone) || !indianPhone($phone)) {
            $errors[] = 'Please enter a valid 10-digit Indian phone number.';
        }

        if ($type === 'booking') {
            if (empty($pickup)) {
                $errors[] = 'Please specify a pickup location.';
            }
            if (empty($travelDate)) {
                $errors[] = 'Please select your travel date.';
            }
        }

        if (!empty($errors)) {
            $msg = implode(' ', $errors);
            if ($isAjax) {
                $this->jsonError($msg, ['errors' => $errors], 422);
            }
            $this->redirectBackWith('error', $msg, $type);
        }

        // 6. Save into enquiries table
        $enquiryModel = new Enquiry();
        $enquiryId = $enquiryModel->create([
            'type'            => $type,
            'name'            => $name,
            'phone'           => $phone,
            'email'           => $email,
            'pickup_location' => $pickup ?: null,
            'drop_location'   => $drop ?: null,
            'travel_date'     => $travelDate ?: null,
            'return_date'     => $returnDate ?: null,
            'passengers'      => $passengers > 0 ? $passengers : null,
            'vehicle_id'      => $vehicleId,
            'package_id'      => $packageId,
            'message'         => $message ?: null,
            'status'          => 'new',
            'source_page'     => $sourcePage,
            'ip'              => $ip,
            'user_agent'      => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 500),
            'is_read'         => 0,
        ]);

        // 7. Send notification email to owner (async / non-blocking if fails)
        $this->sendOwnerNotification($type, $name, $phone, $email, $pickup, $drop, $travelDate, $passengers, $vehicleId, $packageId, $message);

        $successMsg = 'Thank you! Your enquiry has been submitted. Our team will contact you shortly.';
        if ($isAjax) {
            $this->jsonSuccess($successMsg, ['enquiry_id' => $enquiryId]);
        }

        $this->redirectBackWith('success', $successMsg, $type);
    }

    private function redirectBackWith(string $type, string $message, string $formType): never
    {
        \App\Core\Session::flash($type, $message);
        $redirectUrl = $formType === 'booking' ? '/booking' : '/contact';
        $this->redirect($redirectUrl);
    }

    private function sendOwnerNotification(
        string $type,
        string $name,
        string $phone,
        ?string $email,
        string $pickup,
        string $drop,
        string $travelDate,
        int $passengers,
        ?int $vehicleId,
        ?int $packageId,
        string $message
    ): void {
        $notifyEmail = env('ADMIN_NOTIFY_EMAIL', setting('email', ''));
        if (empty($notifyEmail)) return;

        $vehicleName = '';
        if ($vehicleId) {
            $v = (new Vehicle())->find($vehicleId);
            if ($v) $vehicleName = $v['name'];
        }

        $packageName = '';
        if ($packageId) {
            $p = (new TourPackage())->find($packageId);
            if ($p) $packageName = $p['name'];
        }

        $subject = strtoupper($type) . " Request from {$name} ({$phone}) — OM Trip & Travels";
        $body = "
            <h2>New {$type} enquiry received on OM Trip and Travels</h2>
            <table cellpadding='8' cellspacing='0' border='1' style='border-collapse:collapse; font-family:sans-serif;'>
                <tr><td><strong>Name</strong></td><td>{$name}</td></tr>
                <tr><td><strong>Phone</strong></td><td><a href='tel:{$phone}'>{$phone}</a></td></tr>
                <tr><td><strong>Email</strong></td><td>" . ($email ?: 'Not provided') . "</td></tr>
                " . ($vehicleName ? "<tr><td><strong>Vehicle</strong></td><td>{$vehicleName}</td></tr>" : "") . "
                " . ($packageName ? "<tr><td><strong>Package</strong></td><td>{$packageName}</td></tr>" : "") . "
                " . ($pickup ? "<tr><td><strong>Pickup</strong></td><td>{$pickup}</td></tr>" : "") . "
                " . ($drop ? "<tr><td><strong>Drop</strong></td><td>{$drop}</td></tr>" : "") . "
                " . ($travelDate ? "<tr><td><strong>Travel Date</strong></td><td>{$travelDate}</td></tr>" : "") . "
                " . ($passengers ? "<tr><td><strong>Passengers</strong></td><td>{$passengers}</td></tr>" : "") . "
                " . ($message ? "<tr><td><strong>Message</strong></td><td>" . nl2br(htmlspecialchars($message)) . "</td></tr>" : "") . "
                <tr><td><strong>Received At</strong></td><td>" . date('d M Y, h:i A') . "</td></tr>
            </table>
            <p><a href='" . env('APP_URL', 'http://localhost') . "/admin/enquiries' style='display:inline-block;padding:10px 20px;background:#e65c00;color:#fff;text-decoration:none;border-radius:5px;'>View in Admin Panel</a></p>
        ";

        Mailer::send($notifyEmail, $subject, $body, [
            'reply_to' => $email ?: '',
        ]);
    }
}

