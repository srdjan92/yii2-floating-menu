var STATE_COLLAPSED = 'collapsed';
var STATE_EXPANDED = 'expanded';
var COOKIE_KEY = 'floating_menu_state';

$(function() {
    var floatingMenu = $('.floating-menu');
    var collapseMenuBtn = $('.collapse-floating-menu');
    var expandMenuBtn = $('.expand-floating-menu');

    $('.nav-toggle').on('click', function(e) {
        e.preventDefault();

        if(floatingMenu.data('state') === STATE_COLLAPSED) {
            floatingMenu.find('.nav-item').animate({left: 0}, 200);

            collapseMenuBtn.show();
            expandMenuBtn.hide();

            floatingMenu.data('state', STATE_EXPANDED);
            floatingMenu.removeClass('nav-collapsed').addClass('nav-expanded');

            document.cookie = COOKIE_KEY + "=" + STATE_EXPANDED + ";path=/;";
        } else {
            floatingMenu.find('.nav-item').animate({left: -40}, 200);

            collapseMenuBtn.hide();
            expandMenuBtn.show();

            floatingMenu.data('state', STATE_COLLAPSED);
            floatingMenu.removeClass('nav-expanded').addClass('nav-collapsed');

            document.cookie = COOKIE_KEY + "=" + STATE_COLLAPSED + ";path=/;";
        }
    });

    floatingMenu.fadeIn();
    
    var windowHeight = $(window).height();
    var elementHeight = floatingMenu.find('.floating-nav').height();

    floatingMenu.css('top', (windowHeight - elementHeight) / 2);
    
});



