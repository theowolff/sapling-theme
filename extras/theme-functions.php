<?php
    /**
     * Theme Functions: put your filters/actions here (WP hooks).
     */

    // Conditionally skip CF7 emails from the site
    function splng_skip_cf7_emails_conditionally($cf7) {

        // Default - skip.
        return true;
    }
    add_filter('wpcf7_skip_mail', 'splng_skip_cf7_emails_conditionally');
    
    // Add a body class with the current page's
    // and its' parent's (if available) slugs
    function splng_page_slug_body_class($classes) {

        if(is_singular('page') || is_home()) {
            $page_obj  = get_post(is_home() ? get_option('page_for_posts') : get_the_ID());
            $classes[] = "page--{$page_obj->post_name}";

            // If a parent exists, add it as well
            if(isset($page_obj->post_parent) && $page_obj->post_parent !== 0) {
                $parent_obj = get_post($page_obj->post_parent);
                $classes[]  = "page-parent--{$parent_obj->post_name}";    
            }
        }

        return $classes;
    }
    add_action('body_class', 'splng_page_slug_body_class');
