<?php

namespace Wprsrv\Tests;

if (php_sapi_name() !== 'cli') {
    exit(1);
}


if (strpos(__DIR__, 'wp-content') !== false) {
    $pathParts = explode('wp-content', __DIR__);

    $wpRoot = array_shift($pathParts);
    define('WPRSRV_TRAVIS', false);
} else {
    $wpRoot = '/tmp/wordpress-tests-lib/';
    define('WPRSRV_TRAVIS', true);
}

function loadPlugin()
{
    require_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'wprsrv.php');
}

if (WPRSRV_TRAVIS) {
    require_once $wpRoot . 'includes/functions.php';

    tests_add_filter('muplugins_loaded', 'loadPlugin');

    require $wpRoot . 'includes/bootstrap.php';
} else {
    define('SHORT_INIT', true);
    define('WP_MEMORY_LIMIT', '256M');

    require_once($wpRoot . 'wp-load.php');
    require_once($wpRoot . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'admin.php');

    global $wpdb;

// Begin MySQL transactions so we don't mod the database records.
    $wpdb->query('SET autocommit=0');
    $wpdb->query('START TRANSACTION');

    add_action('muplugins_loaded', 'loadPlugin');

// Rollback database transaction on script end.
    register_shutdown_function(function () use ($wpdb) {
        $wpdb->query('ROLLBACK');
    });

    wp_set_current_user(1);
}
