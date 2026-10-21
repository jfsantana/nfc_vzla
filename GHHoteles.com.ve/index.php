<?php
/**
 * Landing Page NFC - GH Hoteles
 * Cadena de Hoteles: Valencia, Araure y Aruba
 * "¡Nuestra estrella eres tú!"
 */

// Configuración general y enlaces
$company = [
    'name' => 'GH Hoteles',
    'slogan_script' => 'Nuestra',
    'slogan_serif_estrella' => 'Estrella',
    'slogan_serif_resto' => 'eres tú',
    'claim' => 'Cadena de hoteles Valencia, Araure y Aruba',
    'badge' => '¡Nuestra estrella eres tú!',
    'logo' => 'media/logoGH color balnco trasnaprente.png',
    'logo_alt' => 'GH Hoteles - Valencia · Araure · Aruba',
    'slogan_img' => 'media/NuetsraEstrellaEresTu.png',
    'website' => 'https://ghhoteles.com.ve/',
    'whatsapp_general' => 'https://api.whatsapp.com/send?phone=584144167029',
    'whatsapp_eventos' => 'https://api.whatsapp.com/send/?phone=584148829942&text&type=phone_number&app_absent=0',
    'dossier_cadena' => 'https://drive.google.com/file/d/1RhgdVvTNMvaZBXQgiNBhBgn2wy3osCaw/view',
    'aliados' => 'https://drive.google.com/file/d/1nAPXBFRmZS9nyQyknQHEUJ6WDVCzPIs_/view',
    'ig_hoteles' => 'https://www.instagram.com/ghhoteles/',
    'ig_suites' => 'https://www.instagram.com/hotelghsuites?igsh=cjh6MmtoaXFqbmYy',
];

$destinos = [
    [
        'title' => 'GH Guaparo Inn',
        'city' => 'Valencia, Carabobo',
        'badge' => 'Reel Oficial',
        'url' => 'https://www.instagram.com/reels/DP2Qv8uj3Fh/',
        'icon' => 'inn',
        'desc' => 'Confort y excelencia hotelera en el corazón empresarial de Valencia.'
    ],
    [
        'title' => 'GH Guaparo Suites',
        'city' => 'Valencia, Carabobo',
        'badge' => 'Reel Oficial',
        'url' => 'https://www.instagram.com/reels/C2iip_MPE-H/',
        'icon' => 'suites',
        'desc' => 'Suites modernas, exclusividad y comodidad para estadías prolongadas.'
    ],
    [
        'title' => 'GH Buenaventura',
        'city' => 'Araure, Portuguesa',
        'badge' => 'Reel Oficial',
        'url' => 'https://www.instagram.com/reels/DXIIxhDD1oe/',
        'icon' => 'buenaventura',
        'desc' => 'Ubicación privilegiada en Acarigua-Araure, servicios de primera y eventos.'
    ],
    [
        'title' => 'Voco Surfside Aruba',
        'city' => 'Surfside Beach, Aruba',
        'badge' => 'by IHG · Sitio Web',
        'url' => 'https://tottaruba.com/',
        'icon' => 'aruba',
        'desc' => 'Experiencia internacional frente al mar Caribe con el sello IHG.'
    ]
];

// Imágenes de fondo aleatorias (guardadas en local para carga instantánea y evitar bloqueos)
$background_images = [
    'media/bg1.jpg',
    'media/bg2.png',
    'media/bg3.jpg',
    'media/bg4.jpg',
    'media/bg5.jpg'
];

$selected_bg = $background_images[array_rand($background_images)];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?php echo htmlspecialchars($company['name']); ?> — ¡Nuestra estrella eres tú!</title>
    <meta name="description" content="Cadena de hoteles Valencia, Araure y Aruba. ¡Nuestra estrella eres tú! Conoce nuestras sedes, reserva eventos y contáctanos.">
    <meta name="theme-color" content="#121316">

    <!-- Favicon / Iconos de pestaña y dispositivos -->
    <link rel="icon" type="image/webp" href="media/logoGH.webp">
    <link rel="icon" type="image/png" href="media/logoGH color balnco trasnaprente.png">
    <link rel="apple-touch-icon" href="media/logoGH.webp">

    <!-- Open Graph / Meta para compartir vía NFC/WhatsApp -->
    <meta property="og:title" content="GH Hoteles — ¡Nuestra estrella eres tú!">
    <meta property="og:description" content="Cadena de hoteles Valencia, Araure y Aruba. Contacto rápido, reservas y destinos.">
    <meta property="og:image" content="<?php echo htmlspecialchars($company['logo']); ?>">
    <meta property="og:type" content="website">

    <!-- Google Fonts: Playfair Display + Cormorant Garamond + Dancing Script + Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Dancing+Script:wght@600;700&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-dark: #121316;
            --bg-dark-rgb: 18, 19, 22;
            --bg-card: rgba(28, 29, 34, 0.75);
            --bg-card-hover: rgba(38, 40, 48, 0.9);
            --gold-primary: #b89768;
            --gold-light: #d6b88d;
            --gold-dark: #917145;
            --gold-gradient: linear-gradient(135deg, #d6b88d 0%, #b89768 50%, #917145 100%);
            --gold-glow: rgba(184, 151, 104, 0.25);
            --text-light: #f5f5f7;
            --text-muted: #a6a7ad;
            --border-glass: rgba(255, 255, 255, 0.08);
            --border-gold: rgba(184, 151, 104, 0.35);
            --whatsapp-green: #25D366;
            --instagram-pink: #E1306C;
            --radius-xl: 20px;
            --radius-lg: 16px;
            --radius-pill: 50px;
            --shadow-subtle: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
            --shadow-glow: 0 8px 25px rgba(184, 151, 104, 0.2);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-light);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            position: relative;
            overflow-x: hidden;
            padding: 0;
            background-image: 
                radial-gradient(circle at 50% 0%, rgba(184, 151, 104, 0.15), transparent 45%),
                radial-gradient(circle at 10% 40%, rgba(184, 151, 104, 0.08), transparent 35%),
                radial-gradient(circle at 90% 80%, rgba(184, 151, 104, 0.06), transparent 40%),
                linear-gradient(180deg, #0d0e10 0%, #15161a 50%, #0d0e10 100%);
            background-attachment: fixed;
        }

        /* Contenedor optimizado para pantalla móvil / NFC */
        .nfc-container {
            width: 100%;
            max-width: 480px;
            min-height: 100vh;
            padding: 28px 20px 48px 20px;
            display: flex;
            flex-direction: column;
            position: relative;
            z-index: 2;
        }

        /* Elementos decorativos de fondo */
        .ambient-light {
            position: fixed;
            top: -100px;
            left: 50%;
            transform: translateX(-50%);
            width: 360px;
            height: 360px;
            background: radial-gradient(circle, rgba(184, 151, 104, 0.18) 0%, transparent 70%);
            filter: blur(40px);
            pointer-events: none;
            z-index: 1;
        }

        /* Capa de fondo fotográfica difuminada y transparente */
        .bg-photo-layer {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            opacity: 0.45;
            filter: blur(6px) saturate(125%);
            transform: scale(1.08); /* Evita bordes blancos al aplicar blur */
            pointer-events: none;
            z-index: 0;
            transition: opacity 0.5s ease-in-out;
        }

        .bg-overlay-gradient {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: radial-gradient(circle at 50% 25%, rgba(18, 19, 22, 0.35) 0%, rgba(18, 19, 22, 0.85) 100%);
            pointer-events: none;
            z-index: 1;
        }

        /* HEADER & LOGO */
        .header {
            text-align: center;
            padding-bottom: 24px;
            position: relative;
        }

        .logo-wrapper {
            display: inline-block;
            margin-bottom: 16px;
            position: relative;
            padding: 6px;
            transition: transform 0.3s ease;
        }

        .logo-wrapper:active {
            transform: scale(0.97);
        }

        .logo-img {
            max-width: 190px;
            height: auto;
            display: block;
            margin: 0 auto;
            filter: drop-shadow(0 4px 14px rgba(0,0,0,0.4));
        }

        /* Frase idéntica al diseño del Home web */
        .signature-phrase {
            margin: 8px auto 14px;
            text-align: center;
            user-select: none;
            position: relative;
            max-width: 320px;
        }

        .signature-img {
            width: 100%;
            max-width: 310px;
            height: auto;
            display: block;
            margin: 0 auto;
            /* Filtro para invertir la imagen negra a blanco brillante */
            filter: invert(1) brightness(1.2) drop-shadow(0 4px 16px rgba(0, 0, 0, 0.7));
            transition: transform 0.3s ease;
        }

        .chain-claim {
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 2.5px;
            color: var(--gold-light);
            font-weight: 500;
            margin-top: 10px;
            opacity: 0.95;
        }

        .chain-divider {
            width: 45px;
            height: 2px;
            background: var(--gold-gradient);
            margin: 12px auto 0;
            border-radius: 2px;
            box-shadow: 0 0 10px var(--gold-glow);
        }

        /* ACCIONES PRINCIPALES (HERO CTA) */
        .hero-actions {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 24px;
        }

        .btn-cta {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-radius: var(--radius-lg);
            text-decoration: none;
            color: #ffffff;
            font-weight: 600;
            font-size: 0.96rem;
            overflow: hidden;
            transition: all 0.25s cubic-bezier(0.2, 0.8, 0.2, 1);
            border: 1px solid transparent;
            box-shadow: var(--shadow-subtle);
        }

        .btn-cta:active {
            transform: scale(0.985);
        }

        .btn-cta-gold {
            background: linear-gradient(135deg, #c7a778 0%, #b89768 50%, #917145 100%);
            color: #121316;
            box-shadow: var(--shadow-glow);
            border-color: rgba(255, 255, 255, 0.25);
        }

        .btn-cta-gold .btn-subtitle {
            color: rgba(18, 19, 22, 0.8);
        }

        .btn-cta-gold .icon-circle {
            background: rgba(18, 19, 22, 0.15);
            color: #121316;
        }

        .btn-cta-whatsapp {
            background: linear-gradient(135deg, #1f944a 0%, #157338 100%);
            border-color: rgba(37, 211, 102, 0.3);
            box-shadow: 0 8px 24px rgba(37, 211, 102, 0.22);
        }

        .btn-cta-whatsapp .icon-circle {
            background: rgba(255, 255, 255, 0.18);
            color: #ffffff;
        }

        .btn-cta-events {
            background: linear-gradient(135deg, #2c251e 0%, #1d1915 100%);
            border: 1px solid var(--border-gold);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
        }

        .btn-cta-events .icon-circle {
            background: rgba(184, 151, 104, 0.2);
            color: var(--gold-light);
        }

        .btn-content {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .btn-text-group {
            display: flex;
            flex-direction: column;
            text-align: left;
        }

        .btn-title {
            font-size: 0.97rem;
            font-weight: 700;
            letter-spacing: 0.2px;
            line-height: 1.25;
        }

        .btn-subtitle {
            font-size: 0.74rem;
            color: var(--text-muted);
            font-weight: 400;
            margin-top: 2px;
        }

        .icon-circle {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: transform 0.2s;
        }

        .btn-arrow {
            opacity: 0.7;
            transition: transform 0.2s ease;
        }

        .btn-cta:active .btn-arrow,
        .link-card:active .btn-arrow {
            transform: translateX(3px);
        }

        /* SECCIONES */
        .section-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 22px 0 12px;
            padding: 0 4px;
        }

        .section-tag {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--gold-light);
        }

        .section-line {
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, rgba(184, 151, 104, 0.4), transparent);
        }

        /* TARJETAS DE DESTINOS / REELS */
        .destinos-grid {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .destino-card {
            background: var(--bg-card);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--border-glass);
            border-radius: var(--radius-lg);
            padding: 14px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            color: var(--text-light);
            transition: all 0.22s ease;
            position: relative;
            overflow: hidden;
        }

        .destino-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 3px;
            height: 100%;
            background: var(--gold-gradient);
            opacity: 0;
            transition: opacity 0.2s;
        }

        .destino-card:hover,
        .destino-card:active {
            background: var(--bg-card-hover);
            border-color: var(--border-gold);
            transform: translateY(-1px);
        }

        .destino-card:hover::before,
        .destino-card:active::before {
            opacity: 1;
        }

        .destino-info {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .destino-avatar {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(184, 151, 104, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gold-light);
            flex-shrink: 0;
        }

        .destino-details h3 {
            font-size: 0.94rem;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 2px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .destino-city {
            font-size: 0.75rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .badge-reel {
            font-size: 0.65rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 10px;
            background: rgba(225, 48, 108, 0.15);
            color: #ff5b94;
            border: 1px solid rgba(225, 48, 108, 0.3);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-ihg {
            font-size: 0.65rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 10px;
            background: rgba(184, 151, 104, 0.18);
            color: var(--gold-light);
            border: 1px solid var(--border-gold);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ENLACES RÁPIDOS / INFORMACIÓN CORPORATIVA */
        .links-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 8px;
        }

        .link-card {
            background: var(--bg-card);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid var(--border-glass);
            border-radius: var(--radius-lg);
            padding: 14px;
            text-decoration: none;
            color: var(--text-light);
            display: flex;
            flex-direction: column;
            gap: 8px;
            transition: all 0.22s ease;
            position: relative;
        }

        .link-card:hover,
        .link-card:active {
            background: var(--bg-card-hover);
            border-color: var(--border-gold);
            transform: translateY(-2px);
        }

        .link-card-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: rgba(184, 151, 104, 0.12);
            color: var(--gold-light);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .link-card-title {
            font-size: 0.84rem;
            font-weight: 600;
            color: #ffffff;
            line-height: 1.25;
        }

        .link-card-desc {
            font-size: 0.7rem;
            color: var(--text-muted);
            margin-top: -3px;
        }

        /* REDES SOCIALES (INSTAGRAM) */
        .social-buttons {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .social-btn {
            background: var(--bg-card);
            border: 1px solid var(--border-glass);
            border-radius: var(--radius-lg);
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            color: #ffffff;
            font-size: 0.88rem;
            font-weight: 500;
            transition: all 0.22s ease;
        }

        .social-btn:hover,
        .social-btn:active {
            background: var(--bg-card-hover);
            border-color: rgba(225, 48, 108, 0.4);
            transform: translateY(-1px);
        }

        .social-btn-content {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .social-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(225, 48, 108, 0.3);
            flex-shrink: 0;
        }

        .social-handle {
            font-size: 0.72rem;
            color: var(--gold-light);
            font-weight: 400;
        }

        /* BOTÓN VCF / GUARDAR CONTACTO NFC */
        .vcf-action {
            margin-top: 18px;
            margin-bottom: 10px;
        }

        .btn-save-contact {
            width: 100%;
            padding: 14px 20px;
            background: transparent;
            border: 1px dashed var(--gold-light);
            border-radius: var(--radius-lg);
            color: var(--gold-light);
            font-family: 'Montserrat', sans-serif;
            font-size: 0.85rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.25s ease;
            text-decoration: none;
        }

        .btn-save-contact:hover,
        .btn-save-contact:active {
            background: rgba(184, 151, 104, 0.12);
            border-style: solid;
            color: #ffffff;
        }

        /* FOOTER */
        .footer {
            margin-top: 28px;
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .footer-logo {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.1rem;
            letter-spacing: 2px;
            color: var(--gold-light);
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .footer-copy {
            font-size: 0.7rem;
            color: #6a6b73;
            line-height: 1.5;
        }

        .nfc-tag-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 12px;
            padding: 4px 12px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.06);
            font-size: 0.68rem;
            color: var(--text-muted);
            letter-spacing: 0.5px;
        }

        .nfc-tag-badge svg {
            color: var(--gold-light);
        }

        /* SVG ICONS INLINE HELPER */
        svg {
            display: block;
        }
    </style>
</head>
<body>

    <div class="bg-photo-layer" style="background-image: url('<?php echo htmlspecialchars($selected_bg); ?>');"></div>
    <div class="bg-overlay-gradient"></div>
    <div class="ambient-light"></div>

    <main class="nfc-container">

        <!-- HEADER CON LOGO BLANCO Y FRASE EMBLEMÁTICA -->
        <header class="header">
            <a href="<?php echo htmlspecialchars($company['website']); ?>" target="_blank" rel="noopener noreferrer" class="logo-wrapper">
                <img src="<?php echo htmlspecialchars($company['logo']); ?>" alt="<?php echo htmlspecialchars($company['logo_alt']); ?>" class="logo-img">
            </a>

            <!-- Frase oficial con diseño gráfico idéntico al del Home Web -->
            <div class="signature-phrase">
                <?php if (!empty($company['slogan_img']) && file_exists(__DIR__ . '/' . $company['slogan_img'])): ?>
                    <img src="<?php echo htmlspecialchars($company['slogan_img']); ?>" alt="Nuestra Estrella eres tú" class="signature-img">
                <?php else: ?>
                    <span class="signature-script"><?php echo htmlspecialchars($company['slogan_script']); ?></span>
                    <span class="signature-serif">
                        <span class="word-estrella"><?php echo htmlspecialchars($company['slogan_serif_estrella']); ?></span>
                        <span class="word-resto"><?php echo htmlspecialchars($company['slogan_serif_resto']); ?></span>
                    </span>
                <?php endif; ?>
            </div>

            <p class="chain-claim"><?php echo htmlspecialchars($company['claim']); ?></p>
            <div class="chain-divider"></div>
        </header>

        <!-- ACCIONES PRINCIPALES (CONTACTO DIRECTO / WEB) -->
        <section class="hero-actions" aria-label="Contacto directo">
            
            <!-- WhatsApp Principal -->
            <a href="<?php echo htmlspecialchars($company['whatsapp_general']); ?>" target="_blank" rel="noopener noreferrer" class="btn-cta btn-cta-whatsapp">
                <div class="btn-content">
                    <div class="icon-circle">
                        <!-- WhatsApp SVG -->
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                        </svg>
                    </div>
                    <div class="btn-text-group">
                        <span class="btn-title">¡Comunícate con Nosotros!</span>
                        <span class="btn-subtitle">Atención directa por WhatsApp</span>
                    </div>
                </div>
                <div class="btn-arrow">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </div>
            </a>

            <!-- Reserva tu evento -->
            <a href="<?php echo htmlspecialchars($company['whatsapp_eventos']); ?>" target="_blank" rel="noopener noreferrer" class="btn-cta btn-cta-events">
                <div class="btn-content">
                    <div class="icon-circle">
                        <!-- Calendar / Event SVG -->
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                            <polygon points="12 14 13.5 17 17 17 14 19 15.5 22 12 20 8.5 22 10 19 7 17 10.5 17"></polygon>
                        </svg>
                    </div>
                    <div class="btn-text-group">
                        <span class="btn-title">Reserva tu Evento</span>
                        <span class="btn-subtitle">Salones, corporativo y celebraciones</span>
                    </div>
                </div>
                <div class="btn-arrow">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </div>
            </a>

            <!-- Sitio Web Oficial -->
            <a href="<?php echo htmlspecialchars($company['website']); ?>" target="_blank" rel="noopener noreferrer" class="btn-cta btn-cta-gold">
                <div class="btn-content">
                    <div class="icon-circle">
                        <!-- Globe SVG -->
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="2" y1="12" x2="22" y2="12"></line>
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                        </svg>
                    </div>
                    <div class="btn-text-group">
                        <span class="btn-title">Sitio Web Oficial</span>
                        <span class="btn-subtitle">ghhoteles.com.ve</span>
                    </div>
                </div>
                <div class="btn-arrow">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </div>
            </a>

        </section>

        <!-- SECCIÓN: NUESTROS HOTELES / REELS -->
        <section aria-label="Nuestros Hoteles">
            <div class="section-header">
                <span class="section-tag">Nuestros Hoteles & Destinos</span>
                <span class="section-line"></span>
            </div>

            <div class="destinos-grid">
                <?php foreach ($destinos as $hotel): ?>
                    <a href="<?php echo htmlspecialchars($hotel['url']); ?>" target="_blank" rel="noopener noreferrer" class="destino-card">
                        <div class="destino-info">
                            <div class="destino-avatar">
                                <?php if ($hotel['icon'] === 'aruba'): ?>
                                    <!-- Icono Palmera / Playa para Aruba -->
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M13 8c0-2.76-2.46-5-5.5-5S2 5.24 2 8h11z"></path>
                                        <path d="M13 7.14A5.82 5.82 0 0 1 16.5 6c3.04 0 5.5 2.24 5.5 5h-11"></path>
                                        <path d="M5.5 10c0 2.76 2.46 5 5.5 5 1.4 0 2.68-.48 3.66-1.28"></path>
                                        <path d="M8 13v8"></path>
                                        <path d="M16 13v8"></path>
                                        <path d="M12 15v6"></path>
                                    </svg>
                                <?php else: ?>
                                    <!-- Icono Hotel / Edificio -->
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 21h18"></path>
                                        <path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"></path>
                                        <path d="M9 7h1"></path>
                                        <path d="M14 7h1"></path>
                                        <path d="M9 11h1"></path>
                                        <path d="M14 11h1"></path>
                                        <path d="M9 15h1"></path>
                                        <path d="M14 15h1"></path>
                                        <path d="M10 21v-3a2 2 0 0 1 4 0v3"></path>
                                    </svg>
                                <?php endif; ?>
                            </div>
                            <div class="destino-details">
                                <h3>
                                    <?php echo htmlspecialchars($hotel['title']); ?>
                                </h3>
                                <div class="destino-city">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    <span><?php echo htmlspecialchars($hotel['city']); ?></span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <?php if ($hotel['icon'] === 'aruba'): ?>
                                <span class="badge-ihg"><?php echo htmlspecialchars($hotel['badge']); ?></span>
                            <?php else: ?>
                                <span class="badge-reel">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor" style="display:inline-block; margin-right:3px; vertical-align:-1px;">
                                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                    </svg>
                                    Reel
                                </span>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- SECCIÓN: DOSSIER & ALIADOS -->
        <section aria-label="Información Corporativa">
            <div class="section-header">
                <span class="section-tag">Conócenos & Aliados</span>
                <span class="section-line"></span>
            </div>

            <div class="links-grid">
                <!-- Conoce nuestra Cadena GH Hoteles -->
                <a href="<?php echo htmlspecialchars($company['dossier_cadena']); ?>" target="_blank" rel="noopener noreferrer" class="link-card">
                    <div class="link-card-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                    </div>
                    <div class="link-card-title">Conoce nuestra Cadena</div>
                    <div class="link-card-desc">Dossier corporativo</div>
                </a>

                <!-- Conoce nuestros Aliados -->
                <a href="<?php echo htmlspecialchars($company['aliados']); ?>" target="_blank" rel="noopener noreferrer" class="link-card">
                    <div class="link-card-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                    <div class="link-card-title">Nuestros Aliados</div>
                    <div class="link-card-desc">Alianzas comerciales</div>
                </a>
            </div>
        </section>

        <!-- SECCIÓN: REDES SOCIALES (INSTAGRAM) -->
        <section aria-label="Redes Sociales">
            <div class="section-header">
                <span class="section-tag">Síguenos en Instagram</span>
                <span class="section-line"></span>
            </div>

            <div class="social-buttons">
                <!-- Instagram GH Hoteles -->
                <a href="<?php echo htmlspecialchars($company['ig_hoteles']); ?>" target="_blank" rel="noopener noreferrer" class="social-btn">
                    <div class="social-btn-content">
                        <div class="social-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                            </svg>
                        </div>
                        <div>
                            <div>Instagram GH Hoteles</div>
                            <span class="social-handle">@ghhoteles</span>
                        </div>
                    </div>
                    <div class="btn-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>

                <!-- Instagram GH Suites -->
                <a href="<?php echo htmlspecialchars($company['ig_suites']); ?>" target="_blank" rel="noopener noreferrer" class="social-btn">
                    <div class="social-btn-content">
                        <div class="social-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                            </svg>
                        </div>
                        <div>
                            <div>Instagram GH Suites</div>
                            <span class="social-handle">@hotelghsuites</span>
                        </div>
                    </div>
                    <div class="btn-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>
            </div>
        </section>

        <!-- BOTÓN CONTACTO VIRTUAL (VCF) PARA GUARDAR DIRECTO EN EL MÓVIL VÍA NFC -->
        <div class="vcf-action">
            <a href="contacto.vcf" download="GH-Hoteles.vcf" class="btn-save-contact">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                    <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
                Guardar Contacto en el Teléfono
            </a>
        </div>

        <!-- FOOTER -->
        <footer class="footer">
            <div class="footer-logo">GH HOTELES</div>
            <p class="footer-copy">
                Valencia · Araure · Aruba<br>
                &copy; <?php echo date('Y'); ?> GH Hoteles. Todos los derechos reservados.
            </p>
            <div class="nfc-tag-badge">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path>
                    <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path>
                </svg>
                <a href="https://nfcvzla.venecode.com/jsantana/" target="_blank" rel="noopener noreferrer" class="link-card-desc">Tarjeta Digital NFC</a>
            </div>
        </footer>

    </main>

</body>
</html>