<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Services\CaptchaService;

final class CaptchaController extends BaseController
{
    private const array ALLOWED_ACTIONS = ['login', 'contact', 'default'];

    public function refresh(Request $request): void
    {
        $action = (string)$request->get('action', 'default');
        if (!in_array($action, self::ALLOWED_ACTIONS, true)) {
            $action = 'default';
        }

        $image = CaptchaService::generate($action);

        $this->json([
            'success' => true,
            'image'   => $image,
        ]);
    }

    public function image(Request $request): void
    {
        $action = (string)$request->get('action', 'default');
        if (!in_array($action, self::ALLOWED_ACTIONS, true)) {
            $action = 'default';
        }

        CaptchaService::outputImage($action);
    }
}
