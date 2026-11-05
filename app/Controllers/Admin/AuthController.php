<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Admin;
use App\Helpers\Mailer;

/**
 * AuthController — Admin authentication, password recovery, and session management.
 */
class AuthController extends Controller
{
    /**
     * Show login form or process login POST.
     */
    public function login(array $params = []): void
    {
        if (Session::isAdminLoggedIn()) {
            $this->redirect('/admin/dashboard');
        }

        if ($this->isPost()) {
            $this->verifyCsrf();

            $ip = clientIp();
            $adminModel = new Admin();

            // Throttling check
            if ($adminModel->isLockedOut($ip)) {
                Session::flash('error', 'Too many failed login attempts. Please try again after 15 minutes.');
                $this->redirect('/admin/login');
            }

            $email    = sanitize($this->post('email'));
            $password = $this->post('password');

            if (empty($email) || empty($password)) {
                Session::flash('error', 'Please enter both email and password.');
                $this->redirect('/admin/login');
            }

            $admin = $adminModel->findByEmail($email);

            if (!$admin || !password_verify($password, $admin['password'])) {
                $adminModel->recordFailedAttempt($ip, $email);
                Session::flash('error', 'Invalid email address or password.');
                $this->redirect('/admin/login');
            }

            if (empty($admin['is_active'])) {
                Session::flash('error', 'This account has been deactivated. Please contact the administrator.');
                $this->redirect('/admin/login');
            }

            // Success: clear lockout attempts, regenerate session ID to prevent fixation
            $adminModel->clearAttempts($ip);
            session_regenerate_id(true);

            // Save admin session
            Session::setAdmin([
                'id'    => (int) $admin['id'],
                'name'  => $admin['name'],
                'email' => $admin['email'],
                'role'  => $admin['role'],
            ]);

            // Update last login timestamp
            $adminModel->updateLastLogin((int) $admin['id']);

            // Log activity
            logActivity((int) $admin['id'], 'login', 'admin', (int) $admin['id'], "Admin logged in from IP {$ip}");

            Session::flash('success', "Welcome back, {$admin['name']}!");
            $this->redirect('/admin/dashboard');
        }

        // Render login view (no main layout)
        $this->view('admin/auth/login', [
            'pageTitle' => 'Admin Login | OM Trip and Travels',
        ], false);
    }

    /**
     * Logout admin.
     */
    public function logout(array $params = []): void
    {
        $admin = Session::getAdmin();
        if ($admin) {
            logActivity((int)$admin['id'], 'logout', 'admin', (int)$admin['id'], "Admin logged out");
        }
        Session::logoutAdmin();
        Session::flash('info', 'You have been logged out.');
        $this->redirect('/admin/login');
    }

    /**
     * Forgot password form & email dispatch.
     */
    public function forgot(array $params = []): void
    {
        if ($this->isPost()) {
            $this->verifyCsrf();
            $email = sanitize($this->post('email'));
            $adminModel = new Admin();
            $admin = $adminModel->findByEmail($email);

            if ($admin && !empty($admin['is_active'])) {
                $token = bin2hex(random_bytes(32));
                $expires = date('Y-m-d H:i:s', time() + 3600); // 1 hour
                $adminModel->setResetToken((int)$admin['id'], $token, $expires);

                $appUrl = rtrim(env('APP_URL', 'http://localhost'), '/');
                $resetUrl = "{$appUrl}/admin/reset?token={$token}";

                $subject = "Password Reset Request — OM Trip and Travels Admin";
                $body = "
                    <p>Hello {$admin['name']},</p>
                    <p>We received a request to reset your admin password. Click the link below to set a new password (valid for 1 hour):</p>
                    <p><a href='{$resetUrl}' style='display:inline-block;padding:10px 20px;background:#e65c00;color:#fff;text-decoration:none;border-radius:5px;'>Reset Password</a></p>
                    <p>Or copy this URL into your browser:<br>{$resetUrl}</p>
                    <p>If you did not request this, you can safely ignore this email.</p>
                ";

                Mailer::send($admin['email'], $subject, $body);
            }

            // Always show friendly message to prevent email enumeration
            Session::flash('success', 'If an account exists with that email, a password reset link has been sent.');
            $this->redirect('/admin/forgot');
        }

        $this->view('admin/auth/forgot', ['pageTitle' => 'Forgot Password'], false);
    }

    /**
     * Reset password with token.
     */
    public function reset(array $params = []): void
    {
        $token = sanitize($this->get('token') ?: $this->post('token'));
        if (empty($token)) {
            Session::flash('error', 'Invalid password reset token.');
            $this->redirect('/admin/login');
        }

        $adminModel = new Admin();
        $admin = $adminModel->findByResetToken($token);

        if (!$admin) {
            Session::flash('error', 'Reset token has expired or is invalid.');
            $this->redirect('/admin/login');
        }

        if ($this->isPost()) {
            $this->verifyCsrf();
            $password = $this->post('password');
            $confirm  = $this->post('password_confirm');

            if (strlen($password) < 8) {
                Session::flash('error', 'Password must be at least 8 characters long.');
                $this->redirect("/admin/reset?token={$token}");
            }

            if ($password !== $confirm) {
                Session::flash('error', 'Passwords do not match.');
                $this->redirect("/admin/reset?token={$token}");
            }

            $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
            $adminModel->updatePassword((int)$admin['id'], $hash);
            $adminModel->clearResetToken((int)$admin['id']);

            logActivity((int)$admin['id'], 'password_reset', 'admin', (int)$admin['id'], 'Password reset via email token');

            Session::flash('success', 'Your password has been reset successfully. You can now login.');
            $this->redirect('/admin/login');
        }

        $this->view('admin/auth/reset', ['token' => $token, 'pageTitle' => 'Set New Password'], false);
    }
}

