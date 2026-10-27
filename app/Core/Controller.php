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
        $data['auth_user'] = $_SESSION['user'] ?? null;
        $data['sidebar_links'] = $this->sideBar();
        $data['csrf_token'] = $this->csrfToken();
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

    protected function csrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    protected function validateCsrfToken(?string $token): bool
    {
        if (empty($_SESSION['csrf_token']) || empty($token)) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    protected function handleFileUpload(array $file, string $path): ?string
    {
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $maxFileSize = 2 * 1024 * 1024;
        if ($file['size'] > $maxFileSize) {
            $this->setFlash('error', 'File size exceeds the maximum allowed limit of 2MB.');
            return null;
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedExtensions)) {
            $this->setFlash('error', 'Invalid file type. Only JPG, JPEG, PNG, and WEBP files are allowed.');
            return null;
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];

        if (!in_array($mimeType, $allowedMimeTypes)) {
            $this->setFlash('error', 'Invalid file content detected.');
            return null;
        }

        $absolutePath = dirname(__DIR__, 2) . '/public/' . ltrim($path, '/');
        if (!is_dir($absolutePath)) {
            mkdir($absolutePath, 0755, true);
        }

        $filename = bin2hex(random_bytes(16)) . '.' . $extension;
        $targetFilePath = $absolutePath . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $targetFilePath)) {
            $this->setFlash('error', 'Failed to save the uploaded file.');
            return null;
        }

        return '/' . trim($path, '/') . '/' . $filename;
    }
}
