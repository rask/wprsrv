<?php

namespace Wprsrv\Admin;

use Wprsrv\PostTypes\Objects\Reservable;

/**
 * Class ReservableCalendar
 *
 * Calendar to display reservations for a reservable.
 *
 * @since 0.1.0
 * @package Wprsrv
 */
class ReservableCalendar
{
    /**
     * Which reservable this calendar is for.
     *
     * @since 0.1.0
     * @access protected
     * @var Reservable
     */
    protected $reservable;

    /**
     * Calendar date object.
     *
     * @since 0.1.0
     * @access protected
     * @var \DateTime
     */
    protected $date;

    /**
     * Calendar year.
     *
     * @since 0.1.0
     * @access protected
     * @var Integer
     */
    protected $year;

    /**
     * Calendar month.
     *
     * @since 0.1.0
     * @access protected
     * @var Integer
     */
    protected $month;

    /**
     * Is this the first calendar in the sequence?
     *
     * @since 0.1.0
     * @access protected
     * @var Boolean
     */
    protected $isFirstCalendar;

    /**
     * Is this the last calendar in the sequence?
     *
     * @since 0.1.0
     * @access protected
     * @var Boolean
     */
    protected $isLastCalendar;

    /**
     * Constructor.
     *
     * @since 0.1.0
     *
     * @param Reservable $reservable Reservable to create calendar for.
     * @param \DateTime $date DateTime object to use for year and month.
     *
     * @return void
     */
    public function __construct(Reservable $reservable, \DateTime $date, $firstOrLast = null)
    {
        $this->reservable = $reservable;

        $this->date = $date;
        $this->year = (int) $date->format('Y');
        $this->month = (int) $date->format('m');

        $this->daysInMonth = $date->format('t');

        // Is this the first or last calendar in a set of calendars.
        if ($firstOrLast !== null) {
            if ($firstOrLast === 'first') {
                $this->isFirstCalendar = true;
            } elseif ($firstOrLast === 'last') {
                $this->isLastCalendar = true;
            }
        }

        $this->reservations = $this->reservable->getReservations();

        $this->transientKey = 'wprsrv_reservable' . $this->reservable->ID . 'cal' . $this->year . $this->month;
    }

    /**
     * Generate a table cell for a single day.
     *
     * @todo Validate reservations are shown correctly for overlapping
     *        reservation dates.
     *
     * @since 0.1.0
     * @access protected
     *
     * @param \DateTime $date Datetime for the day to generate.
     *
     * @return String
     */
    protected function generateDayCell(\DateTime $date, $weekdayNum)
    {
        $day = $date->format('d');

        $dayReserved = $this->reservable->isDayReserved($date, false);

        if (!$dayReserved) {
            return '<td class="single-day"><span class="day-num">' . $day . '</span></td>';
        }

        $reservations = $this->reservable->getReservationsForDate($date);

        $reservationLabel = $this->generateDayCellReservationsData($reservations, $date, $weekdayNum);

        $dayNum = sprintf('<span class="day-num">%s</span>', $day);

        $tdClasses[] = 'single-day';

        return sprintf('<td class="%s">%s %s</td>', implode(' ', $tdClasses), $dayNum, $reservationLabel);
    }

    /**
     * Generate reservation output for a day cell.
     *
     * @since 0.4.1
     *
     * @param \Wprsrv\PostTypes\Objects\Reservation[] $reservations
     *
     * @return String
     */
    protected function generateDayCellReservationsData($reservations, \DateTime $date, $weekdayNum)
    {
        $output = '';

        foreach ($reservations as $reservation) {
            // Prevent declined reservations from showing up on overlapping days.
            if ($reservation->isDeclined()) {
                continue;
            }

            $resBoxClasses = ['reservation-box', $reservation->post_status];

            $nowYmd = $date->format('Y-m-d');
            $startYmd = $reservation->getStartDate('Y-m-d');
            $endYmd = $reservation->getEndDate('Y-m-d');

            if ($nowYmd === $startYmd) {
                $resBoxClasses[] = 'start';
            } elseif ($nowYmd === $endYmd) {
                $resBoxClasses[] = 'end';
            }

            if ($weekdayNum === 1) {
                $resBoxClasses[] = 'weekstart';
            } elseif ($weekdayNum === 7) {
                $resBoxClasses[] = 'weekend';
            }

            $classes = implode(' ', $resBoxClasses);

            $status = get_post_status_object($reservation->post_status);
            $reserver = $reservation->getReserverEmail();
            $editLink = get_edit_post_link($reservation->ID);

            $output .= sprintf('<div class="%s">', $classes);

            $output .= sprintf('<a href="%s">%s, %s</a>', $editLink, $status->label, $reserver);

            $output .= '</div>';
        }

        return $output;
    }

    /**
     * Generate calendar table headings and nav.
     *
     * @since 0.1.0
     * @access protected
     * @return String
     */
    protected function generateCalendarHead()
    {
        $wdays = [
            __('Monday'),
            __('Tuesday'),
            __('Wednesday'),
            __('Thursday'),
            __('Friday'),
            __('Saturday'),
            __('Sunday')
        ];

        $wdays = array_map(function ($name) {
            $first = mb_substr($name, 0, 1);
            $short = mb_substr($name, 1, 2);
            $rest = mb_substr($name, 3, 99);

            return sprintf('%s<span class="short">%s<span class="full">%s</span></span>', $first, $short, $rest);
        }, $wdays);

        $now = $this->date;
        $month = new \DateInterval('P1M');

        $now->sub($month);
        $prev = $now->format('Y-m');
        $now->add($month)->add($month);
        $next = $now->format('Y-m');
        $now->sub($month);

        $output = '<thead><tr>';

        $prevLink = sprintf('<a href="#cal-%s" class="prev-month">&laquo;</a>', $prev);
        $nextLink = sprintf('<a href="#cal-%s" class="next-month">&raquo;</a>', $next);

        $output .= sprintf(
            '<th>%s</th><th colspan="5">%s</th><th>%s</th>',
            $this->isFirstCalendar ? '' : $prevLink,
            $now->format('F Y'), //FIXME localization
            $this->isLastCalendar ? '' : $nextLink
        );

        $output .= '</tr><tr>';

        foreach ($wdays as $dayname) {
            $output .= sprintf('<th>%s</th>', $dayname);
        }

        $output .= '</tr></thead>';

        return $output;
    }

    /**
     * Generate the calendar body.
     *
     * @since 0.1.0
     * @access protected
     *
     * @param Integer $iter Day loop number.
     *
     * @return string
     */
    protected function generateCalendarBody($iter)
    {
        $output = '';

        $calDateStr = sprintf('%s-%s-%s', $this->year, $this->month, $iter);
        $calDate = date_create_from_format('Y-m-j', $calDateStr);

        $wdayNum = (int) $calDate->format('N');

        // Start row on monday.
        if ($wdayNum == 1) {
            $output .= '<tr>';
        }

        if ($iter == 1 && $iter != $wdayNum) {
            // Pad days.
            $output .= sprintf('<td colspan="%s"></td>', $wdayNum-1);
        }

        $output .= $this->generateDayCell($calDate, $wdayNum);

        if ($iter == $this->daysInMonth && $wdayNum != 7) {
            // Pad days.
            $padAmount = 7 - $wdayNum;
            $output .= sprintf('<td colspan="%s"></td>', $padAmount);
        }

        // End row on sunday.
        if ($wdayNum == 7) {
            $output .= '</tr>';
        }

        return $output;
    }

    /**
     * Generate calendar output for rendering.
     *
     * @since 0.1.0
     * @access protected
     *
     * @param \DateTime $now Current date.
     *
     * @return String
     */
    protected function generateCalendarOutput(\DateTime $now)
    {
        if ($now->format('Y-m') === $this->date->format('Y-m')) {
            $output = sprintf('<table class="reservations-calendar active" id="cal-%1$s-%2$s" data-year="%1$s" data-month="%2$s" summary="Reservations">', $this->year, $this->month);
        } else {
            $output = sprintf('<table class="reservations-calendar" id="cal-%1$s-%2$s" data-year="%1$s" data-month="%2$s" summary="Reservations">', $this->year, $this->month);
        }

        $output .= $this->generateCalendarHead();

        $output .= '<tbody>';

        $i = 1;

        while ($i <= $this->daysInMonth) {
            $output .= $this->generateCalendarBody($i);

            $i++;
        }

        $output .= '</tbody>';

        $output .= '</table>';

        return $output;
    }

    /**
     * Render the calendar.
     *
     * @since 0.1.0
     *
     * @param Boolean $invalidateCache Clear the calendar cache before rendering?
     * @param bool|true $echo Echo or return. Use true to echo.
     *
     * @return String
     */
    public function render($invalidateCache = false, $echo = true)
    {
        if ($invalidateCache) {
            $output = false;
        } else {
            $output = get_transient($this->transientKey);
        }

        if ($output === false) {
            $output = $this->generateCalendarOutput(new \DateTime('now'));

            set_transient($this->transientKey, $output);
        }

        if ($echo) {
            echo $output;
        }

        return $output;
    }
}
