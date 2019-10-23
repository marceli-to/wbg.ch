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
    };

    var _toggle = function() {
        $(selectors.menu).toggleClass(classes.visible);
        $(selectors.btnMenu).toggleClass(classes.active);
        $(selectors.html).toggleClass(classes.hasMenu);
    };

    var _toggleSub = function(btn) {
        $(btn).next('ul').toggle();
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

