<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Session;
use App\Models\Client;
use App\Models\Company;
use PDOException;

final class ClientsController extends BaseController
{
    private Client $clients;
    private Company $companies;

    public function __construct()
    {
        $this->clients = new Client();
        $this->companies = new Company();
    }

    public function index(Request $request): void
    {
        if (!$this->requireSuperAdmin()) {
            return;
        }

        $statusFilter = (string)$request->get('status', '');
        $clients = $this->clients->all($statusFilter !== '' ? $statusFilter : null);
        $counts = $this->clients->countByStatus();

        $this->adminView('admin/clients', [
            'title'        => 'Clientes y Prospectos (Leads) | Sistema NFC',
            'clients'      => $clients,
            'counts'       => $counts,
            'statusFilter' => $statusFilter,
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
        $client = $this->clients->find($id);

        if ($client === null) {
            http_response_code(404);
            echo 'Cliente o prospecto no encontrado.';
            return;
        }

        $this->showForm($client);
    }

    public function store(Request $request): void
    {
        if (!$this->requireSuperAdmin() || !$this->verifyCsrf($request)) {
            return;
        }

        $data = $this->validatedData($request);
        if ($data === null) {
            redirect('/admin/clients/new');
            return;
        }

        try {
            $this->clients->create($data);
            Session::flash('success', 'Cliente registrado correctamente.');
        } catch (PDOException $e) {
            Session::flash('error', 'Error de base de datos al registrar el cliente: ' . $e->getMessage());
            redirect('/admin/clients/new');
            return;
        }

        redirect('/admin/clients');
    }

    public function update(Request $request): void
    {
        if (!$this->requireSuperAdmin() || !$this->verifyCsrf($request)) {
            return;
        }

        $id = (int)$request->post('id', 0);
        $client = $this->clients->find($id);

        if ($client === null) {
            http_response_code(404);
            echo 'Cliente no encontrado.';
            return;
        }

        $data = $this->validatedData($request);
        if ($data === null) {
            redirect('/admin/clients/edit?id=' . $id);
            return;
        }

        try {
            $this->clients->update($id, $data);
            Session::flash('success', 'Ficha del cliente actualizada correctamente.');
        } catch (PDOException $e) {
            Session::flash('error', 'Error al actualizar el cliente: ' . $e->getMessage());
            redirect('/admin/clients/edit?id=' . $id);
            return;
        }

        redirect('/admin/clients');
    }

    public function delete(Request $request): void
    {
        if (!$this->requireSuperAdmin() || !$this->verifyCsrf($request)) {
            return;
        }

        $id = (int)$request->post('id', 0);

        if ($this->clients->delete($id)) {
            Session::flash('success', 'Cliente eliminado del sistema.');
        } else {
            Session::flash('error', 'No se pudo eliminar el cliente solicitado.');
        }

        redirect('/admin/clients');
    }

    private function showForm(?array $client): void
    {
        $this->adminView('admin/client_form', [
            'title'     => $client === null ? 'Nuevo Cliente / Prospecto | Sistema NFC' : 'Editar Cliente | Sistema NFC',
            'client'    => $client ?? [
                'name'                 => '',
                'company_name'         => '',
                'email'                => '',
                'phone'                => '',
                'rif'                  => '',
                'phone_secondary'      => '',
                'address'              => '',
                'notes'                => '',
                'status'               => 'lead',
                'source'               => 'admin',
                'converted_company_id' => null,
            ],
            'companies' => $this->companies->all(),
            'isEditing' => $client !== null,
        ]);
    }

    private function validatedData(Request $request): ?array
    {
        $name = trim((string)$request->post('name', ''));
        $companyName = trim((string)$request->post('company_name', ''));
        $email = trim((string)$request->post('email', ''));
        $phone = trim((string)$request->post('phone', ''));
        $rif = trim((string)$request->post('rif', ''));
        $phoneSecondary = trim((string)$request->post('phone_secondary', ''));
        $address = trim((string)$request->post('address', ''));
        $notes = trim((string)$request->post('notes', ''));
        $status = (string)$request->post('status', 'lead');
        $convertedCompanyId = $request->post('converted_company_id');

        if ($name === '' || mb_strlen($name) > 120) {
            Session::flash('error', 'Indica un nombre de contacto válido (hasta 120 caracteres).');
            return null;
        }

        if ($companyName === '' || mb_strlen($companyName) > 150) {
            Session::flash('error', 'Indica el nombre de la empresa (hasta 150 caracteres).');
            return null;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150) {
            Session::flash('error', 'Indica un correo electrónico válido (hasta 150 caracteres).');
            return null;
        }

        if ($phone === '' || mb_strlen($phone) > 50) {
            Session::flash('error', 'Indica un número de teléfono principal válido.');
            return null;
        }

        $allowedStatuses = ['lead', 'contacted', 'qualified', 'converted', 'discarded'];
        if (!in_array($status, $allowedStatuses, true)) {
            Session::flash('error', 'El estado seleccionado no es válido.');
            return null;
        }

        $companyIdInt = null;
        if ($convertedCompanyId !== null && $convertedCompanyId !== '') {
            $companyIdInt = filter_var($convertedCompanyId, FILTER_VALIDATE_INT);
            if ($companyIdInt === false) {
                $companyIdInt = null;
            }
        }

        return [
            'name'                 => $name,
            'company_name'         => $companyName,
            'email'                => $email,
            'phone'                => $phone,
            'rif'                  => $rif !== '' ? $rif : null,
            'phone_secondary'      => $phoneSecondary !== '' ? $phoneSecondary : null,
            'address'              => $address !== '' ? $address : null,
            'notes'                => $notes !== '' ? $notes : null,
            'status'               => $status,
            'converted_company_id' => $companyIdInt,
        ];
    }
}
