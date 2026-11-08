const { test } = require('node:test');
const assert = require('node:assert/strict');
const { readFileSync } = require('node:fs');
const { join } = require('node:path');
const { runInNewContext } = require('node:vm');

const source = readFileSync(join(__dirname, '..', 'ecard', 'shared', 'autism-guide.js'), 'utf8');

function setup(supported = true) {
    const classes = new Set();
    const dialog = {
        id: 'autism-guide-dialog',
        listeners: {},
        open: false,
        addEventListener(name, callback) { this.listeners[name] = callback; },
        getBoundingClientRect: () => ({ left: 20, right: 300, top: 20, bottom: 500 }),
        close() { this.open = false; this.listeners.close(); },
    };
    if (supported) dialog.showModal = () => { dialog.open = true; };
    const link = {
        attributes: {},
        setAttribute(name, value) { this.attributes[name] = value; },
        addEventListener(name, callback) { this[name] = callback; },
        focus() { this.focused = true; },
    };
    runInNewContext(source, {
        document: {
            getElementById: () => dialog,
            querySelectorAll: () => [link],
            documentElement: { classList: { add: (value) => classes.add(value), remove: (value) => classes.delete(value) } },
        },
    });
    const click = {
        button: 0,
        preventDefault() { this.prevented = true; },
    };
    return { dialog, link, classes, click };
}

test('opening locks background scrolling and closing restores focus', () => {
    const { dialog, link, classes, click } = setup();
    link.click(click);
    assert.equal(click.prevented, true);
    assert.equal(dialog.open, true);
    assert.equal(dialog.scrollTop, 0);
    assert.equal(classes.has('guide-is-open'), true);
    assert.equal(link.attributes['aria-haspopup'], 'dialog');
    dialog.close();
    assert.equal(classes.has('guide-is-open'), false);
    assert.equal(link.focused, true);
});

test('only a gesture beginning and ending outside the dialog closes it', () => {
    const { dialog, link, click } = setup();
    link.click(click);
    const inside = { target: dialog, clientX: 40, clientY: 40 };
    const outside = { target: dialog, clientX: 5, clientY: 5 };
    dialog.listeners.pointerdown(inside);
    dialog.listeners.click(inside);
    assert.equal(dialog.open, true);
    dialog.listeners.pointerdown(inside);
    dialog.listeners.click(outside);
    assert.equal(dialog.open, true);
    dialog.listeners.pointerdown(outside);
    dialog.listeners.click(outside);
    assert.equal(dialog.open, false);
});

test('modified links retain normal browser navigation', () => {
    for (const modifier of [{ ctrlKey: true }, { metaKey: true }, { shiftKey: true }, { altKey: true }, { button: 1 }]) {
        const { dialog, link, click } = setup();
        Object.assign(click, modifier);
        link.click(click);
        assert.equal(click.prevented, undefined);
        assert.equal(dialog.open, false);
    }
});

test('unsupported browsers keep the independent page link', () => {
    const { link } = setup(false);
    assert.equal(link.click, undefined);
    assert.equal(link.attributes['aria-haspopup'], undefined);
});
