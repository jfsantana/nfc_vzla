<style>
    .form-wrap { max-width:760px; margin:0 auto; }
    .form-heading { margin-bottom:1.5rem; }
    .form-heading h1 { color:var(--dark); font-size:1.8rem; font-weight:800; }
    .form-heading p { color:var(--gray-600); margin-top:.3rem; }
    .form-card { background:white; border:1px solid var(--gray-200); border-radius:16px; padding:2rem; }
    .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:1.1rem; }
    .field { display:flex; flex-direction:column; gap:.4rem; }
    .field.full { grid-column:1/-1; }
    .field label { color:var(--dark); font-size:.88rem; font-weight:700; }
    .field input,.field select { width:100%; padding:.8rem .9rem; border:1px solid var(--gray-200); border-radius:10px; background:#fff; color:var(--dark); font:inherit; }
    .field input:focus,.field select:focus { outline:3px solid rgba(6,182,212,.18); border-color:#06b6d4; }
    .form-help { color:var(--gray-600); font-size:.8rem; }
    .form-error { padding:.9rem 1rem; margin-bottom:1rem; color:#991b1b; background:#fee2e2; border-radius:10px; font-weight:600; }
    .form-actions { display:flex; justify-content:flex-end; gap:.75rem; margin-top:1.5rem; }
    .button { border:0; border-radius:10px; padding:.75rem 1.1rem; font-weight:700; cursor:pointer; text-decoration:none; }
    .button.primary { background:#0891b2; color:white; }
    .button.secondary { background:#f1f5f9; color:#334155; }
    @media(max-width:600px) { .form-grid { grid-template-columns:1fr; } .field.full { grid-column:auto; } .form-card { padding:1.25rem; } }
</style>

<div class="form-wrap">
    <div class="form-heading">
        <h1><?= $isEditing ? 'Editar usuario de empresa' : 'Nuevo usuario de empresa' ?></h1>
        <p>Se asigna el rol Administrador de Empresa. Cada empresa puede tener un usuario principal.</p>
    </div>
    <?php if (!empty($error)): ?><div class="form-error"><?= e($error) ?></div><?php endif; ?>
    <form class="form-card" method="post" action="<?= url($isEditing ? '/admin/users/update' : '/admin/users/store') ?>">
        <?= csrf_field() ?>
        <?php if ($isEditing): ?><input type="hidden" name="id" value="<?= (int)$user['id'] ?>"><?php endif; ?>
        <div class="form-grid">
            <?php if (!$isEditing): ?>
                <div class="field full">
                    <label for="company_id">Empresa</label>
                    <select id="company_id" name="company_id" required>
                        <option value="">Selecciona una empresa sin usuario asignado</option>
                        <?php foreach ($companies as $company): ?>
                            <option value="<?= (int)$company['id'] ?>"><?= e($company['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($companies === []): ?><span class="form-help">No hay empresas disponibles. Crea primero una empresa o revisa si ya tiene usuario asignado.</span><?php endif; ?>
                </div>
            <?php else: ?>
                <div class="field full">
                    <label>Empresa asignada</label>
                    <input value="<?= e($user['company_name']) ?>" readonly>
                    <span class="form-help">La empresa no se cambia desde la edición de usuarios.</span>
                </div>
            <?php endif; ?>
            <div class="field">
                <label for="name">Nombre</label>
                <input id="name" name="name" maxlength="100" required value="<?= e($user['name']) ?>" autocomplete="name">
            </div>
            <div class="field">
                <label for="email">Correo electrónico</label>
                <input id="email" name="email" type="email" maxlength="150" required value="<?= e($user['email']) ?>" autocomplete="email">
            </div>
            <div class="field">
                <label for="password"><?= $isEditing ? 'Nueva contraseña (opcional)' : 'Contraseña inicial' ?></label>
                <input id="password" name="password" type="password" <?= $isEditing ? '' : 'required' ?> minlength="12" autocomplete="new-password">
                <span class="form-help"><?= $isEditing ? 'Déjala en blanco para mantener la contraseña actual.' : 'Debe tener al menos 12 caracteres.' ?></span>
            </div>
            <?php if ($isEditing): ?>
                <div class="field">
                    <label for="status">Estado de la cuenta</label>
                    <select id="status" name="status" required>
                        <option value="active" <?= $user['status'] === 'active' ? 'selected' : '' ?>>Activo</option>
                        <option value="inactive" <?= $user['status'] === 'inactive' ? 'selected' : '' ?>>Inactivo</option>
                    </select>
                </div>
            <?php endif; ?>
        </div>
        <div class="form-actions">
            <a class="button secondary" href="<?= url('/admin/users') ?>">Cancelar</a>
            <button class="button primary" type="submit"><?= $isEditing ? 'Guardar cambios' : 'Crear usuario' ?></button>
        </div>
    </form>
</div>
