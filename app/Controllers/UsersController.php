<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Schools;
use App\Models\Users;
use Throwable;

class UsersController extends Controller
{
    private Users $users;
    private Schools $schools;

    public function __construct()
    {
        parent::__construct();
        if (!$this->checkRole('admin')) {
            $this->flash('error', 'You are not authorized to manage school admins.');
            $this->redirect('/dashboard');
        }

        $this->users = new Users();
        $this->schools = new Schools();
    }

    public function index(?int $id = null): void
    {
        try {
            $schoolAdmin = null;
            if ($id !== null) {
                $schoolAdmin = $this->users->find($id);
                if (!$schoolAdmin || $schoolAdmin['role'] !== 'school') {
                    $this->flash('error', 'School admin not found.');
                    $this->redirect('/admin/users');
                }
            }

            $this->view('admin/users', [
                'title' => 'Manage School Admins',
                'users' => $this->users->getSchoolAdmins(),
                'schools' => $this->schools->all(),
                'school_admin' => $schoolAdmin,
            ]);
        } catch (Throwable $e) {
            error_log('Unable to load school admins: ' . $e->getMessage());
            $this->flash('error', 'Unable to load school admins. Please try again.');
            $this->view('admin/users', [
                'title' => 'Manage School Admins',
                'users' => [],
                'schools' => [],
                'school_admin' => null,
            ]);
        }
    }

    public function edit(string $id): void
    {
        if (!ctype_digit($id) || (int) $id < 1) {
            $this->flash('error', 'School admin not found.');
            $this->redirect('/admin/users');
        }

        $this->index((int) $id);
    }

    public function save(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/users');
        }
        if (!$this->validateCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->flash('error', 'Invalid security token. Please try again.');
            $this->redirect('/admin/users');
        }

        $idValue = trim((string) ($_POST['id'] ?? ''));
        if ($idValue !== '' && (!ctype_digit($idValue) || (int) $idValue < 1)) {
            $this->flash('error', 'Invalid school admin details.');
            $this->redirect('/admin/users');
        }

        $id = $idValue !== '' ? (int) $idValue : null;
        $schoolIdValue = (string) ($_POST['school_id'] ?? '');
        $username = $this->toLower(trim((string) ($_POST['username'] ?? '')));
        $password = (string) ($_POST['password'] ?? '');

        if (!ctype_digit($schoolIdValue) || (int) $schoolIdValue < 1
            || trim($username) === ''
            || mb_strlen($username) > 100
            || ($id === null && strlen($password) < 8)
            || ($password !== '' && strlen($password) < 8)
        ) {
            $this->flash('error', 'Provide a school, username, and a password of at least 8 characters.');
            $this->redirect($id !== null ? "/admin/users/edit/{$id}" : '/admin/users');
        }

        try {
            $school = $this->schools->find((int) $schoolIdValue);
            if (!$school) {
                $this->flash('error', 'Select a valid school.');
                $this->redirect($id !== null ? "/admin/users/edit/{$id}" : '/admin/users');
            }

            if ($id !== null) {
                $user = $this->users->find($id);
                if (!$user || $user['role'] !== 'school') {
                    $this->flash('error', 'School admin not found.');
                    $this->redirect('/admin/users');
                }
            }

            if ($this->users->usernameExists($username, $id)) {
                $this->flash('error', 'That username is already in use.');
                $this->redirect($id !== null ? "/admin/users/edit/{$id}" : '/admin/users');
            }

            $data = [
                'school_id' => (int) $schoolIdValue,
                'username' => $username,
                'role' => 'school',
            ];
            if ($password !== '') {
                $data['password'] = password_hash($password, PASSWORD_DEFAULT);
            }

            if ($id === null) {
                $this->users->create($data);
                error_log("School admin created for school ID {$schoolIdValue}.");
            } else {
                $this->users->update($id, $data);
                error_log("School admin ID {$id} updated.");
            }

            $this->flash('success', $id === null ? 'School admin registered successfully.' : 'School admin updated successfully.');
        } catch (Throwable $e) {
            error_log('Unable to save school admin: ' . $e->getMessage());
            $this->flash('error', 'Unable to save the school admin. Please try again.');
        }

        $this->redirect('/admin/users');
    }

    public function delete(string $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/users');
        }
        if (!$this->validateCsrfToken($_POST['csrf_token'] ?? null)) {
            error_log('Invalid security token submitted while deleting a school admin.');
            $this->flash('error', 'Invalid security token. Please try again.');
            $this->redirect('/admin/users');
        }
        if (!ctype_digit($id) || (int) $id < 1) {
            $this->flash('error', 'School admin not found.');
            $this->redirect('/admin/users');
        }

        $id = (int) $id;

        try {
            $user = $this->users->find($id);
            if (!$user || $user['role'] !== 'school') {
                $this->flash('error', 'School admin not found.');
                $this->redirect('/admin/users');
            }

            $this->users->delete($id);
            error_log("School admin ID {$id} deleted.");
            $this->flash('success', 'School admin deleted successfully.');
        } catch (Throwable $e) {
            error_log("Unable to delete school admin ID {$id}: " . $e->getMessage());
            $this->flash('error', 'Unable to delete the school admin. Please try again.');
        }

        $this->redirect('/admin/users');
    }
}
