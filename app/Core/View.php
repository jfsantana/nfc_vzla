<?php

declare(strict_types=1);

namespace App\Core;

final class View
{
    public static function render(string $viewPath, array $data = [], string $layout = 'main'): void
    {
        extract($data, EXTR_SKIP);

        $viewFile = BASE_PATH . '/app/Views/' . trim($viewPath, '/') . '.php';

        if (!file_exists($viewFile)) {
            http_response_code(500);
            echo "Vista [{$viewPath}] no encontrada.";
            return;
        }

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        if ($layout === '') {
            echo $content;
            return;
        }

        $layoutFile = BASE_PATH . '/app/Views/layouts/' . $layout . '.php';
        if (file_exists($layoutFile)) {
            require $layoutFile;
        } else {
            echo $content;
        }
    }
}
