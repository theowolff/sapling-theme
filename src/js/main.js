jQuery(function($) {

    // Set the VH variable: 
    // Mostly used for mobile screen height calculation on iOS
    var vh = window.innerHeight * 0.01;
    document.documentElement.style.setProperty('--vh', vh + 'px');

    jQuery(window).on('load resize', function() {
        var vh = window.innerHeight * 0.01;
        document.documentElement.style.setProperty('--vh', vh + 'px');
    });

    jQuery(window).on('orientationchange', function() {

        setTimeout(function() {
            var vh = window.innerHeight * 0.01;
            document.documentElement.style.setProperty('--vh', vh + 'px');
        }, 100);
    });

    /** Contact Form 7: Add the default response output classes back **/
    // On success:
    $('.wpcf7').on('wpcf7mailsent', function() {
        $(this).find('.wpcf7-response-output').removeClass('wpcf7-validation-errors').addClass('wpcf7-mail-sent-ok');
    });

    // On error:
    $('.wpcf7').on('wpcf7invalid wpcf7spam wpcf7mailfailed', function() {
        var form = $(this);
        
        setTimeout(function() {
            form.find('.wpcf7-response-output').removeClass('wpcf7-mail-sent-ok').addClass('wpcf7-validation-errors');

            // Also, add the invalid class to the parent
            form.find('.wpcf7-not-valid').each(function() {
                $(this).parents('label').addClass('wpcf7-not-valid');
            });
        }, 250);
    });

    // Contact Form 7: Reset the invalid classes on input / textarea keyup
    $('.wpcf7-form').on('keyup', 'input textarea', function() {
        $(this).removeClass('wpcf7-not-valid').parents('label').removeClass('wpcf7-not-valid').find('.wpcf7-not-valid-tip').remove();
    });

    // Get the default header height
    window.header_height = $('.site-header').outerHeight();

    // On window resize, check if the header height has changed
    $(window).on('resize', function() {
        window.header_height = $('.site-header').outerHeight();
    });

    // On a link with a hash click, scroll to that section
    $('a[href*="#"]').on('click', function(e) {
        var section = $('section[data-id="' + $(this).attr('href').replace('#', '').replace('/', '') + '"]');

        if(section && section.length > 0 && $(this).attr('href') !== '#') {
            e.preventDefault();

            // Load all of the images prior to that section
            section.prevAll('section').find('[data-lazy-src]').not('lazyloaded').each(function() {
                $(this).attr('src', $(this).attr('data-lazy-src'));
            });

            // Animate the scroll to that section
            var top_offset = window.header_height + ($('body').hasClass('admin-bar') ? $('#wpadminbar').outerHeight() : 0);
            $('body, html').animate({ scrollTop: section.offset().top - top_offset }, 900, 'swing');
        }
    });

    // On a hash presence in the URL, scroll to that section
    $(document).ready(function() {

        if(window.location.hash && window.location.hash.length > 0) {

            setTimeout(function() {
                var section    = $('section[data-id="' + window.location.hash.replace('#', '') + '"]'),
                    top_offset = window.header_height + ($('body').hasClass('admin-bar') ? $('#wpadminbar').outerHeight() : 0);

                if(section && section.length > 0) {
                    $('body, html').animate({ scrollTop: section.offset().top - top_offset }, 900, 'swing');
                }
            }, 500);
        }    
    });

    // Header: Set and reset the is-scrolled header class
    function header_set_scrolled_class() {

        if($(window).scrollTop() > 0) {
            $('#wrapper-navbar').addClass('is-scrolled');
        } else {
            $('#wrapper-navbar').removeClass('is-scrolled');
        }
    }

    // Do it on page load
    header_set_scrolled_class();

    // And also on scroll
    $(window).on('scroll', header_set_scrolled_class);
});