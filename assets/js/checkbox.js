const $ = require('jquery');

// CACHER SUIVANT UN CHECKBOX
function cacher(num)
{
    var text = document.getElementById("si_case_a_cocher" + num);

    var col_checkBox = document.getElementsByClassName("case_a_cocher" + num);
    var checkBox;
    if (col_checkBox.length === 0)
        checkBox = null;
    else
        checkBox = col_checkBox[0];

    if (checkBox && text)
    {
        if (checkBox.checked == true) {
            text.style.display = "block";
        } else {
            text.style.display = "none";
        }
    }
}

// ACTIVER NEXT1 SEULEMENT SI UN SEUL CHEXBOX EST VALIDE
function textNext1() {
//    var n = $("input:checked").length;
    var n = $(".only-one:checked").length;
    console.log(n);
    
    if (n != 1) {
        $('.next1').attr("disabled", "disabled");
    } else {
        $('.next1').removeAttr("disabled");
    }
}

$(document).ready(function () {

    $('[type*="radio"]').change(function () {
        var me = $(this);
//        log.html(me.attr('value'));        
//        
        console.log(me.attr('value'));
    });
    
     $('input.only-one').click(function () {
        $('input.only-one').not(this).prop('checked', false);
    });

    $('input.only-one1').click(function () {
        $('input.only-one1').not(this).prop('checked', false);
    });

    $('input.only-one2').click(function () {
        $('input.only-one2').not(this).prop('checked', false);
    });

    $('input.only-one3').click(function () {
        $('input.only-one3').not(this).prop('checked', false);
    });

    cacher("1");
    cacher("2");
    cacher("3");
    cacher("4");
    textNext1();

    $(':checkbox').click(function () {
        textNext1();
        cacher("1");
        cacher("2");
        cacher("3");
        cacher("4");
    });

});

console.log('Hello Webpack Encore! Edit me in assets/js/checkbox.js');