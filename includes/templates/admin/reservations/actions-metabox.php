<?php

namespace Wprsrv;

if (!$reservation && !empty($post)) {
    $reservation = $post;
}

if (!$reservation instanceof \Wprsrv\PostTypes\Objects\Reservation) {
    $reservation = new \Wprsrv\PostTypes\Objects\Reservation($reservation);
}

?>

<table summary="Reservation actions">
    <tr>
        <td>
            <p>
                <?php _ex('If you want to decline this reservation, use this button. An email will be sent to the person who made this reservation, informing them of the declination.', 'actions metabox for reservation', 'wprsrv'); ?>
            </p>
            <?php if ($reservation->post_status === 'reservation_declined') : ?>
                <button disabled="disabled" name="decline-reservation" id="decline-reservation" class="button button-cancel"><?php _ex('Decline reservation', 'actions metabox for reservation', 'wprsrv'); ?></button>
                <em><?php _ex('This reservation has already been declined.', 'actions metabox for reservation', 'wprsrv'); ?></em>
            <?php else : ?>
                <button name="decline-reservation" id="decline-reservation" class="button button-cancel"><?php _ex('Decline reservation', 'actions metabox for reservation', 'wprsrv'); ?></button>
            <?php endif; ?>
        </td>
        <td>
            <p>
                <?php _ex('If you want to approve this reservation, use this button. An email will be sent to the person who made this reservation, informing them about the accepted reservation.', 'actions metabox for reservation', 'wprsrv'); ?>
            </p>
            <?php if ($reservation->post_status === 'reservation_accepted') : ?>
                <button disabled="disabled" class="button primary"><?php _ex('Accept reservation', 'actions metabox for reservation', 'wprsrv'); ?></button>
                <em><?php _ex('This reservation has already been accepted.', 'actions metabox for reservation', 'wprsrv'); ?></em>
            <?php else : ?>
                <button name="accept-reservation" id="accept-reservation" class="button button-primary"><?php _ex('Accept reservation', 'actions metabox for reservation', 'wprsrv'); ?></button>
            <?php endif; ?>
        </td>
    </tr>
</table>
