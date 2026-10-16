<?php

declare(strict_types=1);

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\CompaniesController;
use App\Controllers\CompanyPortalController;
use App\Controllers\HomeController;
use App\Controllers\SystemUsersController;
use App\Controllers\UsersController;
use App\Core\Router;

/** @var Router $router */

// Landing Page pública
$router->get('/', [HomeController::class, 'index']);

// Autenticación
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout']);

// Panel de Administración (SuperAdmin)
$router->get('/admin/dashboard', [AdminController::class, 'dashboard']);

// CRUD de empresas y usuarios administradores de empresa
$router->get('/admin/companies', [CompaniesController::class, 'index']);
$router->get('/admin/companies/new', [CompaniesController::class, 'new']);
$router->get('/admin/companies/edit', [CompaniesController::class, 'edit']);
$router->post('/admin/companies/store', [CompaniesController::class, 'store']);
$router->post('/admin/companies/update', [CompaniesController::class, 'update']);
$router->post('/admin/companies/delete', [CompaniesController::class, 'delete']);

$router->get('/admin/users', [UsersController::class, 'index']);
$router->get('/admin/users/new', [UsersController::class, 'new']);
$router->get('/admin/users/edit', [UsersController::class, 'edit']);
$router->post('/admin/users/store', [UsersController::class, 'store']);
$router->post('/admin/users/update', [UsersController::class, 'update']);
$router->post('/admin/users/delete', [UsersController::class, 'delete']);

// CRUD de administradores del sistema (SuperAdmin únicamente)
$router->get('/admin/system-users', [SystemUsersController::class, 'index']);
$router->get('/admin/system-users/new', [SystemUsersController::class, 'new']);
$router->get('/admin/system-users/edit', [SystemUsersController::class, 'edit']);
$router->post('/admin/system-users/store', [SystemUsersController::class, 'store']);
$router->post('/admin/system-users/update', [SystemUsersController::class, 'update']);
$router->post('/admin/system-users/delete', [SystemUsersController::class, 'delete']);

// Panel básico para el usuario administrador de cada empresa
$router->get('/company/dashboard', [CompanyPortalController::class, 'dashboard']);
