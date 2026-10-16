<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\Session;
use App\Models\Client;
use App\Services\CaptchaService;
use App\Services\Mailer;
use PDOException;

final class HomeController extends BaseController
{
    private const string CONTACT_RECIPIENT = 'venecode.contact@gmail.com';

    public function index(Request $request): void
    {
        $this->view('home/index', [
            'title'        => 'Sistema NFC | Tarjetas Inteligentes Contactless',
            'version'      => ' ' . PHP_VERSION,
            'success'      => Session::flash('contact_success'),
            'error'        => Session::flash('contact_error'),
            'captchaImage' => CaptchaService::generate('contact'),
        ]);
    }

    public function contact(Request $request): void
    {
        if (!$this->verifyCsrf($request)) {
            return;
        }

        $captcha = trim((string)$request->post('captcha', ''));
        if (!CaptchaService::validate('contact', $captcha)) {
            Session::flash('contact_error', 'El código de seguridad (CAPTCHA) es incorrecto o ha vencido. Inténtalo de nuevo.');
            redirect('/#contacto');
            return;
        }

        $name = trim((string)$request->post('name', ''));
        $companyName = trim((string)$request->post('company_name', ''));
        $email = trim((string)$request->post('email', ''));
        $phone = trim((string)$request->post('phone', ''));
        $message = trim((string)$request->post('message', ''));

        if ($name === '' || mb_strlen($name) > 120) {
            Session::flash('contact_error', 'Por favor ingresa tu nombre completo (máximo 120 caracteres).');
            redirect('/#contacto');
            return;
        }

        if ($companyName === '' || mb_strlen($companyName) > 150) {
            Session::flash('contact_error', 'Por favor ingresa el nombre de tu empresa (máximo 150 caracteres).');
            redirect('/#contacto');
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150) {
            Session::flash('contact_error', 'Por favor ingresa un correo electrónico válido.');
            redirect('/#contacto');
            return;
        }

        if ($phone === '' || mb_strlen($phone) > 50) {
            Session::flash('contact_error', 'Por favor ingresa un número de teléfono válido.');
            redirect('/#contacto');
            return;
        }

        try {
            $clientModel = new Client();
            $clientId = $clientModel->createFromContact([
                'name'         => $name,
                'company_name' => $companyName,
                'email'        => $email,
                'phone'        => $phone,
                'notes'        => $message !== '' ? "Mensaje inicial desde el formulario de contacto:\n" . $message : null,
            ]);

            // Enviar notificación por correo a venecode.contact@gmail.com
            $subject = "Nuevo Contacto de Cliente Potencial: {$companyName} ({$name})";
            $htmlBody = $this->buildContactEmailBody([
                'id'           => $clientId,
                'name'         => $name,
                'company_name' => $companyName,
                'email'        => $email,
                'phone'        => $phone,
                'message'      => $message,
                'date'         => date('d/m/Y H:i:s'),
            ]);

            Mailer::send(self::CONTACT_RECIPIENT, $subject, $htmlBody, $email);

            Session::flash('contact_success', '¡Gracias por contactarnos! Tu información fue registrada exitosamente. Nos pondremos en contacto contigo a la brevedad.');
        } catch (PDOException $e) {
            Session::flash('contact_error', 'Ocurrió un error al registrar tu contacto. Por favor inténtalo de nuevo.');
        } catch (\Throwable $e) {
            Session::flash('contact_error', 'No pudimos procesar tu solicitud en este momento. Por favor inténtalo más tarde.');
        }

        redirect('/#contacto');
    }

    private function buildContactEmailBody(array $data): string
    {
        $safeName = htmlspecialchars($data['name'], ENT_QUOTES, 'UTF-8');
        $safeCompany = htmlspecialchars($data['company_name'], ENT_QUOTES, 'UTF-8');
        $safeEmail = htmlspecialchars($data['email'], ENT_QUOTES, 'UTF-8');
        $safePhone = htmlspecialchars($data['phone'], ENT_QUOTES, 'UTF-8');
        $safeMessage = nl2br(htmlspecialchars($data['message'] ?? 'Sin mensaje adicional.', ENT_QUOTES, 'UTF-8'));
        $safeDate = htmlspecialchars($data['date'], ENT_QUOTES, 'UTF-8');
        $clientId = (int)$data['id'];

        return <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Lead / Contacto Comercial</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8fafc; color: #334155; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
        <div style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); padding: 24px 28px; color: #ffffff;">
            <h2 style="margin: 0 0 6px; font-size: 20px; font-weight: 800;">Nuevo Cliente Potencial Registrado</h2>
            <p style="margin: 0; font-size: 13px; opacity: 0.95;">Formulario de contacto de la Landing Page pública</p>
        </div>
        <div style="padding: 28px;">
            <p style="font-size: 14px; color: #475569; margin-top: 0;">Se ha recibido una nueva solicitud de contacto. Los datos ya fueron almacenados automáticamente en la tabla de <strong>Clientes (Leads)</strong> del Sistema NFC.</p>
            <table style="width: 100%; border-collapse: collapse; font-size: 14px; margin-top: 16px;">
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; color: #0f172a; width: 140px;">ID Cliente:</td>
                    <td style="padding: 8px 0; color: #0891b2; font-weight: 700;">#{$clientId}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; color: #0f172a;">Empresa:</td>
                    <td style="padding: 8px 0; font-weight: 600;">{$safeCompany}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; color: #0f172a;">Contacto:</td>
                    <td style="padding: 8px 0;">{$safeName}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; color: #0f172a;">Correo:</td>
                    <td style="padding: 8px 0;"><a href="mailto:{$safeEmail}" style="color: #0284c7; text-decoration: none;">{$safeEmail}</a></td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; color: #0f172a;">Teléfono:</td>
                    <td style="padding: 8px 0;"><a href="tel:{$safePhone}" style="color: #0284c7; text-decoration: none;">{$safePhone}</a></td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; color: #0f172a;">Fecha / Hora:</td>
                    <td style="padding: 8px 0; color: #64748b;">{$safeDate}</td>
                </tr>
            </table>

            <div style="margin-top: 20px; padding: 16px; background: #f8fafc; border-left: 4px solid #06b6d4; border-radius: 6px;">
                <div style="font-size: 12px; font-weight: bold; text-transform: uppercase; color: #64748b; margin-bottom: 6px;">Mensaje o requerimiento:</div>
                <div style="font-size: 14px; line-height: 1.5; color: #1e293b;">{$safeMessage}</div>
            </div>

            <div style="margin-top: 24px; text-align: center;">
                <p style="font-size: 12px; color: #94a3b8; margin: 0;">Este correo fue generado automáticamente por el Sistema SaaS de Tarjetas NFC.</p>
            </div>
        </div>
    </div>
</body>
</html>
HTML;
    }
}
