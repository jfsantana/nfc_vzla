<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Sistema NFC') ?></title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #0f172a; color: #f8fafc; margin: 0; padding: 2rem; }
        .container { max-width: 800px; margin: 0 auto; background: #1e293b; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        h1 { color: #38bdf8; margin-top: 0; }
        .badge { background: #0284c7; color: white; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.85rem; }
        footer { margin-top: 2rem; font-size: 0.85rem; color: #94a3b8; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <?= $content ?>
    </div>
    <footer>
        Sistema NFC &copy; <?= date('Y') ?>
    </footer>
</body>
</html>
