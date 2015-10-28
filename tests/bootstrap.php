<?php

namespace Wprsrv\Tests;

if (php_sapi_name() !== 'cli') {
    exit(1);
}

$pathParts = explode('wp-content', __DIR__);

$wpRoot = array_shift($pathParts);

define('SHORT_INIT', true);
define('WP_MEMORY_LIMIT', '256M');

require_once($wpRoot . 'wp-load.php');
require_once($wpRoot . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'admin.php');

global $wpdb;

// Begin MySQL transactions so we don't mod the database records.
$wpdb->query('SET autocommit=0');
$wpdb->query('START TRANSACTION');

add_action('muplugins_loaded', function () {
    include_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'wprsrv.php');
});

// Rollback database transaction on script end.
register_shutdown_function(function () use ($wpdb) {
    $wpdb->query('ROLLBACK');
});

wp_set_current_user(1);
