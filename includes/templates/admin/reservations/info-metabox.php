<?php

namespace Wprsrv;

if (!$reservation && !empty($post)) {
    $reservation = $post;
}

if (!$reservation instanceof \Wprsrv\PostTypes\Objects\Reservation) {
    $reservation = new \Wprsrv\PostTypes\Objects\Reservation($reservation);
}

?>

<table summary="Reservation information">
    <tbody>
        <tr>
            <td>
                <h4><?php _ex('Submitted at', 'info metabox for reservation', 'wprsrv'); ?></h4>
                <?php echo $reservation->post_date; ?>
            </td>
            <td>
                <h4><?php _ex('Status', 'info metabox for reservation, post status', 'wprsrv'); ?></h4>
                <?php echo get_post_status_object($reservation->post_status)->label; ?>
            </td>
        </tr>
        <tr>
            <td>
                <h4><?php _ex('Reservation timespan', 'info metabox for reservation', 'wprsrv'); ?></h4>
                <?php $start = sprintf('<strong>%s</strong>', $reservation->getStartDate()); ?>
                <?php $end = sprintf('<strong>%s</strong>', $reservation->getEndDate()); ?>
                <?php printf(_x('Reserved from %s to %s', 'info metabox for reservation', 'wprsrv'), $start, $end); ?>
            </td>
            <td>
                <h4><?php _ex('Reserved item', 'info metabox for reservation', 'wprsrv'); ?></h4>
                <?php
                    try {
                        echo $reservation->getReservable()->post_title;
                    } catch (\DomainException $de) {
                        printf('<p class="notice error">%s</p>', $de->getMessage());
                    }
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <h4><?php _ex('Description (from reserver)', 'info metabox for reservation', 'wprsrv'); ?></h4>
                <?php echo wpautop(wptexturize($reservation->post_content)); ?>
            </td>
        </tr>
    </tbody>
</table>
