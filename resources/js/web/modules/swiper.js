/**
 * Dependencies
 */
import Swiper from '../vendor/swiper/swiper.js';

var SwiperUi = (function() {
     
	var _initialize = function() {
        var swiper = new Swiper('.swiper-container', {
            autoHeight: false,
            // autoplay: {
            //     delay: 3000,
            // },
            pagination: {
              el: '.swiper-pagination',
              clickable: true,
            },
            navigation: {
                nextEl: '.swiper-nav-next',
                prevEl: '.swiper-nav-prev'
            }
        });        
    };

    return {
        init:  _initialize,
	};
	
})();

// Initialize
$(function() {
    SwiperUi.init();
});