<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Models\Company;
use PDOException;

final class CompaniesController extends BaseController
{
    private Company $companies;

    public function __construct()
    {
        $this->companies = new Company();
    }

    public function index(Request $request): void
    {
        if (!$this->requireSuperAdmin()) {
            return;
        }

        $this->adminView('admin/companies', [
            'title' => 'Empresas | Sistema NFC',
            'companies' => $this->companies->all(),
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

        $company = $this->companies->find((int)$request->get('id', 0));
        if ($company === null) {
            http_response_code(404);
            echo 'Empresa no encontrada.';
            return;
        }

        $this->showForm($company);
    }

    public function store(Request $request): void
    {
        if (!$this->requireSuperAdmin() || !$this->verifyCsrf($request)) {
            return;
        }

        $data = $this->validatedData($request);
        if ($data === null) {
            redirect('/admin/companies/new');
            return;
        }

        try {
            $this->companies->create($data);
            \App\Core\Session::flash('success', 'Empresa creada correctamente. Ahora puedes asignarle su usuario administrador.');
        } catch (PDOException $exception) {
            if ($exception->getCode() !== '23000') {
                throw $exception;
            }
            \App\Core\Session::flash('error', 'Ya existe una empresa con ese identificador.');
            redirect('/admin/companies/new');
            return;
        }

        redirect('/admin/companies');
    }

    public function update(Request $request): void
    {
        if (!$this->requireSuperAdmin() || !$this->verifyCsrf($request)) {
            return;
        }

        $id = (int)$request->post('id', 0);
        if ($this->companies->find($id) === null) {
            http_response_code(404);
            echo 'Empresa no encontrada.';
            return;
        }

        $data = $this->validatedData($request, $id);
        if ($data === null) {
            redirect('/admin/companies/edit?id=' . $id);
            return;
        }

        try {
            $this->companies->update($id, $data);
            \App\Core\Session::flash('success', 'Empresa actualizada correctamente.');
        } catch (PDOException $exception) {
            if ($exception->getCode() !== '23000') {
                throw $exception;
            }
            \App\Core\Session::flash('error', 'Ya existe otra empresa con ese identificador.');
            redirect('/admin/companies/edit?id=' . $id);
            return;
        }

        redirect('/admin/companies');
    }

    public function delete(Request $request): void
    {
        if (!$this->requireSuperAdmin() || !$this->verifyCsrf($request)) {
            return;
        }

        $deleted = $this->companies->delete((int)$request->post('id', 0));
        if (!$deleted) {
            \App\Core\Session::flash('error', 'No se pudo eliminar la empresa. Si tiene tarjetas asociadas, elimínalas o reasígnalas primero.');
        } else {
            \App\Core\Session::flash('success', 'Empresa eliminada. Sus usuarios administradores también fueron eliminados.');
        }

        redirect('/admin/companies');
    }

    private function showForm(?array $company): void
    {
        $this->adminView('admin/company_form', [
            'title' => $company === null ? 'Nueva empresa | Sistema NFC' : 'Editar empresa | Sistema NFC',
            'company' => $company ?? [
                'name' => '',
                'slug' => '',
                'plan_id' => '',
                'subscription_ends_at' => '',
                'status' => 'active',
            ],
            'plans' => $this->companies->plans(),
            'isEditing' => $company !== null,
        ]);
    }

    private function validatedData(Request $request, ?int $exceptId = null): ?array
    {
        $name = trim((string)$request->post('name', ''));
        $slugInput = trim((string)$request->post('slug', ''));
        $slug = $this->slugify($slugInput !== '' ? $slugInput : $name);
        $planInput = (string)$request->post('plan_id', '');
        $planId = $planInput === '' ? null : filter_var($planInput, FILTER_VALIDATE_INT);
        $status = (string)$request->post('status', '');
        $endsAt = trim((string)$request->post('subscription_ends_at', ''));

        if ($name === '' || mb_strlen($name) > 150 || $slug === '' || strlen($slug) > 100) {
            \App\Core\Session::flash('error', 'Indica un nombre válido y un slug de hasta 100 caracteres.');
            return null;
        }

        if ($this->companies->slugExists($slug, $exceptId)) {
            \App\Core\Session::flash('error', 'Ese slug ya está asignado a otra empresa.');
            return null;
        }

        if ($planInput !== '' && ($planId === false || !$this->companies->planExists((int)$planId))) {
            \App\Core\Session::flash('error', 'Selecciona un plan vigente.');
            return null;
        }

        if (!in_array($status, ['active', 'suspended_payment', 'inactive'], true)) {
            \App\Core\Session::flash('error', 'El estado seleccionado no es válido.');
            return null;
        }

        if ($endsAt !== '') {
            $date = \DateTimeImmutable::createFromFormat('!Y-m-d', $endsAt);
            if (!$date || $date->format('Y-m-d') !== $endsAt) {
                \App\Core\Session::flash('error', 'La fecha de vencimiento no es válida.');
                return null;
            }
        }

        return [
            'name' => $name,
            'slug' => $slug,
            'plan_id' => $planId === false ? null : $planId,
            'subscription_ends_at' => $endsAt !== '' ? $endsAt : null,
            'status' => $status,
        ];
    }

    private function slugify(string $value): string
    {
        $ascii = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
        $slug = strtolower($ascii === false ? $value : $ascii);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug) ?? '';

        return trim($slug, '-');
    }
}
