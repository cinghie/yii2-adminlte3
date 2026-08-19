(function () {
    'use strict';

    document.addEventListener('click', function (event) {
        var trigger = event.target.closest('[data-cinghie-action="print"]');
        if (!trigger) {
            return;
        }

        event.preventDefault();
        window.print();
    });
}());
