(function () {
    'use strict';

    var globalHandlersRegistered = false;

    function normalize(value) {
        var v = String(value || '').trim();
        if (/^#[0-9a-f]{6}$/i.test(v)) {
            return v.toLowerCase();
        }
        if (/^#[0-9a-f]{3}$/i.test(v)) {
            return ('#' + v.slice(1).split('').map(function (c) { return c + c; }).join('')).toLowerCase();
        }
        return null;
    }

    function paintCanvas(canvas, value) {
        var normalized = normalize(value);
        if (!canvas || !normalized || typeof canvas.getContext !== 'function') {
            return;
        }
        var context = canvas.getContext('2d');
        if (!context) {
            return;
        }
        context.clearRect(0, 0, canvas.width, canvas.height);
        context.fillStyle = normalized;
        context.fillRect(0, 0, canvas.width, canvas.height);
        canvas.setAttribute('data-color', normalized);
    }

    function paintRoot(root) {
        root.querySelectorAll('canvas[data-color]').forEach(function (canvas) {
            paintCanvas(canvas, canvas.getAttribute('data-color'));
        });
    }

    function setOpen(root, value) {
        var palette = root.querySelector('.cinghie-color-popover');
        var toggle = root.querySelector('.cinghie-color-toggle');
        if (!palette || !toggle) {
            return;
        }
        palette.hidden = !value;
        toggle.setAttribute('aria-expanded', value ? 'true' : 'false');
    }

    function sync(root, value, fireChange) {
        var normalized = normalize(value);
        if (!normalized) {
            return;
        }

        var input = root.querySelector('.cinghie-color-value');
        var nativePicker = root.querySelector('.cinghie-color-native');
        var preview = root.querySelector('.cinghie-color-preview');
        if (!input || !nativePicker || !preview) {
            return;
        }

        input.value = normalized;
        nativePicker.value = normalized;
        paintCanvas(preview, normalized);
        root.querySelectorAll('.cinghie-color-swatch').forEach(function (swatch) {
            var selected = swatch.getAttribute('data-color') === normalized;
            swatch.classList.toggle('is-selected', selected);
            swatch.setAttribute('aria-pressed', selected ? 'true' : 'false');
        });
        if (fireChange) {
            input.dispatchEvent(new Event('change', {bubbles: true}));
        }
    }

    function registerGlobalHandlers() {
        if (globalHandlersRegistered) {
            return;
        }

        document.addEventListener('click', function (event) {
            document.querySelectorAll('[data-cinghie-color-initialized="1"]').forEach(function (root) {
                var palette = root.querySelector('.cinghie-color-popover');
                if (palette && !palette.hidden && !root.contains(event.target)) {
                    setOpen(root, false);
                }
            });
        });
        document.addEventListener('keydown', function (event) {
            if (event.key !== 'Escape') {
                return;
            }
            document.querySelectorAll('[data-cinghie-color-initialized="1"]').forEach(function (root) {
                var palette = root.querySelector('.cinghie-color-popover');
                var toggle = root.querySelector('.cinghie-color-toggle');
                if (palette && !palette.hidden) {
                    setOpen(root, false);
                    if (toggle) {
                        toggle.focus();
                    }
                }
            });
        });
        globalHandlersRegistered = true;
    }

    function initialize(root) {
        if (root.getAttribute('data-cinghie-color-initialized') === '1') {
            return;
        }

        var input = root.querySelector('.cinghie-color-value');
        var nativePicker = root.querySelector('.cinghie-color-native');
        var toggle = root.querySelector('.cinghie-color-toggle');
        var palette = root.querySelector('.cinghie-color-popover');
        if (!input || !nativePicker || !toggle || !palette) {
            return;
        }

        paintRoot(root);
        toggle.addEventListener('click', function () {
            setOpen(root, palette.hidden);
        });
        nativePicker.addEventListener('input', function () {
            sync(root, nativePicker.value, true);
        });
        nativePicker.addEventListener('change', function () {
            setOpen(root, false);
            toggle.focus();
        });
        input.addEventListener('change', function () {
            sync(root, input.value, false);
        });
        root.addEventListener('click', function (event) {
            var swatch = event.target.closest('[data-color]');
            if (!swatch || !root.contains(swatch)) {
                return;
            }
            sync(root, swatch.getAttribute('data-color'), true);
            setOpen(root, false);
            toggle.focus();
        });

        root.setAttribute('data-cinghie-color-initialized', '1');
    }

    function initializeAll() {
        registerGlobalHandlers();
        document.querySelectorAll('[data-cinghie-color-picker]').forEach(initialize);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeAll);
    } else {
        initializeAll();
    }
}());
