<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Panel SuperAdmin | Sistema NFC') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #06b6d4;
            --primary-dark: #0891b2;
            --dark: #0f172a;
            --sidebar-bg: #0b1120;
            --gray-700: #334155;
            --gray-600: #475569;
            --gray-400: #94a3b8;
            --gray-200: #e2e8f0;
            --gray-100: #f1f5f9;
            --gray-50: #f8fafc;
            --white: #ffffff;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--gray-50);
            color: var(--gray-700);
            display: flex;
            min-height: 100vh;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* SIDEBAR */
        .sidebar {
            width: 270px;
            background-color: var(--sidebar-bg);
            color: white;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #1e293b;
        }

        .sidebar-brand {
            padding: 1.75rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.25rem;
            font-weight: 800;
            border-bottom: 1px solid #1e293b;
        }

        .sidebar-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--primary) 0%, #2563eb 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-nav {
            padding: 1.5rem 1rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }

        .nav-link-item {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            font-size: 0.92rem;
            font-weight: 600;
            color: #94a3b8;
            transition: all 0.2s ease;
        }

        .nav-link-item:hover, .nav-link-item.active {
            background-color: rgba(6, 182, 212, 0.12);
            color: var(--primary);
        }

        .sidebar-user {
            padding: 1.25rem 1.5rem;
            border-top: 1px solid #1e293b;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
        }

        /* MAIN CONTENT */
        .main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .topbar {
            height: 70px;
            background: var(--white);
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
        }

        .role-badge {
            background: #fef3c7;
            color: #b45309;
            font-size: 0.78rem;
            font-weight: 800;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .content-area {
            padding: 2.5rem;
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 8.32a7.43 7.43 0 0 1 0 7.36"/>
                    <path d="M9.46 6a11.12 11.12 0 0 1 0 12"/>
                    <path d="M12.91 3.68a14.81 14.81 0 0 1 0 16.64"/>
                </svg>
            </div>
            <span>NFC SaaS Admin</span>
        </div>

        <nav class="sidebar-nav">
            <a href="<?= url('/admin/dashboard') ?>" class="nav-link-item active">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="9"></rect>
                    <rect x="14" y="3" width="7" height="5"></rect>
                    <rect x="14" y="12" width="7" height="9"></rect>
                    <rect x="3" y="16" width="7" height="5"></rect>
                </svg>
                Dashboard
            </a>
            <a href="#" class="nav-link-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                </svg>
                Empresas (Tenants)
            </a>
            <a href="#" class="nav-link-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                    <line x1="2" y1="10" x2="22" y2="10"></line>
                </svg>
                Tarjetas NFC
            </a>
            <a href="#" class="nav-link-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                </svg>
                Usuarios Globales
            </a>
            <a href="#" class="nav-link-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="1" x2="12" y2="23"></line>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
                Planes & Facturación
            </a>
        </nav>

        <div class="sidebar-user">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <div class="user-avatar">
                    <?= strtoupper(substr($userName ?? 'A', 0, 1)) ?>
                </div>
                <div>
                    <div style="font-size: 0.88rem; font-weight: 700;"><?= e($userName ?? 'Admin') ?></div>
                    <div style="font-size: 0.75rem; color: #94a3b8;"><?= e($userEmail ?? '') ?></div>
                </div>
            </div>
            <a href="<?= url('/logout') ?>" title="Cerrar sesión" style="color: #ef4444; padding: 0.4rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
            </a>
        </div>
    </aside>

    <div class="main-wrapper">
        <header class="topbar">
            <div>
                <span class="role-badge"><?= e($roleName ?? 'Super Administrador') ?></span>
            </div>
            <div style="display: flex; align-items: center; gap: 1rem;">
                <a href="<?= url('/') ?>" target="_blank" style="font-size: 0.88rem; font-weight: 600; color: var(--primary-dark); display: flex; align-items: center; gap: 0.35rem;">
                    Ver Landing Pública
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                        <polyline points="15 3 21 3 21 9"></polyline>
                        <line x1="10" y1="14" x2="21" y2="3"></line>
                    </svg>
                </a>
            </div>
        </header>

        <main class="content-area">
            <?= $content ?>
        </main>
    </div>
</body>
</html>
