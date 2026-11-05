<?php
declare(strict_types=1);

namespace App\Models;

/**
 * Admin model.
 */
class Admin extends BaseModel
{
    protected string $table = 'admins';

    public function findByEmail(string $email): ?array
    {
        return $this->findBy('email', $email);
    }

    public function updateLastLogin(int $id): void
    {
        $this->db->update($this->table, ['last_login' => date('Y-m-d H:i:s')], ['id' => $id]);
    }

    public function setResetToken(int $id, string $token, string $expires): void
    {
        $this->db->update($this->table, [
            'password_reset_token'   => $token,
            'password_reset_expires' => $expires,
        ], ['id' => $id]);
    }

    public function findByResetToken(string $token): ?array
    {
        return $this->db->fetchOne(
            "SELECT * FROM admins WHERE password_reset_token = ? AND password_reset_expires > ? LIMIT 1",
            [$token, date('Y-m-d H:i:s')]
        );
    }

    public function clearResetToken(int $id): void
    {
        $this->db->update($this->table, [
            'password_reset_token'   => null,
            'password_reset_expires' => null,
        ], ['id' => $id]);
    }

    public function updatePassword(int $id, string $hash): void
    {
        $this->db->update($this->table, ['password' => $hash], ['id' => $id]);
    }

    /**
     * Login throttle: check if IP is locked.
     */
    public function isLockedOut(string $ip): bool
    {
        $row = $this->db->fetchOne(
            "SELECT locked_until FROM login_attempts WHERE ip = ? LIMIT 1",
            [$ip]
        );
        if (!$row || !$row['locked_until']) return false;
        return strtotime($row['locked_until']) > time();
    }

    /**
     * Record a failed login attempt.
     */
    public function recordFailedAttempt(string $ip, string $email = ''): void
    {
        $existing = $this->db->fetchOne("SELECT id, attempts FROM login_attempts WHERE ip = ?", [$ip]);
        if ($existing) {
            $attempts = $existing['attempts'] + 1;
            $lockedUntil = $attempts >= 5 ? date('Y-m-d H:i:s', time() + 900) : null; // 15 min
            $this->db->update('login_attempts', [
                'attempts'     => $attempts,
                'locked_until' => $lockedUntil,
                'email'        => $email,
            ], ['ip' => $ip]);
        } else {
            $this->db->insert('login_attempts', [
                'ip'      => $ip,
                'email'   => $email,
                'attempts'=> 1,
            ]);
        }
    }

    /**
     * Clear login attempts for IP after success.
     */
    public function clearAttempts(string $ip): void
    {
        $this->db->delete('login_attempts', ['ip' => $ip]);
    }
}

