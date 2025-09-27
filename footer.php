<?php
    /**
     * The template for displaying the footer.
     * 
     * @package sapling-theme
     * @author theowolff
     **/

    // Exit if accessed directly.
    defined('ABSPATH') || exit;
?>

        <footer class="site-footer" id="colophon">
            <div class="container">
                <?php
                    /**
                     * Fire the footer content hook.
                     **/
                    do_action('sapling/footer');
                ?>
            </div>
        </footer>

        <?php wp_footer(); ?>
    </body>
</html>