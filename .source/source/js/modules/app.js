var App = (function() {

    /* --------------------------------------------------------------
     * VARIABLES
     * ------------------------------------------------------------ */
	
	// selectors
	var selectors = {
        body: 'body',
        overlay: '.js-overlay'
    };
    
    var mq = window.matchMedia("(min-width: 720px)");

    /* --------------------------------------------------------------
     * METHODS
     * ------------------------------------------------------------ */
     
	var _initialize = function() {
        _bind();
    };

    var _bind = function() {
        
        $(selectors.body).on('mouseover', selectors.overlay, function(){
            if (mq.matches) {
                _showOverlay($(this));
            }
        });

        $(selectors.body).on('mouseout', selectors.overlay, function(){
            if (mq.matches) {
                _hideOverlay($(this));
            }
        });
        
        $('[data-fancybox]').fancybox({
            toolbar: true,
            buttons: ["close"],
            btnTpl: {
                arrowLeft:  '<a href="javascript:;" class="btn-fancybox-nav is-prev" data-fancybox-prev></a>',
                arrowRight: '<a href="javascript:;" class="btn-fancybox-nav is-next" data-fancybox-next></a>',
                close: '<a href="javascript:;" data-fancybox-close class="btn-fancybox-close"></a>',
            }           
        });
    };

    var _showOverlay = function(el) {
        $(selectors.overlay).addClass('has-overlay');
        el.removeClass('has-overlay');
    };

    var _hideOverlay = function() {
        $(selectors.overlay).removeClass('has-overlay');
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
    App.init();
});

