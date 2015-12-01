<?php

namespace Wprsrv;

/**
 * export.php
 *
 * The admin export panel template.
 */

$reservables = get_reservables_with_reservations();

$downloadAction = $_SERVER['REQUEST_URI'] . '&download_export';

?>

<div class="wrap" id="wprsrv-export">

    <h2><?php _e('Export reservation data', 'wprsrv'); ?></h2>

    <p>
        <?php _e('Choose a reservable to export reservation data for. Currently only CSV exports are supported.', 'wprsrv'); ?>
    </p>

    <?php if (empty($reservables)) : ?>

        <div class="notice update-nag">
            <p><?php _e('There are not reservables with reservations available for exporting. Gather some reservations first!', 'wprsrv'); ?></p>
        </div>

    <?php else : ?>

    <form class="postbox" id="wprsrv-export-form" method="post" action="<?php echo $downloadAction; ?>" target="_blank">

        <p>
            <label for="wprsrv-export-reservable"><?php _e('Choose a reservable', 'wprsrv'); ?></label>
            <select id="wprsrv-export-reservable" name="wprsrv[reservable_id]">
                <?php foreach ($reservables as $reservable) : ?>
                <option value="<?php echo $reservable->ID; ?>"><?php echo $reservable->post_title; ?></option>
                <?php endforeach; ?>
            </select>
        </p>

        <p>
            <label for="wprsrv-export-datestart"><?php _e('From date', 'wprsrv'); ?></label>
            <input id="wprsrv-export-datestart" type="text" class="wprsrv-pikaday" name="wprsrv[date_start]">
            <em><?php _e('Use YYYY-MM-DD format. Reservations with ending dates earlier than this date will not be included.', 'wprsrv'); ?></em>
        </p>

        <p>
            <label for="wprsrv-export-dateend"><?php _e('To date', 'wprsrv'); ?></label>
            <input id="wprsrv-export-dateend" type="text" class="wprsrv-pikaday" name="wprsrv[date_end]">
            <em><?php _e('Use YYYY-MM-DD format. Reservations with starting dates later that this date will not be included.', 'wprsrv'); ?></em>
        </p>

        <p>
            <input type="hidden" name="wprsrv[format]" value="csv">
            <input class="button button-primary" type="submit" value="Export as CSV">
            <?php wp_nonce_field('wprsrv-export'); ?>
        </p>

    </form>

    <?php endif; ?>

</div>
