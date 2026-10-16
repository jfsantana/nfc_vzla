<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Session;
use App\Models\User;

final class AuthController extends BaseController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function showLogin(Request $request): void
    {
        // Si ya está autenticado, redirigir al dashboard según el rol
        if (Session::has('user_id')) {
            redirect(Session::get('role_slug') === 'superadmin' ? '/admin/dashboard' : '/company/dashboard');
            return;
        }

        $error = Session::flash('error');
        $oldEmail = Session::flash('old_email');

        $this->view('auth/login', [
            'title'    => 'Iniciar Sesión | Sistema NFC SaaS',
            'error'    => $error,
            'oldEmail' => $oldEmail,
        ], 'auth');
    }

    public function login(Request $request): void
    {
        $csrf = $request->post('_csrf');
        $sessionToken = (string)Session::get('_csrf_token', '');
        if (!$csrf || $sessionToken === '' || !hash_equals($sessionToken, (string)$csrf)) {
            Session::flash('error', 'Token de seguridad inválido. Por favor recarga e intenta de nuevo.');
            redirect('/login');
            return;
        }

        $email = trim((string)$request->post('email', ''));
        $password = (string)$request->post('password', '');

        if ($email === '' || $password === '') {
            Session::flash('error', 'Por favor ingresa tu correo y contraseña.');
            Session::flash('old_email', $email);
            redirect('/login');
            return;
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            Session::flash('error', 'Credenciales incorrectas. Verifica tu correo y contraseña.');
            Session::flash('old_email', $email);
            redirect('/login');
            return;
        }

        if ($user['status'] !== 'active') {
            Session::flash('error', 'Tu cuenta se encuentra inactiva o suspendida.');
            redirect('/login');
            return;
        }

        if (!in_array($user['role_slug'], ['superadmin', 'company_admin'], true)) {
            Session::flash('error', 'Este rol no tiene acceso al panel.');
            redirect('/login');
            return;
        }

        if ($user['role_slug'] === 'company_admin' && ($user['company_id'] === null || $user['company_status'] !== 'active')) {
            Session::flash('error', 'La empresa asociada no está activa.');
            redirect('/login');
            return;
        }

        // Iniciar sesión segura y regenerar ID de sesión
        session_regenerate_id(true);
        Session::set('user_id', $user['id']);
        Session::set('user_name', $user['name']);
        Session::set('user_email', $user['email']);
        Session::set('role_slug', $user['role_slug']);
        Session::set('role_name', $user['role_name']);
        Session::set('company_id', $user['company_id']);
        Session::set('company_name', $user['company_name']);

        redirect($user['role_slug'] === 'superadmin' ? '/admin/dashboard' : '/company/dashboard');
    }

    public function logout(Request $request): void
    {
        $token = (string)$request->post('_csrf', '');
        $sessionToken = (string)Session::get('_csrf_token', '');
        if ($token === '' || $sessionToken === '' || !hash_equals($sessionToken, $token)) {
            http_response_code(419);
            echo 'La sesión del formulario venció. Recarga la página e inténtalo de nuevo.';
            return;
        }

        Session::destroy();
        redirect('/');
    }
}
