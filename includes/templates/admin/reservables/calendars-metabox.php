<?php

namespace Wprsrv;

use Wprsrv\PostTypes\Objects\Reservable;

if (!$reservable && !empty($post)) {
    $reservable = $post;
}

if (!$reservable instanceof Reservable) {
    $reservable = new Reservable($reservable);
}

if ($reservable->hasReservations()) :

$threeMonths = new \DateInterval('P3M');
$year = new \DateInterval('P1Y');

$dateFrom = new \DateTime('now');
$dateFrom->sub($threeMonths);

$dateTo = new \DateTime('now');
$dateTo->add($year);

$month = new \DateInterval('P1M');
$calDate = $dateFrom;

$i = 0;

while ($dateFrom->format('Y-m') <= $dateTo->format('Y-m')) {
    if ($i === 0) {
        $cal = new \Wprsrv\Admin\ReservableCalendar($reservable, $calDate, 'first');
    } elseif ($dateFrom->format('Y-m') == $dateTo->format('Y-m')) {
        $cal = new \Wprsrv\Admin\ReservableCalendar($reservable, $calDate, 'last');
    } else {
        $cal = new \Wprsrv\Admin\ReservableCalendar($reservable, $calDate);
    }

    //FIXME
    $flushed = $reservable->calendarCacheFlushed();

    $cal->render($flushed);

    if (!!$flushed) {
        $reservable->clearCalendarFlush();
    }

    $calDate->add($month);

    $i++;
}

else :

    printf('<p class="empty-notice">%s</p>', __('This reservable has no reservations.', 'wprsrv'));

endif;
