<?php

namespace Wprsrv;

/**
 * Default settings for the plugin.
 */

if (!isset($_SERVER['HTTP_HOST']) || !$_SERVER['HTTP_HOST']) {
    $_SERVER['HTTP_HOST'] = 'localhost';
}

$uploads = wp_upload_dir();

return [
    // Email system related.
    'email' => [
        // What email address gets admin notifications.
        'admin_email' => get_option('admin_email', null),

        // Mail address that "sends" notifications.
        'from' => get_option('admin_email', 'noreply@' . $_SERVER['HTTP_HOST'])
    ],

    // Logging related.
    'logging' => [
        // Log file absolute path.
        'log_file' => $uploads['basedir'] . '/wprsrv/wprsrv.log',

        // Maximum log filesize.
        'log_max_size' => 1024*1024*1024 // ~10MB
    ]
];
