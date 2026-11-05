<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Admin;
use App\Core\Database;

/**
 * AdminUserController — Admin user accounts, password change, and audit activity logs.
 */
class AdminUserController extends Controller
{
    public function index(array $params = []): void
    {
        $this->requireSuperAdmin();
        $admins = (new Admin())->all([], 'id ASC');

        $this->view('admin/admins/index', [
            'pageTitle' => 'Admin Users Management',
            'admins'    => $admins,
        ], 'layouts/admin');
    }

    public function create(array $params = []): void
    {
        $this->requireSuperAdmin();
        $adminModel = new Admin();

        if ($this->isPost()) {
            $this->verifyCsrf();

            $name     = sanitize($this->post('name'));
            $email    = sanitize($this->post('email'));
            $password = $this->post('password');
            $role     = in_array($this->post('role'), ['super_admin', 'staff']) ? $this->post('role') : 'staff';
            $isActive = $this->postInt('is_active', 1);

            if (empty($name) || empty($email) || strlen($password) < 8) {
                Session::flash('error', 'Name, valid email, and password of at least 8 characters are required.');
                $this->redirect('/admin/admins/create');
            }

            if ($adminModel->findByEmail($email)) {
                Session::flash('error', 'An admin with that email address already exists.');
                $this->redirect('/admin/admins/create');
            }

            $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
            $id = $adminModel->create([
                'name'      => $name,
                'email'     => $email,
                'password'  => $hash,
                'role'      => $role,
                'is_active' => $isActive,
            ]);

            logActivity(Session::getAdmin()['id'] ?? 0, 'create', 'admin_user', (int)$id, "Created admin user '{$email}' ({$role})");
            Session::flash('success', "Admin user '{$name}' created.");
            $this->redirect('/admin/admins');
        }

        $this->view('admin/admins/form', [
            'pageTitle' => 'Create New Admin User',
            'user'      => null,
        ], 'layouts/admin');
    }

    public function edit(array $params = []): void
    {
        $this->requireSuperAdmin();
        $id = (int) ($params['id'] ?? 0);
        $adminModel = new Admin();
        $user = $adminModel->find($id);

        if (!$user) {
            Session::flash('error', 'Admin user not found.');
            $this->redirect('/admin/admins');
        }

        if ($this->isPost()) {
            $this->verifyCsrf();

            $name     = sanitize($this->post('name'));
            $email    = sanitize($this->post('email'));
            $role     = in_array($this->post('role'), ['super_admin', 'staff']) ? $this->post('role') : 'staff';
            $isActive = $this->postInt('is_active', 1);
            $newPass  = $this->post('password');

            $data = [
                'name'      => $name,
                'email'     => $email,
                'role'      => $role,
                'is_active' => $isActive,
            ];

            if (!empty($newPass)) {
                if (strlen($newPass) < 8) {
                    Session::flash('error', 'Password must be at least 8 characters long.');
                    $this->redirect("/admin/admins/{$id}/edit");
                }
                $data['password'] = password_hash($newPass, PASSWORD_BCRYPT, ['cost' => 12]);
            }

            $adminModel->update($id, $data);
            logActivity(Session::getAdmin()['id'] ?? 0, 'update', 'admin_user', $id, "Updated admin user '{$email}'");
            Session::flash('success', 'Admin user updated.');
            $this->redirect('/admin/admins');
        }

        $this->view('admin/admins/form', [
            'pageTitle' => "Edit Admin: {$user['name']}",
            'user'      => $user,
        ], 'layouts/admin');
    }

    public function delete(array $params = []): void
    {
        $this->requireSuperAdmin();
        $this->verifyCsrf();

        $id = (int) ($params['id'] ?? 0);
        $currentAdmin = Session::getAdmin();

        if ($id === (int) $currentAdmin['id']) {
            Session::flash('error', 'You cannot delete your own account while logged in.');
            $this->redirect('/admin/admins');
        }

        (new Admin())->delete($id);
        logActivity((int)$currentAdmin['id'], 'delete', 'admin_user', $id, "Deleted admin ID {$id}");
        Session::flash('success', 'Admin user deleted.');
        $this->redirect('/admin/admins');
    }

    public function changePassword(array $params = []): void
    {
        $this->requireAdmin();
        $currentAdmin = Session::getAdmin();
        $adminModel = new Admin();
        $user = $adminModel->find((int)$currentAdmin['id']);

        if ($this->isPost()) {
            $this->verifyCsrf();

            $currentPass = $this->post('current_password');
            $newPass     = $this->post('new_password');
            $confirmPass = $this->post('confirm_password');

            if (!password_verify($currentPass, $user['password'])) {
                Session::flash('error', 'Current password is incorrect.');
                $this->redirect('/admin/change-password');
            }

            if (strlen($newPass) < 8) {
                Session::flash('error', 'New password must be at least 8 characters long.');
                $this->redirect('/admin/change-password');
            }

            if ($newPass !== $confirmPass) {
                Session::flash('error', 'New passwords do not match.');
                $this->redirect('/admin/change-password');
            }

            $hash = password_hash($newPass, PASSWORD_BCRYPT, ['cost' => 12]);
            $adminModel->updatePassword((int)$currentAdmin['id'], $hash);

            logActivity((int)$currentAdmin['id'], 'change_password', 'admin', (int)$currentAdmin['id'], 'Changed own password');
            Session::flash('success', 'Your password has been changed successfully.');
            $this->redirect('/admin/dashboard');
        }

        $this->view('admin/auth/change-password', [
            'pageTitle' => 'Change Your Password',
        ], 'layouts/admin');
    }

    public function activityLog(array $params = []): void
    {
        $this->requireSuperAdmin();

        $db = Database::getInstance();
        $logs = [];
        if ($db->isConnected()) {
            $logs = $db->fetchAll("
                SELECT al.*, a.name AS admin_name, a.email AS admin_email
                FROM activity_logs al
                LEFT JOIN admins a ON al.admin_id = a.id
                ORDER BY al.created_at DESC
                LIMIT 100
            ");
        }

        $this->view('admin/activity/index', [
            'pageTitle' => 'System Activity Audit Log',
            'logs'      => $logs,
        ], 'layouts/admin');
    }
}

