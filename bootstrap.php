<?php // Ruta: bootstrap.php

declare(strict_types=1);

use Dotenv\Dotenv;

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Wamp puede proporcionar variables externas solo a traves de $_SERVER.
foreach ([
    'APP_ENV', 'DB_HOST', 'DB_PORT', 'DB_NAME', 'DB_USER', 'DB_PASSWORD', 'DB_CHARSET',
    'MAIL_HOST', 'MAIL_PORT', 'MAIL_USERNAME', 'MAIL_PASSWORD', 'MAIL_ENCRYPTION',
    'MAIL_FROM_ADDRESS', 'MAIL_FROM_NAME',
] as $key) {
    if (!isset($_ENV[$key]) && isset($_SERVER[$key])) {
        $_ENV[$key] = $_SERVER[$key];
    }
}

$dotenv->required([
    'DB_HOST', 'DB_PORT', 'DB_NAME', 'DB_USER', 'DB_PASSWORD', 'DB_CHARSET',
])->notEmpty();
$dotenv->required('DB_PORT')->isInteger();

if ((int) $_ENV['DB_PORT'] < 1 || (int) $_ENV['DB_PORT'] > 65535) {
    throw new RuntimeException('DB_PORT debe estar entre 1 y 65535.');
}

foreach (['DB_HOST', 'DB_NAME', 'DB_CHARSET'] as $key) {
    if (preg_match('/^[a-zA-Z0-9_.-]+$/D', $_ENV[$key]) !== 1) {
        throw new RuntimeException("Valor no valido para {$key}.");
    }
}
