<?php
    /**
     * Theme Functions: put your filters/actions here (WP hooks).
     *
     * @package sapling-theme
     * @author theowolff
     */

    /**
     * Conditionally skip CF7 emails from the site.
     * @param object $cf7
     * @return bool
     */
    function splng_skip_cf7_emails_conditionally($cf7) {

        // Default - skip.
        return true;
    }
    add_filter('wpcf7_skip_mail', 'splng_skip_cf7_emails_conditionally');

    /**
     * Add a body class with the current page's and its parent's (if available) slugs.
     * @param array $classes
     * @return array
     */
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

    /**
     * Add a menu icon if set in ACF for the menu item.
     * @param array $items
     * @param object $args
     * @return array
     */
    function splng_add_menu_item_icon( $items, $args ) {
        
        /** 
         * Loop through menu items and add icon if set.
         */
        foreach($items as &$item) {
            
            /**
             * Get icon field value.
             */
            $icon = splng_acf_image('splng__menu-item_icon', $item);
            
            /**
             * Append icon HTML to menu item title if icon is set.
             */
            if($icon) {
                $item->title .= splng_html_image($icon);
            }
        }
        
        return $items;
    }
    add_filter('wp_nav_menu_objects', 'splng_add_menu_item_icon', 10, 2);
