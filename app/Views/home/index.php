<style>
    /* HERO SECTION */
    .hero {
        position: relative;
        padding: 5.5rem 0 4.5rem;
        background: radial-gradient(circle at 50% -10%, rgba(6, 182, 212, 0.12) 0%, rgba(255, 255, 255, 0) 65%);
        overflow: hidden;
    }

    .hero-badge-wrap {
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .pill-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.4rem 1.1rem;
        background: rgba(6, 182, 212, 0.08);
        border: 1px solid rgba(6, 182, 212, 0.25);
        border-radius: 9999px;
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--primary-dark);
        letter-spacing: 0.02em;
    }

    .pill-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--primary);
        box-shadow: 0 0 10px var(--primary);
        animation: pulseDot 2s infinite;
    }

    @keyframes pulseDot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.3); }
    }

    .hero-title {
        font-size: clamp(2.3rem, 5vw, 3.8rem);
        font-weight: 800;
        line-height: 1.15;
        letter-spacing: -0.035em;
        text-align: center;
        color: var(--dark);
        max-width: 960px;
        margin: 0 auto 1.25rem;
    }

    .hero-title .gradient-text {
        background: linear-gradient(135deg, #06b6d4 0%, #2563eb 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .hero-subtitle {
        font-size: 1.2rem;
        font-weight: 700;
        text-align: center;
        color: var(--primary-dark);
        margin-bottom: 1.25rem;
    }

    .hero-desc {
        font-size: 1.1rem;
        color: var(--gray-600);
        text-align: center;
        max-width: 780px;
        margin: 0 auto 2.5rem;
        line-height: 1.7;
    }

    .hero-cta {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 4rem;
    }

    /* STATS BANNER */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        max-width: 1000px;
        margin: 0 auto;
        padding: 2rem;
        background: var(--white);
        border: 1px solid var(--gray-200);
        border-radius: 20px;
        box-shadow: var(--shadow-lg);
    }

    .stat-item {
        text-align: center;
    }

    .stat-number {
        font-size: 2.2rem;
        font-weight: 800;
        color: var(--primary);
        letter-spacing: -0.02em;
        line-height: 1;
        margin-bottom: 0.4rem;
    }

    .stat-label {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--gray-600);
    }

    /* CARD PREVIEW MOCKUP */
    .mockup-preview {
        max-width: 860px;
        margin: 3.5rem auto 0;
        background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
        border: 1px solid var(--gray-200);
        border-radius: 24px;
        padding: 2.5rem;
        box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.08);
        display: flex;
        align-items: center;
        gap: 2.5rem;
    }

    .nfc-card-graphic {
        flex: 1;
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        border-radius: 18px;
        padding: 2rem;
        color: white;
        box-shadow: 0 20px 35px rgba(15, 23, 42, 0.25);
        position: relative;
        overflow: hidden;
        aspect-ratio: 1.58 / 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .nfc-card-graphic::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, rgba(6, 182, 212, 0.3) 0%, transparent 70%);
    }

    .chip-icon {
        width: 38px;
        height: 30px;
        background: #e2e8f0;
        border-radius: 6px;
        border: 1px solid #94a3b8;
    }

    .card-meta h4 {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 0.2rem;
    }

    .card-meta p {
        font-size: 0.85rem;
        color: #94a3b8;
    }

    .mockup-info {
        flex: 1.2;
    }

    .mockup-info h3 {
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--dark);
        margin-bottom: 0.75rem;
    }

    .mockup-info p {
        color: var(--gray-600);
        font-size: 0.95rem;
        margin-bottom: 1.25rem;
    }

    .check-list {
        list-style: none;
        display: flex;
        flex-direction: column;
        gap: 0.6rem;
    }

    .check-list li {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        font-size: 0.92rem;
        font-weight: 600;
        color: var(--gray-700);
    }

    .check-icon {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: rgba(6, 182, 212, 0.15);
        color: var(--primary-dark);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 800;
    }

    /* SECTION HEADERS */
    .section {
        padding: 5.5rem 0;
    }

    .section-light {
        background-color: var(--gray-50);
    }

    .section-header {
        text-align: center;
        max-width: 720px;
        margin: 0 auto 3.5rem;
    }

    .section-badge {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--primary);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 0.5rem;
        display: inline-block;
    }

    .section-title {
        font-size: 2.3rem;
        font-weight: 800;
        color: var(--dark);
        letter-spacing: -0.025em;
        margin-bottom: 1rem;
    }

    .section-desc {
        font-size: 1.05rem;
        color: var(--gray-600);
    }

    /* FEATURE CARDS GRID */
    .grid-3 {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
    }

    .feature-card {
        background: var(--white);
        border: 1px solid var(--gray-200);
        border-radius: 18px;
        padding: 2.2rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
    }

    .feature-card:hover {
        transform: translateY(-6px);
        box-shadow: var(--shadow-xl);
        border-color: rgba(6, 182, 212, 0.3);
    }

    .feature-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: var(--primary-light);
        color: var(--primary-dark);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
    }

    .feature-card h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 0.75rem;
    }

    .feature-card p {
        font-size: 0.95rem;
        color: var(--gray-600);
        line-height: 1.6;
    }

    /* DUAL PROFILES SECTION */
    .dual-box {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2.5rem;
        margin-top: 2rem;
    }

    .profile-card {
        background: var(--white);
        border: 2px solid var(--gray-200);
        border-radius: 20px;
        padding: 2.5rem;
        position: relative;
        transition: all 0.3s ease;
    }

    .profile-card.active {
        border-color: var(--primary);
        box-shadow: 0 10px 30px rgba(6, 182, 212, 0.15);
    }

    .profile-badge {
        position: absolute;
        top: -14px;
        left: 2rem;
        background: var(--primary);
        color: white;
        padding: 0.25rem 0.9rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .profile-card h3 {
        font-size: 1.45rem;
        font-weight: 800;
        color: var(--dark);
        margin-bottom: 0.8rem;
    }

    .profile-card p {
        font-size: 0.95rem;
        color: var(--gray-600);
        margin-bottom: 1.5rem;
    }

    /* CTA FOOTER */
    .cta-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        border-radius: 24px;
        padding: 4rem 2rem;
        text-align: center;
        color: white;
        margin: 4rem 0;
        position: relative;
        overflow: hidden;
    }

    .cta-banner h2 {
        font-size: 2.3rem;
        font-weight: 800;
        margin-bottom: 1rem;
        letter-spacing: -0.02em;
    }

    .cta-banner p {
        font-size: 1.1rem;
        color: #94a3b8;
        max-width: 600px;
        margin: 0 auto 2rem;
    }

    /* CONTACT SECTION */
    .contact-section {
        padding: 5rem 0;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        border-top: 1px solid var(--gray-200);
    }

    .contact-grid {
        display: grid;
        grid-template-columns: 1fr 1.25fr;
        gap: 3rem;
        align-items: start;
        margin-top: 2rem;
    }

    .contact-info-card {
        background: linear-gradient(135deg, #0b1120 0%, #1e293b 100%);
        border-radius: 20px;
        padding: 2.75rem 2.25rem;
        color: white;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.15);
    }

    .contact-info-card h3 {
        font-size: 1.6rem;
        font-weight: 800;
        margin-bottom: 0.8rem;
        letter-spacing: -0.02em;
    }

    .contact-info-card p {
        color: #94a3b8;
        font-size: 0.95rem;
        line-height: 1.65;
        margin-bottom: 2rem;
    }

    .contact-meta-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .contact-meta-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: rgba(6, 182, 212, 0.15);
        color: #38bdf8;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .contact-meta-content h4 {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #94a3b8;
        margin-bottom: 0.25rem;
    }

    .contact-meta-content a, .contact-meta-content span {
        color: white;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
    }

    .contact-form-card {
        background: white;
        border: 1px solid var(--gray-200);
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.04);
    }

    .contact-form-card h3 {
        font-size: 1.45rem;
        font-weight: 800;
        color: var(--dark);
        margin-bottom: 0.4rem;
    }

    .contact-form-card p {
        color: var(--gray-600);
        font-size: 0.92rem;
        margin-bottom: 1.75rem;
    }

    .form-group-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
    }

    .form-control-wrap {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
        margin-bottom: 1.25rem;
    }

    .form-control-wrap label {
        font-size: 0.88rem;
        font-weight: 700;
        color: var(--dark);
    }

    .form-control-wrap input,
    .form-control-wrap textarea {
        width: 100%;
        padding: 0.8rem 1rem;
        border: 1px solid var(--gray-200);
        border-radius: 12px;
        background: #f8fafc;
        color: var(--dark);
        font: inherit;
        box-sizing: border-box;
        transition: all 0.2s ease;
    }

    .form-control-wrap input:focus,
    .form-control-wrap textarea:focus {
        outline: none;
        background: #ffffff;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.18);
    }

    .form-alert {
        padding: 1rem 1.25rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        font-weight: 600;
        font-size: 0.92rem;
    }

    .form-alert.success {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .form-alert.error {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    footer {
        border-top: 1px solid var(--gray-200);
        padding: 2.5rem 0;
        text-align: center;
        color: var(--gray-400);
        font-size: 0.9rem;
    }

    /* PARTNER / ALIADOS SECTION */
    .partner-section {
        padding: 5.5rem 0;
        background-color: var(--white);
        border-top: 1px solid var(--gray-200);
    }

    .partner-card {
        background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
        border: 1px solid var(--gray-200);
        border-radius: 24px;
        padding: 3rem;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
        display: flex;
        align-items: center;
        gap: 3rem;
        position: relative;
        overflow: hidden;
    }

    .partner-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 6px;
        height: 100%;
        background: linear-gradient(180deg, var(--primary) 0%, #2563eb 100%);
    }

    .partner-logo-box {
        flex: 0 0 220px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2rem 1.5rem;
        background: white;
        border: 1px solid var(--gray-200);
        border-radius: 18px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
        text-align: center;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .partner-logo-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 22px rgba(6, 182, 212, 0.15);
    }

    .partner-logo-img {
        max-width: 160px;
        height: auto;
        display: block;
        margin-bottom: 0.75rem;
    }

    .partner-badge-tech {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--primary-dark);
        background: rgba(6, 182, 212, 0.1);
        padding: 0.25rem 0.65rem;
        border-radius: 9999px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .partner-content {
        flex: 1;
    }

    .partner-content h3 {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--dark);
        letter-spacing: -0.02em;
        margin-bottom: 0.75rem;
    }

    .partner-content p {
        font-size: 1.02rem;
        color: var(--gray-600);
        line-height: 1.7;
        margin-bottom: 1.5rem;
    }

    .partner-tags {
        display: flex;
        gap: 0.65rem;
        flex-wrap: wrap;
        margin-bottom: 1.75rem;
    }

    .partner-tag-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.35rem 0.8rem;
        background: #f1f5f9;
        border-radius: 8px;
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--gray-700);
    }

    .partner-tag-pill svg {
        color: var(--primary-dark);
    }

    .partner-link-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.8rem 1.6rem;
        background: linear-gradient(135deg, var(--dark) 0%, #1e293b 100%);
        color: white;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.25s ease;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);
    }

    .partner-link-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.25);
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    }

    @media (max-width: 992px) {
        .grid-3 {
            grid-template-columns: repeat(2, 1fr);
        }
        .stats-row {
            grid-template-columns: repeat(2, 1fr);
        }
        .mockup-preview {
            flex-direction: column;
        }
        .dual-box {
            grid-template-columns: 1fr;
        }
        .contact-grid {
            grid-template-columns: 1fr;
        }
        .partner-card {
            flex-direction: column;
            text-align: center;
            padding: 2.25rem 1.5rem;
            gap: 2rem;
        }
        .partner-card::before {
            width: 100%;
            height: 5px;
        }
        .partner-tags {
            justify-content: center;
        }
    }

    @media (max-width: 640px) {
        .grid-3 {
            grid-template-columns: 1fr;
        }
        .stats-row {
            grid-template-columns: 1fr;
        }
        .form-group-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- HERO -->
<section class="hero" id="inicio">
    <div class="container">
        <div class="hero-badge-wrap">
            <div class="pill-badge">
                <span class="pill-dot"></span>
                Plataforma SaaS B2B • Tarjetas Inteligentes NFC
            </div>
        </div>

        <h1 class="hero-title">
            Transforma la manera en que tu empresa <span class="gradient-text">conecta con el mundo</span>
        </h1>

        <div class="hero-subtitle">
            Sustituye el papel por un ecosistema digital dinámico, centralizado y contactless
        </div>

        <p class="hero-desc">
            Nuestra plataforma integral permite a las organizaciones modernizar su networking corporativo con tarjetas de presentación inteligentes NFC, perfiles ejecutivos dinámicos y analíticas comerciales en tiempo real.
        </p>

        <div class="hero-cta">
            <a href="<?= url('/login') ?>" class="btn btn-primary">
                Iniciar Sesión en el Panel
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </a>
            <a href="#caracteristicas" class="btn btn-outline">
                Explorar Características
            </a>
        </div>

        <!-- STATS -->
        <div class="stats-row">
            <div class="stat-item">
                <div class="stat-number">100%</div>
                <div class="stat-label">Contactless & Digital</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">Universal</div>
                <div class="stat-label">Soporte iOS y Android</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">vCard</div>
                <div class="stat-label">Agenda Nativa Instantánea</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">Real-Time</div>
                <div class="stat-label">Métricas de Escaneo</div>
            </div>
        </div>

        <!-- MOCKUP PREVIEW -->
        <div class="mockup-preview">
            <div class="nfc-card-graphic">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <div class="chip-icon"></div>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#38bdf8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 8.32a7.43 7.43 0 0 1 0 7.36"/>
                        <path d="M9.46 6a11.12 11.12 0 0 1 0 12"/>
                        <path d="M12.91 3.68a14.81 14.81 0 0 1 0 16.64"/>
                    </svg>
                </div>
                <div class="card-meta">
                    <h4>Alejandro Ramírez</h4>
                    <p>Director Ejecutivo de Ventas • B2B</p>
                </div>
            </div>
            <div class="mockup-info">
                <h3>Un solo acercamiento. Todos tus datos.</h3>
                <p>Acerca la tarjeta a cualquier smartphone moderno para desplegar de inmediato tu landing page corporativa sin necesidad de instalar apps externas.</p>
                <ul class="check-list">
                    <li><span class="check-icon">✓</span> Descarga directa de contacto a la agenda del teléfono</li>
                    <li><span class="check-icon">✓</span> Enlaces dinámicos a WhatsApp, llamada y LinkedIn</li>
                    <li><span class="check-icon">✓</span> Catálogo de productos y folletos PDF descargables</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- CARACTERISTICAS PRINCIPALES -->
<section class="section section-light" id="caracteristicas">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">Solución Integral</span>
            <h2 class="section-title">Características Principales de la Plataforma</h2>
            <p class="section-desc">Diseñada con arquitectura robusta para escalar desde emprendedores hasta grandes corporaciones.</p>
        </div>

        <div class="grid-3">
            <div class="feature-card">
                <div class="feature-icon">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                </div>
                <h3>Gestión Corporativa Centralizada</h3>
                <p>Panel administrativo B2B donde cada empresa gestiona de forma autónoma las tarjetas, información y accesos de sus colaboradores con permisos basados en roles.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                        <line x1="8" y1="21" x2="16" y2="21"></line>
                        <line x1="12" y1="17" x2="12" y2="21"></line>
                    </svg>
                </div>
                <h3>Perfiles Duales Inteligentes</h3>
                <p>Flexibilidad total para desplegar Landing Pages Personales (estilo vCard para ejecutivos) o Landing Pages Publicitarias (para catálogos, promociones y conversión).</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="m4.93 4.93 4.24 4.24"></path>
                        <path d="m14.83 9.17 4.24-4.24"></path>
                        <path d="m14.83 14.83 4.24 4.24"></path>
                        <path d="m9.17 14.83-4.24 4.24"></path>
                    </svg>
                </div>
                <h3>Identidad de Marca Avanzada</h3>
                <p>Configuración de logos, paletas de colores corporativas y soporte para CSS personalizado, con fotos y fondos individuales para cada miembro del equipo.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12.55a11 11 0 0 1 14.08 0"></path>
                        <path d="M1.42 9a16 16 0 0 1 21.16 0"></path>
                        <path d="M8.53 16.11a6 6 0 0 1 6.95 0"></path>
                        <line x1="12" y1="20" x2="12.01" y2="20"></line>
                    </svg>
                </div>
                <h3>Networking Instantáneo</h3>
                <p>Tecnología contactless con integración de vCard nativa para iOS y Android, además de disparadores directos a WhatsApp, correo corporativo y redes sociales.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="20" x2="18" y2="10"></line>
                        <line x1="12" y1="20" x2="12" y2="4"></line>
                        <line x1="6" y1="20" x2="6" y2="14"></line>
                    </svg>
                </div>
                <h3>Analíticas de Impacto</h3>
                <p>Monitorea en tiempo real el rendimiento de cada tarjeta NFC, registrando estadísticas de escaneos, dispositivos y horarios para medir el impacto de ventas.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </div>
                <h3>Seguridad y Escalabilidad</h3>
                <p>Construido sobre Lenguajes de ultima generacion con protección estricta de base de datos, sesiones cifradas y aislamiento seguro fuera del Document Root público.</p>
            </div>
        </div>
    </div>
</section>

<!-- PERFILES DUALES -->
<section class="section" id="perfiles">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">Versatilidad Sin Límites</span>
            <h2 class="section-title">Perfiles Duales Inteligentes</h2>
            <p class="section-desc">Configura el comportamiento de cada tarjeta según el objetivo comercial de tu equipo.</p>
        </div>

        <div class="dual-box">
            <div class="profile-card active">
                <div class="profile-badge">Modo 1</div>
                <h3>Landing Page Personal (vCard)</h3>
                <p>Diseñada para directores, ejecutivos y representantes de ventas que buscan conectar directamente con sus clientes.</p>
                <ul class="check-list">
                    <li><span class="check-icon">✓</span> Botón "Guardar Contacto" con vCard compatible con iOS y Android</li>
                    <li><span class="check-icon">✓</span> Fotografía de perfil, cargo, bio profesional y empresa</li>
                    <li><span class="check-icon">✓</span> Acceso rápido a WhatsApp, Teléfono, Email y LinkedIn</li>
                </ul>
            </div>

            <div class="profile-card">
                <div class="profile-badge" style="background: var(--dark);">Modo 2</div>
                <h3>Landing Page Publicitaria</h3>
                <p>Enfocada en campañas de marketing, stands corporativos en ferias y promoción de productos o servicios.</p>
                <ul class="check-list">
                    <li><span class="check-icon">✓</span> Descarga de catálogos y presentaciones corporativas en PDF</li>
                    <li><span class="check-icon">✓</span> Enlaces a formularios de captación de leads y promociones</li>
                    <li><span class="check-icon">✓</span> Banner institucional y llamado a la acción comercial directo</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- ANALITICAS -->
<section class="section section-light" id="metricas">
    <div class="container">
        <div class="cta-banner">
            <h2>Listo para digitalizar la identidad de tu empresa</h2>
            <p>Descubre el poder de las tarjetas inteligentes NFC y lidera la transformación del networking corporativo.</p>
            <a href="<?= url('/login') ?>" class="btn btn-primary" style="padding: 0.9rem 2rem; font-size: 1.05rem;">
                Ingresar al Sistema
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </a>
        </div>
    </div>
</section>

<!-- SECCIÓN ALIADOS TECNOLÓGICOS -->
<section class="partner-section" id="aliados">
    <div class="container">
        <div class="section-header" style="margin-bottom: 2.5rem;">
            <span class="section-badge">Alianza Estratégica</span>
            <h2 class="section-title">Innovación y Desarrollo Tecnológico</h2>
            <p class="section-desc">
                Todo el desarrollo de la arquitectura tecnológica, seguridad cloud y funcionalidades de nuestro ecosistema SaaS se realiza de la mano de nuestro aliado estratégico.
            </p>
        </div>

        <div class="partner-card">
            <div class="partner-logo-box">
                <a href="https://venecode.com" target="_blank" rel="noopener noreferrer" title="Ir al sitio oficial de Venecode">
                    <img src="<?= url('/assets/img/partner/venecode.png') ?>" alt="Venecode - Aliado Tecnológico" class="partner-logo-img">
                </a>
                <span class="partner-badge-tech">Aliado Tecnológico</span>
            </div>

            <div class="partner-content">
                <h3>Venecode • Soluciones Tecnológicas & Desarrollo de Software</h3>
                <p>
                    En el <strong>Sistema NFC</strong> todo el desarrollo de la parte de tecnología, ingeniería de software, basado en Apis y tecnologia multiserviciso e integraciones contactless se realiza de la mano de <strong>Venecode</strong>, garantizando una plataforma robusta, escalable y con altos estándares de calidad internacional.
                </p>

                <div class="partner-tags">
                    <span class="partner-tag-pill">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        Arquitectura Cloud Segura
                    </span>
                    <span class="partner-tag-pill">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        Desarrollo Web & SaaS a Medida
                    </span>
                    <span class="partner-tag-pill">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        Innovación Contactless NFC
                    </span>
                </div>

                <a href="https://venecode.com" target="_blank" rel="noopener noreferrer" class="partner-link-btn">
                    <span>Visitar venecode.com</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                        <polyline points="15 3 21 3 21 9"></polyline>
                        <line x1="10" y1="14" x2="21" y2="3"></line>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- SECCIÓN CONTACTO -->
<section class="contact-section" id="contacto">
    <div class="container">
        <div class="section-header" style="margin-bottom: 2rem;">
            <div class="section-tag">Hablemos de tu Proyecto</div>
            <h2 class="section-title">Contáctanos y solicita una cotización</h2>
            <p class="section-desc">
                ¿Interesado en implementar tarjetas inteligentes NFC en tu organización? Déjanos tus datos y un especialista corporativo se comunicará contigo de inmediato.
            </p>
        </div>

        <?php if (!empty($success)): ?>
            <div class="form-alert success">
                <strong>&check; ¡Mensaje enviado con éxito!</strong><br>
                <?= e($success) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="form-alert error">
                <strong>&excl; Atención:</strong><br>
                <?= e($error) ?>
            </div>
        <?php endif; ?>

        <div class="contact-grid">
            <div class="contact-info-card">
                <h3>Transforma la presencia digital de tu equipo</h3>
                <p>
                    Ofrecemos planes escalables para emprendedores, pymes y corporaciones. Configuración de marca blanca, dominios personalizados e inventario de tarjetas grabado a láser.
                </p>

                <div class="contact-meta-item">
                    <div class="contact-meta-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                    </div>
                    <div class="contact-meta-content">
                        <h4>Correo Comercial</h4>
                        <a href="mailto:venecode.contact@gmail.com">venecode.contact@gmail.com</a>
                    </div>
                </div>

                <div class="contact-meta-item">
                    <div class="contact-meta-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                        </svg>
                    </div>
                    <div class="contact-meta-content">
                        <h4>Atención Inmediata</h4>
                        <span>Soporte comercial para empresas</span>
                    </div>
                </div>

                <div class="contact-meta-item">
                    <div class="contact-meta-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                        </svg>
                    </div>
                    <div class="contact-meta-content">
                        <h4>Modalidad</h4>
                        <span>Plataforma SaaS B2B Multi-Empresa</span>
                    </div>
                </div>
            </div>

            <div class="contact-form-card">
                <h3>Envíanos un mensaje</h3>
                <p>Completa el formulario y te contactaremos en menos de 24 horas.</p>

                <form method="post" action="<?= url('/contacto') ?>">
                    <?= csrf_field() ?>

                    <div class="form-group-row">
                        <div class="form-control-wrap">
                            <label for="c_name">Nombre y Apellido *</label>
                            <input id="c_name" name="name" type="text" maxlength="120" required placeholder="Ej: Carlos Silva" autocomplete="name">
                        </div>
                        <div class="form-control-wrap">
                            <label for="c_company">Nombre de la Empresa *</label>
                            <input id="c_company" name="company_name" type="text" maxlength="150" required placeholder="Ej: Corporación Alpha C.A." autocomplete="organization">
                        </div>
                    </div>

                    <div class="form-group-row">
                        <div class="form-control-wrap">
                            <label for="c_email">Correo Electrónico *</label>
                            <input id="c_email" name="email" type="email" maxlength="150" required placeholder="carlos@miempresa.com" autocomplete="email">
                        </div>
                        <div class="form-control-wrap">
                            <label for="c_phone">Número de Teléfono *</label>
                            <input id="c_phone" name="phone" type="tel" maxlength="50" required placeholder="+58 412 1234567" autocomplete="tel">
                        </div>
                    </div>

                    <div class="form-control-wrap">
                        <label for="c_message">Mensaje / Requerimiento (Opcional)</label>
                        <textarea id="c_message" name="message" rows="4" placeholder="¿Cuántas tarjetas requiere tu empresa o qué características te gustaría cotizar?"></textarea>
                    </div>

                    <div class="form-control-wrap" style="margin-bottom: 1.5rem;">
                        <label for="captcha-input-contact">Código de Seguridad Anti-Spam *</label>
                        <div style="display: flex; gap: 0.75rem; align-items: center; margin-bottom: 0.5rem; flex-wrap: wrap;">
                            <img 
                                id="captcha-image-contact" 
                                src="<?= $captchaImage ?>" 
                                alt="Código CAPTCHA" 
                                style="height: 44px; border-radius: 10px; border: 1px solid var(--gray-200); cursor: pointer; background: #fff;"
                                title="Haz clic sobre la imagen para recargar el código"
                                onclick="reloadCaptcha('contact')"
                            >
                            <button 
                                type="button" 
                                onclick="reloadCaptcha('contact')" 
                                title="Recargar nuevo código"
                                style="background: #f1f5f9; border: 1px solid var(--gray-200); border-radius: 10px; padding: 0.7rem 0.9rem; cursor: pointer; color: var(--gray-700); display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.85rem; font-weight: 600; font-family: inherit; transition: all 0.2s;"
                            >
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="23 4 23 10 17 10"></polyline>
                                    <polyline points="1 20 1 14 7 14"></polyline>
                                    <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                                </svg>
                                Recargar
                            </button>
                        </div>
                        <input 
                            id="captcha-input-contact" 
                            name="captcha" 
                            type="text" 
                            maxlength="6" 
                            required 
                            placeholder="Escribe los 5 caracteres que ves arriba" 
                            autocomplete="off"
                            style="text-transform: uppercase; font-weight: 700; letter-spacing: 0.1em;"
                        >
                        <span style="font-size: 0.76rem; color: var(--gray-600); margin-top: 0.2rem; display: block;">
                            Validación para proteger el formulario contra envíos automatizados y ataques de fuerza bruta.
                        </span>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.95rem; font-size: 1rem; border-radius: 12px;">
                        Enviar Solicitud de Contacto
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="22" y1="2" x2="11" y2="13"></line>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
function reloadCaptcha(action) {
    fetch('<?= url('/captcha/refresh') ?>?action=' + encodeURIComponent(action))
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data && data.image) {
                var img = document.getElementById('captcha-image-' + action);
                if (img) img.src = data.image;
                var input = document.getElementById('captcha-input-' + action);
                if (input) { input.value = ''; input.focus(); }
            }
        })
        .catch(function(err) { console.error('Error al recargar captcha:', err); });
}
</script>

<footer>
    <div class="container">
        <p>&copy; <?= date('Y') ?> Sistema NFC SaaS. Todos los derechos reservados.</p>
    </div>
</footer>
