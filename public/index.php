<?php

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));

if (file_exists(BASE_PATH . '/vendor/autoload.php')) {
    require_once BASE_PATH . '/vendor/autoload.php';
} else {
    // Autoloader fallback PSR-4 simple para cuando aun no se haya ejecutado composer install
    spl_autoload_register(static function (string $class): void {
        $prefix = 'App\\';
        $baseDir = BASE_PATH . '/app/';
        $len = strlen($prefix);

        if (strncmp($prefix, $class, $len) !== 0) {
            return;
        }

        $relativeClass = substr($class, $len);
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

        if (file_exists($file)) {
            require_once $file;
        }
    });

    if (file_exists(BASE_PATH . '/app/Helpers/functions.php')) {
        require_once BASE_PATH . '/app/Helpers/functions.php';
    }
}

$app = require_once BASE_PATH . '/bootstrap/app.php';
$app->run();
