<style>
    .form-wrap { max-width:840px; margin:0 auto; }
    .form-heading { margin-bottom:1.5rem; }
    .form-heading h1 { color:var(--dark); font-size:1.8rem; font-weight:800; }
    .form-heading p { color:var(--gray-600); margin-top:.3rem; }
    .form-card { background:white; border:1px solid var(--gray-200); border-radius:16px; padding:2rem; box-shadow:0 1px 3px rgba(0,0,0,0.02); }
    .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:1.1rem; }
    .field { display:flex; flex-direction:column; gap:.4rem; }
    .field.full { grid-column:1/-1; }
    .field label { color:var(--dark); font-size:.88rem; font-weight:700; }
    .field input,.field select,.field textarea { width:100%; padding:.8rem .9rem; border:1px solid var(--gray-200); border-radius:10px; background:#fff; color:var(--dark); font:inherit; box-sizing:border-box; }
    .field textarea { resize:vertical; min-height:80px; }
    .field input:focus,.field select:focus,.field textarea:focus { outline:3px solid rgba(6,182,212,.18); border-color:#06b6d4; }
    .form-help { color:var(--gray-600); font-size:.8rem; }
    .form-error { padding:.9rem 1rem; margin-bottom:1rem; color:#991b1b; background:#fee2e2; border-radius:10px; font-weight:600; }
    .form-actions { display:flex; justify-content:flex-end; gap:.75rem; margin-top:1.5rem; }
    .button { border:0; border-radius:10px; padding:.75rem 1.25rem; font-weight:700; cursor:pointer; text-decoration:none; font-family:inherit; font-size:.9rem; display:inline-flex; align-items:center; gap:.5rem; }
    .button.primary { background:#0891b2; color:white; }
    .button.primary:hover { background:#0e7490; }
    .button.secondary { background:#f1f5f9; color:#334155; }
    .button.secondary:hover { background:#e2e8f0; }

    .section-divider { grid-column:1/-1; margin-top:.75rem; padding-top:.75rem; border-top:1px dashed var(--gray-200); font-size:.8rem; font-weight:800; text-transform:uppercase; letter-spacing:.05em; color:var(--gray-400); }
    @media(max-width:600px) { .form-grid { grid-template-columns:1fr; } .field.full { grid-column:auto; } .form-card { padding:1.25rem; } }
</style>

<div class="form-wrap">
    <div class="form-heading">
        <h1><?= $isEditing ? 'Ficha de Cliente / Prospecto' : 'Registrar Nuevo Cliente' ?></h1>
        <p>Datos corporativos, información fiscal, contacto comercial y vinculación como tenant.</p>
    </div>

    <?php if (!empty($error)): ?><div class="form-error"><?= e($error) ?></div><?php endif; ?>

    <form class="form-card" method="post" action="<?= url($isEditing ? '/admin/clients/update' : '/admin/clients/store') ?>">
        <?= csrf_field() ?>
        <?php if ($isEditing): ?><input type="hidden" name="id" value="<?= (int)$client['id'] ?>"><?php endif; ?>

        <div class="form-grid">
            <div class="section-divider">Datos de la Empresa & Contacto</div>

            <div class="field">
                <label for="company_name">Nombre de la Empresa *</label>
                <input id="company_name" name="company_name" maxlength="150" required value="<?= e($client['company_name']) ?>" placeholder="Ej: Corporación Industrial C.A.">
            </div>

            <div class="field">
                <label for="rif">RIF / Identificación Fiscal</label>
                <input id="rif" name="rif" maxlength="30" value="<?= e($client['rif'] ?? '') ?>" placeholder="Ej: J-12345678-9">
            </div>

            <div class="field">
                <label for="name">Persona de Contacto *</label>
                <input id="name" name="name" maxlength="120" required value="<?= e($client['name']) ?>" placeholder="Ej: Lic. Roberto García">
            </div>

            <div class="field">
                <label for="email">Correo Electrónico *</label>
                <input id="email" name="email" type="email" maxlength="150" required value="<?= e($client['email']) ?>" placeholder="contacto@empresa.com">
            </div>

            <div class="field">
                <label for="phone">Teléfono Principal *</label>
                <input id="phone" name="phone" maxlength="50" required value="<?= e($client['phone']) ?>" placeholder="Ej: +58 412 1234567">
            </div>

            <div class="field">
                <label for="phone_secondary">Teléfono Auxiliar / Celular</label>
                <input id="phone_secondary" name="phone_secondary" maxlength="50" value="<?= e($client['phone_secondary'] ?? '') ?>" placeholder="Ej: +58 241 8765432">
            </div>

            <div class="field full">
                <label for="address">Dirección Fiscal / Sede</label>
                <textarea id="address" name="address" rows="2" placeholder="Ubicación física o dirección comercial"><?= e($client['address'] ?? '') ?></textarea>
            </div>

            <div class="section-divider">Seguimiento Comercial & Estado</div>

            <div class="field">
                <label for="status">Estado del Prospecto</label>
                <select id="status" name="status" required>
                    <option value="lead" <?= ($client['status'] ?? 'lead') === 'lead' ? 'selected' : '' ?>>Nuevo Lead</option>
                    <option value="contacted" <?= ($client['status'] ?? '') === 'contacted' ? 'selected' : '' ?>>Contactado</option>
                    <option value="qualified" <?= ($client['status'] ?? '') === 'qualified' ? 'selected' : '' ?>>Calificado / Interesado</option>
                    <option value="converted" <?= ($client['status'] ?? '') === 'converted' ? 'selected' : '' ?>>Convertido en Tenant</option>
                    <option value="discarded" <?= ($client['status'] ?? '') === 'discarded' ? 'selected' : '' ?>>Descartado</option>
                </select>
            </div>

            <div class="field">
                <label for="converted_company_id">Empresa Tenant Asociada (Opcional)</label>
                <select id="converted_company_id" name="converted_company_id">
                    <option value="">Ninguna / Aún en negociación</option>
                    <?php foreach ($companies as $comp): ?>
                        <option value="<?= (int)$comp['id'] ?>" <?= (string)($client['converted_company_id'] ?? '') === (string)$comp['id'] ? 'selected' : '' ?>>
                            <?= e($comp['name']) ?> (<?= e($comp['slug']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="form-help">Si este prospecto ya fue dado de alta como Tenant, puedes vincularlo aquí.</span>
            </div>

            <div class="field full">
                <label for="notes">Observaciones / Requerimientos Comerciales</label>
                <textarea id="notes" name="notes" rows="4" placeholder="Notas sobre número de tarjetas solicitadas, acuerdos de precios, reuniones realizadas, etc."><?= e($client['notes'] ?? '') ?></textarea>
            </div>
        </div>

        <div class="form-actions">
            <a class="button secondary" href="<?= url('/admin/clients') ?>">Cancelar</a>
            <button class="button primary" type="submit">
                <?= $isEditing ? 'Guardar Cambios' : 'Registrar Cliente' ?>
            </button>
        </div>
    </form>
</div>
