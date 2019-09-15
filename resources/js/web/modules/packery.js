import Packery from '../vendor/packery/packery.min.js';

var PackeryUi = (function() {

    /* --------------------------------------------------------------
     * VARIABLES
     * ------------------------------------------------------------ */
	
	// selectors
	var selectors = {
        html: 'html',
        body: 'body',
	};

    /* --------------------------------------------------------------
     * METHODS
     * ------------------------------------------------------------ */
    
    // Init
	var _initialize = function() {

        var $grid = $('.js-masonry').packery({
            itemSelector: '.span',
            percentPosition: true,
            gutter: 24,
            //stagger: 30,
            transitionDuration: 0
        });

        $grid.on('click', '.js-btn-packery', function( event ) {
            var $item = $(event.currentTarget);
            $item.parent('.span').toggleClass('has-detail');
            $item.next('div').toggle();

            if ($item.parent('.span').hasClass('.has-detail')) {
                $grid.packery('fit', event.currentTarget);
            }
            else {
                $grid.packery('shiftLayout');
            }
        });

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
    PackeryUi.init();
});

