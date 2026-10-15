<?php // Ruta: app/Config/Database.php

declare(strict_types=1);

namespace App\Config;

use LogicException;
use PDO;
use RuntimeException;

/**
 * Mantiene una conexion PDO compartida durante la peticion actual.
 * No selecciona ni autoriza tenants: esa responsabilidad pertenece a otra capa.
 */
final class Database
{
    private static ?self $instance = null;

    private readonly PDO $connection;

    /**
     * Abre la conexion MySQL con consultas preparadas nativas.
     *
     * @throws RuntimeException Si no se ha cargado la configuracion.
     * @throws \PDOException Si no se puede establecer la conexion.
     */
    private function __construct()
    {
        foreach (['DB_HOST', 'DB_PORT', 'DB_NAME', 'DB_USER', 'DB_PASSWORD', 'DB_CHARSET'] as $key) {
            if (!isset($_ENV[$key]) || $_ENV[$key] === '') {
                throw new RuntimeException(
                    "Falta {$key}. Cargue bootstrap.php antes de conectar a MySQL."
                );
            }
        }

        $this->connection = new PDO(
            sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                $_ENV['DB_HOST'],
                $_ENV['DB_PORT'],
                $_ENV['DB_NAME'],
                $_ENV['DB_CHARSET']
            ),
            $_ENV['DB_USER'],
            $_ENV['DB_PASSWORD'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_PERSISTENT => false,
            ]
        );
    }

    /**
     * Obtiene la unica instancia; la crea al solicitarla por primera vez.
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Devuelve la conexion para ejecutar consultas y transacciones.
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    /**
     * Impide duplicar la instancia mediante clone.
     */
    private function __clone(): void
    {
    }

    /**
     * Impide serializar la conexion y la instancia Singleton.
     *
     * @return array<string, mixed>
     * @throws LogicException Siempre.
     */
    public function __serialize(): array
    {
        throw new LogicException('La conexion a la base de datos no puede serializarse.');
    }

    /**
     * Impide reconstruir otra instancia mediante unserialize.
     *
     * @param array<string, mixed> $data Datos de serializacion.
     * @throws LogicException Siempre.
     */
    public function __unserialize(array $data): void
    {
        throw new LogicException('La conexion a la base de datos no puede deserializarse.');
    }
}
