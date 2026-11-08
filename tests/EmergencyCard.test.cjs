const { test } = require('node:test');
const assert = require('node:assert/strict');
const { readFileSync } = require('node:fs');
const { join } = require('node:path');
const { runInNewContext } = require('node:vm');

const source = readFileSync(join(__dirname, '..', 'ecard', 'esantana', 'emergency.js'), 'utf8');

function setup({ secure = true, geolocation, clipboard } = {}) {
    const elements = new Map();
    const element = (id) => {
        if (!elements.has(id)) {
            elements.set(id, {
                hidden: true,
                disabled: false,
                textContent: '',
                value: '',
                dataset: {},
                attributes: {},
                listeners: {},
                addEventListener(event, callback) { this.listeners[event] = callback; },
                setAttribute(name, value) { this.attributes[name] = value; },
                removeAttribute(name) { delete this.attributes[name]; delete this[name]; },
                focus() { this.focused = true; },
            });
        }
        return elements.get(id);
    };
    element('location-tools').dataset = { phone: '580000000000', message: 'Mensaje de prueba' };
    element('copy-phone').dataset = { phone: '+580000000000' };
    runInNewContext(source, {
        document: {
            getElementById: element,
            querySelectorAll: () => [element('copy-phone')],
        },
        navigator: { geolocation, clipboard },
        window: { isSecureContext: secure },
    });
    return { element, click: (id) => element(id).listeners.click() };
}

test('location is requested only on click; success prepares a message, never sends it', () => {
    let requests = 0;
    const { element, click } = setup({
        geolocation: {
            getCurrentPosition(success, failure, options) {
                requests++;
                assert.equal(options.timeout, 15000);
                assert.equal(options.maximumAge, 0);
                success({ coords: { latitude: 10.5, longitude: -66.9, accuracy: 150.2 } });
            },
        },
    });
    assert.equal(requests, 0);
    assert.equal(element('location-tools').hidden, false);
    click('locate');
    assert.equal(requests, 1);
    assert.equal(element('location-share').hidden, false);
    assert.match(decodeURIComponent(element('location-share').href), /maps\?q=10.5,-66.9/);
    assert.match(element('location-status').textContent, /151 m/);
    assert.match(element('location-status').textContent, /precisión es baja/);
    assert.match(element('location-status').textContent, /No se ha enviado/);
    assert.equal(element('locate').disabled, false);
});

for (const [code, expected] of [[1, /No se autorizó/], [2, /no pudo determinar/], [3, /Se agotó el tiempo/]]) {
    test(`geolocation error ${code} clears a previous location and offers a fallback`, () => {
        const { element, click } = setup({
            geolocation: { getCurrentPosition: (success, failure) => failure({ code }) },
        });
        element('location-share').href = 'https://example.invalid/old-location';
        element('location-share').hidden = false;
        click('locate');
        assert.match(element('location-status').textContent, expected);
        assert.equal(element('location-share').hidden, true);
        assert.equal(element('location-share').href, undefined);
        assert.equal(element('locate').disabled, false);
        assert.equal(element('location-tools').attributes['aria-busy'], undefined);
    });
}

test('insecure or unsupported browsers retain the manual fallback', () => {
    for (const options of [{ secure: false }, {}]) {
        const { element, click } = setup(options);
        click('locate');
        assert.match(element('location-status').textContent, /HTTPS/);
        assert.equal(element('location-share').hidden, true);
        assert.equal(element('locate').disabled, false);
    }
});

test('invalid coordinates are not offered for sharing', () => {
    const { element, click } = setup({
        geolocation: { getCurrentPosition: (success) => success({ coords: { latitude: NaN, longitude: 0, accuracy: 0 } }) },
    });
    click('locate');
    assert.match(element('location-status').textContent, /ubicación válida/);
    assert.equal(element('location-share').hidden, true);
});

test('manual location validates, URL-encodes and invalidates edited messages', () => {
    const { element, click } = setup();
    element('meeting-point').value = '   ';
    click('prepare-location');
    assert.equal(element('meeting-point').attributes['aria-invalid'], 'true');
    assert.equal(element('meeting-point').focused, true);
    assert.equal(element('manual-share').hidden, true);
    element('meeting-point').value = ' Entrada & café #2 ';
    click('prepare-location');
    assert.equal(element('meeting-point').attributes['aria-invalid'], undefined);
    const url = new URL(element('manual-share').href);
    assert.equal(url.searchParams.get('text'), 'Mensaje de prueba\nEstamos en: Entrada & café #2');
    assert.equal(element('manual-share').hidden, false);
    element('meeting-point').listeners.input();
    assert.equal(element('manual-share').hidden, true);
    assert.equal(element('manual-share').href, undefined);
});

test('copying the phone reports success or an actionable fallback', async () => {
    let copied;
    const success = setup({ clipboard: { writeText: async (value) => { copied = value; } } });
    await success.click('copy-phone');
    assert.equal(copied, '+580000000000');
    assert.match(success.element('contact-status').textContent, /Número copiado/);
    const denied = setup({ clipboard: { writeText: async () => { throw new Error('Permission denied'); } } });
    await denied.click('copy-phone');
    assert.match(denied.element('contact-status').textContent, /No se pudo copiar/);
    const unsupported = setup();
    await unsupported.click('copy-phone');
    assert.match(unsupported.element('contact-status').textContent, /\+580000000000/);
});
