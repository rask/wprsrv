<?php

namespace Wprsrv;

/**
 * Plugin name: wprsrv
 * Description: A basic reservation system, for WordPress.
 * Version: 0.1.0
 * Author: Otto J. Rask
 * Author URL: http://www.ottorask.com
 * License: GPLv3 (see LICENSE.md)
 * Text Domain: wprsrv
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!defined('RDS')) {
    define('RDS', DIRECTORY_SEPARATOR);
}

/**
 * Plugin and vendor autoloading.
 */
require_once(__DIR__ . RDS . 'vendor' . RDS . 'autoload.php');

/**
 * Plugin general use functions.
 */
require_once(__DIR__ . RDS . 'functions.php');

/**
 * Activation and deactivation.
 */
register_activation_hook(__FILE__, ['Wprsrv\Plugin', 'activate']);
register_deactivation_hook(__FILE__, ['Wprsrv\Plugin', 'deactivate']);

global $wprsrv;

/**
 * Unforced singleton fetcher function for the plugin.
 *
 * @since 0.1.0
 * @return \Wprsrv\Plugin
 */
function wprsrv()
{
    global $wprsrv;

    if (!$wprsrv) {
        $wprsrv = new \Wprsrv\Plugin(true);
    }

    return $wprsrv;
}

/**
 * Init.
 */
wprsrv();
