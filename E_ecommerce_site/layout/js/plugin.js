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

    // Toggle Between Forms To Login & Sign up
    $('.login-front h1 span').click(function () {
        $(this).addClass('active').siblings().removeClass('active');
        $('.login-front form').slideUp();
        $('.' + $(this).data('class')).slideDown(300);
    });

    // Write The AD When I Inputing In Ad Form

    $('.live-name').keyup(function() {
        let adName = $('.item-name');
        adName.text($(this).val());
        if(adName.is(":empty")) {
            adName.text('Test Name');
        } else {
            adName.text($(this).val());
        } 
    });
    $('.live-desc').keyup(function() {
        let adDesc = $('.item-desc');
        adDesc.text($(this).val());
        if(adDesc.is(":empty")) {
            adDesc.text('Test Description');
        } else {
            adDesc.text($(this).val());
        } 
    });
    $('.live-price').keyup(function() {
        let adPrice = $('.item-price');
        adPrice.text($(this).val());

        // Check Element Is Empty 
        if(adPrice.is(":empty")) {
            adPrice.text('$' + '0');
        } else {
            adPrice.text('$' + $(this).val());
        }
    });



});