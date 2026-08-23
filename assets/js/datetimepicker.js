(function ($) {
    'use strict';

    function parseOptions(element) {
        var raw = element.getAttribute('data-cinghie-datetime-options');
        if (!raw) {
            return {};
        }

        try {
            return JSON.parse(raw);
        } catch (error) {
            return {};
        }
    }

    function initializePicker(element) {
        var picker = $(element);
        if (!picker.length || typeof $.fn.datetimepicker !== 'function') {
            return false;
        }
        if (!picker.data('cinghie-datetimepicker-ready')) {
            picker.datetimepicker(parseOptions(element));
            picker.data('cinghie-datetimepicker-ready', true);
        }

        picker.find('[data-cinghie-datetime-toggle]')
            .off('.cinghieDateTimePicker')
            .on('click.cinghieDateTimePicker keydown.cinghieDateTimePicker', function (event) {
                if (event.type === 'keydown' && event.key !== 'Enter' && event.key !== ' ') {
                    return;
                }
                event.preventDefault();
                picker.datetimepicker('show');
            });

        picker.find('.datetimepicker-input')
            .off('focus.cinghieDateTimePicker')
            .on('focus.cinghieDateTimePicker', function () {
                picker.datetimepicker('show');
            });

        return true;
    }

    function initializeAll() {
        $('[data-cinghie-datetimepicker]').each(function () {
            initializePicker(this);
        });
    }

    $(initializeAll);
    $(window).one('load.cinghieDateTimePicker', initializeAll);
}(jQuery));
