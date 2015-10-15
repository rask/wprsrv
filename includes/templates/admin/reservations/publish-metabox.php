<?php

namespace Wprsrv;

if (!$reservation && !empty($post)) {
    $reservation = $post;
}

if (!$reservation instanceof \Wprsrv\PostTypes\Objects\Reservation) {
    $reservation = new \Wprsrv\PostTypes\Objects\Reservation($reservation);
}

$params = [
    'post' => $reservation->ID,
    'action' => 'trash'
];

$trashUrlRaw = admin_url('post.php') . '?' . http_build_query($params);
$trashUrl = wp_nonce_url($trashUrlRaw);

?>

<div id="submitpost" class="submitbox">

    <div id="major-publishing-actions">

        <div id="delete-action">
            <a href="<?php echo $trashUrl; ?>" class="submitdelete deletion">
                <?php _e('Move to Trash'); ?>
            </a>
        </div>

        <div id="publishing-action">
            <span class="spinner"></span>
            <input type="hidden" value="<?php _e('Update'); ?>" id="original_publish" name="original_publish">
            <input type="submit" value="<?php _e('Update'); ?>" id="publish" class="button button-primary button-large" name="save">
        </div>

        <div class="clear"></div>

    </div>

</div>
