<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Schools;
use App\Models\Users;

class DashboardController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->isLoggedIn() === false) {
            $this->flash('error', 'You must be logged in!');
            $this->redirect('/login');
        }
    }
    public function index(): void
    {
        $role = $_SESSION['user']['role'] ?? '';
        switch ($role) {
            case 'admin':
                $data['title'] = 'Admin Dashboard';
                $data['total_schools'] = (new Schools())->count(['status' => 'active']);
                $data['total_users'] = (new Users())->count();
                $this->view('admin/index', $data);
                break;

            default:
                echo 'Invalid role';
                break;
        }
    }
}
