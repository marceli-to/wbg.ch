var Menu = (function() {

    /* --------------------------------------------------------------
     * VARIABLES
     * ------------------------------------------------------------ */
	
	// selectors
	var selectors = {
        html:           'html',
        body:           'body',
        btnMenu:        '.js-btn-menu',
        menu:           '.js-menu',
	};

    // css classes
    var classes = {
        active:  'is-active',
        visible: 'is-visible',
        open:    'is-open',
        parent:  'is-parent',
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
    };

    var _toggle = function() {
        $(selectors.menu).toggleClass(classes.visible);
        $(selectors.btnMenu).toggleClass(classes.active);
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

