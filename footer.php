<?php
    /**
     * The template for displaying the footer
     **/

    // Exit if accessed directly.
    defined('ABSPATH') || exit;
?>

        <footer class="site-footer" id="colophon">
            <div class="site-info">
                <?php
                    /**
                     * Fire the footer content hook.
                     **/
                    do_action('twwp/footer');
                ?>
            </div>
        </footer>

        <?php wp_footer(); ?>
    </body>
</html>