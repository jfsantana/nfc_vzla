<style>
    .page-heading { display:flex; justify-content:space-between; align-items:center; gap:1rem; margin-bottom:1.5rem; }
    .page-heading h1 { color:var(--dark); font-size:1.8rem; font-weight:800; }
    .page-heading p { color:var(--gray-600); margin-top:.25rem; }
    .action-button { display:inline-flex; align-items:center; gap:.5rem; justify-content:center; padding:.7rem 1.1rem; border:0; border-radius:10px; background:#0891b2; color:white; font-weight:700; text-decoration:none; transition:background .2s; }
    .action-button:hover { background:#0e7490; }
    .panel { overflow-x:auto; background:white; border:1px solid var(--gray-200); border-radius:16px; box-shadow:0 1px 3px rgba(0,0,0,0.02); }
    .data-table { width:100%; border-collapse:collapse; min-width:720px; }
    .data-table th,.data-table td { padding:1rem 1.1rem; text-align:left; border-bottom:1px solid var(--gray-100); }
    .data-table th { background:var(--gray-50); color:var(--gray-600); font-size:.78rem; text-transform:uppercase; letter-spacing:.05em; font-weight:700; }
    .data-table td { font-size:.9rem; vertical-align:middle; }
    .user-cell { display:flex; align-items:center; gap:.75rem; }
    .user-icon { width:34px; height:34px; border-radius:50%; background:linear-gradient(135deg, #0891b2 0%, #2563eb 100%); color:white; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:.82rem; }
    .row-actions { display:flex; align-items:center; gap:.75rem; }
    .text-action { border:0; background:none; color:#0369a1; font-weight:700; text-decoration:underline; cursor:pointer; font-family:inherit; font-size:.85rem; }
    .danger-action { color:#b91c1c; }
    .self-badge { display:inline-block; border-radius:999px; padding:.2rem .55rem; font-size:.72rem; font-weight:700; background:#f1f5f9; color:#64748b; }
    .badge { display:inline-block; border-radius:999px; padding:.25rem .65rem; font-size:.78rem; font-weight:700; background:#dcfce7; color:#166534; }
    .badge.inactive { background:#f1f5f9; color:#475569; }
    .badge.superadmin { background:#fef3c7; color:#92400e; border:1px solid #fde68a; }
    .notice { padding:1rem 1.2rem; border-radius:12px; margin-bottom:1rem; font-weight:600; }
    .notice.success { color:#166534; background:#dcfce7; }
    .notice.error { color:#991b1b; background:#fee2e2; }
    .empty-state { padding:3rem 1.5rem; text-align:center; color:var(--gray-600); }
    .role-tag { display:inline-flex; align-items:center; gap:.35rem; }
    @media(max-width:650px) { .page-heading { align-items:flex-start; flex-direction:column; } }
</style>

<div class="page-heading">
    <div>
        <h1>Usuarios del Sistema</h1>
        <p>Control central de accesos globales. Todos los administradores listados tienen rol exclusivo de <strong>Súper Administrador</strong>.</p>
    </div>
    <a class="action-button" href="<?= url('/admin/system-users/new') ?>">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        Nuevo Administrador
    </a>
</div>

<?php if (!empty($success)): ?><div class="notice success"><?= e($success) ?></div><?php endif; ?>
<?php if (!empty($error)): ?><div class="notice error"><?= e($error) ?></div><?php endif; ?>

<div class="panel">
    <?php if ($users === []): ?>
        <div class="empty-state">No hay administradores registrados en el sistema.</div>
    <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Administrador</th>
                    <th>Correo Electrónico</th>
                    <th>Rol Global</th>
                    <th>Estado</th>
                    <th>Fecha de Alta</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $user): ?>
                <?php $isSelf = (int)$user['id'] === (int)$currentUserId; ?>
                <tr>
                    <td>
                        <div class="user-cell">
                            <div class="user-icon"><?= strtoupper(substr($user['name'], 0, 1)) ?></div>
                            <div>
                                <strong><?= e($user['name']) ?></strong>
                                <?php if ($isSelf): ?>
                                    <span class="self-badge">Tu cuenta</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td><?= e($user['email']) ?></td>
                    <td>
                        <span class="badge superadmin role-tag">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                            <?= e($user['role_name'] ?? 'Súper Administrador') ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge <?= e($user['status']) ?>">
                            <?= $user['status'] === 'active' ? 'Activo' : 'Inactivo' ?>
                        </span>
                    </td>
                    <td><?= e(substr((string)$user['created_at'], 0, 10)) ?></td>
                    <td>
                        <div class="row-actions">
                            <a class="text-action" href="<?= url('/admin/system-users/edit?id=' . (int)$user['id']) ?>">Editar</a>
                            <?php if (!$isSelf): ?>
                                <form method="post" action="<?= url('/admin/system-users/delete') ?>" onsubmit="return confirm('¿Seguro que deseas eliminar al administrador <?= e(addslashes($user['name'])) ?>? Esta acción no se puede deshacer.');">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= (int)$user['id'] ?>">
                                    <button class="text-action danger-action" type="submit">Eliminar</button>
                                </form>
                            <?php else: ?>
                                <span style="font-size:.8rem; color:#94a3b8;" title="No puedes eliminar tu propia sesión activa">(Sesión activa)</span>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
