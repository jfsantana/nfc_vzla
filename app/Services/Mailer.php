<?php

declare(strict_types=1);

namespace App\Services;

final class Mailer
{
    public static function send(string $to, string $subject, string $htmlBody, ?string $replyTo = null): bool
    {
        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=UTF-8',
            'From: Sistema NFC <no-reply@sistemanfc.com>',
            'X-Mailer: PHP/' . PHP_VERSION,
        ];

        if ($replyTo !== null && $replyTo !== '') {
            $headers[] = 'Reply-To: ' . $replyTo;
        }

        $headerString = implode("\r\n", $headers);

        $sent = false;
        try {
            // Suprimir advertencias en caso de que en entorno local WAMP no esté configurado sendmail/SMTP
            $sent = @mail($to, $subject, $htmlBody, $headerString);
        } catch (\Throwable $e) {
            $sent = false;
        }

        // Siempre registrar en el archivo de log para auditoría y desarrollo local
        self::logEmail($to, $subject, $htmlBody, $replyTo, $sent);

        return $sent;
    }

    private static function logEmail(string $to, string $subject, string $htmlBody, ?string $replyTo, bool $sent): void
    {
        $logDir = defined('BASE_PATH') ? BASE_PATH . '/storage/logs' : __DIR__ . '/../../storage/logs';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0777, true);
        }

        $logFile = $logDir . '/mail-' . date('Y-m-d') . '.log';
        $entry = sprintf(
            "[%s] STATUS: %s | TO: %s | REPLY-TO: %s | SUBJECT: %s\nBODY:\n%s\n%s\n",
            date('Y-m-d H:i:s'),
            $sent ? 'SENT' : 'LOCAL_LOG (sendmail unavailable on local host)',
            $to,
            $replyTo ?? 'none',
            $subject,
            strip_tags($htmlBody),
            str_repeat('-', 80)
        );

        @file_put_contents($logFile, $entry, FILE_APPEND);
    }
}
