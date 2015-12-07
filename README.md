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

-   Block off dates, date ranges and weekdays (disable reservations for those days)
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

There are development builds available at the GitHub repo's releases section.

### Requirements

The plugin has been developed on WordPress 4.3+ and PHP 5.5+. Minimum versions may
change when testing continues. Assumably WP4+ works fine. This plugin does
not work with PHP 5.3 at all.

### Installation guide

Download the wanted version from GitHub releases, unzip to the WP plugins directory
Then just activate in `wp-admin`. Versions from 0.2.0 and up should then update
automatically from the GitHub releases system.

### Uninstallation

Deactivate the plugin and uninstall it through `wp-admin`. Removing the plugin
directory only may leave traces of content around.

**NOTE**: uninstalling the plugin using this procedure also destroys all posts this
plugin has created (reservables and reservations). If not then the feature is *still*
missing or there is a bug.

## FAQ & Info

**Is this production ready?**

Yes and no. I use it in production on a site I work with often so keeping things in
check is a breeze. I suggest that you wait a little longer for a really stable 1.0.0
version before using this in production.

**The plugin updates are sloooow!*

As far as I know, this is an issue with the GitHub releases system and the speed it
offers. I'd like to keep this updatable directly from this GitHub repo, but if the
speed does not get any better later on perhaps the wordpress.org plugin directory
has its place here. Or maybe I'll just create a cache system, who knows.

But yes, the slowness has been experienced and testing is on its way to make it
faster.

**Can you create feature X that allows me to do Y and Z?**

Yes and no. Make a feature request at the issue tracker on GitHub and we'll see
whether the idea is worth implementing.

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

### WP REST API endpoints

With the WP REST API being implemented in core, proper endpoints should be created
for the plugin.

## Known issues

-   Not enough of the codebase is covered by tests.
-   Not enough documentation for current features and such.
    
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
