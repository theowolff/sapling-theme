<?php
    /**
     * Theme setup & bootstrap: enqueue, supports, analytics placeholders.
     */

    // Enqueue styles and scripts
    function twwp_enqueue_styles_scripts() {
        global $wp;
        $the_theme = wp_get_theme();
        
        /** Styles */
        wp_register_style('twwp-main', TWWP_DIST . '/css/main.min.css', [], $the_theme->get('Version'));

        /** Scripts **/
        wp_enqueue_script('jquery');
        wp_register_script('twwp-main', TWWP_DIST . '/js/main.js', [], $the_theme->get('Version'), true);
        
        // Localize important data to be used in the frontend
        $localized = array(
            'home_url'     => rtrim(get_home_url(), '/'),
            'current_post' => get_the_ID(),
            'ajax_url'     => admin_url('admin-ajax.php')
        );
        wp_localize_script('child-understrap-scripts', 'twwp', $localized);

        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }
    add_action('wp_enqueue_scripts', 'twwp_enqueue_styles_scripts');

    // Allow JSON and SVG media library uploads
    function twwp_whitelist_mimetype_uploads($mimes) {
        $mimes['json'] = 'text/plain';
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }
    add_filter('upload_mimes', 'twwp_whitelist_mimetype_uploads');

    // Add custom logo support
    function twwp_add_custom_logo_support() {

        add_theme_support('custom-logo', array(
            'height'      => 80,
            'width'       => 240,
            'flex-height' => true,
            'flex-width'  => true,
        ));
    }
    add_action('after_setup_theme', 'twwp_add_custom_logo_support', 5);

    // Register the primary (default) navigation menu
    function twwp_register_default_primary_menu() {

        register_nav_menus(array(
            'primary' => __('Primary Menu', get_stylesheet()),
        ));
    }
    add_action('after_setup_theme', 'twwp_register_default_primary_menu', 5);