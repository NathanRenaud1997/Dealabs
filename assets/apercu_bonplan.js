$(document).ready(function() {
    $('#bon_plan_title').change(function() {
        $('.apercu-title').html($('#bon_plan_title').val());
    });
    $('#bon_plan_description').change(function() {
        $('.apercu-description').html($('#bon_plan_description').val());
    });
})