const $ = require('jquery');



$(document).ready(function () {
    var btn = document.getElementById("envoyer");
    var texte = document.getElementById("texte");
    
    $(btn).attr('disabled', true);
    
    $(texte).on('keyup',function() {
        var textarea_value = $("#texte").val();
        var text_value = $('textarea[name="texte"]').val();
        
        if(textarea_value != '' && text_value != '') {
            $(btn).attr('disabled', false);
            console.log('FALSE');
        } else {
            $(btn).attr('disabled', true);
            console.log('TRUE');
        }
    });
});

console.log('Hello Webpack Encore! Edit me in assets/js/message.js');