<style>
    .dash-header {
        margin-bottom: 2rem;
    }

    .dash-header h1 {
        font-size: 1.85rem;
        font-weight: 800;
        color: var(--dark);
        letter-spacing: -0.02em;
        margin-bottom: 0.3rem;
    }

    .dash-header p {
        color: var(--gray-600);
        font-size: 0.95rem;
    }

    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    .metric-card {
        background: var(--white);
        border: 1px solid var(--gray-200);
        border-radius: 16px;
        padding: 1.6rem;
        display: flex;
        flex-direction: column;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
    }

    .metric-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .metric-title {
        font-size: 0.88rem;
        font-weight: 700;
        color: var(--gray-400);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .metric-icon-wrap {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: rgba(6, 182, 212, 0.1);
        color: var(--primary-dark);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .metric-value {
        font-size: 2.1rem;
        font-weight: 800;
        color: var(--dark);
        line-height: 1;
        margin-bottom: 0.4rem;
    }

    .metric-sub {
        font-size: 0.82rem;
        color: #10b981;
        font-weight: 600;
    }

    .info-box {
        background: var(--white);
        border: 1px solid var(--gray-200);
        border-radius: 16px;
        padding: 2rem;
    }

    .info-box h2 {
        font-size: 1.3rem;
        font-weight: 800;
        color: var(--dark);
        margin-bottom: 1rem;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.82rem;
        font-weight: 700;
        background: #dcfce7;
        color: #15803d;
    }

    @media (max-width: 1024px) {
        .metrics-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 640px) {
        .metrics-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="dash-header">
    <h1>Panel General del SaaS</h1>
    <p>Bienvenido, <strong><?= e($userName) ?></strong>. Control central del ecosistema B2B de tarjetas NFC.</p>
</div>

<div class="metrics-grid">
    <div class="metric-card">
        <div class="metric-top">
            <span class="metric-title">Empresas (Tenants)</span>
            <div class="metric-icon-wrap">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                </svg>
            </div>
        </div>
        <div class="metric-value"><?= (int)($metrics['total_companies'] ?? 0) ?></div>
        <div class="metric-sub">Cuentas corporativas registradas</div>
    </div>

    <div class="metric-card">
        <div class="metric-top">
            <span class="metric-title">Tarjetas NFC Emitidas</span>
            <div class="metric-icon-wrap">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                    <line x1="2" y1="10" x2="22" y2="10"></line>
                </svg>
            </div>
        </div>
        <div class="metric-value"><?= (int)($metrics['total_cards'] ?? 0) ?></div>
        <div class="metric-sub">Chips y códigos activos</div>
    </div>

    <div class="metric-card">
        <div class="metric-top">
            <span class="metric-title">Usuarios Activos</span>
            <div class="metric-icon-wrap">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                </svg>
            </div>
        </div>
        <div class="metric-value"><?= (int)($metrics['total_users'] ?? 0) ?></div>
        <div class="metric-sub">Colaboradores con acceso</div>
    </div>

    <div class="metric-card">
        <div class="metric-top">
            <span class="metric-title">Escaneos Totales</span>
            <div class="metric-icon-wrap">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="20" x2="18" y2="10"></line>
                    <line x1="12" y1="20" x2="12" y2="4"></line>
                    <line x1="6" y1="20" x2="6" y2="14"></line>
                </svg>
            </div>
        </div>
        <div class="metric-value"><?= (int)($metrics['total_scans'] ?? 0) ?></div>
        <div class="metric-sub">Interacciones registradas</div>
    </div>
</div>

<div class="info-box">
    <h2>Estado de la Plataforma & Entorno</h2>
    <table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
        <tbody>
            <tr style="border-bottom: 1px solid var(--gray-200);">
                <td style="padding: 1rem 0; font-weight: 600; color: var(--dark);">Versión de PHP</td>
                <td style="padding: 1rem 0; color: var(--gray-600);"><?= PHP_VERSION ?> (Requerido 8.3+)</td>
            </tr>
            <tr style="border-bottom: 1px solid var(--gray-200);">
                <td style="padding: 1rem 0; font-weight: 600; color: var(--dark);">Conexión a Base de Datos</td>
                <td style="padding: 1rem 0;">
                    <span class="status-badge">
                        <span style="width: 8px; height: 8px; background: #15803d; border-radius: 50%;"></span>
                        MySQL Conectado (nfc_landing_db)
                    </span>
                </td>
            </tr>
            <tr style="border-bottom: 1px solid var(--gray-200);">
                <td style="padding: 1rem 0; font-weight: 600; color: var(--dark);">Rol de Usuario en Sesión</td>
                <td style="padding: 1rem 0; color: var(--primary-dark); font-weight: 700;"><?= e($roleName) ?> (<?= e($_SESSION['role_slug'] ?? '') ?>)</td>
            </tr>
            <tr>
                <td style="padding: 1rem 0; font-weight: 600; color: var(--dark);">Sesión Segura</td>
                <td style="padding: 1rem 0; color: var(--gray-600);">HttpOnly: Sí | SameSite: Lax</td>
            </tr>
        </tbody>
    </table>
</div>
