<style>
    .page-heading { display:flex; justify-content:space-between; align-items:center; gap:1rem; margin-bottom:1.5rem; }
    .page-heading h1 { color:var(--dark); font-size:1.8rem; font-weight:800; }
    .page-heading p { color:var(--gray-600); margin-top:.25rem; }
    .action-button { display:inline-flex; align-items:center; justify-content:center; padding:.7rem 1rem; border:0; border-radius:10px; background:#0891b2; color:white; font-weight:700; text-decoration:none; }
    .action-button:hover { background:#0e7490; }
    .panel { overflow-x:auto; background:white; border:1px solid var(--gray-200); border-radius:16px; }
    .data-table { width:100%; border-collapse:collapse; min-width:700px; }
    .data-table th,.data-table td { padding:1rem; text-align:left; border-bottom:1px solid var(--gray-100); }
    .data-table th { background:var(--gray-50); color:var(--gray-600); font-size:.78rem; text-transform:uppercase; letter-spacing:.05em; }
    .data-table td { font-size:.9rem; }
    .row-actions { display:flex; align-items:center; gap:.65rem; }
    .text-action { border:0; background:none; color:#0369a1; font-weight:700; text-decoration:underline; cursor:pointer; }
    .danger-action { color:#b91c1c; }
    .badge { display:inline-block; border-radius:999px; padding:.25rem .65rem; font-size:.78rem; font-weight:700; background:#dcfce7; color:#166534; }
    .badge.inactive { background:#f1f5f9; color:#475569; }
    .notice { padding:1rem 1.2rem; border-radius:12px; margin-bottom:1rem; font-weight:600; }
    .notice.success { color:#166534; background:#dcfce7; }
    .notice.error { color:#991b1b; background:#fee2e2; }
    .empty-state { padding:3rem 1.5rem; text-align:center; color:var(--gray-600); }
    @media(max-width:650px) { .page-heading { align-items:flex-start; flex-direction:column; } }
</style>

<div class="page-heading">
    <div><h1>Usuarios de empresa</h1><p>Administra la cuenta de acceso principal de cada empresa. El SuperAdmin no se modifica desde esta sección.</p></div>
    <a class="action-button" href="<?= url('/admin/users/new') ?>">+ Nuevo usuario</a>
</div>

<?php if (!empty($success)): ?><div class="notice success"><?= e($success) ?></div><?php endif; ?>
<?php if (!empty($error)): ?><div class="notice error"><?= e($error) ?></div><?php endif; ?>

<div class="panel">
    <?php if ($users === []): ?>
        <div class="empty-state">No hay usuarios de empresa. Primero registra una empresa y luego crea su usuario.</div>
    <?php else: ?>
        <table class="data-table">
            <thead><tr><th>Nombre</th><th>Correo</th><th>Empresa</th><th>Rol</th><th>Estado</th><th>Acciones</th></tr></thead>
            <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><strong><?= e($user['name']) ?></strong></td>
                    <td><?= e($user['email']) ?></td>
                    <td><?= e($user['company_name']) ?></td>
                    <td>Administrador de empresa</td>
                    <td><span class="badge <?= e($user['status']) ?>"><?= $user['status'] === 'active' ? 'Activo' : 'Inactivo' ?></span></td>
                    <td>
                        <div class="row-actions">
                            <a class="text-action" href="<?= url('/admin/users/edit?id=' . (int)$user['id']) ?>">Editar</a>
                            <form method="post" action="<?= url('/admin/users/delete') ?>" onsubmit="return confirm('¿Eliminar este usuario? Las tarjetas asociadas quedarán sin usuario y podrán reasignarse.');">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= (int)$user['id'] ?>">
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
