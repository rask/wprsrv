<?php

namespace Wprsrv;

use Wprsrv\Forms\ReservationForm;
use Wprsrv\PostTypes\Objects\Reservable;
use Wprsrv\PostTypes\Objects\Reservation;

/**
 * Render reservation form.
 *
 * @global $reservation_form
 * @global $post
 * @since 0.1.0
 * @return void
 */
function reservation_form()
{
    global $reservation_form;
    global $post;

    if (!is_single()) {
        return;
    }

    if (get_post_type() !== 'reservable') {
        return;
    }

    if (!$reservation_form) {
        $reservation_form = new ReservationForm($post);
    }

    $reservation_form->render();
}

/**
 * Get all reservables that have any kind of reservations tied to them.
 *
 * @global $wpdb
 * @since 0.1.0
 * @return \Wprsrv\PostTypes\Objects\Reservable[]
 */
function get_reservables_with_reservations()
{
    global $wpdb;

    $reservables = [];

    $query = 'SELECT DISTINCT meta_value from ' . $wpdb->postmeta . ' WHERE meta_key = "_wprsrv_reservable_id"';

    $results = $wpdb->get_col($query);

    if (empty($results)) {
        return $reservables;
    }

    foreach ($results as $id) {
        $reservables[] = new Reservable($id);
    }

    return $reservables;
}

/**
 * Get a pseudo-user for system related messages etc.
 *
 * @since 0.1.0
 * @return \stdClass
 */
function get_system_user()
{
    $user = new \stdClass();

    $user->display_name = 'System';
    $user->ID = 0;

    return $user;
}

/**
 * AJAX
 * Add a note for a reservation in wp-admin.
 *
 * @since 0.1.0
 * @return void
 */
add_action('wp_ajax_wprsrv_add_note', function ()
{
    if (!isset($_POST) || !isset($_POST['note_content'])) {
        echo 1;
        exit;
    }

    $post = $_POST;

    $reservationId = (int) $post['post_id'];
    $noteContent = $post['note_content'];
    $noteUserId = $post['user_id'];

    if (empty($reservationId) || empty($noteContent) || empty($noteUserId)) {
        echo 1;
        exit;
    }

    $reservation = new Reservation($reservationId);

    $noteContent = strip_tags($noteContent, 'a,b,i,strong,em,p');

    $reservation->addNote($noteContent, $noteUserId);

    echo 0;
    exit;
});

/**
 * AJAX
 * Accept a reservation in the wp-admin.
 *
 * @since 0.1.0
 * @return void
 */
add_action('wp_ajax_wprsrv_accept_reservation', function ()
{
    if (!isset($_POST) || !isset($_POST['post_id'])) {
        echo 1;
        exit;
    }

    $post = $_POST;

    $postId = $post['post_id'];

    if (!$postId) {
        echo 1;
        exit;
    }

    $reservation = new Reservation($postId);

    $reservation->accept();
    $reservation->getReservable()->flushCache();

    echo 0;
    exit;
});

/**
 * AJAX
 * Accept a reservation in the wp-admin.
 *
 * @since 0.1.0
 * @return void
 */
add_action('wp_ajax_wprsrv_decline_reservation', function ()
{
    if (!isset($_POST) || !isset($_POST['post_id'])) {
        echo 1;
        exit;
    }

    $post = $_POST;

    $postId = $post['post_id'];

    if (!$postId) {
        echo 1;
        exit;
    }

    $reservation = new Reservation($postId);

    $reservation->decline();
    $reservation->getReservable()->flushCache();

    echo 0;
    exit;
});

/**
 * AJAX
 * Flush cache for a reservable.
 *
 * @since 0.1.0
 * @return void
 */
add_action('wp_ajax_wprsrv_flush_reservable_cache', function ()
{
    if (!isset($_POST) || !isset($_POST['post_id'])) {
        echo 1;
        exit;
    }

    $post = $_POST;

    $postId = $post['post_id'];

    if (!$postId) {
        echo 1;
        exit;
    }

    $reservable = new Reservable($postId);

    $reservable->flushCache();

    echo 0;
    exit;
});
