<?php

namespace Wprsrv;

use Wprsrv\PostTypes\Objects\Reservable;

if (!$reservable && !empty($post)) {
    $reservable = $post;
}

if (!$reservable instanceof Reservable) {
    $reservable = new Reservable($reservable);
}

if ($reservable->hasReservations()) {

    $calDate = new \DateTime('now');

    $cal = new \Wprsrv\Admin\ReservableCalendar($reservable, $calDate);

    //FIXME
    $flushed = $reservable->calendarCacheFlushed();

    $cal->render($flushed);

    if (!!$flushed) {
        $reservable->clearCalendarFlush();
    }
} else {
    printf('<p class="empty-notice">%s</p>', __('This reservable has no reservations.', 'wprsrv'));
}
