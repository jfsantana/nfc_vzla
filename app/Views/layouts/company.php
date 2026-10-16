<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Panel de empresa | Sistema NFC') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing:border-box; }
        body { margin:0; background:#f8fafc; color:#334155; font-family:'Plus Jakarta Sans',sans-serif; }
        .topbar { min-height:72px; padding:1rem max(1.25rem,calc((100% - 1120px)/2)); background:#fff; border-bottom:1px solid #e2e8f0; display:flex; align-items:center; justify-content:space-between; gap:1rem; }
        .brand { color:#0f172a; font-weight:800; font-size:1.1rem; }
        .brand span { color:#0891b2; }
        .top-user { display:flex; align-items:center; gap:1rem; font-size:.88rem; }
        .logout { color:#b91c1c; font-weight:700; text-decoration:none; }
        main { width:min(1120px,calc(100% - 2.5rem)); margin:2.5rem auto; }
        @media(max-width:560px) { .topbar { align-items:flex-start; flex-direction:column; } .top-user { width:100%; justify-content:space-between; } }
    </style>
</head>
<body>
    <header class="topbar">
        <a class="brand" href="<?= url('/company/dashboard') ?>"><span>NFC</span>Connect · Empresa</a>
        <div class="top-user">
            <span><?= e($userName ?? '') ?> · <?= e($userEmail ?? '') ?></span>
            <form method="post" action="<?= url('/logout') ?>">
                <?= csrf_field() ?>
                <button class="logout" type="submit" style="border:0;background:none;font:inherit;cursor:pointer;">Cerrar sesión</button>
            </form>
        </div>
    </header>
    <main><?= $content ?></main>
</body>
</html>
