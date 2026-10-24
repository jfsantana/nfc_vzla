<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jesus Santana - VeneCode NFC Vzla</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-cyan: #00B4DB;
            --primary-blue: #0083B0;
            --bg-dark: #0a0f18;
            --card-bg: rgba(255, 255, 255, 0.04);
            --card-border: rgba(255, 255, 255, 0.08);
            --text-light: #f8fafc;
            --text-gray: #94a3b8;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: radial-gradient(circle at top, #142238 0%, var(--bg-dark) 100%);
            color: var(--text-light);
            display: flex;
            justify-content: center;
            min-height: 100vh;
        }

        .profile-container {
            width: 100%;
            max-width: 480px;
            padding: 50px 25px;
            padding-bottom: calc(110px + env(safe-area-inset-bottom, 0px));
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .profile-container::before {
            content: '';
            position: absolute;
            top: -100px;
            left: 50%;
            transform: translateX(-50%);
            width: 300px;
            height: 300px;
            background: var(--primary-cyan);
            filter: blur(150px);
            opacity: 0.2;
            z-index: -1;
        }

        .logo-container {
            margin-bottom: 35px;
            text-align: center;
        }

        .logo-text {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 1px;
            color: var(--text-light);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .logo-text i {
            color: var(--primary-cyan);
        }

        .avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-cyan), var(--primary-blue));
            color: var(--text-light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 20px;
            border: 4px solid #142238;
            box-shadow: 0 0 25px rgba(0, 180, 219, 0.4);
        }

        .name {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }

        .company {
            font-size: 13px;
            font-weight: 600;
            color: var(--primary-cyan);
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 12px;
        }

        .description {
            font-size: 14px;
            color: var(--text-gray);
            text-align: center;
            margin-bottom: 35px;
            line-height: 1.6;
            font-weight: 300;
        }

        .btn-save {
            width: 100%;
            background: linear-gradient(to right, var(--primary-cyan), var(--primary-blue));
            color: var(--text-light);
            border: none;
            padding: 18px;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 600;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            margin-bottom: 35px;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(0, 131, 176, 0.4);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(0, 131, 176, 0.6);
        }

        .links-container {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .link-card {
            display: flex;
            align-items: center;
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            padding: 18px;
            border-radius: 14px;
            text-decoration: none;
            color: var(--text-light);
            border: 1px solid var(--card-border);
            transition: all 0.3s ease;
        }

        .link-card:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(0, 180, 219, 0.5);
            transform: translateX(5px);
        }

        .link-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            background: rgba(0, 180, 219, 0.15);
            color: var(--primary-cyan);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-right: 18px;
        }

        .link-text {
            flex-grow: 1;
        }

        .link-title {
            font-weight: 600;
            font-size: 15px;
            margin-bottom: 5px;
        }

        .link-subtitle {
            font-size: 12px;
            color: var(--text-gray);
            font-weight: 300;
        }

        .link-arrow {
            color: rgba(255, 255, 255, 0.3);
            transition: color 0.3s ease;
        }

        /* --- NUEVAS SECCIONES DE ESTADÍSTICAS Y SKILLS --- */

        .section-title {
            font-size: 11px;
            color: var(--text-gray);
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 15px;
            font-weight: 600;
            width: 100%;
            text-align: left;
            margin-top: 40px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            width: 100%;
        }

        .stat-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 14px;
            padding: 20px 15px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .stat-number {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-light);
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 11px;
            color: var(--text-gray);
            line-height: 1.3;
        }

        .skills-flex {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            width: 100%;
        }

        .skill-pill {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: var(--text-light);
            padding: 10px 16px;
            border-radius: 20px;
            font-size: 12px;
            transition: all 0.3s ease;
        }

        .skill-pill:hover {
            border-color: var(--primary-cyan);
            background: rgba(0, 180, 219, 0.1);
        }

        /* --- FOOTER Y REDES --- */

        .social-icons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 15px;
        }

        .social-circle {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            border: 1px solid var(--card-border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-gray);
            text-decoration: none;
            font-size: 18px;
            transition: all 0.3s ease;
        }

        .social-circle:hover {
            border-color: var(--primary-cyan);
            color: var(--primary-cyan);
            box-shadow: 0 0 15px rgba(0, 180, 219, 0.3);
        }

        .instagram-float {
            position: fixed;
            right: calc(16px + env(safe-area-inset-right, 0px));
            bottom: calc(16px + env(safe-area-inset-bottom, 0px));
            z-index: 1000;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            min-height: 52px;
            padding: 14px 20px;
            border-radius: 999px;
            background: linear-gradient(135deg, #833ab4, #b51f65, #a83232);
            color: #fff;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            box-shadow: 0 6px 24px rgba(181, 31, 101, 0.4);
        }

        .instagram-float i {
            font-size: 24px;
        }

        .instagram-float:hover {
            background: linear-gradient(135deg, #6e3098, #981a55, #902b2b);
        }

        .instagram-float:focus-visible {
            outline: 3px solid #fff;
            outline-offset: 4px;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            border-top: 1px solid var(--card-border);
            padding-top: 30px;
            width: 100%;
        }

        .footer-logo {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-light);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .footer-logo i {
            color: var(--primary-cyan);
        }

        .footer-address {
            font-size: 12px;
            color: var(--text-gray);
            margin-bottom: 5px;
        }

        .footer-copy {
            font-size: 10px;
            color: rgba(148, 163, 184, 0.6);
            margin-top: 15px;
        }

    </style>
</head>
<body>

    <div class="profile-container">
        
        <div class="logo-container">
            <div class="logo-text">
                <i class="fas fa-wifi" style="transform: rotate(45deg);"></i>
                NFC Connect
            </div>
        </div>

        <!-- Avatar -->
        <div class="avatar">JS</div>

        <!-- Info -->
        <h1 class="name">Jesus Santana</h1>
        <div class="company">VeneCode - NFC Vzla</div>
        <p class="description">Digitalización de empresas y<br>Desarrollo de apps.</p>

        <!-- Botón -->
        <a href="#" class="btn-save">
            <i class="fas fa-user-plus"></i>
            Guardar contacto
        </a>

        <!-- Enlaces de Contacto -->
        <div class="links-container">
            <a href="https://wa.me/584244380137" class="link-card" target="_blank">
                <div class="link-icon"><i class="fab fa-whatsapp"></i></div>
                <div class="link-text">
                    <div class="link-title">WhatsApp</div>
                    <div class="link-subtitle">Escríbenos ahora</div>
                </div>
                <div class="link-arrow"><i class="fas fa-chevron-right"></i></div>
            </a>

            <a href="tel:+584244380137" class="link-card">
                <div class="link-icon"><i class="fas fa-phone-alt"></i></div>
                <div class="link-text">
                    <div class="link-title">Llamar</div>
                    <div class="link-subtitle">0424-4380137</div>
                </div>
                <div class="link-arrow"><i class="fas fa-chevron-right"></i></div>
            </a>

            <a href="mailto:venecode.contact@gmail.com" class="link-card">
                <div class="link-icon"><i class="far fa-envelope"></i></div>
                <div class="link-text">
                    <div class="link-title">Correo</div>
                    <div class="link-subtitle">venecode.contact@gmail.com</div>
                </div>
                <div class="link-arrow"><i class="fas fa-chevron-right"></i></div>
            </a>

            <a href="https://nfcvzla.venecode.com" class="link-card" target="_blank">
                <div class="link-icon"><i class="fas fa-globe"></i></div>
                <div class="link-text">
                    <div class="link-title">Página web - NFC Vzla (Web)</div>
                    <div class="link-subtitle">nfcvzla.venecode.com</div>
                </div>
                <div class="link-arrow"><i class="fas fa-chevron-right"></i></div>
            </a>

            <a href="https://venecode.com" class="link-card" target="_blank">
                <div class="link-icon"><i class="fas fa-globe"></i></div>
                <div class="link-text">
                    <div class="link-title">Ingenieria de Software (Web)</div>
                    <div class="link-subtitle">venecode.com</div>
                </div>
                <div class="link-arrow"><i class="fas fa-chevron-right"></i></div>
            </a>
        </div>

        <!-- SECCIÓN DE ESTADÍSTICAS -->
        <div class="section-title">Impulsando la transformación</div>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">15+</div>
                <div class="stat-label">años de<br>experiencia</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">10+</div>
                <div class="stat-label">clientes<br>corporativos</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">10K+</div>
                <div class="stat-label">horas de<br>desarrollo</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">100%</div>
                <div class="stat-label">proyectos<br>entregados</div>
            </div>
        </div>

        <!-- SECCIÓN DE SKILLS -->
        <div class="section-title">Áreas de Expertise</div>
        <div class="skills-flex">
    <div class="skill-pill">
        <i class="fas fa-project-diagram"></i> Consultor SAP FIORI
    </div>
    <div class="skill-pill">
        <i class="fas fa-laptop-code"></i> Desarrollador Fullstack
    </div>
    <div class="skill-pill">
        <i class="fas fa-database"></i> Experto en Bases de Datos
    </div>
    <div class="skill-pill">
        <i class="fas fa-mobile-alt"></i> Apps Móviles
    </div>
    <div class="skill-pill">
        <i class="fas fa-code"></i> Desarrollo de Aplicaciones
    </div>
    <div class="skill-pill">
        <i class="fas fa-chart-line"></i> Digitalización Empresarial
    </div>
</div>

        <!-- FOOTER CORPORATIVO -->
        <div class="footer">
            <div class="social-title" style="font-size: 11px; color: var(--text-gray); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px;">Sigueme</div>
            <div class="social-icons" style="margin-bottom: 30px;">
                <a href="https://www.instagram.com/venecode.contact/" class="social-circle"><i class="fab fa-instagram"></i></a>
                <a href="https://www.linkedin.com/in/jesus-f-santana-s-22687831/" class="social-circle"><i class="fab fa-linkedin-in"></i></a>
                
            </div>
            
            <div class="footer-logo">
                <i class="fas fa-wifi" style="transform: rotate(45deg);"></i>
                VeneCode
            </div>
            <div class="footer-address">Valencia, Carabobo</div>
            <div class="footer-copy">© 2026 VeneCode - NFC Vzla. Todos los derechos reservados.</div>
        </div>

    </div>

    <a href="https://www.instagram.com/venecode.contact/"
       class="instagram-float"
       target="_blank"
       rel="noopener noreferrer"
       aria-label="Sígueme en Instagram: @venecode.contact (abre en una nueva pestaña)">
        <i class="fab fa-instagram" aria-hidden="true"></i>
        <span>Sígueme en Instagram</span>
    </a>

</body>
</html>