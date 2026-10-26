<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Model\Users;

class HomeController extends Controller
{
    public function index(): void
    {
        $this->view('home/index', [
            'title' => 'Dashboard - Assessfy'
        ]);
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $this->toLower($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            if (!$username || !$password) {
                $this->setFlash('error', 'All fields required.');
                $this->redirect('/login');
            }

            $user = (new Users())->getUserByUsername($username);
            if (!$user || !password_verify($password, $user['password'])) {
                $this->setFlash('error', 'Incorrect credentials.');
                $this->redirect('/login');
            }
            unset($user['password']);
            $_SESSION['user'] = $user;
            $this->setFlash('success', 'Logged in successfully.');
            $this->redirect('/dashboard');
        } else {
            $this->view('auth/login', [
                'title' => 'Login - Assessfy'
            ]);
        }
    }

    public function logout(): void
    {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_destroy();

        $this->setFlash('success', 'Logged out successfully.');
        $this->redirect('/login');
    }
}
