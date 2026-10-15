<?php

declare(strict_types=1);

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Core\Router;

/** @var Router $router */

// Landing Page pública
$router->get('/', [HomeController::class, 'index']);

// Autenticación
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);

// Panel de Administración (SuperAdmin)
$router->get('/admin/dashboard', [AdminController::class, 'dashboard']);
