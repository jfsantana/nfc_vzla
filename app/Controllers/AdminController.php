<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Session;
use App\Models\User;

final class AdminController extends BaseController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function dashboard(Request $request): void
    {
        if (!$this->requireSuperAdmin()) {
            return;
        }

        $metrics = $this->userModel->getMetrics();

        $this->view('admin/dashboard', [
            'title'       => 'Panel de Control SuperAdmin | Sistema NFC',
            'userName'    => Session::get('user_name'),
            'userEmail'   => Session::get('user_email'),
            'roleName'    => Session::get('role_name'),
            'metrics'     => $metrics,
        ], 'admin');
    }
}
