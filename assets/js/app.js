/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
//import '../css/app.css';import $ from 'jquery';
import '../css/app.css';

// TEST BOOSTRAP

// or you can include specific pieces
//require('bootstrap/js/dist/tooltip');
//require('bootstrap/js/dist/popover');

//require('bootstrap/dist/js/bootstrap.bundle');

// 
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');

// or you can include specific pieces
//require('bootstrap/js/dist/tooltip');
//require('bootstrap/js/dist/popover');

// require the JavaScript
require('bootstrap-star-rating');
// require 2 CSS files needed
require('bootstrap-star-rating/css/star-rating.css');
require('bootstrap-star-rating/themes/krajee-svg/theme.css');

require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');

const $ = require('jquery');

$(document).ready(function () {
    $('[data-toggle="tooltip-enigmant"]').tooltip({
        animation: true,
        delay: {
            show: 1000,
            hide: 0
        }
    });

    $('[data-toggle="tooltip-navbar"]').tooltip();
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover();

    $('[data-toggle="popover"]').on('click', function (e) {
        $('[data-toggle="popover"]').not(this).popover('hide');
    });

    $(":checkbox").click(function () {
        var n = $("input:checked").length;
        if (n < 2) {
            $('[name="next2"]').attr("disabled", "disabled");
        } else {
            $('[name="next2"]').removeAttr("disabled");
        }
    });
});


// FIN TEST BOOSTRAP

import 'select2';
$('select').select2();

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');
