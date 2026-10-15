<?php

declare(strict_types=1);

if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed
    {
        return $_ENV[$key] ?? $default;
    }
}

if (!function_exists('e')) {
    function e(?string $value): string
    {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('url')) {
    function url(string $path = ''): string
    {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $base = rtrim(dirname($scriptName), '/\\');

        $cleanPath = '/' . ltrim($path, '/');
        if ($base === '' || $base === '/' || $base === '\\') {
            return $cleanPath;
        }

        return $base . $cleanPath;
    }
}

if (!function_exists('redirect')) {
    function redirect(string $path): void
    {
        header('Location: ' . url($path));
        exit;
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        \App\Core\Session::start();
        $token = \App\Core\Session::get('_csrf_token');
        if (!$token) {
            $token = bin2hex(random_bytes(32));
            \App\Core\Session::set('_csrf_token', $token);
        }
        return $token;
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
    }
}
