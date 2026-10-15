<?php

declare(strict_types=1);

namespace App\Config;

final class Database
{
    public static function config(): array
    {
        return [
            'driver'   => $_ENV['DB_CONNECTION'] ?? 'mysql',
            'host'     => $_ENV['DB_HOST'] ?? '127.0.0.1',
            'port'     => (int)($_ENV['DB_PORT'] ?? 3306),
            'database' => $_ENV['DB_DATABASE'] ?? 'nfc_landing_db',
            'username' => $_ENV['DB_USERNAME'] ?? 'root',
            'password' => $_ENV['DB_PASSWORD'] ?? '',
            'charset'  => $_ENV['DB_CHARSET'] ?? 'utf8mb4',
        ];
    }
}
