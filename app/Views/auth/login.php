<?php if (!empty($error)): ?>
    <div class="alert-error">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="8" x2="12" y2="12"></line>
            <line x1="12" y1="16" x2="12.01" y2="16"></line>
        </svg>
        <?= e($error) ?>
    </div>
<?php endif; ?>

<form action="<?= url('/login') ?>" method="POST">
    <?= csrf_field() ?>

    <div class="form-group">
        <label for="email" class="form-label">Correo Electrónico</label>
        <input 
            type="email" 
            id="email" 
            name="email" 
            class="form-control" 
            value="<?= e($oldEmail ?? '') ?>" 
            placeholder="superadmin@sistemanfc.com" 
            required 
            autofocus
        >
    </div>

    <div class="form-group">
        <label for="password" class="form-label">Contraseña</label>
        <input 
            type="password" 
            id="password" 
            name="password" 
            class="form-control" 
            placeholder="••••••••" 
            required
        >
    </div>

    <button type="submit" class="btn-submit">
        Entrar al Sistema
    </button>
</form>

<div style="margin-top: 1.5rem; padding: 0.9rem; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; font-size: 0.82rem; color: #166534; text-align: left;">
    <strong>Credenciales del SuperAdmin de prueba:</strong><br>
    📧 <code>superadmin@sistemanfc.com</code><br>
    🔑 <code>Admin123*</code>
</div>
