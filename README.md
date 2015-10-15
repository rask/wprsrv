# wprsrv

*wprsrv* is a basic reservation system plugin for WordPress.

## Installation

Currently the installation procedure is manual, as the plugin is under development.

### Requirements

-   Git
-   Composer
-   Node+NPM
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
plugin has created (reservables and reservations).

## Features

### Reservables

A custom post type makes modifying reservable items easy.

-   Single or multi-day reservations
-   Block off dates and date ranges (disable reservations for those days)
-   Deactivate reservations when needed

### Reservations

Reservations are a simple custom post type.

-   Accept or decline reservations using a simple interface in `wp-admin`
-   Add notes for reservations
-   Pending and accepted reservations show reservable days as blocked
-   Email notifications to all parties when reservation statuses change
-   Ready-to-use reservation form
    -   Either use the `reservation_form()` helper function or create a form class
        instance and adjust it before rendering
        
### Customization

Loads of hooks are available to help making the plugin work the way you want.

## Upcoming features

Note: the following is partly a wishlist. No guarantees on implementations.

### Time of day reservations

Allow single-day reservations to use time pickers with wanted lengths per reservable,
e.g. Split the day to 30 minute blocks which can be reserved.

## Known issues

-   All emails do not send as expected. Pending reservation notices are mailed fine.
-   UI quirks here and there.
-   Not enough of the codebase is covered by tests.

## Documentation

Documentation is under construction. Please create issues on items you wish
documented first.

Auto-generated PHP documentation is available inside the `docs/` directory. The docs
are generated with Sami.

## Contributing

Create a fork, create a topic branch, create a pull request.

## License

*wprsrv* is licensed with the GPLv3 license. See `LICENSE.md`. WP uses GPLv2 and
allows derivative work (i.e. plugins) to use GPLv2 *or later*.
