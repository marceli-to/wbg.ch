var Competences = (function() {

    /* --------------------------------------------------------------
     * VARIABLES
     * ------------------------------------------------------------ */
	
	// selectors
	var selectors = {
        html:   'html',
        body:   'body',
        header: '.js-header',
	};

    // media queries
    var mq = {
        sm: window.matchMedia("(min-width: 720px)"),
        md: window.matchMedia("(min-width: 1024px)"),
        lg: window.matchMedia("(min-width: 1168px)")
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

        // Check for hash and scroll to it
        var hash = document.location.hash;
        if (hash.length) {
            _scrollTo(hash);
        }
    };

    /**
     * Scrolls to a certain project based on the given hash
     * @param string hash 
     */

    var _scrollTo = function(hash){
        var competence = hash.substr(1, hash.length), 
            header = $(selectors.header),
            target = $('[data-competence="' + competence + '"]');

        if (target.length)
        {
            var offsetTop = target.offset().top - header.height() - 80;
            if (mq.sm.matches) {
                offsetTop = target.offset().top - header.height() - 24;
            }
            $.scrollTo(offsetTop, 400);
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
    Competences.init();
});

