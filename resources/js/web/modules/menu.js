var Menu = (function() {

    /* --------------------------------------------------------------
     * VARIABLES
     * ------------------------------------------------------------ */
	
	// selectors
	var selectors = {
        html:           'html',
        body:           'body',
        btnMenu:        '.js-btn-menu',
        btnSub:         '.js-btn-toggle-sub',
        menu:           '.js-menu',
	};

    // css classes
    var classes = {
        active:  'is-active',
        visible: 'is-visible',
        open:    'is-open',
        parent:  'is-parent',
        hasMenu: 'has-menu',
    };

    // media queries
    var mq = {
        sm: window.matchMedia("(min-width: 600px)"),
        md: window.matchMedia("(min-width: 900px)"),
        lg: window.matchMedia("(min-width: 1200px)")
    };

    /* --------------------------------------------------------------
     * METHODS
     * ------------------------------------------------------------ */
    
    // Init
	var _initialize = function() {
        _bind();
    };

    // Bind events
    var _bind = function() {

        $(selectors.body).on('click', selectors.btnMenu, function(){
            _toggle();
        });

        $(selectors.body).on('click', selectors.btnSub, function(){
            _toggleSub($(this));
        });

        // Elements with role="button" are expected to react to the space key
        $(selectors.body).on('keydown', selectors.btnMenu + ',' + selectors.btnSub, function(e){
            if (e.key === ' ') {
                e.preventDefault();
                $(this).trigger('click');
            }
        });
    };

    var _toggle = function() {
        $(selectors.menu).toggleClass(classes.visible);
        $(selectors.btnMenu).toggleClass(classes.active);
        $(selectors.html).toggleClass(classes.hasMenu);
        $(selectors.btnMenu).attr('aria-expanded', $(selectors.menu).hasClass(classes.visible) ? 'true' : 'false');
    };

    var _toggleSub = function(btn) {
        var list = $(btn).next('ul');
        list.toggle();
        $(btn).attr('aria-expanded', list.is(':visible') ? 'true' : 'false');
    };



    /* --------------------------------------------------------------
     * RETURN PUBLIC METHODS
     * ------------------------------------------------------------ */

    return {
        init:  _initialize,
	};
	
})();

// Initialize
$(function() {
    Menu.init();
});

