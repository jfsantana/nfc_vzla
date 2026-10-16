<style>
    .welcome { margin-bottom:2rem; }
    .welcome h1 { color:#0f172a; font-size:clamp(1.7rem,4vw,2.2rem); font-weight:800; }
    .welcome p { margin-top:.5rem; color:#64748b; }
    .metrics { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:1rem; }
    .metric { background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.5rem; }
    .metric span { display:block; color:#64748b; font-size:.85rem; font-weight:600; }
    .metric strong { display:block; margin-top:.5rem; color:#0f172a; font-size:2rem; }
    .details { margin-top:1.5rem; padding:1.5rem; background:#fff; border:1px solid #e2e8f0; border-radius:16px; }
    .details h2 { margin-bottom:1rem; color:#0f172a; font-size:1.15rem; }
    .details p { margin:.55rem 0; }
    .active { color:#15803d; font-weight:700; }
    @media(max-width:700px) { .metrics { grid-template-columns:1fr; } }
</style>

<section class="welcome">
    <h1>Bienvenido, <?= e($userName) ?></h1>
    <p>Este es el resumen de la cuenta para <strong><?= e($company['name']) ?></strong>.</p>
</section>

<section class="metrics" aria-label="Resumen de la empresa">
    <article class="metric"><span>Tarjetas registradas</span><strong><?= (int)$company['card_count'] ?></strong></article>
    <article class="metric"><span>Tarjetas activas</span><strong><?= (int)$company['active_card_count'] ?></strong></article>
    <article class="metric"><span>Escaneos acumulados</span><strong><?= (int)$company['scan_count'] ?></strong></article>
</section>

<section class="details">
    <h2>Datos de la cuenta</h2>
    <p><strong>Empresa:</strong> <?= e($company['name']) ?></p>
    <p><strong>Plan:</strong> <?= e($company['plan_name'] ?? 'Sin plan asignado') ?></p>
    <p><strong>Estado:</strong> <span class="active">Activa</span></p>
    <p><strong>Vencimiento:</strong> <?= e($company['subscription_ends_at'] ?: 'Sin vencimiento') ?></p>
</section>
