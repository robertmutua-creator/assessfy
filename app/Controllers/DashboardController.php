<?php

namespace App\Controllers;

use App\Core\Controller;

class DashboardController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->isLoggedIn() === false) {
            $this->setFlash('error', 'You must be logged in!');
            $this->redirect('/login');
        }
    }
    public function index(): void
    {
        $role = $_SESSION['user']['role'] ?? '';
        $data = [];
        switch ($role) {
            case 'admin':
                $data['title'] = 'Admin Dashboard';
                $data['auth_user'] = $_SESSION['user'];
                $this->view('admin/index', $data);
                break;

            default:
                echo 'Invalid role';
                break;
        }
    }
}
