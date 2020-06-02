const $ = require('jquery');

global.moment = require('moment');


//require('tempusdominus-bootstrap-4');
require('tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.js');
require('tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css');


var debut_location = new Date();
debut_location.setDate(debut_location.getDate());
debut_location.setMinutes(00);
var fin_location = new Date();
fin_location.setDate(fin_location.getDate() + 7);
fin_location.setHours(23, 59, 59);
var proposition = new Date();
proposition.setDate(proposition.getDate() + 3);
proposition.setHours(17, 00, 00);

$.fn.datetimepicker.Constructor.Default =
        $.extend($.fn.datetimepicker.Constructor.Default,
                {icons: {
                        time: 'fas fa-clock',
                        date: 'fas fa-calendar-alt',
                        up: 'fas fa-chevron-up',
                        down: 'fas fa-chevron-down',
                        previous: 'fas fa-chevron-left',
                        next: 'fas fa-chevron-right',
                        today: 'fas fa-calendar-check-o',
                        clear: 'fas fa-trash',
                        close: 'fas fa-times'

                    },
                    locale: 'fr',
//                    format: 'dddd D MMMM YYYY à HH:mm',
                });



$(document).ready(function () {
    $('.picker-datetime-location').datetimepicker(
            {
                sideBySide: true,        
                format: 'dddd D MMMM YYYY à HH:mm',      
                defaultDate: proposition,  
                minDate: debut_location,
                maxDate: fin_location,
                stepping: 15,
            }
    );
    
    $('.picker-datetime-retour').datetimepicker(
            {
                sideBySide: true,        
                format: 'dddd D MMMM YYYY à HH:mm',      
                defaultDate: proposition,  
                minDate: debut_location,
                stepping: 15,
            }
    );

    $('.picker-datetime').datetimepicker(
            {
                sideBySide: true,        
                format: 'D/MM/YYYY HH:mm',
                stepping: 15,
            }
    );

    $('.picker-datetime-readall').datetimepicker(
            {
                sideBySide: true,        
                format: 'dddd D MMMM YYYY à HH:mm',
                stepping: 15,
            }
    );

    $('.picker-date').datetimepicker(
            {
                format: 'D/MM/YYYY'
            }
    );

});

console.log('Hello Webpack Encore! Edit me in assets/js/datepicker.js');