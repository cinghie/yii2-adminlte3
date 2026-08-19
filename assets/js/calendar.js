(function () {
    'use strict';

    function parseJsonAttribute(element, name, fallback) {
        var value = element.getAttribute(name);
        if (!value) {
            return fallback;
        }

        try {
            return JSON.parse(value);
        } catch (error) {
            return fallback;
        }
    }

    function applyLegacyDefaults(options) {
        var version = typeof FullCalendar.version === 'string' ? FullCalendar.version : '';
        if (version.indexOf('4.') !== 0) {
            return options;
        }

        if (!options.plugins) {
            options.plugins = ['interaction', 'dayGrid', 'timeGrid', 'bootstrap'];
        }
        if (!options.themeSystem) {
            options.themeSystem = 'bootstrap';
        }

        return options;
    }

    function initializeCalendar(element) {
        if (element.getAttribute('data-cinghie-calendar-initialized') === '1') {
            return;
        }

        if (typeof FullCalendar === 'undefined' || typeof FullCalendar.Calendar !== 'function') {
            return;
        }

        var options = parseJsonAttribute(element, 'data-cinghie-calendar-options', {});
        options = applyLegacyDefaults(options);
        options.events = parseJsonAttribute(element, 'data-cinghie-calendar-events', []);

        var calendar = new FullCalendar.Calendar(element, options);
        calendar.render();
        element.cinghieCalendar = calendar;
        element.setAttribute('data-cinghie-calendar-initialized', '1');
    }

    function initializeAll() {
        var calendars = document.querySelectorAll('[data-cinghie-calendar]');
        for (var i = 0; i < calendars.length; i += 1) {
            initializeCalendar(calendars[i]);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeAll);
    } else {
        initializeAll();
    }
}());
