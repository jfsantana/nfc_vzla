<?php // Ruta: app/Services/Mailer.php

declare(strict_types=1);

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use RuntimeException;

/**
 * Envia correo autenticado mediante SMTP sin ocultar errores de transporte.
 */
final class Mailer
{
    /**
     * Envia un mensaje HTML con alternativa de texto plano.
     *
     * @throws RuntimeException Si la configuracion SMTP esta incompleta.
     * @throws \PHPMailer\PHPMailer\Exception Si el envio falla.
     */
    public function send(
        string $recipient,
        string $subject,
        string $html,
        string $plainText
    ): void {
        foreach (['MAIL_HOST', 'MAIL_PORT', 'MAIL_USERNAME', 'MAIL_PASSWORD', 'MAIL_ENCRYPTION', 'MAIL_FROM_ADDRESS', 'MAIL_FROM_NAME'] as $key) {
            if (!isset($_ENV[$key]) || $_ENV[$key] === '') {
                throw new RuntimeException("Configure {$key} antes de enviar correo.");
            }
        }

        $port = filter_var($_ENV['MAIL_PORT'], FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1, 'max_range' => 65535],
        ]);

        if ($port === false) {
            throw new RuntimeException('MAIL_PORT debe estar entre 1 y 65535.');
        }

        $encryption = match ($_ENV['MAIL_ENCRYPTION']) {
            'tls' => PHPMailer::ENCRYPTION_STARTTLS,
            'ssl' => PHPMailer::ENCRYPTION_SMTPS,
            default => throw new RuntimeException('MAIL_ENCRYPTION debe ser tls o ssl.'),
        };

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->CharSet = PHPMailer::CHARSET_UTF8;
        $mail->Host = $_ENV['MAIL_HOST'];
        $mail->Port = $port;
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['MAIL_USERNAME'];
        $mail->Password = $_ENV['MAIL_PASSWORD'];
        $mail->SMTPSecure = $encryption;
        $mail->Timeout = 15;
        $mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
        $mail->addAddress($recipient);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $html;
        $mail->AltBody = $plainText;
        $mail->send();
    }
}
