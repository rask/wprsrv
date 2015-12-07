<?php

namespace Wprsrv;

?>

<div id="reservation-form">
    <h2><?php _ex('Make a reservation', 'reservation frontend form', 'wprsrv'); ?></h2>

    <?php if (isset($_POST['reservation_notice'])) : ?>
        <p class="reservation-notice"><?php echo htmlentities(strip_tags($_POST['reservation_notice'])); ?></p>
    <?php endif; ?>

    <?php if (isset($_POST['reservation_success'])) : ?>
        <p class="reservation-notice success"><?php echo htmlentities(strip_tags($_POST['reservation_success'])); ?></p>
    <?php else : ?>
    <form method="post" action="">
        <?php echo $this->formFieldMarkup; ?>

        <?php do_action('wprsrv/reservation_form/after_fields', $this); ?>

        <?php $this->hiddenFormFields(); ?>

        <input type="submit" value="<?php _ex('Submit reservation', 'reservation frontend form', 'wprsrv'); ?>">
    </form>
    <?php endif; ?>
</div>
