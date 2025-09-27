
<?php
    /**
     * Prints out a sanitized Contact Form 7 form by ID.
     * @param array $atts
     * @return string|null
     */
    function splng_cf7_shortcode($atts = array()) {

        if($atts['id'] && ! empty($atts['id'])) {
            return splng_cf7($atts['id']);
        }
        
        return null;
    }
    add_shortcode('cf7', 'splng_cf7_shortcode');