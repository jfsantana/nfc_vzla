(() => {
    'use strict';

    const contactStatus = document.getElementById('contact-status');
    document.querySelectorAll('.copy-phone').forEach((button) => {
        button.hidden = false;
        button.addEventListener('click', async () => {
            const phone = button.dataset.phone;
            if (!navigator.clipboard || !window.isSecureContext) {
                contactStatus.textContent = `Puedes seleccionar y copiar este número: ${phone}`;
                return;
            }
            try {
                await navigator.clipboard.writeText(phone);
                contactStatus.textContent = 'Número copiado. Puedes pegarlo en tu aplicación de llamadas.';
            } catch {
                contactStatus.textContent = `No se pudo copiar. Selecciona y copia este número: ${phone}`;
            }
        });
    });

    const tools = document.getElementById('location-tools');
    const locate = document.getElementById('locate');
    const status = document.getElementById('location-status');
    const share = document.getElementById('location-share');
    const meetingPoint = document.getElementById('meeting-point');
    const manualStatus = document.getElementById('manual-status');
    const manualShare = document.getElementById('manual-share');
    const whatsappUrl = (text) => `https://wa.me/${tools.dataset.phone}?text=${encodeURIComponent(`${tools.dataset.message}\n${text}`)}`;
    tools.hidden = false;

    locate.addEventListener('click', () => {
        share.hidden = true;
        share.removeAttribute('href');
        if (!window.isSecureContext || !navigator.geolocation) {
            status.textContent = 'La ubicación necesita HTTPS y un navegador compatible. Escribe el lugar más abajo o compártelo directamente desde WhatsApp.';
            return;
        }

        locate.disabled = true;
        tools.setAttribute('aria-busy', 'true');
        status.textContent = 'Esperando tu permiso y la ubicación del dispositivo…';
        navigator.geolocation.getCurrentPosition(
            ({ coords }) => {
                locate.disabled = false;
                tools.removeAttribute('aria-busy');
                if (!Number.isFinite(coords.latitude) || !Number.isFinite(coords.longitude) || !Number.isFinite(coords.accuracy)) {
                    status.textContent = 'El dispositivo no devolvió una ubicación válida. Escribe un punto de referencia o llama a la familia.';
                    return;
                }
                const accuracy = Math.ceil(coords.accuracy);
                const map = `https://www.google.com/maps?q=${coords.latitude},${coords.longitude}`;
                const time = new Date().toLocaleTimeString('es', { hour: '2-digit', minute: '2-digit' });
                status.textContent = `Ubicación obtenida a las ${time}, con un margen aproximado de ${accuracy} m.${accuracy > 100 ? ' La precisión es baja; añade un punto de referencia en el chat.' : ''} No se ha enviado. Revisa y envía el mensaje en WhatsApp.`;
                share.href = whatsappUrl(`Estoy junto al niño. Ubicación de mi dispositivo a las ${time} (margen aproximado: ${accuracy} m): ${map}`);
                share.hidden = false;
            },
            (error) => {
                locate.disabled = false;
                tools.removeAttribute('aria-busy');
                const reasons = {
                    1: 'No se autorizó el acceso a la ubicación.',
                    2: 'El dispositivo no pudo determinar la ubicación.',
                    3: 'Se agotó el tiempo para obtener la ubicación.',
                };
                status.textContent = `${reasons[error.code] || 'No se pudo obtener la ubicación.'} Puedes reintentar, escribir el lugar más abajo o llamar a la familia.`;
            },
            { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
        );
    });

    meetingPoint.addEventListener('input', () => {
        manualShare.hidden = true;
        manualShare.removeAttribute('href');
        manualStatus.textContent = '';
        meetingPoint.removeAttribute('aria-invalid');
    });

    document.getElementById('prepare-location').addEventListener('click', () => {
        const place = meetingPoint.value.trim();
        if (!place) {
            manualStatus.textContent = 'Escribe un lugar o punto de referencia antes de preparar el mensaje.';
            meetingPoint.setAttribute('aria-invalid', 'true');
            meetingPoint.focus();
            return;
        }
        meetingPoint.removeAttribute('aria-invalid');
        manualShare.href = whatsappUrl(`Estamos en: ${place}`);
        manualShare.hidden = false;
        manualStatus.textContent = 'Mensaje preparado, todavía no enviado. Ábrelo en WhatsApp para revisarlo y enviarlo.';
    });
})();
