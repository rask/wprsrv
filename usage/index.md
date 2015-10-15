---
title: Usage
slug: usage
layout: page
---

# Usage instructions

*wprsrv* is not that complicated, some may say its actually pretty easy to use.

This page describes how to install, use and uninstall the plugin.

## Installation and activation

***NOTE***: during the development phase the installation procedure is aimed towards
plugin contributors and other technical folks. This will change when the first so
called "stable" plugin release is available.

### Requirements

-   PHP 5.5
-   WordPress 4.3
-   Composer
-   npm
-   Bower
-   Gulp

### Getting the plugin

First you need to get the code.

    $ cd .../wp-content/plugins
    $ git clone https://github.com/rask/wprsrv.git wprsrv
    
Then you need to setup the code.

    $ cd wprsrv
    $ composer install && npm install && bower install
    $ gulp compile
    
Now the plugin is "built" for use in WordPress.

### Activation

Activate the plugin as you would activate any other plugin.

## Deactivation and uninstallation

### Deactivation

Deactivating the plugin disables the reservation system and clears caches and 
flushes all plugin related rewrite rules.

### Uninstallation

Uninstall the plugin in `wp-admin`. Uninstallation does the following:

-   Deletes the plugin
-   Deletes all options and cached values related to *wprsrv*
-   Deletes **all** reservations and reservables

Please note that last item: you will lose all reservation data when you uninstall
this plugin, unless you export the data before plugin uninstallation.

## Plugin configuration

The plugin has no configuration settings yet. "Plug'n'play" for now.

## Creating and managing reservables

Reservables are the things people reserve; hotel rooms, spa evenings, show seats, and
so on. Reservables are a post type similar to posts. Each reservable is an "article" in
WordPress.

### Reservable options

Reservables make use of a few different options:

-   **Active**: When the reservable is active, users can create new reservations for
    it.
-   **Single-day**: Force users to reserve only single days instead of date ranges.
-   **Logged in only**: Allow only logged in users to make reservations.
-   **Disabled dates**: Pick date ranges to prevent users from reserving those dates.

### Reservables in code

You can create a reservable instance using the `Wprsrv\PostTypes\Objects\Reservable`
class. The class contains magic methods and getters that map to `WP_Post`, meaning
you can use `Reservable::ID`, `Reservable::post_status` and so on to get WordPress
post properties. You can use post IDs and post objects to create reservable objects.

    // Assuming there is a post with ID 3 and it has the post_type reservable.
    $reservable = new \Wprsrv\PostTypes\Objects\Reservable(3);
    
    $reservable_status = $reservable->post_status;
    
    ...
    
    // Assuming the global $post contains a reservable post type post.
    $reservable = new \Wprsrv\PostTypes\Objects\Reservable($post);
    
    $reservable_id = $reservable->ID;

## Creating and managing reservations

### Displaying a reservation form

*wprsrv* has a few methods of displaying reservation forms.

#### The `reservation_form()` function

You can embed a reservation form using the `Wprsrv\reservation_form()` function.

If you call the function without a parameter, it will attempt to create a reservation
form the current reservable post type being viewed.

If you pass in a post (reservable) ID, the form will create reservations for the 
reservable in question.

    // Create a reservation for the current global `$post` which has the post type
    // `reservable`.
    \Wprsrv\reservation_form();
    
    // Create the reservation form for the reservable with ID 3.
    \Wprsrv\reservation_form(3);
    
Note: when using `reservation_form()`, no form is displayed if the reservable has its
active option set to `false`.

#### The `ReservationForm` class

A reservation form can be created by using the `Wpsrv\Forms\ReservationForm` class. 

    
    // Instantiate the `ReservationForm` class, pass in a reservable post ID.
    $reservation_form = new \Wprsrv\Forms\ReservationForm(3);
    
    // Create a reservable object from ID.
    $reservable = new \Wprsrv\PostTypes\Objects\Reservable(3);
    
    if ($reservable->isActive()) {
        // Render the form.
        $reservation_form->render();
    }
