<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Session;
use App\Models\User;
use PDOException;

final class UsersController extends BaseController
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

        $this->adminView('admin/users', [
            'title' => 'Usuarios de empresa | Sistema NFC',
            'users' => $this->users->companyAdmins(),
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

        $user = $this->users->companyAdminById((int)$request->get('id', 0));
        if ($user === null) {
            http_response_code(404);
            echo 'Usuario de empresa no encontrado.';
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
            redirect('/admin/users/new');
            return;
        }

        try {
            $this->users->createCompanyAdmin(
                $data['company_id'],
                $data['name'],
                $data['email'],
                $data['password']
            );
            Session::flash('success', 'Usuario administrador de empresa creado correctamente.');
        } catch (PDOException $exception) {
            if ($exception->getCode() !== '23000') {
                throw $exception;
            }
            Session::flash('error', 'Ese correo ya está registrado. Cada empresa solo puede tener un usuario administrador.');
            redirect('/admin/users/new');
            return;
        } catch (\DomainException $exception) {
            Session::flash('error', $exception->getMessage());
            redirect('/admin/users/new');
            return;
        }

        redirect('/admin/users');
    }

    public function update(Request $request): void
    {
        if (!$this->requireSuperAdmin() || !$this->verifyCsrf($request)) {
            return;
        }

        $id = (int)$request->post('id', 0);
        if ($this->users->companyAdminById($id) === null) {
            http_response_code(404);
            echo 'Usuario de empresa no encontrado.';
            return;
        }

        $data = $this->validatedData($request, true);
        if ($data === null) {
            redirect('/admin/users/edit?id=' . $id);
            return;
        }

        try {
            $this->users->updateCompanyAdmin(
                $id,
                $data['name'],
                $data['email'],
                $data['status'],
                $data['password']
            );
            Session::flash('success', 'Usuario actualizado correctamente.');
        } catch (PDOException $exception) {
            if ($exception->getCode() !== '23000') {
                throw $exception;
            }
            Session::flash('error', 'Ese correo ya está registrado por otro usuario.');
            redirect('/admin/users/edit?id=' . $id);
            return;
        }

        redirect('/admin/users');
    }

    public function delete(Request $request): void
    {
        if (!$this->requireSuperAdmin() || !$this->verifyCsrf($request)) {
            return;
        }

        if (!$this->users->deleteCompanyAdmin((int)$request->post('id', 0))) {
            Session::flash('error', 'No se pudo eliminar el usuario solicitado.');
        } else {
            Session::flash('success', 'Usuario eliminado. Las tarjetas asignadas quedaron sin usuario y pueden reasignarse.');
        }

        redirect('/admin/users');
    }

    private function showForm(?array $user): void
    {
        $this->adminView('admin/user_form', [
            'title' => $user === null ? 'Nuevo usuario de empresa | Sistema NFC' : 'Editar usuario | Sistema NFC',
            'user' => $user ?? [
                'name' => '',
                'email' => '',
                'company_id' => '',
                'status' => 'active',
            ],
            'companies' => $this->users->companiesAvailableForAdmin(
                $user === null ? null : (int)$user['company_id']
            ),
            'isEditing' => $user !== null,
        ]);
    }

    private function validatedData(Request $request, bool $isEditing = false): ?array
    {
        $name = trim((string)$request->post('name', ''));
        $email = trim((string)$request->post('email', ''));
        $status = (string)$request->post('status', 'active');
        $password = (string)$request->post('password', '');

        if ($name === '' || mb_strlen($name) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150) {
            Session::flash('error', 'Indica un nombre y un correo electrónico válidos.');
            return null;
        }

        if (!in_array($status, ['active', 'inactive'], true)) {
            Session::flash('error', 'El estado seleccionado no es válido.');
            return null;
        }

        if (($isEditing && $password !== '' && strlen($password) < 12) || (!$isEditing && strlen($password) < 12)) {
            Session::flash('error', 'La contraseña debe tener al menos 12 caracteres.');
            return null;
        }

        if ($isEditing) {
            return [
                'name' => $name,
                'email' => $email,
                'status' => $status,
                'password' => $password !== '' ? $password : null,
            ];
        }

        $companyId = filter_var($request->post('company_id'), FILTER_VALIDATE_INT);
        if ($companyId === false || !in_array((int)$companyId, array_map(
            static fn(array $company): int => (int)$company['id'],
            $this->users->companiesAvailableForAdmin()
        ), true)) {
            Session::flash('error', 'Selecciona una empresa disponible para asignarle su usuario.');
            return null;
        }

        return [
            'company_id' => (int)$companyId,
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ];
    }
}
