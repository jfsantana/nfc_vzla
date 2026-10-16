<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Session;
use App\Core\View;
use App\Models\User;

abstract class BaseController
{
    protected function view(string $viewPath, array $data = [], string $layout = 'main'): void
    {
        View::render($viewPath, $data, $layout);
    }

    protected function json(mixed $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    }

    protected function requireSuperAdmin(): bool
    {
        return $this->requireRole('superadmin');
    }

    protected function requireRole(string $role): bool
    {
        if (!Session::has('user_id')) {
            Session::flash('error', 'Debes iniciar sesión para continuar.');
            redirect('/login');
            return false;
        }

        if (Session::get('role_slug') !== $role) {
            http_response_code(403);
            echo 'Acceso denegado para este rol.';
            return false;
        }

        $user = (new User())->findById((int)Session::get('user_id'));
        if ($user === null || $user['status'] !== 'active' || $user['role_slug'] !== $role) {
            Session::destroy();
            redirect('/login');
            return false;
        }

        return true;
    }

    protected function verifyCsrf(Request $request): bool
    {
        $token = (string)$request->post('_csrf', '');
        $sessionToken = (string)Session::get('_csrf_token', '');

        if ($token !== '' && $sessionToken !== '' && hash_equals($sessionToken, $token)) {
            return true;
        }

        http_response_code(419);
        echo 'La sesión del formulario venció. Recarga la página e inténtalo de nuevo.';
        return false;
    }

    protected function adminView(string $viewPath, array $data = []): void
    {
        $data += [
            'userName' => Session::get('user_name'),
            'userEmail' => Session::get('user_email'),
            'roleName' => Session::get('role_name'),
            'success' => Session::flash('success'),
            'error' => Session::flash('error'),
        ];

        $this->view($viewPath, $data, 'admin');
    }
}
