<?php

namespace Wprsrv;

/**
 * Plugin name: wprsrv
 * Description: A basic reservation system, for WordPress.
 * Version: 0.2.0
 * Plugin URI: https://github.com/rask/wprsrv
 * Author: Otto J. Rask
 * Author URI: http://www.ottorask.com
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

define('WPRSRV_DIR', __DIR__);
define('GITHUB_REPO_ID', 'rask/wprsrv');
define('PLUGIN_DIR_ID', basename(__DIR__) . '/' . basename(__FILE__));

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

/**
 * Run updater.
 */
if (is_admin()) {
    new \Wprsrv\Updater(PLUGIN_DIR_ID, GITHUB_REPO_ID);
}
