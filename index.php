
<?php
    /**
     * Main index template for displaying posts.
     *
     * @package sapling-theme
     * @author theowolff
     */

    /**
     * Load site header template.
     */
    get_header();
?>

<main class="site-main" id="main">
    <div class="container">
        <?php
            /**
             * Loop through posts and display content.
             */
            if(have_posts()) {
                while(have_posts()) {
                    the_post();
                }
            }
        ?>
    </div>
</main>

<?php
    /**
     * Load site footer template.
     */
    get_footer();