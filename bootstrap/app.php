<?php

declare(strict_types=1);

use App\Core\App;
use App\Core\Request;
use App\Core\Router;

// Cargar variables de entorno del archivo .env si phpdotenv o función nativa
$envFile = BASE_PATH . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        if (str_contains($line, '=')) {
            [$name, $value] = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value, " \t\n\r\0\x0B\"'");
            $_ENV[$name] = $value;
            putenv("{$name}={$value}");
        }
    }
}

// Configurar zona horaria
date_default_timezone_set($_ENV['APP_TIMEZONE'] ?? 'America/Santo_Domingo');

// Inicializar el router y registrar las rutas
$router = new Router();
$request = new Request();

require_once BASE_PATH . '/routes/web.php';

return new App($router, $request);
