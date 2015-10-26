<?php

namespace Wprsrv;

?>

<div id="reservation-form">
    <h2><?php _ex('Make a reservation', 'reservation frontend form', 'reserve'); ?></h2>

    <?php if (isset($_POST['reservation_notice'])) : ?>
        <p class="reservation-notice"><?php echo htmlentities(strip_tags($_POST['reservation_notice'])); ?></p>
    <?php endif; ?>

    <?php if (isset($_POST['reservation_success'])) : ?>
        <p class="reservation-notice success"><?php echo htmlentities(strip_tags($_POST['reservation_success'])); ?></p>
    <?php else : ?>
    <form method="post" action="">
        <?php echo $this->formFieldMarkup; ?>

        <?php $this->hiddenFormFields(); ?>

        <input type="submit" value="<?php _ex('Submit reservation', 'reservation frontend form', 'reserve'); ?>">
    </form>
    <?php endif; ?>
</div>
