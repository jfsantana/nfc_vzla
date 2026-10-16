<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Session;
use App\Models\User;
use PDOException;

final class SystemUsersController extends BaseController
{
    private User $users;

    public function __construct()
    {
        $this->users = new User();
    }

    public function index(Request $request): void
    {
        if (!$this->requireSuperAdmin()) {
            return;
        }

        $this->adminView('admin/system_users', [
            'title' => 'Usuarios del Sistema (SuperAdmin) | Sistema NFC',
            'users' => $this->users->superadmins(),
            'currentUserId' => (int)Session::get('user_id'),
        ]);
    }

    public function new(Request $request): void
    {
        if (!$this->requireSuperAdmin()) {
            return;
        }

        $this->showForm(null);
    }

    public function edit(Request $request): void
    {
        if (!$this->requireSuperAdmin()) {
            return;
        }

        $id = (int)$request->get('id', 0);
        $user = $this->users->superadminById($id);

        if ($user === null) {
            http_response_code(404);
            echo 'Usuario administrador del sistema no encontrado.';
            return;
        }

        $this->showForm($user);
    }

    public function store(Request $request): void
    {
        if (!$this->requireSuperAdmin() || !$this->verifyCsrf($request)) {
            return;
        }

        $data = $this->validatedData($request);
        if ($data === null) {
            redirect('/admin/system-users/new');
            return;
        }

        if ($this->users->emailExists($data['email'])) {
            Session::flash('error', 'El correo electrónico indicado ya está en uso por otro usuario.');
            redirect('/admin/system-users/new');
            return;
        }

        try {
            $this->users->createSuperadmin(
                $data['name'],
                $data['email'],
                $data['password'],
                $data['status']
            );
            Session::flash('success', 'Administrador del sistema creado correctamente con rol SuperAdmin.');
        } catch (PDOException $exception) {
            if ($exception->getCode() !== '23000') {
                throw $exception;
            }
            Session::flash('error', 'El correo electrónico ya está registrado.');
            redirect('/admin/system-users/new');
            return;
        }

        redirect('/admin/system-users');
    }

    public function update(Request $request): void
    {
        if (!$this->requireSuperAdmin() || !$this->verifyCsrf($request)) {
            return;
        }

        $id = (int)$request->post('id', 0);
        $user = $this->users->superadminById($id);

        if ($user === null) {
            http_response_code(404);
            echo 'Usuario administrador del sistema no encontrado.';
            return;
        }

        $currentUserId = (int)Session::get('user_id');
        $status = (string)$request->post('status', 'active');

        // Evitar que el usuario en sesión se desactive a sí mismo
        if ($id === $currentUserId && $status === 'inactive') {
            Session::flash('error', 'No puedes desactivar tu propia cuenta mientras estás en sesión.');
            redirect('/admin/system-users/edit?id=' . $id);
            return;
        }

        $data = $this->validatedData($request, true);
        if ($data === null) {
            redirect('/admin/system-users/edit?id=' . $id);
            return;
        }

        if ($this->users->emailExists($data['email'], $id)) {
            Session::flash('error', 'El correo electrónico indicado ya pertenece a otro usuario.');
            redirect('/admin/system-users/edit?id=' . $id);
            return;
        }

        try {
            $this->users->updateSuperadmin(
                $id,
                $data['name'],
                $data['email'],
                $data['status'],
                $data['password']
            );

            // Si el usuario actual editó su propio perfil, actualizar datos de sesión
            if ($id === $currentUserId) {
                Session::set('user_name', $data['name']);
                Session::set('user_email', $data['email']);
            }

            Session::flash('success', 'Administrador del sistema actualizado correctamente.');
        } catch (PDOException $exception) {
            if ($exception->getCode() !== '23000') {
                throw $exception;
            }
            Session::flash('error', 'El correo electrónico ya está registrado por otro usuario.');
            redirect('/admin/system-users/edit?id=' . $id);
            return;
        }

        redirect('/admin/system-users');
    }

    public function delete(Request $request): void
    {
        if (!$this->requireSuperAdmin() || !$this->verifyCsrf($request)) {
            return;
        }

        $id = (int)$request->post('id', 0);
        $currentUserId = (int)Session::get('user_id');

        if ($id === $currentUserId) {
            Session::flash('error', 'No puedes eliminar tu propia cuenta de SuperAdmin en sesión.');
            redirect('/admin/system-users');
            return;
        }

        if ($this->users->countSuperadmins() <= 1) {
            Session::flash('error', 'No se puede eliminar el único Súper Administrador del sistema.');
            redirect('/admin/system-users');
            return;
        }

        if ($this->users->superadminById($id) === null) {
            Session::flash('error', 'El usuario a eliminar no existe o no es un administrador del sistema.');
            redirect('/admin/system-users');
            return;
        }

        if ($this->users->deleteSuperadmin($id)) {
            Session::flash('success', 'Administrador del sistema eliminado correctamente.');
        } else {
            Session::flash('error', 'No se pudo eliminar el administrador solicitado.');
        }

        redirect('/admin/system-users');
    }

    private function showForm(?array $user): void
    {
        $this->adminView('admin/system_user_form', [
            'title' => $user === null ? 'Nuevo Administrador del Sistema | Sistema NFC' : 'Editar Administrador | Sistema NFC',
            'user' => $user ?? [
                'name' => '',
                'email' => '',
                'status' => 'active',
            ],
            'isEditing' => $user !== null,
            'currentUserId' => (int)Session::get('user_id'),
        ]);
    }

    private function validatedData(Request $request, bool $isEditing = false): ?array
    {
        $name = trim((string)$request->post('name', ''));
        $email = trim((string)$request->post('email', ''));
        $status = (string)$request->post('status', 'active');
        $password = (string)$request->post('password', '');

        if ($name === '' || mb_strlen($name) > 100) {
            Session::flash('error', 'Indica un nombre válido (máximo 100 caracteres).');
            return null;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150) {
            Session::flash('error', 'Indica un correo electrónico válido (máximo 150 caracteres).');
            return null;
        }

        if (!in_array($status, ['active', 'inactive'], true)) {
            Session::flash('error', 'El estado seleccionado no es válido.');
            return null;
        }

        if (!$isEditing && strlen($password) < 12) {
            Session::flash('error', 'La contraseña para un nuevo SuperAdmin debe tener al menos 12 caracteres.');
            return null;
        }

        if ($isEditing && $password !== '' && strlen($password) < 12) {
            Session::flash('error', 'La nueva contraseña debe tener al menos 12 caracteres.');
            return null;
        }

        return [
            'name' => $name,
            'email' => $email,
            'status' => $status,
            'password' => $password !== '' ? $password : null,
        ];
    }
}
