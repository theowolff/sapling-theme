jQuery(($) => {

    // Set the VH variable: 
    // Mostly used for mobile screen height calculation on iOS
    const set_vh = () => {
        const vh = window.innerHeight * 0.01;
        document.documentElement.style.setProperty('--vh', `${vh}px`);
    };

    set_vh();
    $(window).on('load resize', set_vh);
    $(window).on('orientationchange', () => setTimeout(set_vh, 100));

    /** Contact Form 7: Add the default response output classes back **/
    // On success:
    $(document).on('wpcf7mailsent', '.wpcf7', (e) => {
        const form = $(e.currentTarget);

        form.find('.wpcf7-response-output')
            .removeClass('wpcf7-validation-errors')
            .addClass('wpcf7-mail-sent-ok');
    });

    // On error:
    $(document).on('wpcf7invalid wpcf7spam wpcf7mailfailed', '.wpcf7', (e) => {
        const form = $(e.currentTarget);

        setTimeout(() => {
            form.find('.wpcf7-response-output')
                .removeClass('wpcf7-mail-sent-ok')
                .addClass('wpcf7-validation-errors');

            // Also, add the invalid class to the parent
            form.find('.wpcf7-not-valid').each(function () {
                $(this).closest('label').addClass('wpcf7-not-valid');
            });
        }, 250);
    });

    // Contact Form 7: Reset the invalid classes on input / textarea keyup
    $(document).on('keyup', '.wpcf7-form input, .wpcf7-form textarea', (e) => {
        const input = $(e.currentTarget);

        input.removeClass('wpcf7-not-valid')
            .closest('label')
            .removeClass('wpcf7-not-valid')
            .find('.wpcf7-not-valid-tip')
            .remove();
    });

    // Get the default header height
    const get_header_height = () => $('.site-header').outerHeight() || 0;
    let header_height = get_header_height();

    // On window resize, check if the header height has changed
    $(window).on('resize', () => {
        header_height = get_header_height();
    });

    // On a link with a hash click, scroll to that section
    $(document).on('click', 'a[href*="#"]', (e) => {
        const href = $(e.currentTarget).attr('href');
        if (!href || href === '#') return;

        const id = href.replace('#', '').replace('/', '');
        const section = $(`section[data-id="${id}"]`);

        if (section.length) {
            e.preventDefault();

            // Load all of the images prior to that section
            section.prevAll('section')
                .find('[data-lazy-src]')
                .not('.lazyloaded')
                .each(function () {
                    const src = $(this).attr('data-lazy-src');
                    if (src) $(this).attr('src', src);
                });

            // Animate the scroll to that section
            const top_offset = header_height + (
                $('body').hasClass('admin-bar') ? $('#wpadminbar').outerHeight() || 0 : 0
            );

            $('html, body').animate(
                { scrollTop: section.offset().top - top_offset },
                900,
                'swing'
            );
        }
    });

    // On a hash presence in the URL, scroll to that section
    $(document).ready(() => {
        if (window.location.hash && window.location.hash.length > 0) {
            setTimeout(() => {
                const id = window.location.hash.substring(1);
                const section = $(`section[data-id="${id}"]`);
                const top_offset = header_height + (
                    $('body').hasClass('admin-bar') ? $('#wpadminbar').outerHeight() || 0 : 0
                );

                if (section.length) {
                    $('html, body').animate(
                        { scrollTop: section.offset().top - top_offset },
                        900,
                        'swing'
                    );
                }
            }, 500);
        }
    });

    // Header: Set and reset the is-scrolled header class
    const header_set_scrolled_class = () => {
        if ($(window).scrollTop() > 0) {
            $('#wrapper-navbar').addClass('is-scrolled');
        } else {
            $('#wrapper-navbar').removeClass('is-scrolled');
        }
    };

    // Do it on page load
    header_set_scrolled_class();

    // And also on scroll
    $(window).on('scroll', header_set_scrolled_class);
});
