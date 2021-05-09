$(function() {
    'use strict';

    // Swich between Login and Signup Pages
    $('.login-page h1 span').click(function() {
        $(this).addClass('selected').siblings().removeClass('selected');
        $('.login-page form').hide();
        $($(this).data('class')).fadeIn(100);
    });


    // Calls the selectBoxIt method on your HTML select box and uses the default theme
    $("select").selectBoxIt({
        autoWidth: false,
    });


    // Add active class on active link in NavBar 

    $('.nav-item').click(function() {
        $(this).addClass('active').siblings('.nav-item').removeClass('active');
    })

    // Hide Placeholder on Form focus 

    $('[placeholder]').focus(function() {

        $(this).attr('data-text', $(this).attr('placeholder')); // => set new attr = data text with value in placeholder 
        $(this).attr('placeholder', ''); // => Empty placeholder 

    }).blur(function() {

        $(this).attr('placeholder', $(this).attr('data-text')); // => Return value to placeholder 

    });

    // Add Asterisk On Required Field 
    $('input').each(function() {

        if ($(this).attr('required') === 'required') {
            $(this).after('<span class="asterisk">*</span>');

        }
    });

    // Show Password WHEN hover on Eye hover

    $('.show-pass').hover(function() {
        $('.password').attr("type", "text");
    }, function() {
        $('.password').attr("type", "password");
    });

    // Confirmation message before delete 

    $('.confirm').click(function() {
        return confirm('Are You Sure?');
    })

    // Live Add Review 

    $('.live').keyup(function() {
        $($(this).data('class')).text($(this).val());
    })



});