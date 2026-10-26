<?php

namespace App\Core;

abstract class Controller
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    protected function view(string $file, array $data = []): void
    {
        extract($data);

        $viewPath = dirname(__DIR__) . "/Views/{$file}.php";
        $layoutFile = dirname(__DIR__) . "/Views/layouts/main.php";

        if (!file_exists($viewPath)) {
            throw new \Exception("View file not found: {$file}");
        }

        if (!file_exists($layoutFile)) {
            throw new \Exception("Layout file not found.");
        }

        // Require the fixed layout, which has access to $viewPath and $data
        require $layoutFile;
    }

    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }

    protected function setFlash(string $type, string $message): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['flash'][$type] = $message;
    }

    protected function toUpper(string $value): string
    {
        return mb_strtoupper($value);
    }

    protected function toLower(string $value): string
    {
        return mb_strtolower($value);
    }

    protected function toTitle(string $value): string
    {
        return mb_convert_case($value, MB_CASE_TITLE);
    }

    protected function sideBar(): array
    {
        $role = $_SESSION['user']['role'] ?? 'guest';

        $links = [
            'Dashboard' => '/dashboard'
        ];

        // Role-based menu items
        switch ($role) {
            case 'admin':
                $links['Manage Schools'] = '/admin/schools';
                $links['Manage School Admins'] = '/admin/admins';
                break;
            default:
                break;
        }

        return $links;
    }

    protected function isLoggedIn(): bool
    {
        return !empty($_SESSION['user']);
    }

    protected function checkRole(string $role): bool
    {
        return $this->isLoggedIn() && ($_SESSION['user']['role'] ?? '') === $role;
    }
}
