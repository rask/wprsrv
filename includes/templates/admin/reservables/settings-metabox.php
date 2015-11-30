<?php

namespace Wprsrv;

use Wprsrv\PostTypes\Objects\Reservable;

if (!$reservable && !empty($post)) {
    $reservable = $post;
}

if (!$reservable instanceof Reservable) {
    $reservable = new Reservable($reservable);
}

$weekdays = [
    'monday' => __('Monday'),
    'tuesday' => __('Tuesday'),
    'wednesday' => __('Wednesday'),
    'thursday' => __('Thursday'),
    'friday' => __('Friday'),
    'saturday' => __('Saturday'),
    'sunday' => __('Sunday')
];

?>

<table summary="Reservable settings">
    <tbody>

        <tr>
            <th class="wprsrv-mainlabel" scope="row">
                <label for=""><?php _ex('Reservable active', 'reservable metabox form', 'wprsrv'); ?></label>
                <em><?php _e('If the reservable is not active, it will be visible in the frontend but no reservation form will be shown.', 'wprsrv'); ?></em>
            </th>
            <td>
                <label for="wprsrv-reservable-active">
                    <input type="checkbox" name="wprsrv[reservable_active]" <?php echo $reservable->isActive() ? 'checked="checked"' : ''; ?> id="wprsrv-reservable-active">
                    <?php _e('Active', 'wprsrv'); ?>
                </label>
            </td>
        </tr>

        <tr>
            <th class="wprsrv-mainlabel" scope="row">
                <label for=""><?php _ex('Only single-day reservations', 'reservable metabox form', 'wprsrv'); ?></label>
                <em><?php _e('Disable date range selecting in the reservation form.', 'wprsrv'); ?></em>
            </th>
            <td>
                <label for="wprsrv-reservable-singleday">
                    <input type="checkbox" name="wprsrv[reservable_singleday]" <?php echo $reservable->isSingleDay() ? 'checked' : ''; ?> id="wprsrv-reservable-singleday">
                    <?php _e('Single day reservations', 'wprsrv'); ?>
                </label>
            </td>
        </tr>

        <tr>
            <th class="wprsrv-mainlabel" scope="row">
                <label for=""><?php _ex('Only logged in users can reserve', 'reservable metabox form', 'wprsrv'); ?></label>
                <em><?php _e('Disable reservations from guests.', 'wprsrv'); ?></em>
            </th>
            <td>
                <label for="wprsrv-reservable-loggedin">
                    <input type="checkbox" name="wprsrv[reservable_loggedin_only]" <?php echo $reservable->isLoginRequired() ? 'checked' : ''; ?> id="wprsrv-reservable-loggedin">
                    <?php _e('Require login', 'wprsrv'); ?>
                </label>
            </td>
        </tr>

        <tr>
            <th class="wprsrv-mainlabel" scope="row">
                <label for="wprsrv-reservable-disabled-days"><?php _ex('Disabled days', 'reservable metabox form', 'wprsrv'); ?></label>
                <em><?php _e('Choose dates when this reservable is not available for reservation. Dates are inclusive.', 'wprsrv'); ?></em>
            </th>
            <td>
                <div class="wprsrv-clonerow" style="display:none !important;">
                    <div>
                        <input type="text" name="clone_start" class="wprsrv-pikaday">
                        &rarr;
                        <input type="text" name="clone_end" class="wprsrv-pikaday">
                    </div>
                    <div>
                        <a class="deletion delete-row" href="#"><?php _ex('Delete', 'delete repeater row', 'wprsrv'); ?></a>
                    </div>
                </div>

                <?php $disDays = $reservable->getDisabledDaysAdminData(); ?>

                <?php if (!empty($disDays) && is_array($disDays)) : foreach ($disDays as $ranges) : ?>
                <div class="wprsrv-repeater-row">
                    <div>
                        <input type="text" name="wprsrv[reservable_disabled_days][start][]" class="wprsrv-pikaday" value="<?php echo $ranges['start']; ?>">
                        &rarr;
                        <input type="text" name="wprsrv[reservable_disabled_days][end][]" class="wprsrv-pikaday" value="<?php echo $ranges['end']; ?>">
                    </div>
                    <div>
                        <a class="deletion delete-row" href="#"><?php _ex('Delete', 'delete repeater row', 'wprsrv'); ?></a>
                    </div>
                </div>
                <?php endforeach; endif; ?>

                <button class="add-row button button-primary"><?php _ex('Add', 'add repeater row', 'wprsrv'); ?></button>
            </td>
        </tr>

        <tr>
            <th class="wprsrv-mainlabel" scope="row">
                <label for="wprsrv-reservable-disabled-weekdays"><?php _ex('Disabled weekdays', 'reservable metabox form', 'wprsrv'); ?></label>
                <em><?php _e('Choose the weekdays that should not be reservable.', 'wprsrv'); ?></em>
            </th>
            <td>
                <?php foreach ($weekdays as $wdayVal => $wdayName) : ?>
                <label for="wprsrv-disable-weekday-<?php echo $wdayVal; ?>">
                    <input type="checkbox" name="wprsrv[reservable_disabled_weekdays][]" id="wprsrv-disable-weekday-<?php echo $wdayVal; ?>" value="<?php echo $wdayVal; ?>" <?php echo $reservable->isWeekdayDisabled($wdayVal) ? 'checked' : ''; ?>>
                    <?php echo $wdayName; ?>
                </label>
                <?php endforeach; ?>
            </td>
        </tr>

    </tbody>
</table>
