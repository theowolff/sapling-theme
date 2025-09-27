<?php
    /**
     * Helpers: pure helper functions (no WP hooks).
     * Add re-usable utilities here. Keep side effects out.
     */

    // Get the target of an ACF link field
    if(! function_exists('twwp_get_link_target')) {
        function twwp_get_link_target($link) {
            return isset($link['target']) && ! empty($link['target']) ? $link['target'] : '_self';
        }
    }

    // Remove <p> tags from string
    if(! function_exists('twwp_remove_p')) {
        function twwp_remove_p($string) {
            return str_replace(array('<p>', '</p>'), '', $string);
        }
    }

    // Remove <br> tags from string
    if(! function_exists('twwp_remove_br')) {
        function twwp_remove_br($string) {
            return str_replace(array('<br>', '</br>', '<br />', '<br/>'), '', $string);
        }
    }

    // Get a partial (without losing previously declared variables)
    if(! function_exists('twwp_template_part')) {
        function twwp_template_part($name, $args = array()) {
            extract($args);
            include dirname(__DIR__) . "/partial-templates/{$name}.php";
        }
    }

    // Return an image HTML string from an image array
    if(! function_exists('twwp_html_image')) {
        function twwp_html_image($image_array) {
            return "<img src=\"{$image_array['url']}\" alt=\"{$image_array['alt']}\" />";
        }
    }

    // Get an image from ACF (handles inconsistent return values)
    if(! function_exists('twwp_acf_image')) {
        function twwp_acf_image($field_name, $post_id = null) {
            if(empty($post_id)) {
                $post_id = get_the_ID();
            }
            return twwp_cast_image_array(
                get_field($field_name, $post_id)
            );
        }
    }

    // Cast an image array (url, alt) from array / id / URL
    if(! function_exists('twwp_cast_image_array')) {
        function twwp_cast_image_array($value) {
            return is_array($value) ? $value
                : (is_int($value)
                    ? array(
                        'url' => wp_get_attachment_url($value),
                        'alt' => get_post_meta($value, '_wp_attachment_image_alt', true)
                    )
                    : array(
                        'url' => $value,
                        'alt' => get_post_meta(
                            attachment_url_to_postid($value),
                            '_wp_attachment_image_alt',
                            true
                        )
                    )
                );
        }
    }

    // Get a page's full URL
    if(! function_exists('twwp_get_page_url')) {
        function twwp_get_page_url() {
            return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http')
                . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
        }
    }

    // Clean and print a CF7 contact form
    if(! function_exists('twwp_cf7')) {
        function twwp_cf7($form) {
            return twwp_remove_p(twwp_remove_br(
                do_shortcode('[contact-form-7 id="' . $form . '"]')
            ));
        }
    }

    // Display a pre-defined field value or fetch it from ACF
    if(! function_exists('twwp_default_or_acf')) {
        function twwp_default_or_acf($key, $config, $is_sub = true) {
            if (isset($config[$key]) && ! empty($config[$key])) {
                return $config[$key];
            }
            return ($is_sub ? get_sub_field($key) : get_field($key));
        }
    }

    // Convert an absolute uploads path into a relative one
    if(! function_exists('twwp_real_uploads_path')) {
        function twwp_real_uploads_path($url) {
            return str_replace(
                array(get_home_url(), '/wp-content/uploads'),
                array('', wp_get_upload_dir()['basedir']),
                $url
            );
        }
    }
