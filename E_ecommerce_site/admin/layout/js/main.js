$(function () {

    'use strict';
    // Toggle Hide And Show Placeholder On User Click On Input
    $('[placeholder]').focus(function () {
        $(this).attr('data-text', $(this).attr('placeholder'));
        console.log($(this).attr('data-text'));
        $(this).attr('placeholder', '');
    }).blur(function () {
        $(this).attr('placeholder', $(this).attr('data-text'));
    });

    // Convert Password To String On Hover On Eye
    let passEye = $('.show-pass');
    passEye.hover(function() {

        $('.password').attr('type', 'text');

    }, function () {
        $('.password').attr('type', 'password');
    });

    // Check If Fields Empty Make Border Red

    let inputValue = $('.form-control').value;

    $('.form-control').blur(function () {
        if($(this).empty()) {
            $(this).css('border', '1px solid #f00');
        } else {
            $(this).css('border', '1px solid #ddd')
        }   
    });

    // Confirm Delete Yes Or Not
    $('.confirm').click(function(){
        return confirm('Are you Sure?');
    });

    // Trigger Select Box It Plugin

    $('select').selectBoxIt({
        autoWidth: false
    });

});