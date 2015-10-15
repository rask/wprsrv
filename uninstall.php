<?php

/**
 * uninstall.php
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit();
}



// Remove settings from database.
$optKeys = ['wprsrv'];

foreach ($optKeys as $key) {
    delete_option($key);
    delete_site_option($key);
}
