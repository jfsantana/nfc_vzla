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
    .button { border:0; border-radius:10px; padding:.75rem 1.1rem; font-family:inherit; font-size:.9rem; font-weight:700; cursor:pointer; text-decoration:none; }
    .button.primary { background:#0891b2; color:white; }
    .button.secondary { background:#f1f5f9; color:#334155; }
    @media(max-width:600px) { .form-grid { grid-template-columns:1fr; } .field.full { grid-column:auto; } .form-card { padding:1.25rem; } }
</style>

<div class="form-wrap">
    <div class="form-heading">
        <h1><?= $isEditing ? 'Editar empresa' : 'Nueva empresa' ?></h1>
        <p>El usuario administrador se administra desde la sección Usuarios de empresa.</p>
    </div>
    <?php if (!empty($error)): ?><div class="form-error"><?= e($error) ?></div><?php endif; ?>
    <form class="form-card" method="post" action="<?= url($isEditing ? '/admin/companies/update' : '/admin/companies/store') ?>">
        <?= csrf_field() ?>
        <?php if ($isEditing): ?><input type="hidden" name="id" value="<?= (int)$company['id'] ?>"><?php endif; ?>
        <div class="form-grid">
            <div class="field full">
                <label for="name">Nombre de la empresa</label>
                <input id="name" name="name" maxlength="150" required value="<?= e($company['name']) ?>" autocomplete="organization">
            </div>
            <div class="field full">
                <label for="slug">Identificador URL (slug)</label>
                <input id="slug" name="slug" maxlength="100" value="<?= e($company['slug']) ?>" placeholder="se-genera-del-nombre-si-se-deja-vacio">
                <span class="form-help">Único; se normaliza para utilizarlo en URLs.</span>
            </div>
            <div class="field">
                <label for="plan_id">Plan</label>
                <select id="plan_id" name="plan_id">
                    <option value="">Sin plan asignado</option>
                    <?php foreach ($plans as $plan): ?>
                        <option value="<?= (int)$plan['id'] ?>" <?= (string)$company['plan_id'] === (string)$plan['id'] ? 'selected' : '' ?>><?= e($plan['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="field">
                <label for="status">Estado</label>
                <select id="status" name="status" required>
                    <option value="active" <?= $company['status'] === 'active' ? 'selected' : '' ?>>Activa</option>
                    <option value="suspended_payment" <?= $company['status'] === 'suspended_payment' ? 'selected' : '' ?>>Suspendida por pago</option>
                    <option value="inactive" <?= $company['status'] === 'inactive' ? 'selected' : '' ?>>Inactiva</option>
                </select>
            </div>
            <div class="field">
                <label for="subscription_ends_at">Vencimiento de suscripción</label>
                <input id="subscription_ends_at" name="subscription_ends_at" type="date" value="<?= e($company['subscription_ends_at'] ?? '') ?>">
            </div>
        </div>
        <div class="form-actions">
            <a class="button secondary" href="<?= url('/admin/companies') ?>">Cancelar</a>
            <button class="button primary" type="submit"><?= $isEditing ? 'Guardar cambios' : 'Crear empresa' ?></button>
        </div>
    </form>
</div>
