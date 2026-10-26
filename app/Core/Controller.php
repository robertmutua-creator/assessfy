<?php

namespace App\Core;

abstract class Controller
{
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
}
