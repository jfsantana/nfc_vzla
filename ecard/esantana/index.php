<?php
declare(strict_types=1);

$profile = require __DIR__ . '/profile.php';
$escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$contact = $profile['contacts'][0];
$message = 'Hola, estoy con ' . $profile['name'] . '. Encontré su ficha de ayuda. Por favor, comunícate conmigo.';
$whatsapp = static fn (array $person): string => 'https://wa.me/' . preg_replace('/\D/', '', $person['phone']) . '?text=' . rawurlencode($message);
$email = 'mailto:' . $contact['email'] . '?subject=' . rawurlencode('Contacto sobre ' . $profile['name']) . '&body=' . rawurlencode($message);
header('X-Robots-Tag: noindex, nofollow, noarchive');
header('Referrer-Policy: no-referrer');
header('Cache-Control: no-store');

function icon(string $name): string
{
    $paths = [
        'shield' => '<path d="M12 3 4 6v6c0 5 8 9 8 9s8-4 8-9V6l-8-3Z"/><path d="m8 12 3 3 5-6"/>',
        'phone' => '<path d="m5 3 4 1 1 5-3 2a15 15 0 0 0 6 6l2-3 5 1 1 4c0 2-3 3-5 2C9 19 5 15 3 8 2 6 3 3 5 3Z"/>',
        'chat' => '<path d="M21 11a9 9 0 0 1-13 8l-5 2 1-5a9 9 0 1 1 17-5Z"/><path d="M8 11h8m-8 4h5"/>',
        'pin' => '<path d="M20 10c0 6-8 11-8 11S4 16 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="3"/>',
        'arrow' => '<path d="M5 12h14m-5-5 5 5-5 5"/>',
        'heart' => '<path d="M20 5c-3-3-6-1-8 1-2-2-5-4-8-1-4 4 1 9 8 15 7-6 12-11 8-15Z"/>',
        'download' => '<path d="M12 3v12m-5-5 5 5 5-5M4 16v5h16v-5"/>',
        'mail' => '<rect x="3" y="5" width="18" height="14" rx="3"/><path d="m3 6 9 7 9-7"/>',
        'copy' => '<rect x="8" y="8" width="12" height="13" rx="2"/><path d="M16 8V3H3v13h5"/>',
        'info' => '<circle cx="12" cy="12" r="9"/><path d="M12 11v6m0-10v1"/>',
        'medical' => '<path d="M9 3h6v6h6v6h-6v6H9v-6H3V9h6V3Z"/>',
    ];

    return '<svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $paths[$name] . '</svg>';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#193e3c">
    <meta name="robots" content="noindex, nofollow, noarchive">
    <meta name="description" content="Ficha de ayuda: contacto con la familia y orientación para acompañar con calma.">
    <title><?= $escape($profile['short_name']) ?> · Ficha de ayuda</title>
    <link rel="stylesheet" href="styles.css?v=<?= filemtime(__DIR__ . '/styles.css') ?>">
    <link rel="stylesheet" href="../shared/autism-guide.css?v=<?= filemtime(dirname(__DIR__) . '/shared/autism-guide.css') ?>">
    <script src="emergency.js" defer></script>
    <script src="../shared/autism-guide.js?v=<?= filemtime(dirname(__DIR__) . '/shared/autism-guide.js') ?>" defer></script>
</head>
<body>
    <a class="skip-link" href="#contenido">Saltar al contenido</a>
    <header class="site-header">
        <div class="shell header-inner">
            <a class="brand" href="#inicio" aria-label="Cerca, inicio"><?= icon('shield') ?><span>cerca<span class="brand-dot">.</span></span></a>
            <span class="brand-caption">Pequeños enlaces.<br>Grandes cuidados.</span>
            <nav aria-label="Navegación principal">
                <a href="#contactos">Contactar</a>
                <a href="../ayuda-autismo.php" data-autism-guide>Cómo ayudar</a>
                <a href="#datos">Datos importantes</a>
            </nav>
            <span class="header-tag">FICHA DE AYUDA · NFC</span>
        </div>
    </header>

    <main id="contenido" class="shell">
        <div class="main-grid">
            <div class="main-column">
                <section class="hero panel" id="inicio" aria-labelledby="nombre">
                    <div class="hero-top"><span class="pill"><?= icon('shield') ?> Mi red de apoyo</span><span class="small-label">ESTÁS EN EL LUGAR INDICADO</span></div>
                    <div class="hero-content">
                        <div class="hero-copy">
                            <p class="hello">Hola, soy</p>
                            <h1 id="nombre"><?= $escape($profile['short_name']) ?><span class="name-dot">.</span></h1>
                            <p class="full-name"><?= $escape($profile['name']) ?></p>
                        </div>
                        <div class="avatar-art" aria-hidden="true"><div class="orbit orbit-one"></div><div class="orbit orbit-two"></div><span class="art-spark">✦</span><div class="avatar"><?= $escape($profile['initials']) ?><span>UN MUNDO ÚNICO</span></div><div class="art-heart"><?= icon('heart') ?></div></div>
                    </div>
                    <div class="profile-tags">
                        <span class="autism-tag"><span aria-hidden="true">∞</span> Soy Autista</span>
                        <span class="autism-tag"><span aria-hidden="true">∞</span> Soy Sordo</span>
                    </div>
                    <section class="priority-contacts" aria-labelledby="priority-title">
                        <div class="priority-heading"><h2 id="priority-title">Contacta a mi familia ahora</h2></div>
                        <div class="priority-grid">
                            <?php foreach ($profile['contacts'] as $index => $person): ?>
                                <a class="priority-call" href="tel:<?= $escape($person['phone']) ?>" aria-label="Llamar a <?= $escape($person['relationship']) ?>, <?= $escape($person['name']) ?>, <?= $escape($person['phone_display']) ?>">
                                    <span class="priority-order" aria-hidden="true"><?= $index + 1 ?></span>
                                    <span class="priority-person"><span class="priority-label"><?= $escape($person['relationship']) ?> · <?= $escape($person['name']) ?></span><strong class="priority-number"><?= $escape($person['phone_display']) ?></strong></span>
                                    <?= icon('phone') ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </section>
                    <div class="hero-message"><h2>Tu calma puede ayudarme mucho.</h2><p>Si estoy solo o necesito ayuda, quédate cerca y contacta a mi familia. Esta ficha te orienta para dar el primer paso.</p></div>
                    <a class="hero-link" href="../ayuda-autismo.php" data-autism-guide>Así puedes acompañarme <?= icon('arrow') ?></a>
                </section>

                <section class="urgent-notice" aria-labelledby="urgencia"><?= icon('medical') ?><div><h2 id="urgencia">¿Hay peligro inmediato o una urgencia médica?</h2><p>Contacta a los servicios de emergencia de tu localidad. No esperes una respuesta por WhatsApp o correo.</p><?php if ($profile['emergency_phone'] !== ''): ?><a href="tel:<?= $escape($profile['emergency_phone']) ?>">Llamar a emergencias: <?= $escape($profile['emergency_phone']) ?></a><?php else: ?><span class="emergency-pending">Número local de emergencias: pendiente de configurar.</span><?php endif; ?></div></section>

                <section class="personal-note" aria-labelledby="personal-help-title"><?= icon('heart') ?><div><h2 id="personal-help-title">Lo que me ayuda a mí</h2><p><?= $escape($profile['comfort'] ?: 'Pendiente de completar con mi familia: cómo me comunico, qué me tranquiliza y qué situaciones debo evitar.') ?></p></div></section>

                <section id="datos" class="panel medical-panel" aria-labelledby="datos-title">
                    <div class="section-heading"><div><span class="eyebrow">PARA UNA ATENCIÓN MÁS SEGURA</span><h2 id="datos-title">Información importante</h2></div><span class="section-number">01 /</span></div>
                    <p class="section-description">Información que debe confirmar la familia. «Pendiente» no significa que no existan alergias o condiciones médicas.</p>
                    <dl class="medical-grid">
                        <?php foreach ($profile['medical'] as $label => $value): ?>
                            <div><dt><?= $escape($label) ?></dt><dd class="<?= $value === '' ? 'pending' : '' ?>"><?= $escape($value ?: 'Pendiente de confirmar') ?></dd></div>
                        <?php endforeach; ?>
                    </dl>
                    <div class="medical-warning"><?= icon('info') ?><p>No administres medicamentos, alimentos o bebidas sin confirmar con la familia o con personal de emergencias. Esta ficha no sustituye la atención médica.</p></div>
                    <p class="review-date">Revisión familiar: <?= $escape($profile['reviewed_at'] ?: 'pendiente antes de publicar') ?></p>
                </section>
            </div>

            <aside class="side-column" aria-label="Contacto y ubicación">
                <section id="contactos" class="contact-panel" aria-labelledby="contactos-title">
                    <div class="contact-kicker"><span class="contact-dot"></span> EL PASO MÁS IMPORTANTE</div>
                    <h2 id="contactos-title">Mi familia,<br>a un toque de ti.</h2>
                    <p class="contact-intro">Si estoy contigo, avísales ahora.<br> Una llamada es el mejor primer paso.</p>
                    <?php foreach ($profile['contacts'] as $index => $person): ?>
                        <article class="family-contact">
                            <div class="person"><span class="person-avatar" aria-hidden="true"><?= icon('heart') ?></span><div><span class="relationship"><?= $escape($person['relationship']) ?><?= $index === 0 ? ' · Contacto principal' : ' · Contacto alternativo' ?></span><h3><?= $escape($person['name']) ?></h3></div></div>
                            <a class="phone-number" href="tel:<?= $escape($person['phone']) ?>"><?= $escape($person['phone_display']) ?></a>
                            <a class="button button-lime" href="tel:<?= $escape($person['phone']) ?>"><?= icon('phone') ?> Llamar a <?= $escape($person['relationship']) ?> <?= icon('arrow') ?></a>
                            <a class="button button-whatsapp" href="<?= $escape($whatsapp($person)) ?>" target="_blank" rel="noopener noreferrer"><?= icon('chat') ?> Avisar por WhatsApp</a>
                            <div class="contact-minor"><a href="sms:<?= $escape($person['phone']) ?>"><?= icon('chat') ?> Enviar SMS</a><button type="button" class="copy-phone" data-phone="<?= $escape($person['phone']) ?>" hidden><?= icon('copy') ?> Copiar número</button></div>
                            <a class="save-contact" href="<?= $escape($person['vcard']) ?>" download><?= icon('download') ?> Guardar contacto de <?= $escape($person['relationship']) ?></a>
                        </article>
                    <?php endforeach; ?>
                    <?php if (count($profile['contacts']) < 2): ?>
                        <div class="backup-note"><?= icon('info') ?><p><strong>Una segunda persona hace la diferencia.</strong> Contacto alternativo pendiente de configurar.</p></div>
                    <?php endif; ?>
                    <p class="contact-status" id="contact-status" role="status" aria-live="polite"></p>
                </section>

                <section class="panel location-panel" aria-labelledby="ubicacion-title">
                    <span class="feature-icon"><?= icon('pin') ?></span>
                    <h2 id="ubicacion-title">Ayúdales a encontrarnos</h2>
                    <p>Comparte con <?= $escape($contact['relationship']) ?> la ubicación del <strong>dispositivo que estás usando</strong>, solo si estás junto a mí.</p>
                    <div id="location-tools" data-phone="<?= $escape(preg_replace('/\D/', '', $contact['phone'])) ?>" data-message="<?= $escape($message) ?>" hidden>
                        <button class="button button-outline" id="locate" type="button"><?= icon('pin') ?> Obtener mi ubicación</button>
                        <p class="privacy-hint">Te pediremos permiso. Nada se envía automáticamente ni se guarda en esta página.</p>
                        <p id="location-status" class="location-status" role="status" aria-live="polite"></p>
                        <a id="location-share" class="button button-lime" target="_blank" rel="noopener noreferrer" hidden><?= icon('chat') ?> Enviar ubicación por WhatsApp</a>
                        <details class="manual-location"><summary>Prefiero escribir dónde estamos</summary><label for="meeting-point">Lugar o punto de referencia</label><textarea id="meeting-point" rows="3" maxlength="500" placeholder="Ej.: entrada principal del centro comercial, junto a seguridad."></textarea><button type="button" class="button button-outline" id="prepare-location">Preparar mensaje</button><p id="manual-status" role="status"></p><a id="manual-share" class="text-link" target="_blank" rel="noopener noreferrer" hidden>Abrir WhatsApp con el lugar escrito <?= icon('arrow') ?></a></details>
                    </div>
                    <noscript><p class="no-script">Para compartir ubicación, abre WhatsApp desde el botón de contacto y adjunta tu ubicación, o describe el lugar por llamada o SMS.</p></noscript>
                </section>

                <section class="email-panel" aria-labelledby="correo-title"><span class="feature-icon"><?= icon('mail') ?></span><div><h2 id="correo-title">¿No es urgente?</h2><p>También puedes escribir a la familia por correo.</p><a class="text-link" href="<?= $escape($email) ?>">Enviar un correo <?= icon('arrow') ?></a><span class="email-note">Abre tu aplicación de correo. No es un canal de atención inmediata.</span></div></section>
            </aside>
        </div>

        <section class="privacy-panel" aria-labelledby="privacidad-title"><?= icon('shield') ?><div><h2 id="privacidad-title">Cuidar también es proteger su privacidad.</h2><p>Usa esta información únicamente para ayudar. No compartas esta ficha, fotos ni datos del niño en redes sociales. Quien tenga el enlace podrá acceder a la ficha.</p></div><span class="privacy-signature">Con empatía.<br>Con respeto.</span></section>
    </main>
    <dialog id="autism-guide-dialog" class="guide-dialog" aria-labelledby="guide-dialog-title">
        <div class="guide-dialog-header">
            <p id="guide-dialog-title">Guía de acompañamiento</p>
            <form method="dialog"><button class="guide-close" aria-label="Cerrar guía" autofocus><span aria-hidden="true">&times;</span></button></form>
        </div>
        <div class="guide-dialog-content">
            <?php require dirname(__DIR__) . '/shared/autism-guide.php'; ?>
        </div>
    </dialog>
    <footer class="shell footer"><div><a class="brand footer-brand" href="#inicio"><?= icon('shield') ?><span>cerca.</span></a><p>Tecnología que acerca. Personas que cuidan.</p></div><div class="footer-service"><span>Una ficha NFC de <strong>VeneCode · NFC Vzla</strong></span><a href="https://nfcvzla.venecode.com/" target="_blank" rel="noopener noreferrer">Conoce el proyecto <?= icon('arrow') ?></a></div></footer>
    <nav class="mobile-actions" aria-label="Contacto rápido"><a class="button button-lime" href="tel:<?= $escape($contact['phone']) ?>"><span><span class="fixed-action-label"><?= icon('phone') ?> Llamar a <?= $escape($contact['relationship']) ?></span><strong class="fixed-phone"><?= $escape($contact['phone_display']) ?></strong></span></a><a class="button mobile-whatsapp" href="<?= $escape($whatsapp($contact)) ?>" target="_blank" rel="noopener noreferrer"><?= icon('chat') ?> WhatsApp</a></nav>
</body>
</html>
