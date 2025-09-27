<?php
    // Constant for assets
    if(! defined('TWWP_DIST')) {
        define('TWWP_DIST', get_template_directory_uri() . '/dist');
    }

    // Wire extras: core files
    require_once __DIR__ . '/extras/helpers.php';
    require_once __DIR__ . '/extras/setup.php';
    require_once __DIR__ . '/extras/theme-functions.php';