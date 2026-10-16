<style>
    .page-heading { display:flex; justify-content:space-between; align-items:center; gap:1rem; margin-bottom:1.5rem; flex-wrap:wrap; }
    .page-heading h1 { color:var(--dark); font-size:1.8rem; font-weight:800; }
    .page-heading p { color:var(--gray-600); margin-top:.25rem; }
    .action-button { display:inline-flex; align-items:center; gap:.5rem; justify-content:center; padding:.7rem 1.1rem; border:0; border-radius:10px; background:#0891b2; color:white; font-weight:700; text-decoration:none; transition:background .2s; }
    .action-button:hover { background:#0e7490; }

    .status-filters { display:flex; gap:.5rem; margin-bottom:1.5rem; flex-wrap:wrap; }
    .filter-chip { display:inline-flex; align-items:center; gap:.4rem; padding:.5rem .9rem; border-radius:999px; background:white; border:1px solid var(--gray-200); color:var(--gray-700); font-size:.82rem; font-weight:700; text-decoration:none; transition:all .2s; }
    .filter-chip:hover, .filter-chip.active { background:#0891b2; color:white; border-color:#0891b2; }
    .filter-chip .count { background:rgba(0,0,0,0.08); padding:.15rem .45rem; border-radius:999px; font-size:.75rem; }
    .filter-chip.active .count { background:rgba(255,255,255,0.25); }

    .panel { overflow-x:auto; background:white; border:1px solid var(--gray-200); border-radius:16px; box-shadow:0 1px 3px rgba(0,0,0,0.02); }
    .data-table { width:100%; border-collapse:collapse; min-width:880px; }
    .data-table th,.data-table td { padding:1rem 1.1rem; text-align:left; border-bottom:1px solid var(--gray-100); }
    .data-table th { background:var(--gray-50); color:var(--gray-600); font-size:.78rem; text-transform:uppercase; letter-spacing:.05em; font-weight:700; }
    .data-table td { font-size:.9rem; vertical-align:middle; }

    .client-title { font-weight:800; color:var(--dark); font-size:.95rem; }
    .client-sub { color:var(--gray-600); font-size:.82rem; margin-top:.15rem; }
    .row-actions { display:flex; align-items:center; gap:.75rem; }
    .text-action { border:0; background:none; color:#0369a1; font-weight:700; text-decoration:underline; cursor:pointer; font-family:inherit; font-size:.85rem; }
    .danger-action { color:#b91c1c; }

    .badge-status { display:inline-block; border-radius:999px; padding:.25rem .65rem; font-size:.78rem; font-weight:700; text-transform:capitalize; }
    .badge-status.lead { background:#e0f2fe; color:#0369a1; }
    .badge-status.contacted { background:#fef3c7; color:#92400e; }
    .badge-status.qualified { background:#ede9fe; color:#6d28d9; }
    .badge-status.converted { background:#dcfce7; color:#166534; }
    .badge-status.discarded { background:#f1f5f9; color:#64748b; }

    .badge-source { display:inline-block; border-radius:6px; padding:.15rem .45rem; font-size:.72rem; font-weight:600; background:#f8fafc; color:#64748b; border:1px solid var(--gray-200); }

    .notice { padding:1rem 1.2rem; border-radius:12px; margin-bottom:1rem; font-weight:600; }
    .notice.success { color:#166534; background:#dcfce7; }
    .notice.error { color:#991b1b; background:#fee2e2; }
    .empty-state { padding:3rem 1.5rem; text-align:center; color:var(--gray-600); }
</style>

<div class="page-heading">
    <div>
        <h1>Clientes y Prospectos (Leads)</h1>
        <p>Empresas potenciales captadas a través del formulario de contacto público y registradas manualmente.</p>
    </div>
    <a class="action-button" href="<?= url('/admin/clients/new') ?>">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        Nuevo Cliente
    </a>
</div>

<?php if (!empty($success)): ?><div class="notice success"><?= e($success) ?></div><?php endif; ?>
<?php if (!empty($error)): ?><div class="notice error"><?= e($error) ?></div><?php endif; ?>

<!-- FILTROS POR ESTADO -->
<div class="status-filters">
    <a href="<?= url('/admin/clients') ?>" class="filter-chip <?= $statusFilter === '' ? 'active' : '' ?>">
        Todos <span class="count"><?= (int)($counts['total'] ?? 0) ?></span>
    </a>
    <a href="<?= url('/admin/clients?status=lead') ?>" class="filter-chip <?= $statusFilter === 'lead' ? 'active' : '' ?>">
        Nuevos (Leads) <span class="count"><?= (int)($counts['lead'] ?? 0) ?></span>
    </a>
    <a href="<?= url('/admin/clients?status=contacted') ?>" class="filter-chip <?= $statusFilter === 'contacted' ? 'active' : '' ?>">
        Contactados <span class="count"><?= (int)($counts['contacted'] ?? 0) ?></span>
    </a>
    <a href="<?= url('/admin/clients?status=qualified') ?>" class="filter-chip <?= $statusFilter === 'qualified' ? 'active' : '' ?>">
        Calificados <span class="count"><?= (int)($counts['qualified'] ?? 0) ?></span>
    </a>
    <a href="<?= url('/admin/clients?status=converted') ?>" class="filter-chip <?= $statusFilter === 'converted' ? 'active' : '' ?>">
        Convertidos en Tenant <span class="count"><?= (int)($counts['converted'] ?? 0) ?></span>
    </a>
    <a href="<?= url('/admin/clients?status=discarded') ?>" class="filter-chip <?= $statusFilter === 'discarded' ? 'active' : '' ?>">
        Descartados <span class="count"><?= (int)($counts['discarded'] ?? 0) ?></span>
    </a>
</div>

<div class="panel">
    <?php if ($clients === []): ?>
        <div class="empty-state">No hay registros de clientes o prospectos bajo este criterio.</div>
    <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Empresa / Prospecto</th>
                    <th>Contacto</th>
                    <th>Teléfono</th>
                    <th>Origen</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($clients as $c): ?>
                <tr>
                    <td>
                        <div class="client-title"><?= e($c['company_name']) ?></div>
                        <div class="client-sub">
                            <?php if (!empty($c['rif'])): ?>RIF: <?= e($c['rif']) ?> &bull; <?php endif; ?>
                            <?= e($c['email']) ?>
                        </div>
                        <?php if (!empty($c['converted_company_name'])): ?>
                            <div style="margin-top:.25rem; font-size:.78rem; color:#166534; font-weight:700;">
                                &check; Tenant: <?= e($c['converted_company_name']) ?>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong><?= e($c['name']) ?></strong>
                    </td>
                    <td>
                        <a href="tel:<?= e($c['phone']) ?>" style="color:#0284c7; font-weight:600; text-decoration:none;">
                            <?= e($c['phone']) ?>
                        </a>
                        <?php if (!empty($c['phone_secondary'])): ?>
                            <br><small style="color:#94a3b8;"><?= e($c['phone_secondary']) ?></small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge-source">
                            <?= $c['source'] === 'landing_contact' ? 'Landing Form' : 'Manual / Admin' ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge-status <?= e($c['status']) ?>">
                            <?= match($c['status']) {
                                'lead' => 'Nuevo Lead',
                                'contacted' => 'Contactado',
                                'qualified' => 'Calificado',
                                'converted' => 'Convertido',
                                'discarded' => 'Descartado',
                                default => $c['status'],
                            } ?>
                        </span>
                    </td>
                    <td style="color:#64748b; font-size:.82rem;">
                        <?= e(substr((string)$c['created_at'], 0, 10)) ?>
                    </td>
                    <td>
                        <div class="row-actions">
                            <a class="text-action" href="<?= url('/admin/clients/edit?id=' . (int)$c['id']) ?>">Ver / Editar</a>
                            <form method="post" action="<?= url('/admin/clients/delete') ?>" onsubmit="return confirm('¿Seguro que deseas eliminar este cliente?');">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
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
