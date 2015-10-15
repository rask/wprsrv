<?php

namespace Wprsrv;

if (!$reservation && !empty($post)) {
    $reservation = $post;
}

if (!$reservation instanceof \Wprsrv\PostTypes\Objects\Reservation) {
    $reservation = new \Wprsrv\PostTypes\Objects\Reservation($reservation);
}

$notes = $reservation->getNotes();

?>

<table summary="Reservation information">
    <tbody>
    <tr>
        <th scope="row">
            <h4><label for="new-note-field"><?php _e('Add a note', 'wprsrv'); ?></label></h4>
        </th>
        <td>
            <textarea name="add-note-field" id="new-note-field"></textarea>
            <button id="new-note-button" class="button button-primary"><?php _e('Add', 'wprsrv'); ?></button>
        </td>
    </tr>
    <?php if (empty($notes)) : ?>
        <tr>
            <td colspan="100%">
                <p><?php _e('No reservation notes available.', 'wprsrv'); ?></p>
            </td>
        </tr>
    <?php else : ?>
        <tr>
            <td colspan="100%">
                <p><?php _e('Notes (newest first)', 'wprsrv'); ?></p>
            </td>
        </tr>
        <?php foreach ($notes as $note) : $noteUser = $note['user_id'] === 0 ? \Wprsrv\get_system_user() : get_user_by('id', $note['user_id']); ?>
        <tr>
            <th scope="row">
                <h4><?php printf(_x('Note at %s', 'notes metabox for reservation, %s replaced with datetime', 'wprsrv'), $note['timestamp']); ?></h4>
                <em><?php echo $noteUser->display_name; ?></em>
            </th>
            <td>
                <?php echo wpautop($note['note']); ?>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>
