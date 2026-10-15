<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Iniciar Sesión | Sistema NFC') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #06b6d4;
            --primary-dark: #0891b2;
            --dark: #0f172a;
            --gray-700: #334155;
            --gray-600: #475569;
            --gray-400: #94a3b8;
            --gray-200: #e2e8f0;
            --gray-100: #f1f5f9;
            --gray-50: #f8fafc;
            --white: #ffffff;
            --danger: #ef4444;
            --danger-bg: #fef2f2;
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
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            overflow-x: hidden;
        }

        .auth-bg-glow {
            position: absolute;
            top: -20%;
            left: 50%;
            transform: translateX(-50%);
            width: 700px;
            height: 700px;
            background: radial-gradient(circle, rgba(6, 182, 212, 0.15) 0%, rgba(255, 255, 255, 0) 70%);
            z-index: 0;
            pointer-events: none;
        }

        .auth-container {
            width: 100%;
            max-width: 440px;
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.03);
            position: relative;
            z-index: 1;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-badge {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary) 0%, #2563eb 100%);
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            box-shadow: 0 8px 16px rgba(6, 182, 212, 0.35);
            margin-bottom: 1.25rem;
        }

        .auth-header h1 {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--dark);
            letter-spacing: -0.02em;
            margin-bottom: 0.4rem;
        }

        .auth-header p {
            font-size: 0.92rem;
            color: var(--gray-600);
        }

        .alert-error {
            background-color: var(--danger-bg);
            border: 1px solid rgba(239, 68, 68, 0.25);
            color: var(--danger);
            padding: 0.85rem 1rem;
            border-radius: 12px;
            font-size: 0.88rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.4rem;
        }

        .form-control {
            width: 100%;
            padding: 0.8rem 1rem;
            font-size: 0.95rem;
            font-family: inherit;
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            background-color: var(--gray-50);
            color: var(--dark);
            transition: all 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            background-color: var(--white);
            box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.15);
        }

        .btn-submit {
            width: 100%;
            padding: 0.85rem 1rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: var(--white);
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 4px 12px rgba(6, 182, 212, 0.35);
            margin-top: 0.5rem;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(6, 182, 212, 0.45);
        }

        .auth-footer {
            margin-top: 1.75rem;
            text-align: center;
            font-size: 0.88rem;
            color: var(--gray-600);
        }

        .auth-footer a {
            color: var(--primary-dark);
            text-decoration: none;
            font-weight: 600;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="auth-bg-glow"></div>
    <div class="auth-container">
        <div class="auth-header">
            <div class="logo-badge">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 8.32a7.43 7.43 0 0 1 0 7.36"/>
                    <path d="M9.46 6a11.12 11.12 0 0 1 0 12"/>
                    <path d="M12.91 3.68a14.81 14.81 0 0 1 0 16.64"/>
                </svg>
            </div>
            <h1>Bienvenido de vuelta</h1>
            <p>Ingresa tus credenciales para acceder a la plataforma</p>
        </div>

        <?= $content ?>

        <div class="auth-footer">
            <a href="<?= url('/') ?>">← Volver a la página principal</a>
        </div>
    </div>
</body>
</html>
