var App = (function() {

    /* --------------------------------------------------------------
     * VARIABLES
     * ------------------------------------------------------------ */
	
	// selectors
	var selectors = {
        body: 'body',
        overlay: '.js-overlay'
	};

    /* --------------------------------------------------------------
     * METHODS
     * ------------------------------------------------------------ */
     
	var _initialize = function() {
        _bind();
    };

    var _bind = function() {
        $(selectors.body).on('mouseover', selectors.overlay, function(){
            _showOverlay($(this));
        });
        $(selectors.body).on('mouseout', selectors.overlay, function(){
            _hideOverlay($(this));
        });    
    };

    var _showOverlay = function(el) {
        $(selectors.overlay).addClass('has-overlay');
        el.removeClass('has-overlay');
        
    };

    var _hideOverlay = function() {
        $(selectors.overlay).removeClass('has-overlay');
    }

    /* --------------------------------------------------------------
     * RETURN PUBLIC METHODS
     * ------------------------------------------------------------ */

    return {
        init:  _initialize,
	};
	
})();

// Initialize
$(function() {
    App.init();
});

