<?php

declare(strict_types=1);

namespace App\Core;

final class App
{
    public function __construct(
        private readonly Router $router,
        private readonly Request $request
    ) {}

    public function run(): void
    {
        $this->router->dispatch($this->request);
    }
}
