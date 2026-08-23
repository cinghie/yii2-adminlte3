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

    function normalizeVersionOptions(options) {
        var version = typeof FullCalendar.version === 'string' ? FullCalendar.version : '';
        var legacy = version.indexOf('4.') === 0;

        if (legacy) {
            if (!options.header && options.headerToolbar) {
                options.header = options.headerToolbar;
            }
            if (!options.defaultView && options.initialView) {
                options.defaultView = options.initialView;
            }
            if (!options.defaultDate && options.initialDate) {
                options.defaultDate = options.initialDate;
            }

            delete options.headerToolbar;
            delete options.initialView;
            delete options.initialDate;
            return options;
        }

        if (!options.headerToolbar && options.header) {
            options.headerToolbar = options.header;
        }
        if (!options.initialView && options.defaultView) {
            options.initialView = options.defaultView;
        }
        if (!options.initialDate && options.defaultDate) {
            options.initialDate = options.defaultDate;
        }

        delete options.header;
        delete options.defaultView;
        delete options.defaultDate;

        return options;
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

    function updateCalendarSize(element) {
        if (!element || !element.cinghieCalendar || typeof element.cinghieCalendar.updateSize !== 'function') {
            return;
        }
        window.requestAnimationFrame(function () {
            element.cinghieCalendar.updateSize();
        });
    }

    function observeCalendarSize(element) {
        if (typeof ResizeObserver === 'function') {
            var observer = new ResizeObserver(function () {
                updateCalendarSize(element);
            });
            observer.observe(element.parentElement || element);
            element.cinghieCalendarResizeObserver = observer;
        }

        window.addEventListener('resize', function () {
            updateCalendarSize(element);
        });

        document.addEventListener('collapsed.lte.pushmenu', function () {
            updateCalendarSize(element);
        });
        document.addEventListener('shown.lte.pushmenu', function () {
            updateCalendarSize(element);
        });
    }

    function initializeCalendar(element) {
        if (element.getAttribute('data-cinghie-calendar-initialized') === '1') {
            return;
        }

        if (typeof FullCalendar === 'undefined' || typeof FullCalendar.Calendar !== 'function') {
            return;
        }

        var options = parseJsonAttribute(element, 'data-cinghie-calendar-options', {});
        options = normalizeVersionOptions(options);
        options = applyLegacyDefaults(options);
        options.events = parseJsonAttribute(element, 'data-cinghie-calendar-events', []);

        var calendar = new FullCalendar.Calendar(element, options);
        calendar.render();
        element.cinghieCalendar = calendar;
        element.setAttribute('data-cinghie-calendar-initialized', '1');
        observeCalendarSize(element);
        updateCalendarSize(element);
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
