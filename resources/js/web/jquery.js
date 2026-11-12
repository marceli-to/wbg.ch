// Expose jQuery globally before the (non-module) vendor plugins are evaluated.
import jQuery from 'jquery';

window.$ = window.jQuery = jQuery;
