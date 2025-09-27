<?php
    get_header();
?>

<main class="site-main" id="main">
    <div class="container">
        <?php
            if(have_posts()) {
                while(have_posts()) {
                    the_post();
                }
            }
        ?>
    </div>
</main>

<?php
    get_footer();