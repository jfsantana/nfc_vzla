<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Sistema NFC | Tarjetas Inteligentes') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #06b6d4;
            --primary-dark: #0891b2;
            --primary-light: #ecfeff;
            --accent: #2563eb;
            --dark: #0f172a;
            --gray-900: #0f172a;
            --gray-800: #1e293b;
            --gray-700: #334155;
            --gray-600: #475569;
            --gray-400: #94a3b8;
            --gray-200: #e2e8f0;
            --gray-100: #f1f5f9;
            --gray-50: #f8fafc;
            --white: #ffffff;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.07), 0 2px 4px -2px rgb(0 0 0 / 0.05);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.08), 0 4px 6px -4px rgb(0 0 0 / 0.05);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.08);
            --shadow-glow: 0 0 50px -10px rgba(6, 182, 212, 0.25);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--white);
            color: var(--gray-700);
            line-height: 1.6;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .container {
            max-width: 1240px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        /* NAVBAR */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            transition: all 0.3s ease;
        }

        .nav-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 78px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 800;
            font-size: 1.35rem;
            color: var(--dark);
            letter-spacing: -0.02em;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            box-shadow: 0 4px 12px rgba(6, 182, 212, 0.35);
        }

        .logo span {
            color: var(--primary);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 2rem;
            list-style: none;
        }

        .nav-links a {
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--gray-600);
            transition: color 0.2s ease;
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.7rem 1.4rem;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            border: 1px solid transparent;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: var(--white);
            box-shadow: 0 4px 14px rgba(6, 182, 212, 0.35);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(6, 182, 212, 0.45);
        }

        .btn-outline {
            background: transparent;
            color: var(--gray-800);
            border-color: var(--gray-200);
        }

        .btn-outline:hover {
            background: var(--gray-50);
            border-color: var(--gray-400);
            transform: translateY(-1px);
        }

        .btn-dark {
            background: var(--dark);
            color: var(--white);
        }

        .btn-dark:hover {
            background: var(--gray-800);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.2);
        }

        /* BOTÓN HAMBURGUESA */
        .btn-hamburger {
            display: none;
            width: 44px;
            height: 44px;
            padding: 10px 8px;
            background: #f8fafc;
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            cursor: pointer;
            flex-direction: column;
            justify-content: space-around;
            align-items: center;
            transition: all 0.25s ease;
        }

        .btn-hamburger:hover {
            background: #f1f5f9;
            border-color: var(--primary);
        }

        .hamburger-bar {
            width: 22px;
            height: 2.5px;
            background-color: var(--dark);
            border-radius: 999px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform-origin: center;
        }

        /* Animación del ícono hamburguesa a X */
        .btn-hamburger.active .hamburger-bar:nth-child(1) {
            transform: translateY(7px) rotate(45deg);
        }

        .btn-hamburger.active .hamburger-bar:nth-child(2) {
            opacity: 0;
            transform: scaleX(0);
        }

        .btn-hamburger.active .hamburger-bar:nth-child(3) {
            transform: translateY(-7px) rotate(-45deg);
        }

        /* MENÚ MÓVIL DESPLEGABLE */
        .mobile-nav-drawer {
            position: absolute;
            top: 78px;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--gray-200);
            box-shadow: 0 20px 30px -10px rgba(15, 23, 42, 0.12);
            padding: 1.25rem 1.5rem;
            display: none;
            flex-direction: column;
            gap: 0.35rem;
            z-index: 99;
            transform: translateY(-8px);
            opacity: 0;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .mobile-nav-drawer.open {
            display: flex;
            transform: translateY(0);
            opacity: 1;
        }

        .mobile-nav-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.8rem 1.1rem;
            border-radius: 12px;
            font-size: 0.98rem;
            font-weight: 700;
            color: var(--gray-700);
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .mobile-nav-link:hover,
        .mobile-nav-link:active {
            background: rgba(6, 182, 212, 0.08);
            color: var(--primary-dark);
            transform: translateX(4px);
        }

        .mobile-nav-divider {
            height: 1px;
            background: var(--gray-100);
            margin: 0.4rem 0;
        }

        @media (max-width: 868px) {
            .nav-links {
                display: none;
            }

            .btn-hamburger {
                display: flex;
            }

            .nav-actions {
                gap: 0.65rem;
            }

            .btn-contact-nav {
                padding: 0.6rem 1.15rem;
                font-size: 0.88rem;
            }
        }

        @media (max-width: 480px) {
            .nav-inner {
                height: 70px;
            }

            .mobile-nav-drawer {
                top: 70px;
            }

            .logo {
                font-size: 1.15rem;
                gap: 0.5rem;
            }

            .logo-icon {
                width: 34px;
                height: 34px;
            }

            .btn-contact-nav {
                padding: 0.5rem 0.85rem;
                font-size: 0.82rem;
                gap: 0.35rem;
            }

            .btn-contact-nav svg {
                width: 15px;
                height: 15px;
            }

            .btn-hamburger {
                width: 38px;
                height: 38px;
                padding: 8px 6px;
            }

            .hamburger-bar {
                width: 18px;
            }

            .btn-hamburger.active .hamburger-bar:nth-child(1) {
                transform: translateY(6px) rotate(45deg);
            }

            .btn-hamburger.active .hamburger-bar:nth-child(3) {
                transform: translateY(-6px) rotate(-45deg);
            }
        }
    </style>
</head>
<body>
    <header class="navbar">
        <div class="container nav-inner">
            <a href="<?= url('/') ?>" class="logo">
                <div class="logo-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 8.32a7.43 7.43 0 0 1 0 7.36"/>
                        <path d="M9.46 6a11.12 11.12 0 0 1 0 12"/>
                        <path d="M12.91 3.68a14.81 14.81 0 0 1 0 16.64"/>
                        <path d="M16.37 1.36a18.5 18.5 0 0 1 0 21.28"/>
                    </svg>
                </div>
                <span>NFC</span>Connect
            </a>
            
            <ul class="nav-links">
                <li><a href="#inicio">Inicio</a></li>
                <li><a href="#caracteristicas">Características</a></li>
                <li><a href="#perfiles">Perfiles Duales</a></li>
                <li><a href="#metricas">Analíticas</a></li>
                <li><a href="#aliados">Aliados</a></li>
                <li><a href="#contacto">Contáctanos</a></li>
            </ul>

            <div class="nav-actions">
                <a href="#contacto" class="btn btn-primary btn-contact-nav" title="Ir al formulario de contacto">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                    <span>Contáctanos</span>
                </a>

                <button type="button" class="btn-hamburger" id="hamburger-btn" aria-label="Abrir menú" aria-expanded="false">
                    <span class="hamburger-bar"></span>
                    <span class="hamburger-bar"></span>
                    <span class="hamburger-bar"></span>
                </button>
            </div>
        </div>

        <!-- MENÚ MÓVIL DESPLEGABLE -->
        <nav class="mobile-nav-drawer" id="mobile-menu" aria-label="Menú móvil">
            <a href="#inicio" class="mobile-nav-link">
                <span>Inicio</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </a>
            <a href="#caracteristicas" class="mobile-nav-link">
                <span>Características</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </a>
            <a href="#perfiles" class="mobile-nav-link">
                <span>Perfiles Duales</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </a>
            <a href="#metricas" class="mobile-nav-link">
                <span>Analíticas</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </a>
            <a href="#aliados" class="mobile-nav-link">
                <span>Aliados (Venecode)</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </a>
            <a href="#contacto" class="mobile-nav-link">
                <span>Formulario de Contacto</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </a>
            <div class="mobile-nav-divider"></div>
            <a href="<?= url('/login') ?>" class="mobile-nav-link" style="color: var(--primary-dark);">
                <span>Acceso al Panel SaaS</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>
            </a>
        </nav>
    </header>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var hamburgerBtn = document.getElementById('hamburger-btn');
        var mobileMenu = document.getElementById('mobile-menu');

        if (!hamburgerBtn || !mobileMenu) return;

        function toggleMenu() {
            var isOpen = mobileMenu.classList.contains('open');
            if (isOpen) {
                closeMenu();
            } else {
                openMenu();
            }
        }

        function openMenu() {
            mobileMenu.classList.add('open');
            hamburgerBtn.classList.add('active');
            hamburgerBtn.setAttribute('aria-expanded', 'true');
        }

        function closeMenu() {
            mobileMenu.classList.remove('open');
            hamburgerBtn.classList.remove('active');
            hamburgerBtn.setAttribute('aria-expanded', 'false');
        }

        hamburgerBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleMenu();
        });

        // Cerrar menú al hacer clic en cualquier enlace del menú móvil
        var mobileLinks = mobileMenu.querySelectorAll('a');
        mobileLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                closeMenu();
            });
        });

        // Cerrar si se hace clic fuera del menú o del botón hamburguesa
        document.addEventListener('click', function(e) {
            if (!mobileMenu.contains(e.target) && !hamburgerBtn.contains(e.target)) {
                closeMenu();
            }
        });

        // Cerrar con tecla Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeMenu();
            }
        });
    });
    </script>

    <main>
        <?= $content ?>
    </main>
</body>
</html>
