<?php
declare(strict_types=1);

namespace App\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * Mailer helper using PHPMailer.
 */
class Mailer
{
    /**
     * Send an email using SMTP credentials from environment / settings.
     *
     * @param string $to Recipient email address
     * @param string $subject Email subject
     * @param string $htmlBody HTML content
     * @param array{from_name?: string, reply_to?: string, alt_body?: string} $options
     * @return bool
     */
    public static function send(string $to, string $subject, string $htmlBody, array $options = []): bool
    {
        $host = env('SMTP_HOST', '');
        $user = env('SMTP_USER', '');
        $pass = env('SMTP_PASS', '');
        $port = (int) env('SMTP_PORT', '465');
        $from = env('SMTP_FROM', $user);
        $fromName = $options['from_name'] ?? env('APP_NAME', 'OM Trip and Travels');

        // If SMTP credentials aren't provided, log and return false without crashing
        if (empty($host) || empty($user) || empty($pass)) {
            error_log("Mailer Warning: SMTP not configured. Could not send email to: {$to} with subject: {$subject}");
            return false;
        }

        if (!class_exists(PHPMailer::class)) {
            error_log("Mailer Error: PHPMailer class not found. Run composer install.");
            return false;
        }

        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = $host;
            $mail->SMTPAuth   = true;
            $mail->Username   = $user;
            $mail->Password   = $pass;
            $mail->Port       = $port;
            $mail->CharSet    = 'UTF-8';

            if ($port === 465) {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } else {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }

            $mail->Timeout = 10;

            // Recipients
            $mail->setFrom($from, $fromName);
            $mail->addAddress($to);

            if (!empty($options['reply_to'])) {
                $mail->addReplyTo($options['reply_to']);
            }

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $htmlBody;
            $mail->AltBody = $options['alt_body'] ?? strip_tags($htmlBody);

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Mailer Error ({$to}): " . $mail->ErrorInfo);
            return false;
        }
    }
}

/**
 * Functional wrapper for Mailer::send
 */
function sendMail(string $to, string $subject, string $body, array $options = []): bool
{
    return Mailer::send($to, $subject, $body, $options);
}

