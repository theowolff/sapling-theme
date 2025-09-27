<?php
    /**
     * The theme's Header template
     */
    defined('ABSPATH') || exit;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>
    <?php do_action('wp_body_open'); ?>

    <header class="site-header" role="banner" id="masthead">
    <div class="site-header__inner">
        <div class="site-branding">
            <?php
                if (function_exists('the_custom_logo') && has_custom_logo()) {
                    the_custom_logo();
                } else {
                    // Fallback to site name
                    $home = esc_url(home_url('/'));
                    $name = esc_html(get_bloginfo('name'));
                    echo '<a class="site-title" href="' . $home . '">' . $name . '</a>';
                }
            ?>
        </div>

        <nav class="site-navigation" role="navigation" aria-label="<?php esc_attr_e('Primary Menu', get_stylesheet()); ?>">
        <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'container' => false,
                'menu_class' => 'menu menu--primary',
                'fallback_cb' => function () {
                    echo '<ul class="menu menu--primary"><li><a href="' . esc_url(admin_url('nav-menus.php')) . '">' .
                        esc_html__('Add a menu', get_stylesheet()) . '</a></li></ul>';
                },
                'depth' => 2,
            ));
        ?>
        </nav>
    </div>
</header>