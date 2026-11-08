(() => {
    'use strict';

    const dialog = document.getElementById('autism-guide-dialog');
    // Sin soporte nativo, los enlaces conservan la navegación a la guía independiente.
    if (!dialog || typeof dialog.showModal !== 'function') return;

    let opener;
    let startedOutside = false;
    const isOutside = (event) => {
        const bounds = dialog.getBoundingClientRect();
        return event.target === dialog && (
            event.clientX < bounds.left || event.clientX > bounds.right ||
            event.clientY < bounds.top || event.clientY > bounds.bottom
        );
    };

    document.querySelectorAll('[data-autism-guide]').forEach((link) => {
        link.setAttribute('aria-haspopup', 'dialog');
        link.setAttribute('aria-controls', dialog.id);
        link.addEventListener('click', (event) => {
            if (event.ctrlKey || event.metaKey || event.shiftKey || event.altKey || event.button !== 0) return;
            event.preventDefault();
            opener = link;
            startedOutside = false;
            dialog.showModal();
            dialog.scrollTop = 0;
            document.documentElement.classList.add('guide-is-open');
        });
    });

    dialog.addEventListener('pointerdown', (event) => {
        startedOutside = isOutside(event);
    });
    dialog.addEventListener('click', (event) => {
        if (startedOutside && isOutside(event)) dialog.close();
        startedOutside = false;
    });
    dialog.addEventListener('close', () => {
        document.documentElement.classList.remove('guide-is-open');
        if (opener) opener.focus({ preventScroll: true });
    });
})();
