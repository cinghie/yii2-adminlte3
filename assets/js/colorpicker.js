(function () {
    'use strict';

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

    function initialize(root) {
        if (root.getAttribute('data-cinghie-color-initialized') === '1') {
            return;
        }

        var input = root.querySelector('.cinghie-color-value');
        var nativePicker = root.querySelector('.cinghie-color-native');
        var toggle = root.querySelector('.cinghie-color-toggle');
        var palette = root.querySelector('.cinghie-color-popover');
        var preview = root.querySelector('.cinghie-color-preview');
        if (!input || !nativePicker || !toggle || !palette || !preview) {
            return;
        }

        function open(value) {
            palette.hidden = !value;
            toggle.setAttribute('aria-expanded', value ? 'true' : 'false');
        }

        function sync(value, fireChange) {
            var normalized = normalize(value);
            if (!normalized) {
                return;
            }

            input.value = normalized;
            nativePicker.value = normalized;
            preview.value = normalized;
            root.querySelectorAll('.cinghie-color-swatch').forEach(function (swatch) {
                var selected = swatch.getAttribute('data-color') === normalized;
                swatch.classList.toggle('is-selected', selected);
                swatch.setAttribute('aria-pressed', selected ? 'true' : 'false');
            });
            if (fireChange) {
                input.dispatchEvent(new Event('change', {bubbles: true}));
            }
        }

        toggle.addEventListener('click', function () {
            open(palette.hidden);
        });
        nativePicker.addEventListener('input', function () {
            sync(nativePicker.value, true);
        });
        nativePicker.addEventListener('change', function () {
            open(false);
            toggle.focus();
        });
        input.addEventListener('change', function () {
            sync(input.value, false);
        });
        root.addEventListener('click', function (event) {
            var swatch = event.target.closest('[data-color]');
            if (!swatch || !root.contains(swatch)) {
                return;
            }
            sync(swatch.getAttribute('data-color'), true);
            open(false);
            toggle.focus();
        });
        document.addEventListener('click', function (event) {
            if (!palette.hidden && !root.contains(event.target)) {
                open(false);
            }
        });
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && !palette.hidden) {
                open(false);
                toggle.focus();
            }
        });

        root.setAttribute('data-cinghie-color-initialized', '1');
    }

    function initializeAll() {
        document.querySelectorAll('[data-cinghie-color-picker]').forEach(initialize);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeAll);
    } else {
        initializeAll();
    }
}());
