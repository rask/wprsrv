# wprsrv

*wprsrv* is a basic reservation system plugin for WordPress.

Primarily aimed at other
developers in need of a reservation system in their projects. Non-developer users
may need a helping hand to get up and running.

**NOTE**: this plugin is under development. Features may come and go, and no
backwards compatibility is warranted at this time. If you want to use this in
production environments, you may end up with a broken version at some point. You've
been warned.

## Features

### Reservables

A custom post type makes modifying reservable items easy.

-   Block off dates and date ranges (disable reservations for those days)
-   Deactivate reservations when needed
-   Only allow logged in users to make reservations
-   Browse pending and accepted reservations from a calendar view

### Reservations

Reservations are a simple custom post type.

-   Accept or decline reservations using a simple interface in `wp-admin`
-   Add notes for reservations
-   Pending and accepted reservations show reservable days as blocked
-   Email notifications to all parties when reservation statuses change
-   Ready-to-use reservation form
    -   Either use the `Wprsrv\reservation_form()` helper function or create a form class
        instance and adjust it before rendering
        
### Customization

Loads of hooks are available to help making the plugin work the way you want. These
hooks are constantly changing during development, so no real documentation is
available as of now.

## Installation

Currently the installation procedure is manual, as the plugin is under development.

### Requirements

-   Git
-   Composer
-   npm
-   Bower
-   Gulp

The plugin has been developed on WordPress 4.3+ and PHP 5.5+. Minimum versions may
change when testing continues. Assumably WP4+ works fine. This plugin does
not work with PHP 5.3 at all.

### Installation guide

    $ cd /wordpress/wp-content/plugins
    $ git clone <this repo> wprsrv
    $ cd wprsrv
    $ composer install
    $ npm install
    $ bower install
    $ gulp compile

Then just activate in `wp-admin`.

Use `gulp build` to generate a ZIP from the current plugin state. Built ZIPs are
stored inside `builds/`.

### Uninstallation

Deactivate the plugin and uninstall it through `wp-admin`. Removing the plugin
directory only may leave traces of content around.

**NOTE**: uninstalling the plugin using this procedure also destroys all posts this
plugin has created (reservables and reservations). If not then the feature is *still*
missing or there is a bug.

## Upcoming features

Note: the following is partly a wishlist. No guarantees on implementations. You can
request features or changes in the issue tracker.

### Configuration

Set the first day of week, global disabled days, etc. Implemented when needed, but
soon.

### Time of day reservations

Allow reservers to pick starting and ending times and allow single-day reservations
to use time pickers with wanted lengths per reservable, e.g. split the day to 30
minute blocks which can be reserved.

## Known issues

-   All emails do not send as expected. Pending reservation notices are mailed fine.
-   UI quirks here and there. Perhaps drop AJAX from some actions.
-   Not enough of the codebase is covered by tests.
-   Reservation statuses are a bit wonky. Trashed reservations are seen in
    reservation calendars and so on.
    
There's more and most are not documented yet. Appearing issues, bugs and such will
be added to the issue tracker.

## Documentation

Documentation is under construction. Please create issues on items you wish
documented first.

Auto-generated PHP documentation is available inside the `docs/` directory. The docs
are generated with [Sami](https://github.com/FriendsOfPHP/Sami).

## Contributing

Create a fork, create a topic branch, create a pull request. See `CONTRIBUTING.md`.

## License

*wprsrv* is licensed with the GPLv3 license. See `LICENSE.md`. WP uses GPLv2 and
allows derivative work (i.e. plugins and themes) to use GPLv2 *or later*.

## Third party content

All third party content and packages used should be visible in the `composer.json`,
`package.json` and `bower.json` files. Let me know if this isn't the case.
