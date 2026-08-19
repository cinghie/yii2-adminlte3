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

    function initializeChart(element) {
        if (element.getAttribute('data-cinghie-chartjs-initialized') === '1') {
            return;
        }

        if (typeof Chart !== 'function') {
            return;
        }

        var canvasId = element.getAttribute('data-cinghie-chartjs-canvas');
        var canvas = canvasId ? document.getElementById(canvasId) : null;
        if (!canvas) {
            return;
        }

        var type = element.getAttribute('data-cinghie-chartjs-type') || 'line';
        var data = parseJsonAttribute(element, 'data-cinghie-chartjs-data', {});
        var options = parseJsonAttribute(element, 'data-cinghie-chartjs-options', {});

        element.cinghieChart = new Chart(canvas.getContext('2d'), {
            type: type,
            data: data,
            options: options
        });
        element.setAttribute('data-cinghie-chartjs-initialized', '1');
    }

    function initializeAll() {
        var charts = document.querySelectorAll('[data-cinghie-chartjs]');
        for (var i = 0; i < charts.length; i += 1) {
            initializeChart(charts[i]);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeAll);
    } else {
        initializeAll();
    }
}());
