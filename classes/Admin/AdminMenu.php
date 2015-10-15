<?php

namespace Wprsrv\Admin;

/**
 * Class AdminMenu
 *
 * Admin menu changes and additions.
 *
 * @package Wprsrv\Admin
 */
class AdminMenu
{
    /**
     * Hook to admin menu.
     *
     * @return void
     */
    public function __construct()
    {
        add_action('admin_menu', [$this, 'adminMenu']);
    }

    /**
     * Manage the admin menu. Create top level items and sub items.
     *
     * @return void
     */
    public function adminMenu()
    {
        // Allow adjusting the capability needed to access the pages.
        $reserve_menu_capability = apply_filters('wprsrv/menu_capability', 'edit_posts');

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

        do_action('wprsrv/admin_menu');
    }

    /**
     * Empty page that should redirect straight to submenu item.
     *
     * @return Boolean
     */
    public function generateAdminPage()
    {
        return false;
    }
}
