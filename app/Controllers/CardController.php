<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;

final class CardController extends BaseController
{
    public function jsantana(Request $request): void
    {
        $this->renderCardFile('jsantana');
    }

    public function show(Request $request): void
    {
        $uri = trim($request->uri(), '/');
        // Extraer el identificador de la tarjeta
        $cleaned = preg_replace('#^(public/)?(assets/)?(card/|c/)?#i', '', $uri) ?? '';
        $parts = explode('/', $cleaned);
        $slug = $parts[0] ?? 'jsantana';
        $slug = preg_replace('/\.php$/i', '', $slug) ?? 'jsantana';

        $this->renderCardFile($slug);
    }

    private function renderCardFile(string $slug): void
    {
        // Sanitizar el slug para evitar directory traversal
        $safeSlug = preg_replace('/[^a-zA-Z0-9_-]/', '', $slug);
        if ($safeSlug === '') {
            $safeSlug = 'jsantana';
        }

        $cardPath = BASE_PATH . '/public/assets/card/' . $safeSlug . '/index.php';

        if (file_exists($cardPath)) {
            require $cardPath;
            return;
        }

        http_response_code(404);
        echo 'Tarjeta de presentación no encontrada.';
    }
}
