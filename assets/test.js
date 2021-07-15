$(document).ready(function() {
   $('#code_promo_title').change(function() {
        $('.apercu-title').html($('#code_promo_title').val());
   });
    $('#code_promo_description').change(function() {
        $('.apercu-description').html($('#code_promo_description').val());
    });


})