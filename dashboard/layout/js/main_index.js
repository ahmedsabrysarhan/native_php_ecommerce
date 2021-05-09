$(function() {
    'use strict';

    // Toggle Card Body in Latest Items & Users 
    $('.toggle-show').click(function() {

        $(this).toggleClass('selected').parent().next('.card-body').fadeToggle(100);

        if ($(this).hasClass('selected')) {
            $(this).html('<i class="fa fa-minus fa-lg"></i>');
        } else {
            $(this).html('<i class="fa fa-plus fa-lg"></i>');
        }
    })

    // Calls the selectBoxIt method on your HTML select box and uses the default theme
    $("select").selectBoxIt({
        autoWidth: false,
    });


    // Add active class on active link in NavBar 

    $('.nav-item a').click(function() {
        $(this).parent('.nav-item').addClass('active').siblings().parent().removeClass('active');
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




});