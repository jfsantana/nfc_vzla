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
            placeholder="correo@ejemplo.com" 
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

    <div class="form-group">
        <label for="captcha-input-login" class="form-label">Código de Seguridad</label>
        <div style="display: flex; gap: 0.6rem; align-items: center; margin-bottom: 0.5rem;">
            <img 
                id="captcha-image-login" 
                src="<?= $captchaImage ?>" 
                alt="Código CAPTCHA" 
                style="height: 42px; border-radius: 10px; border: 1px solid var(--gray-200); cursor: pointer;"
                title="Haz clic sobre la imagen para recargar el código"
                onclick="reloadCaptcha('login')"
            >
            <button 
                type="button" 
                onclick="reloadCaptcha('login')" 
                title="Recargar nuevo código"
                style="background: #f1f5f9; border: 1px solid var(--gray-200); border-radius: 10px; padding: 0.65rem 0.8rem; cursor: pointer; color: var(--gray-700); display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s;"
            >
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="23 4 23 10 17 10"></polyline>
                    <polyline points="1 20 1 14 7 14"></polyline>
                    <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                </svg>
            </button>
        </div>
        <input 
            type="text" 
            id="captcha-input-login" 
            name="captcha" 
            class="form-control" 
            placeholder="Escribe el código de la imagen" 
            maxlength="6" 
            autocomplete="off" 
            required
            style="text-transform: uppercase; font-weight: 700; letter-spacing: 0.1em;"
        >
        <span style="font-size: 0.76rem; color: var(--gray-400); margin-top: 0.3rem; display: block;">
            Protección anti-fuerza bruta. No distingue mayúsculas de minúsculas.
        </span>
    </div>

    <button type="submit" class="btn-submit">
        Entrar al Sistema
    </button>
</form>

<script>
function reloadCaptcha(action) {
    fetch('<?= url('/captcha/refresh') ?>?action=' + encodeURIComponent(action))
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data && data.image) {
                var img = document.getElementById('captcha-image-' + action);
                if (img) img.src = data.image;
                var input = document.getElementById('captcha-input-' + action);
                if (input) { input.value = ''; input.focus(); }
            }
        })
        .catch(function(err) { console.error('Error al recargar captcha:', err); });
}
</script>
