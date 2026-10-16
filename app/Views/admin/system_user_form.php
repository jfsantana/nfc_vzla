<style>
    .form-wrap { max-width:760px; margin:0 auto; }
    .form-heading { margin-bottom:1.5rem; }
    .form-heading h1 { color:var(--dark); font-size:1.8rem; font-weight:800; }
    .form-heading p { color:var(--gray-600); margin-top:.3rem; }
    .form-card { background:white; border:1px solid var(--gray-200); border-radius:16px; padding:2rem; box-shadow:0 1px 3px rgba(0,0,0,0.02); }
    .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:1.1rem; }
    .field { display:flex; flex-direction:column; gap:.4rem; }
    .field.full { grid-column:1/-1; }
    .field label { color:var(--dark); font-size:.88rem; font-weight:700; }
    .field input,.field select { width:100%; padding:.8rem .9rem; border:1px solid var(--gray-200); border-radius:10px; background:#fff; color:var(--dark); font:inherit; box-sizing:border-box; }
    .field input:focus,.field select:focus { outline:3px solid rgba(6,182,212,.18); border-color:#06b6d4; }
    .field input[readonly] { background:var(--gray-50); color:var(--gray-600); cursor:not-allowed; }
    .form-help { color:var(--gray-600); font-size:.8rem; }
    .form-error { padding:.9rem 1rem; margin-bottom:1rem; color:#991b1b; background:#fee2e2; border-radius:10px; font-weight:600; }
    .form-actions { display:flex; justify-content:flex-end; gap:.75rem; margin-top:1.5rem; }
    .button { border:0; border-radius:10px; padding:.75rem 1.25rem; font-weight:700; cursor:pointer; text-decoration:none; font-family:inherit; font-size:.9rem; display:inline-flex; align-items:center; gap:.5rem; }
    .button.primary { background:#0891b2; color:white; }
    .button.primary:hover { background:#0e7490; }
    .button.secondary { background:#f1f5f9; color:#334155; }
    .button.secondary:hover { background:#e2e8f0; }
    .role-callout { background:#fffbeb; border:1px solid #fef3c7; border-radius:12px; padding:1rem 1.25rem; display:flex; align-items:center; gap:.85rem; margin-bottom:1.5rem; }
    .role-callout-icon { width:38px; height:38px; border-radius:10px; background:#fde68a; color:#b45309; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .role-callout-text h4 { font-size:.92rem; font-weight:800; color:#92400e; margin-bottom:.15rem; }
    .role-callout-text p { font-size:.82rem; color:#b45309; margin:0; }
    @media(max-width:600px) { .form-grid { grid-template-columns:1fr; } .field.full { grid-column:auto; } .form-card { padding:1.25rem; } }
</style>

<div class="form-wrap">
    <div class="form-heading">
        <h1><?= $isEditing ? 'Editar Administrador del Sistema' : 'Nuevo Administrador del Sistema' ?></h1>
        <p>Módulo exclusivo de administración global. Este usuario no está vinculado a ninguna empresa ni tenant.</p>
    </div>

    <?php if (!empty($error)): ?><div class="form-error"><?= e($error) ?></div><?php endif; ?>

    <div class="role-callout">
        <div class="role-callout-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
        </div>
        <div class="role-callout-text">
            <h4>Rol Exclusivo: Súper Administrador (superadmin)</h4>
            <p>Todos los usuarios de este módulo tienen privilegios máximos sobre la plataforma, métricas y suscripciones.</p>
        </div>
    </div>

    <form class="form-card" method="post" action="<?= url($isEditing ? '/admin/system-users/update' : '/admin/system-users/store') ?>">
        <?= csrf_field() ?>
        <?php if ($isEditing): ?><input type="hidden" name="id" value="<?= (int)$user['id'] ?>"><?php endif; ?>

        <div class="form-grid">
            <div class="field full">
                <label for="name">Nombre Completo</label>
                <input id="name" name="name" maxlength="100" required value="<?= e($user['name']) ?>" autocomplete="name" placeholder="Ej: Carlos Mendoza">
            </div>

            <div class="field">
                <label for="email">Correo Electrónico</label>
                <input id="email" name="email" type="email" maxlength="150" required value="<?= e($user['email']) ?>" autocomplete="email" placeholder="admin@sistemanfc.com">
                <span class="form-help">Se utilizará para iniciar sesión en el panel SaaS.</span>
            </div>

            <div class="field">
                <label for="status">Estado de la Cuenta</label>
                <?php $isSelf = $isEditing && (int)$user['id'] === (int)$currentUserId; ?>
                <select id="status" name="status" required <?= $isSelf ? 'disabled' : '' ?>>
                    <option value="active" <?= ($user['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Activo</option>
                    <option value="inactive" <?= ($user['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactivo</option>
                </select>
                <?php if ($isSelf): ?>
                    <input type="hidden" name="status" value="active">
                    <span class="form-help">No puedes inactivar tu propia cuenta en sesión.</span>
                <?php else: ?>
                    <span class="form-help">Los administradores inactivos no pueden ingresar al sistema.</span>
                <?php endif; ?>
            </div>

            <div class="field full">
                <label for="password"><?= $isEditing ? 'Nueva Contraseña (Opcional)' : 'Contraseña de Acceso' ?></label>
                <input id="password" name="password" type="password" <?= $isEditing ? '' : 'required' ?> minlength="12" autocomplete="new-password" placeholder="Mínimo 12 caracteres">
                <span class="form-help"><?= $isEditing ? 'Déjala en blanco para mantener la contraseña actual. Si la cambias, debe tener al menos 12 caracteres.' : 'Requerida. Debe contener un mínimo de 12 caracteres para garantizar la seguridad del SaaS.' ?></span>
            </div>
        </div>

        <div class="form-actions">
            <a class="button secondary" href="<?= url('/admin/system-users') ?>">Cancelar</a>
            <button class="button primary" type="submit">
                <?= $isEditing ? 'Guardar Cambios' : 'Crear Administrador' ?>
            </button>
        </div>
    </form>
</div>
