<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Session;
use App\Models\Company;

final class CompanyPortalController extends BaseController
{
    private Company $companies;

    public function __construct()
    {
        $this->companies = new Company();
    }

    public function dashboard(Request $request): void
    {
        if (!$this->requireRole('company_admin')) {
            return;
        }

        $companyId = (int)Session::get('company_id', 0);
        $company = $this->companies->portalSummary($companyId);
        if ($company === null || $company['status'] !== 'active') {
            Session::destroy();
            http_response_code(403);
            echo 'La empresa asociada no está activa.';
            return;
        }

        $this->view('company/dashboard', [
            'title' => 'Panel de empresa | Sistema NFC',
            'userName' => Session::get('user_name'),
            'userEmail' => Session::get('user_email'),
            'company' => $company,
        ], 'company');
    }
}
