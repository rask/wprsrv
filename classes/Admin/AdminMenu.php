<?php

namespace Wprsrv\Admin;

use Wprsrv\Admin\Export\ExportHandler;
use Wprsrv\PostTypes\Objects\Reservable;

/**
 * Class AdminMenu
 *
 * Admin menu changes and additions.
 *
 * @since 0.1.0
 * @package Wprsrv\Admin
 */
class AdminMenu
{
    /**
     * Hook to admin menu.
     *
     * @since 0.1.0
     * @return void
     */
    public function __construct()
    {
        add_action('admin_menu', [$this, 'adminMenu']);
        add_action('admin_init', [$this, 'handleExport']);
    }

    /**
     * Manage the admin menu. Create top level items and sub items.
     *
     * @since 0.1.0
     * @return void
     */
    public function adminMenu()
    {
        /**
         * Which capability is needed to access the general reservations menu.
         *
         * @since 0.1.0
         *
         * @param String $capability The capability needed to access reservations
         *               menu.
         *
         * @return String
         */
        $reserve_menu_capability = apply_filters('wprsrv/menu_capability', 'edit_posts');

        /**
         * Which capability is needed to access the reservations export menu.
         *
         * @since 0.1.1
         *
         * @param String $capability The capability needed to access exports.
         *
         * @return String
         */
        $export_menu_capability = apply_filters('wprsrv/export_capability', 'manage_options');

        // Collective top-level menu item.
        add_menu_page(
            __('Reservations', 'wprsrv'),
            __('Reservations', 'wprsrv'),
            $reserve_menu_capability,
            'wprsrv',
            [$this, 'generateAdminPage'],
            'dashicons-calendar-alt',
            '24.1234'
        );

        add_submenu_page(
            'wprsrv',
            __('Export reservation data', 'wprsrv'),
            __('Export', 'wprsrv'),
            $export_menu_capability,
            'wprsrv-export',
            [$this, 'generateExportPage']
        );

        do_action('wprsrv/admin_menu');
    }

    /**
     * Empty page that should redirect straight to submenu item.
     *
     * @todo Create the admin page.
     * @since 0.1.0
     * @return void
     */
    public function generateAdminPage()
    {
        // Create a menu page?
    }

    /**
     * Generate the exports page contents.
     *
     * @since 0.1.1
     * @return void
     */
    public function generateExportPage()
    {
        wp_enqueue_script('pikaday');
        wp_enqueue_style('pikaday');
        wp_enqueue_style('wprsrv-export');

        $exportTemplate = [
            \Wprsrv\wprsrv()->pluginDirectory,
            'includes',
            'templates',
            'admin',
            'export.php'
        ];

        $exportTemplate = implode(RDS, $exportTemplate);

        require_once($exportTemplate);
    }

    /**
     * Handle a submitted export file download request.
     *
     * @since 0.1.1
     * @access protected
     * @return void
     */
    public function handleExport()
    {
        $exportHandler = new ExportHandler();

        $exportHandler->handleExport();
    }
}
