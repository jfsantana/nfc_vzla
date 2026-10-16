<style>
    .page-heading { display:flex; justify-content:space-between; align-items:center; gap:1rem; margin-bottom:1.5rem; }
    .page-heading h1 { color:var(--dark); font-size:1.8rem; font-weight:800; }
    .page-heading p { color:var(--gray-600); margin-top:.25rem; }
    .action-button { display:inline-flex; align-items:center; justify-content:center; padding:.7rem 1rem; border:0; border-radius:10px; background:#0891b2; color:white; font-family:inherit; font-size:.9rem; font-weight:600; cursor:pointer; text-decoration:none; }
    .action-button:hover { background:#0e7490; }
    .panel { overflow-x:auto; background:white; border:1px solid var(--gray-200); border-radius:16px; }
    .data-table { width:100%; border-collapse:collapse; min-width:760px; }
    .data-table th,.data-table td { padding:1rem; text-align:left; border-bottom:1px solid var(--gray-100); }
    .data-table th { background:var(--gray-50); color:var(--gray-600); font-size:.78rem; text-transform:uppercase; letter-spacing:.05em; }
    .data-table td { font-size:.9rem; }
    .row-actions { display:flex; align-items:center; gap:.65rem; }
    .text-action { border:0; background:none; color:#0369a1; font-family:inherit; font-size:.85rem; font-weight:600; text-decoration:underline; cursor:pointer; }
    .danger-action { color:#b91c1c; }
    .badge { display:inline-block; border-radius:999px; padding:.25rem .65rem; font-size:.78rem; font-weight:700; background:#dcfce7; color:#166534; }
    .badge.inactive { background:#f1f5f9; color:#475569; }
    .badge.suspended_payment { background:#fef3c7; color:#92400e; }
    .notice { padding:1rem 1.2rem; border-radius:12px; margin-bottom:1rem; font-weight:600; }
    .notice.success { color:#166534; background:#dcfce7; }
    .notice.error { color:#991b1b; background:#fee2e2; }
    .empty-state { padding:3rem 1.5rem; text-align:center; color:var(--gray-600); }
    @media(max-width:650px) { .page-heading { align-items:flex-start; flex-direction:column; } }
</style>

<div class="page-heading">
    <div><h1>Empresas</h1><p>Gestiona las organizaciones, sus planes y el estado de sus cuentas.</p></div>
    <a class="action-button" href="<?= url('/admin/companies/new') ?>">+ Nueva empresa</a>
</div>

<?php if (!empty($success)): ?><div class="notice success"><?= e($success) ?></div><?php endif; ?>
<?php if (!empty($error)): ?><div class="notice error"><?= e($error) ?></div><?php endif; ?>

<div class="panel">
    <?php if ($companies === []): ?>
        <div class="empty-state">Aún no hay empresas. Crea la primera para comenzar.</div>
    <?php else: ?>
        <table class="data-table">
            <thead><tr><th>Empresa</th><th>Plan</th><th>Estado</th><th>Usuarios</th><th>Tarjetas</th><th>Suscripción</th><th>Acciones</th></tr></thead>
            <tbody>
            <?php foreach ($companies as $company): ?>
                <tr>
                    <td><strong><?= e($company['name']) ?></strong><br><small><?= e($company['slug']) ?></small></td>
                    <td><?= e($company['plan_name'] ?? 'Sin plan') ?></td>
                    <td><span class="badge <?= e($company['status']) ?>"><?= e(match ($company['status']) { 'active' => 'Activa', 'suspended_payment' => 'Pago pendiente', default => 'Inactiva' }) ?></span></td>
                    <td><?= (int)$company['admin_count'] ?> / 1</td>
                    <td><?= (int)$company['card_count'] ?></td>
                    <td><?= e($company['subscription_ends_at'] ?: 'Sin vencimiento') ?></td>
                    <td>
                        <div class="row-actions">
                            <a class="text-action" href="<?= url('/admin/companies/edit?id=' . (int)$company['id']) ?>">Editar</a>
                            <form method="post" action="<?= url('/admin/companies/delete') ?>" onsubmit="return confirm('¿Eliminar esta empresa? Sus usuarios administradores también se eliminarán. Las empresas con tarjetas asociadas no se pueden borrar.');">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= (int)$company['id'] ?>">
                                <button class="text-action danger-action" type="submit">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
