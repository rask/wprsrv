---
title: Installation & uninstallation
slug: installation
layout: page
---

# Installation

Currently the installation procedure is manual, as the plugin is still under heavy development.

## Requirements

-   PHP 5.5
-   WordPress 4.3

### Requirements during development phase

Additionally to the items listed above, the development version installation procedure requires the following

-   Composer
-   npm
-   Bower
-   Gulp

## Getting the plugin

First you need to get the code.

    $ cd .../wp-content/plugins
    $ git clone https://github.com/rask/wprsrv.git wprsrv
    
Then you need to setup the code.

    $ cd wprsrv
    $ composer install && npm install && bower install
    $ gulp compile
    
Now the plugin is "built" for use in WordPress.

## Activation

Activate the plugin as you would activate any other plugin.

## Deactivation

Deactivate the plugin as you would deactivate any other plugin.

## Uninstallation

Uninstall the plugin in `wp-admin`. Uninstallation does the following:

-   Deletes the plugin
-   Deletes all options and cached values related to *wprsrv*
-   Deletes **all** reservations and reservables

Please note that last item: you will lose all reservation data when you uninstall this plugin, unless you export the
data before plugin uninstallation.
