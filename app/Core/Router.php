<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    private array $routes = [];

    public function get(string $path, array|callable $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, array|callable $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    public function add(string $method, string $path, array|callable $handler): void
    {
        $normalized = '/' . trim($path, '/');
        $this->routes[strtoupper($method)][$normalized] = $handler;
    }

    public function dispatch(Request $request): void
    {
        $method = $request->method();
        $uri = $request->uri();

        $handler = $this->routes[$method][$uri] ?? null;

        if ($handler === null) {
            http_response_code(404);
            View::render('errors/404', ['uri' => $uri]);
            return;
        }

        if (is_callable($handler)) {
            call_user_func($handler, $request);
            return;
        }

        if (is_array($handler)) {
            [$controllerClass, $action] = $handler;

            if (!class_exists($controllerClass)) {
                http_response_code(500);
                echo "Controlador {$controllerClass} no encontrado.";
                return;
            }

            $controller = new $controllerClass();

            if (!method_exists($controller, $action)) {
                http_response_code(500);
                echo "Acción {$action} no existe en el controlador {$controllerClass}.";
                return;
            }

            $controller->$action($request);
        }
    }
}
