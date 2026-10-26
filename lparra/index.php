<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#f4f7f8">
    <meta name="description" content="Tarjeta digital de Leidert Parra. Guarda su contacto y conecta con él.">
    <title>Leidert Parra - Tarjeta digital</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --accent: #167d83;
            --accent-dark: #12666b;
            --ink: #1f3038;
            --muted: #697982;
            --line: #e1e8eb;
            --paper: #ffffff;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            min-height: 100%;
        }

        body {
            background:
                radial-gradient(ellipse at 95% 5%, rgba(22, 125, 131, 0.08), transparent 32%),
                linear-gradient(180deg, #f8fafb 0%, #f1f5f6 100%);
            color: var(--ink);
            font-family: 'DM Sans', sans-serif;
            display: flex;
            justify-content: center;
            min-height: 100vh;
        }

        .profile-container {
            width: 100%;
            max-width: 520px;
            padding: 32px 24px calc(108px + env(safe-area-inset-bottom, 0px));
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .profile-container::before {
            content: '';
            position: absolute;
            top: 125px;
            right: -145px;
            width: 230px;
            height: 230px;
            border: 1px solid rgba(22, 125, 131, 0.1);
            border-radius: 50%;
            z-index: -1;
        }

        .logo-container {
            margin-bottom: 25px;
            text-align: center;
        }

        .logo-text {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 2px;
            color: var(--accent-dark);
            text-transform: uppercase;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
        }

        .logo-text i {
            color: var(--accent);
            font-size: 14px;
        }

        .hero-card {
            width: 100%;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 34px 24px 31px;
            border: 1px solid var(--line);
            border-radius: 22px;
            background: var(--paper);
            box-shadow: 0 14px 36px rgba(34, 57, 67, 0.07);
            text-align: center;
        }

        .profile-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 23px;
            padding: 7px 12px;
            border-radius: 999px;
            background: #edf5f5;
            color: var(--accent-dark);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.25px;
            text-transform: uppercase;
        }

        .profile-label i {
            color: var(--accent);
        }

        .avatar {
            width: 104px;
            height: 104px;
            border-radius: 50%;
            background: linear-gradient(145deg, #258f92, #176a75);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'DM Sans', sans-serif;
            font-size: 34px;
            font-weight: 700;
            margin-bottom: 17px;
            border: 5px solid #ffffff;
            box-shadow: 0 6px 18px rgba(23, 106, 117, 0.2);
        }

        .name {
            color: var(--ink);
            font-family: 'DM Sans', sans-serif;
            font-size: clamp(32px, 8vw, 39px);
            font-weight: 700;
            line-height: 1.15;
            margin-bottom: 9px;
        }

        .company {
            font-size: 10px;
            font-weight: 700;
            color: var(--accent-dark);
            text-transform: uppercase;
            letter-spacing: 2.3px;
            margin-bottom: 13px;
        }

        .description {
            max-width: 290px;
            font-size: 14px;
            color: var(--muted);
            text-align: center;
            margin-bottom: 0;
            line-height: 1.7;
            font-weight: 400;
        }

        .btn-save {
            width: 100%;
            background: linear-gradient(105deg, #1d888b, #176a75);
            color: #fff;
            border: none;
            padding: 17px;
            border-radius: 13px;
            font-size: 15px;
            font-weight: 600;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin: 23px 0 30px;
            cursor: pointer;
            box-shadow: 0 7px 18px rgba(23, 106, 117, 0.2);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-save:hover,
        .btn-save:focus-visible {
            transform: translateY(-2px);
            box-shadow: 0 10px 22px rgba(23, 106, 117, 0.28);
        }

        .contacts-heading {
            width: 100%;
            margin: 0 0 13px 4px;
            color: #61737c;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.8px;
            text-transform: uppercase;
        }

        .links-container {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 11px;
        }

        .link-card {
            display: flex;
            align-items: center;
            background: var(--paper);
            padding: 14px 15px;
            border-radius: 14px;
            text-decoration: none;
            color: var(--ink);
            border: 1px solid var(--line);
            box-shadow: 0 4px 12px rgba(34, 57, 67, 0.035);
            transition: all 0.3s ease;
        }

        .link-card:hover {
            background: #fff;
            border-color: rgba(22, 125, 131, 0.42);
            transform: translateY(-1px);
            box-shadow: 0 7px 18px rgba(34, 57, 67, 0.08);
        }

        .link-icon {
            width: 46px;
            height: 46px;
            flex: 0 0 46px;
            border-radius: 13px;
            background: #edf5f5;
            color: var(--accent-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-right: 14px;
        }

        .link-text {
            flex-grow: 1;
            min-width: 0;
        }

        .link-title {
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 3px;
        }

        .link-subtitle {
            font-size: 11px;
            color: var(--muted);
            font-weight: 400;
            overflow-wrap: anywhere;
        }

        .link-arrow {
            color: #9aabb1;
            font-size: 12px;
            transition: color 0.3s ease;
        }

        .link-card:hover .link-arrow {
            color: var(--accent);
        }

        .service-card {
            width: 100%;
            margin-top: 26px;
            padding: 17px 18px 15px;
            border: 1px solid #dce6e8;
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.62);
        }

        .service-heading {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 13px;
        }

        .service-icon {
            width: 35px;
            height: 35px;
            flex: 0 0 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: #eaf1f2;
            color: #55747a;
            font-size: 15px;
        }

        .service-title {
            margin: 1px 0 3px;
            color: #34464d;
            font-size: 13px;
            font-weight: 700;
        }

        .service-description {
            color: var(--muted);
            font-size: 11px;
            line-height: 1.5;
        }

        .service-links {
            display: flex;
            flex-wrap: wrap;
            gap: 8px 17px;
            padding-left: 47px;
        }

        .service-link {
            color: #426c72;
            font-size: 11px;
            font-weight: 600;
            text-decoration: none;
            text-underline-offset: 3px;
        }

        .service-link:hover,
        .service-link:focus-visible {
            color: var(--accent-dark);
            text-decoration: underline;
        }

        .social-circle {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            border: 1px solid #d8e2e5;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent-dark);
            text-decoration: none;
            font-size: 17px;
            transition: all 0.3s ease;
        }

        .social-circle:hover {
            border-color: var(--accent-dark);
            color: #fff;
            background: var(--accent-dark);
            box-shadow: 0 5px 14px rgba(23, 106, 117, 0.2);
        }

        .instagram-float {
            position: fixed;
            right: calc(18px + env(safe-area-inset-right, 0px));
            bottom: calc(18px + env(safe-area-inset-bottom, 0px));
            z-index: 1000;
            display: inline-flex;
            align-items: center;
            gap: 9px;
            min-height: 50px;
            padding: 12px 18px;
            border-radius: 999px;
            background: linear-gradient(110deg, #1d888b, #176a75);
            color: #fff;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            box-shadow: 0 7px 22px rgba(23, 106, 117, 0.25);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .instagram-float i {
            font-size: 21px;
        }

        .instagram-float:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(23, 106, 117, 0.32);
        }

        .instagram-float:focus-visible {
            outline: 3px solid var(--accent);
            outline-offset: 4px;
        }

        .footer {
            margin-top: 27px;
            text-align: center;
            border-top: 1px solid var(--line);
            padding-top: 21px;
            width: 100%;
        }

        .footer-logo {
            font-family: 'DM Sans', sans-serif;
            font-size: 17px;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
        }

        .footer-logo i {
            color: var(--accent);
        }

        .footer-copy {
            font-size: 10px;
            color: #829198;
            margin-top: 8px;
        }

        @media (max-width: 380px) {
            .profile-container {
                padding-right: 17px;
                padding-left: 17px;
            }

            .hero-card {
                padding-right: 18px;
                padding-left: 18px;
            }

            .instagram-float {
                right: 13px;
                bottom: calc(13px + env(safe-area-inset-bottom, 0px));
                padding: 11px 15px;
                font-size: 12px;
            }
        }

    </style>
</head>
<body>

    <div class="profile-container">
        <div class="logo-container">
            <div class="logo-text">
                <i class="fas fa-address-card" aria-hidden="true"></i>
                Perfil digital
            </div>
        </div>

        <section class="hero-card" aria-labelledby="profile-name">
            <div class="profile-label"><i class="fas fa-circle" aria-hidden="true"></i> Información de contacto</div>
            <div class="avatar" aria-hidden="true">LP</div>
            <h1 class="name" id="profile-name">Leidert Parra</h1>
            <div class="company">Contacto personal</div>
            <p class="description">Encuentra aquí mis canales de contacto.</p>
        </section>

        <a href="leidert-parra.vcf" download="leidert-parra.vcf" class="btn-save">
            <i class="fas fa-address-card" aria-hidden="true"></i>
            Guardar contacto
        </a>

        <h2 class="contacts-heading">Conectemos por aquí</h2>
        <div class="links-container">
            <a href="https://wa.me/584122852922" class="link-card" target="_blank" rel="noopener noreferrer">
                <div class="link-icon"><i class="fab fa-whatsapp"></i></div>
                <div class="link-text">
                    <div class="link-title">WhatsApp</div>
                    <div class="link-subtitle">Escríbeme ahora</div>
                </div>
                <div class="link-arrow"><i class="fas fa-chevron-right"></i></div>
            </a>

            <a href="tel:+584122852922" class="link-card">
                <div class="link-icon"><i class="fas fa-phone-alt"></i></div>
                <div class="link-text">
                    <div class="link-title">Llamar</div>
                    <div class="link-subtitle">0412-2852922</div>
                </div>
                <div class="link-arrow"><i class="fas fa-chevron-right"></i></div>
            </a>

            <a href="mailto:leidertparra@gmail.com" class="link-card">
                <div class="link-icon"><i class="far fa-envelope"></i></div>
                <div class="link-text">
                    <div class="link-title">Correo</div>
                    <div class="link-subtitle">leidertparra@gmail.com</div>
                </div>
                <div class="link-arrow"><i class="fas fa-chevron-right"></i></div>
            </a>

            <a href="https://www.instagram.com/leidertparra/" class="link-card" target="_blank" rel="noopener noreferrer">
                <div class="link-icon"><i class="fab fa-instagram"></i></div>
                <div class="link-text">
                    <div class="link-title">Instagram</div>
                    <div class="link-subtitle">@leidertparra</div>
                </div>
                <div class="link-arrow"><i class="fas fa-chevron-right"></i></div>
            </a>
        </div>



        <div class="footer">
            <a href="https://www.instagram.com/leidertparra/" class="social-circle" target="_blank" rel="noopener noreferrer" aria-label="Instagram de Leidert Parra"><i class="fab fa-instagram" aria-hidden="true"></i></a>
            <div class="footer-logo">
                <i class="fas fa-address-card" aria-hidden="true"></i>
                Leidert Parra
            </div>
            <div class="footer-copy">Tarjeta de contacto digital</div>
        </div>
                <aside class="service-card" aria-label="Información sobre tarjetas digitales">
            <div class="service-heading">
                <div class="service-icon"><i class="fas fa-id-card" aria-hidden="true"></i></div>
                <div>
                    <h2 class="service-title">¿Te interesa una tarjeta como esta?</h2>
                    <p class="service-description">Este detalle fue creado por VeneCode · NFC Vzla.</p>
                </div>
            </div>
            <nav class="service-links" aria-label="Contacto de VeneCode">
                <a class="service-link" href="https://nfcvzla.venecode.com/" target="_blank" rel="noopener noreferrer">Conoce el servicio</a>
                <a class="service-link" href="mailto:venecode.contact@gmail.com">Escríbeles</a>
                <a class="service-link" href="https://nfcvzla.venecode.com/jsantana/" target="_blank" rel="noopener noreferrer">Director de ventas</a>
            </nav>
        </aside>

    </div>

    <a href="https://www.instagram.com/leidertparra/"
       class="instagram-float"
       target="_blank"
       rel="noopener noreferrer"
       aria-label="Sígueme en Instagram: @leidertparra (abre en una nueva pestaña)">
        <i class="fab fa-instagram" aria-hidden="true"></i>
        <span>Sígueme en Instagram</span>
    </a>

</body>
</html>