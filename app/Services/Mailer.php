<?php

declare(strict_types=1);

namespace App\Services;

final class Mailer
{
    /**
     * Envía un correo electrónico en formato HTML.
     * Utiliza conexión directa SMTP (ej: Gmail SSL/TLS) y registra bitácora en storage/logs/.
     */
    public static function send(string $to, string $subject, string $htmlBody, ?string $replyTo = null): bool
    {
        $mailerType = strtolower((string)($_ENV['MAIL_MAILER'] ?? 'smtp'));

        if ($mailerType === 'smtp') {
            try {
                return self::sendViaSmtp($to, $subject, $htmlBody, $replyTo);
            } catch (\Throwable $e) {
                self::logEmail($to, $subject, $htmlBody, $replyTo, false, 'SMTP ERROR: ' . $e->getMessage());
            }
        }

        // Respaldo mediante función mail() nativa
        $fallbackSent = self::sendViaNativeMail($to, $subject, $htmlBody, $replyTo);
        self::logEmail($to, $subject, $htmlBody, $replyTo, $fallbackSent, 'Sent via native mail() fallback');

        return $fallbackSent;
    }

    /**
     * Cliente SMTP socket nativo y seguro con autenticación SSL/TLS.
     */
    private static function sendViaSmtp(string $to, string $subject, string $htmlBody, ?string $replyTo): bool
    {
        $host = (string)($_ENV['MAIL_HOST'] ?? 'smtp.gmail.com');
        $port = (int)($_ENV['MAIL_PORT'] ?? 465);
        $encryption = strtolower((string)($_ENV['MAIL_ENCRYPTION'] ?? 'ssl'));
        $username = (string)($_ENV['MAIL_USERNAME'] ?? 'venecode.contact@gmail.com');
        $password = (string)($_ENV['MAIL_PASSWORD'] ?? '');
        $fromAddress = (string)($_ENV['MAIL_FROM_ADDRESS'] ?? 'venecode.contact@gmail.com');
        $fromName = (string)($_ENV['MAIL_FROM_NAME'] ?? 'Sistema NFC');

        // Limpiar espacios de contraseñas de aplicación de Google
        $cleanPassword = str_replace(' ', '', $password);

        $protocol = ($encryption === 'ssl' || $port === 465) ? 'ssl://' : 'tcp://';
        $timeout = 15;

        $socket = @stream_socket_client("{$protocol}{$host}:{$port}", $errno, $errstr, $timeout);
        if (!$socket) {
            throw new \RuntimeException("No se pudo conectar al servidor SMTP {$host}:{$port} ({$errno}: {$errstr})");
        }

        stream_set_timeout($socket, $timeout);

        try {
            self::readExpectedResponse($socket, '220');
            self::sendSmtpCommand($socket, "EHLO localhost", '250');

            // Si es TLS en puerto 587, negociar STARTTLS
            if ($encryption === 'tls' && $port !== 465) {
                self::sendSmtpCommand($socket, "STARTTLS", '220');
                if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                    throw new \RuntimeException("Error al iniciar cifrado TLS con el servidor SMTP.");
                }
                self::sendSmtpCommand($socket, "EHLO localhost", '250');
            }

            // Autenticación
            if ($username !== '' && $cleanPassword !== '') {
                self::sendSmtpCommand($socket, "AUTH LOGIN", '334');
                self::sendSmtpCommand($socket, base64_encode($username), '334');
                self::sendSmtpCommand($socket, base64_encode($cleanPassword), '235');
            }

            // Remitente y destinatario
            self::sendSmtpCommand($socket, "MAIL FROM:<{$fromAddress}>", '250');
            self::sendSmtpCommand($socket, "RCPT TO:<{$to}>", '250');

            // Contenido
            self::sendSmtpCommand($socket, "DATA", '354');

            $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
            $encodedFromName = '=?UTF-8?B?' . base64_encode($fromName) . '?=';

            $headers = [
                "From: {$encodedFromName} <{$fromAddress}>",
                "To: <{$to}>",
                "Subject: {$encodedSubject}",
                "MIME-Version: 1.0",
                "Content-Type: text/html; charset=UTF-8",
                "Content-Transfer-Encoding: base64",
                "Date: " . date(DATE_RFC2822),
                "Message-ID: <" . md5(uniqid((string)time(), true)) . "@sistemanfc.com>",
            ];

            if ($replyTo !== null && $replyTo !== '') {
                $headers[] = "Reply-To: <{$replyTo}>";
            }

            $messageData = implode("\r\n", $headers) . "\r\n\r\n" . chunk_split(base64_encode($htmlBody));
            fputs($socket, $messageData . "\r\n.\r\n");
            self::readExpectedResponse($socket, '250');

            self::sendSmtpCommand($socket, "QUIT", '221');
            fclose($socket);

            self::logEmail($to, $subject, $htmlBody, $replyTo, true, 'Sent via SMTP (' . $host . ')');
            return true;
        } catch (\Throwable $e) {
            if (is_resource($socket)) {
                @fputs($socket, "QUIT\r\n");
                @fclose($socket);
            }
            throw $e;
        }
    }

    private static function sendSmtpCommand($socket, string $command, string $expectedCode): string
    {
        fputs($socket, $command . "\r\n");
        return self::readExpectedResponse($socket, $expectedCode);
    }

    private static function readExpectedResponse($socket, string $expectedCode): string
    {
        $response = '';
        while ($line = fgets($socket, 515)) {
            $response .= $line;
            if (strlen($line) >= 4 && substr($line, 3, 1) === ' ') {
                break;
            }
        }

        $actualCode = substr($response, 0, 3);
        if ($actualCode !== $expectedCode) {
            throw new \RuntimeException("Respuesta inesperada de SMTP: {$response} (se esperaba {$expectedCode})");
        }

        return $response;
    }

    private static function sendViaNativeMail(string $to, string $subject, string $htmlBody, ?string $replyTo): bool
    {
        $fromAddress = (string)($_ENV['MAIL_FROM_ADDRESS'] ?? 'no-reply@sistemanfc.com');
        $fromName = (string)($_ENV['MAIL_FROM_NAME'] ?? 'Sistema NFC');
        $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
        $encodedFromName = '=?UTF-8?B?' . base64_encode($fromName) . '?=';

        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=UTF-8',
            "From: {$encodedFromName} <{$fromAddress}>",
            'X-Mailer: PHP/' . PHP_VERSION,
        ];

        if ($replyTo !== null && $replyTo !== '') {
            $headers[] = 'Reply-To: ' . $replyTo;
        }

        try {
            return (bool)@mail($to, $encodedSubject, $htmlBody, implode("\r\n", $headers));
        } catch (\Throwable) {
            return false;
        }
    }

    private static function logEmail(string $to, string $subject, string $htmlBody, ?string $replyTo, bool $sent, string $details = ''): void
    {
        $logDir = defined('BASE_PATH') ? BASE_PATH . '/storage/logs' : __DIR__ . '/../../storage/logs';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0777, true);
        }

        $logFile = $logDir . '/mail-' . date('Y-m-d') . '.log';
        $entry = sprintf(
            "[%s] STATUS: %s | TO: %s | REPLY-TO: %s | SUBJECT: %s | DETAILS: %s\nBODY:\n%s\n%s\n",
            date('Y-m-d H:i:s'),
            $sent ? 'SENT_OK' : 'FAILED',
            $to,
            $replyTo ?? 'none',
            $subject,
            $details,
            strip_tags($htmlBody),
            str_repeat('-', 80)
        );

        @file_put_contents($logFile, $entry, FILE_APPEND);
    }
}
