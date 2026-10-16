<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Session;

final class CaptchaService
{
    private const string ALLOWED_CHARS = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
    private const int CODE_LENGTH = 5;

    /**
     * Genera un nuevo código, lo almacena en sesión para la acción indicada
     * y retorna la imagen codificada en formato Data URI (base64).
     */
    public static function generate(string $action = 'default'): string
    {
        Session::start();

        $code = self::randomCode();
        Session::set('captcha_' . $action, $code);

        return self::generateDataUri($code);
    }

    /**
     * Valida el código ingresado por el usuario contra el almacenado en la sesión.
     * Inmediatamente después elimina el código de la sesión para prevenir reusos o ataques de replay.
     */
    public static function validate(string $action, ?string $userInput): bool
    {
        Session::start();

        $sessionKey = 'captcha_' . $action;
        $expected = (string)Session::get($sessionKey, '');

        // Limpiar inmediatamente el captcha para que no pueda reutilizarse
        Session::remove($sessionKey);

        $cleanInput = strtoupper(trim((string)$userInput));

        if ($expected === '' || $cleanInput === '') {
            return false;
        }

        return hash_equals($expected, $cleanInput);
    }

    /**
     * Genera la imagen GD y retorna el Data URI base64.
     */
    public static function generateDataUri(string $code): string
    {
        $rawPng = self::renderPngBytes($code);
        return 'data:image/png;base64,' . base64_encode($rawPng);
    }

    /**
     * Genera la imagen GD y la envía directamente al cliente con headers PNG.
     */
    public static function outputImage(string $action = 'default'): void
    {
        Session::start();
        $code = self::randomCode();
        Session::set('captcha_' . $action, $code);

        $rawPng = self::renderPngBytes($code);

        header('Content-Type: image/png');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        echo $rawPng;
        exit;
    }

    private static function randomCode(): string
    {
        $charsLen = strlen(self::ALLOWED_CHARS);
        $code = '';

        for ($i = 0; $i < self::CODE_LENGTH; $i++) {
            $code .= self::ALLOWED_CHARS[random_int(0, $charsLen - 1)];
        }

        return $code;
    }

    private static function renderPngBytes(string $code): string
    {
        $width = 160;
        $height = 46;

        $image = imagecreatetruecolor($width, $height);

        // Fondo suave
        $bgColor = imagecolorallocate($image, 248, 250, 252);
        imagefilledrectangle($image, 0, 0, $width, $height, $bgColor);

        // Borde
        $borderColor = imagecolorallocate($image, 226, 232, 240);
        imagerectangle($image, 0, 0, $width - 1, $height - 1, $borderColor);

        // Líneas de interferencia sutiles
        $lineColors = [
            imagecolorallocate($image, 203, 213, 225),
            imagecolorallocate($image, 186, 230, 253),
            imagecolorallocate($image, 224, 231, 255),
        ];

        for ($i = 0; $i < 5; $i++) {
            imageline(
                $image,
                random_int(0, $width),
                random_int(0, $height),
                random_int(0, $width),
                random_int(0, $height),
                $lineColors[array_rand($lineColors)]
            );
        }

        // Puntos de ruido
        for ($i = 0; $i < 80; $i++) {
            imagesetpixel(
                $image,
                random_int(0, $width),
                random_int(0, $height),
                $lineColors[array_rand($lineColors)]
            );
        }

        // Colores de los caracteres (tonos oscuros contrastantes acordes al diseño)
        $textColors = [
            imagecolorallocate($image, 14, 116, 144), // Primario oscuro
            imagecolorallocate($image, 37, 99, 235),  // Azul acento
            imagecolorallocate($image, 15, 23, 42),   // Gris oscuro
            imagecolorallocate($image, 3, 105, 161),  // Azul cian
        ];

        $len = strlen($code);
        $charWidth = ($width - 32) / $len;

        for ($i = 0; $i < $len; $i++) {
            $x = 16 + ($i * $charWidth) + random_int(-2, 2);
            $y = random_int(12, 18);
            $charColor = $textColors[array_rand($textColors)];

            // Uso de fuente bitmap nativa GD (5 es la más nítida de tamaño mediano/grande)
            imagestring($image, 5, (int)$x, (int)$y, $code[$i], $charColor);
        }

        ob_start();
        imagepng($image);
        $bytes = (string)ob_get_clean();
        imagedestroy($image);

        return $bytes;
    }
}
