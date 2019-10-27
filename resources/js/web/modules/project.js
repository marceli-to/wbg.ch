function debounce(a,b,c){var d;return function(){var e=this,f=arguments;clearTimeout(d),d=setTimeout(function(){d=null,c||a.apply(e,f)},b),c&&!d&&a.apply(e,f)}}

var Projects = (function() {

    /* --------------------------------------------------------------
     * VARIABLES
     * ------------------------------------------------------------ */
	
	// selectors
	var selectors = {
        html:      'html',
        body:      'body',
        header:    '.js-header',
        footer:    '.js-footer',
        menu:      '.js-menu',
        project:   '.js-project-info',
        btnToggle: '.js-toggle-info'
	};

    // media queries
    var mq = {
        sm: window.matchMedia("(min-width: 720px)"),
        md: window.matchMedia("(min-width: 1024px)"),
        lg: window.matchMedia("(min-width: 1168px)")
    };

    var classes = {
        tiny: 'is-tiny',
        open: 'is-open',
        visible: 'is-visible'
    };

    var winHeight = $(window).height();
    var boxHeight = $(selectors.project).outerHeight();
    

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

        // Resize height on load
        if (mq.sm.matches) {
            if ($(selectors.project).length) {
                _resize(true);
            }
        }

        // Observe height to adjust project info box
        $(window).resize(function(event){
            if (mq.sm.matches && $(selectors.project).length) {
                _resize(false);
            }
        });

        // Toggle project info box
        $(selectors.body).on('click', selectors.btnToggle, function(){
            _toggleInfo($(this));
        });
    };

    /**
     * Scrolls to a certain project based on the given hash
     * @param string hash 
     */

    var _scrollTo = function(hash){
        var project = hash.substr(1, hash.length), 
            header = $(selectors.header),
            target = $('[data-project="' + project + '"]');

        var offsetTop = target.offset().top - header.height() - 80;
        if (mq.sm.matches) {
            offsetTop = target.offset().top - header.height() - 24;
        }

        $.scrollTo(offsetTop, 400);
    };

    /**
     * Handle position of project info box on resize
     */

    var _resize = debounce(function(init){

        // get all heights
        var heights = {
            window: $(window).height(),
            header: $(selectors.header).height(),
            footer: $(selectors.footer).outerHeight(),
            menu:   $(selectors.menu).outerHeight(),
            box:    $(selectors.project).outerHeight(),
        };

        // going bigger
        if (heights.window > winHeight) {
            var minHeight = boxHeight + heights.header + heights.footer + heights.menu + 100;
            if (heights.window > minHeight) {
                $(selectors.project).removeClass(classes.tiny);
                $(selectors.project).removeAttr('style');
            }
        }

        // going smaller
        if (heights.window < winHeight || init) {
            var offset = $(selectors.project).position().top, minOffset = heights.header + heights.menu;
            if (offset <= minOffset) {
                $(selectors.project).addClass(classes.tiny);
                $(selectors.project).css('top', (heights.header + heights.menu));
            }
        }

        winHeight = heights.window;

    }, 10);

    var _toggleInfo = function(btn){
        $(selectors.project).toggleClass(classes.visible);
        $(btn).toggleClass(classes.open);
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
    Projects.init();
});

