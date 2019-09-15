var Team = (function() {

    /* --------------------------------------------------------------
     * VARIABLES
     * ------------------------------------------------------------ */
	
	// selectors
	var selectors = {
        html: 'html',
        body: 'body',
        btn:  '.js-btn-more',
	};

    // css classes
    var classes = {
        active:  'is-active',
        visible: 'is-visible',
    };

    // media queries
    var mq = {
        sm: window.matchMedia("(min-width: 768px)"),
        md: window.matchMedia("(min-width: 1024px)"),
        lg: window.matchMedia("(min-width: 1440px)")
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

        $(selectors.body).on('click', selectors.btn, function(){
            _toggle(this);
        });
    };

    var _toggle = function(btn) {
        $(btn).next('div').toggle();
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
    Team.init();
});

