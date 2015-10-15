<?php

namespace Wprsrv;

/**
 * An admin post list filter form element.
 */

if (!isset($reservables) || empty($reservables)) {
    return;
}

if (isset($_GET['reservable_id']) && is_numeric($_GET['reservable_id'])) {
    $currentReservableId = $_GET['reservable_id'];
} else {
    $currentReservableId = false;
}

?>

<select name="reservable_id">
    <option value=""><?php _ex('All reservables', 'reservation filters', 'wprsrv'); ?></option>

    <?php foreach ($reservables as $reservable) : ?>

        <?php $isSel = ((int) $currentReservableId === (int) $reservable->ID) ? 'selected="selected"' : ''; ?>
        <?php printf('<option %s value="%s">%s</option>', $isSel, $reservable->ID, $reservable->post_title); ?>

    <?php endforeach; ?>
</select>
