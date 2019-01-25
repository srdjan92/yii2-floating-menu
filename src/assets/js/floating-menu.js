'use strict';

var fmwidget = {};

fmwidget = (function ($) {
    return {
        STATE_COLLAPSED: 'collapsed',
        STATE_EXPANDED: 'expanded',
        COOKIE_KEY: 'fmwidget-state',

        selector: '.fmwidget',
        collapseBtnSelector: '.fmwidget-collapse',
        expandBtnSelector: '.fmwidget-expand',
        toggleSelector: '.fmwidget-toggle',

        init: function (options) {
            var floatingMenu = $(fmwidget.selector);

            fmwidget.center(floatingMenu);
            fmwidget.registerEvents(floatingMenu);
        },
        center: function(el) {
            el.fadeIn();

            var windowHeight = $(window).height();
            var elementHeight = el.find('.fmwidget-nav').height();

            el.css('top', (windowHeight - elementHeight) / 2);
        },
        registerEvents: function(el) {
            var toggleBtn = $(fmwidget.toggleSelector);
            var collapseBtn = $(fmwidget.collapseBtnSelector);
            var expandBtn = $(fmwidget.expandBtnSelector);

            toggleBtn.on('click', function(e) {
                e.preventDefault();

                if(el.data('state') === fmwidget.STATE_COLLAPSED) {
                    el.find('.fmwidget-item').animate({left: 0}, 200);

                    collapseBtn.show();
                    expandBtn.hide();

                    el.data('state', fmwidget.STATE_EXPANDED);
                    el.removeClass('fmwidget-collapsed').addClass('fmwidget-expanded');

                    document.cookie = fmwidget.COOKIE_KEY + "=" + fmwidget.STATE_EXPANDED + ";path=/;";
                } else {
                    el.find('.fmwidget-item').animate({left: -40}, 200);

                    collapseBtn.hide();
                    expandBtn.show();

                    el.data('state', fmwidget.STATE_COLLAPSED);
                    el.removeClass('fmwidget-expanded').addClass('fmwidget-collapsed');

                    document.cookie = fmwidget.COOKIE_KEY + "=" + fmwidget.STATE_COLLAPSED + ";path=/;";
                }
            });
        }
    };
})(jQuery);



