const $ = require('jquery');


require('bootstrap-datepicker/js/bootstrap-datepicker');
require('bootstrap-datepicker/js/locales/bootstrap-datepicker.fr');
require('bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css');

//$.fn.datepicker.defaults.format = "DD dd MM yyyy";

$(document).ready(function () {
//    $('#datetimepicker3').datetimepicker({
//        format: "DD dd MM yyyy",
//        startView: "months",
//        minViewMode: "hours",
//        language: 'fr'
//    });


//    $('.datepicker').datepicker({
  //      format: "DD dd MM yyyy",
//        startView: "months",
//        minViewMode: "hours",
//        language: 'fr'
//    });



$('.datepicker').datetimepicker({
    format: 'DD dd MM yyyy hh:ii',
    startDate: '-3d',
    language: 'fr'
});

    //        format: "LT",
//       $('.datepicker').each(function () {
//           $(this).datepicker({
//            format: "DD dd MM yyyy",
//            startView: "months",
//            minViewMode: "hours",
//            language: 'fr'
//        });
//       });

});

console.log('Hello Webpack Encore! Edit me in assets/js/datepicker.js');