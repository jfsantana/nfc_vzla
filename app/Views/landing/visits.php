<?php

declare(strict_types=1);

use App\Services\LandingVisitService;

require_once dirname(__DIR__, 2) . '/Services/LandingVisitService.php';
require_once dirname(__DIR__, 2) . '/Helpers/functions.php';

if (!isset($landingId) || !isset(LandingVisitService::LANDINGS[$landingId])) {
    http_response_code(404);
    exit('Landing no encontrada.');
}

header('Content-Type: text/html; charset=UTF-8');
header('Cache-Control: no-store, max-age=0');
header('X-Robots-Tag: noindex, nofollow');

$name = LandingVisitService::LANDINGS[$landingId];
$isHotel = $landingId === 'ghhoteles';
$now = new DateTimeImmutable('now', new DateTimeZone(LandingVisitService::TIMEZONE));
$statistics = null;

try {
    $statistics = (new LandingVisitService())->getStatistics($landingId, $now);
} catch (PDOException $exception) {
    error_log('[landing-visits][' . $landingId . '] ' . $exception->getMessage());
    http_response_code(503);
    header('Retry-After: 60');
}

$formatNumber = static fn (int $value): string => number_format($value, 0, ',', '.');
$formatDate = static fn (string $value): string => (new DateTimeImmutable(
    $value,
    new DateTimeZone(LandingVisitService::TIMEZONE)
))->format('d/m/Y H:i');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Visitas | <?= e($name) ?></title>
    <style>
        :root {
            color-scheme: dark;
            --accent: <?= $isHotel ? '#f1cb79' : '#45d4f1' ?>;
            --background: #0a0f18;
            --surface: #141e2e;
            --muted: #b0bdcf;
            --border: #2c3b50;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: radial-gradient(ellipse at top, #20314a, var(--background) 65%);
            color: #f8fafc;
            font-family: system-ui, sans-serif;
            line-height: 1.6;
            min-height: 100vh;
        }
        main { max-width: 1100px; margin: auto; padding: 40px 24px; }
        header { display: flex; justify-content: space-between; align-items: center; gap: 24px; flex-wrap: wrap; }
        h1 { margin: 4px 0; font-size: clamp(1.8rem, 5vw, 2.8rem); line-height: 1.2; }
        h2 { font-size: 1.25rem; margin-top: 0; }
        p { margin: 8px 0; }
        .eyebrow { color: var(--accent); font-size: .8rem; text-transform: uppercase; letter-spacing: .12em; }
        .muted, footer { color: var(--muted); }
        nav { display: flex; gap: 12px; flex-wrap: wrap; }
        a { color: var(--accent); }
        .button { padding: 10px 16px; border: 1px solid var(--border); border-radius: 12px; text-decoration: none; background: var(--surface); }
        a:hover { border-color: var(--accent); }
        a:focus-visible { outline: 3px solid var(--accent); outline-offset: 4px; }
        .metrics { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 16px; margin: 28px 0; }
        .metric, .panel { background: var(--surface); border: 1px solid var(--border); border-radius: 18px; padding: 24px; }
        .metric dt { color: var(--muted); }
        .metric dd { margin: 8px 0 0; color: var(--accent); font-size: clamp(1.6rem, 4vw, 2.3rem); font-weight: 750; overflow-wrap: anywhere; }
        .metric small { color: var(--muted); }
        .metric dd small { display: block; font-size: .8rem; font-weight: 400; }
        .panel { margin: 24px 0; }
        .empty { border-left: 4px solid var(--accent); }
        .error { border-color: #fb8b8b; }
        .table-wrap { overflow-x: auto; margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; }
        caption { text-align: left; color: var(--muted); margin-bottom: 12px; }
        th, td { text-align: left; padding: 10px 8px; border-bottom: 1px solid var(--border); }
        thead th { color: var(--muted); font-size: .85rem; }
        tbody th { font-weight: 400; white-space: nowrap; }
        .count { text-align: right; font-variant-numeric: tabular-nums; }
        .bar-cell { width: 50%; }
        .track { height: 8px; background: #27364b; border-radius: 8px; overflow: hidden; }
        .bar { height: 100%; background: var(--accent); border-radius: inherit; }
        footer { font-size: .875rem; padding-bottom: 16px; }
        @media (max-width: 700px) {
            .metrics { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            main { padding: 28px 16px; }
            .metric, .panel { padding: 18px; }
            .bar-cell { width: 35%; }
        }
    </style>
</head>
<body>
<main>
    <header>
        <div>
            <p class="eyebrow">Estad&iacute;sticas de tu landing page</p>
            <h1><?= e($name) ?></h1>
            <p class="muted">Conoce cu&aacute;ntas veces se ha abierto tu p&aacute;gina.</p>
        </div>
        <nav aria-label="Acciones">
            <a class="button" href="index.php">Ver landing page</a>
            <a class="button" href="visitas.php">Actualizar datos</a>
        </nav>
    </header>

    <?php if ($statistics === null): ?>
        <section class="panel error" role="alert">
            <h2>Estad&iacute;sticas no disponibles</h2>
            <p>No pudimos consultar el registro de visitas. Intenta actualizar en un minuto.</p>
            <p class="muted">Si el problema contin&uacute;a, contacta al administrador. No se muestran ceros porque no fue posible comprobar los datos.</p>
        </section>
    <?php else: ?>
        <dl class="metrics">
            <?php foreach ([
                ['Visitas totales', 'total', 'Desde el inicio del registro'],
                ['Hoy', 'today', $now->format('d/m/Y')],
                ['&Uacute;ltimos 7 d&iacute;as', 'last7', 'Incluye hoy'],
                ['&Uacute;ltimos 30 d&iacute;as', 'last30', 'Incluye hoy'],
            ] as [$label, $key, $detail]): ?>
                <div class="metric">
                    <dt><?= $label ?></dt>
                    <dd><?= $formatNumber($statistics[$key]) ?><small><?= e($detail) ?></small></dd>
                </div>
            <?php endforeach; ?>
        </dl>

        <?php if ($statistics['total'] === 0): ?>
            <section class="panel empty">
                <h2>Todo listo para comenzar</h2>
                <p>A&uacute;n no hay visitas registradas. Las cifras comenzar&aacute;n a crecer cuando se abra la landing page.</p>
            </section>
        <?php endif; ?>

        <section class="panel">
            <h2>Visitas por d&iacute;a</h2>
            <p class="muted">Actividad de los &uacute;ltimos 30 d&iacute;as. Cada recarga de la landing suma una nueva visita.</p>
            <div class="table-wrap">
                <table>
                    <caption>Del <?= $now->modify('-29 days')->format('d/m/Y') ?> al <?= $now->format('d/m/Y') ?></caption>
                    <thead><tr><th scope="col">Fecha</th><th scope="col" class="count">Visitas</th><th scope="col">Actividad</th></tr></thead>
                    <tbody>
                    <?php $maximum = max(1, ...array_values($statistics['days'])); ?>
                    <?php foreach (array_reverse($statistics['days'], true) as $day => $views): ?>
                        <tr>
                            <th scope="row"><?= (new DateTimeImmutable($day))->format('d/m/Y') ?></th>
                            <td class="count"><?= $formatNumber($views) ?></td>
                            <td class="bar-cell" aria-hidden="true">
                                <div class="track"><div class="bar" style="width: <?= number_format($views / $maximum * 100, 2, '.', '') ?>%"></div></div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="panel">
            <h2>C&oacute;mo interpretar estas cifras</h2>
            <p>Una visita equivale a una carga de la landing que llega al servidor. Se incluyen recargas, visitas propias y accesos automatizados; no son personas &uacute;nicas ni ventas confirmadas.</p>
            <p>Consultar o actualizar este panel no incrementa el contador. Los datos de cada landing se mantienen separados y no se guardan IP, cookies ni datos personales.</p>
            <p class="muted">El registro comienza con la instalaci&oacute;n de este contador; no incluye visitas anteriores.</p>
            <?php if ($statistics['firstVisit'] !== null): ?>
                <p class="muted">Primera visita registrada: <?= e($formatDate($statistics['firstVisit'])) ?>.<br>
                    &Uacute;ltima visita registrada: <?= e($formatDate($statistics['lastVisit'])) ?>.</p>
            <?php endif; ?>
        </section>
    <?php endif; ?>
    <footer>
        <p>Panel p&uacute;blico &middot; Zona horaria: Venezuela (<?= e(LandingVisitService::TIMEZONE) ?>).</p>
        <p>Consulta realizada: <?= $now->format('d/m/Y H:i:s') ?>. Usa &ldquo;Actualizar datos&rdquo; para consultar nuevamente.</p>
    </footer>
</main>
</body>
</html>
