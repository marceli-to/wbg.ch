var Menu = (function() {

    /* --------------------------------------------------------------
     * VARIABLES
     * ------------------------------------------------------------ */
	
	// selectors
	var selectors = {
        html:           'html',
        body:           'body',
        header:         'header',
        btnMenu:        '.js-btn-menu',
        menu:           '.js-menu',
        btnSub:         '.js-btn-sub-menu',
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

        $(selectors.body).on('click', selectors.btnSub, function(){
            _toggleSub(this);
        });

    };

    var _toggle = function() {
        $(selectors.menu).toggleClass(classes.visible);
        $(selectors.btnMenu).toggleClass(classes.active);
    };

    var _toggleSub = function(btn) {

        // The clicked item is parent (= top level) and child item is visible,
        // 1. hide all child items
        // 2. reset the menu
        if ($(btn).hasClass(classes.parent) && $(btn).next('ul').is(':visible')) {
            $(btn).parent('li').find('ul').each(function(){
                $(this).hide();
                $(this).removeClass(classes.open).hide();
            });
            _resetMenuHeight();
        }
        // The clicked item is parent (= top level) but child item is not visible,
        // 1. close all parent siblings
        // 2. reset the menu
        // 3. open child item
        // 4. increment menu
        else if ($(btn).hasClass(classes.parent) && $(btn).next('ul').is(':visible') == false) {
            $(selectors.menu).find('ul ul').each(function(){
                $(this).hide();
                $(this).removeClass(classes.open).hide();
            });

            $(btn).next('ul').addClass(classes.open).show();
            _incrementMenuHeight();
        }
        // The clicked item is NOT a parent but has visible child items
        // 1. save child items height
        // 2. hide child and its children
        // 3. decrement menu
        else if ($(btn).next('ul').is(':visible')) {
            let height = $(btn).next('ul').height();
            $(btn).next('ul').removeClass(classes.open).hide();
            $(btn).parent('li').find('ul').each(function(){
                $(this).hide();
                $(this).removeClass(classes.open).hide();
            });
            _incrementMenuHeight(height);
        }
        // The clicked its is NOT a parent and has no visible child items
        // 1. show child item
        // 2. increment menu
        else {
            $(btn).next('ul').addClass(classes.open).show();
            _incrementMenuHeight();
        }
    };

    var _incrementMenuHeight = function() {
        if (mq.md.matches) {
            let h = $(selectors.menu).find('ul.is-open').first().height();
            $(selectors.menu).height(h + 40);
        }
    }

    var _resetMenuHeight = function() {
        if (mq.md.matches) {
            $(selectors.menu).css('height', '');
        }
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

