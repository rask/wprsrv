<?php

namespace Wprsrv\PostTypes\Objects;

use Wprsrv\Traits\CastsToPost;

/**
 * Class Reservable
 *
 * "Extended" WP\_Post object instance. Allows using WP\_Post methods and properties,
 * but has extended methods and properties specific to reservable post type objects.
 *
 * @since 0.1.0
 * @package Wprsrv\PostTypes\Objects
 */
class Reservable
{
    use CastsToPost;

    /**
     * Generally used reservable cache keys.
     *
     * @since 0.1.0
     * @access protected
     * @var String[]
     */
    protected $cacheKeys = [
        'reservations',
        'calendars',
        'disdays'
    ];

    /**
     * Get all reservations that have been mapped for this reservable.
     *
     * @global $wpdb
     * @since 0.1.0
     * @return \Wprsrv\PostTypes\Objects\Reservation[]
     */
    public function getReservations()
    {
        global $wpdb;

        $reservations = get_transient($this->cachePrefix . 'reservations');

        if ($reservations !== false) {
            return $reservations;
        }

        $query = vsprintf(
            'SELECT p.ID FROM %s p LEFT JOIN %s pm ON (p.ID = pm.post_id) WHERE pm.meta_key = "_wprsrv_reservable_id" AND pm.meta_value = %s',
            [
                $wpdb->posts,
                $wpdb->postmeta,
                $this->ID
            ]
        );

        $results = $wpdb->get_col($query);

        if (empty($results)) {
            return [];
        }

        $results = array_map('intval', $results);

        $reservations = array_map(function ($id) {
            return new Reservation($id);
        }, $results);

        // Cache reservations for 4 hours.
        set_transient($this->cachePrefix . 'reservations', $reservations, HOUR_IN_SECONDS*4);

        return $reservations;
    }

    /**
     * Get the data for all disabled days for a reservable item. This includes
     * blocked time which has been already reserved.
     *
     * @since 0.1.0
     * @return mixed
     */
    public function getDisabledDaysData()
    {
        // Get cached disabled days data.
        $ranges = get_transient($this->cachePrefix . 'disdays');

        if ($ranges !== false) {
            return $ranges;
        }

        $disabledRanges = $this->getDisabledDaysAdminData();
        $disabledWeekdays = $this->getDisabledWeekdaysData();
        $reservations = $this->getReservations();

        $weekInterval = new \DateInterval('P1W');

        if (!empty($disabledWeekdays)) {
            foreach ($disabledWeekdays as $weekday) {
                $wdayDate = new \DateTime('now');
                $wdayDate->modify($weekday);

                // Pad to two years.
                for ($i = 0; $i < 105; $i++) {
                    $range = [
                        'start' => $wdayDate->format('Y-m-d'),
                        'end' => $wdayDate->format('Y-m-d')
                    ];

                    $disabledRanges[] = $range;

                    $wdayDate->add($weekInterval);
                }
            }
        }

        foreach ($reservations as $reservation) {
            if (!$reservation->isPending() && !$reservation->isAccepted()) {
                continue;
            }

            try {
                $start = $reservation->getStartDate();
                $end = $reservation->getEndDate();

                // Don't get "expired" dates.
                if ($end < date('Y-m-d')) {
                    continue;
                }

                $range = [
                    'start' => $start,
                    'end' => $end,
                    'reservation_id' => $reservation->ID
                ];

                $disabledRanges[] = $range;
            } catch (\Exception $e) {
                \Wprsrv\wprsrv()->logger->alert('Invalid date data for reservation {id}', ['id' => $reservation->ID]);
            }
        }

        set_transient($this->cachePrefix . 'disdays', $disabledRanges, HOUR_IN_SECONDS*4);

        return $disabledRanges;
    }

    /**
     * Is the reservable set as active?
     *
     * @since 0.1.0
     * @return Boolean
     */
    public function isActive()
    {
        return !! $this->getMeta('active');
    }

    /**
     * Set the active state for this reservable.
     *
     * @since 0.1.0
     *
     * @param Boolean $active Optional. True or false, defaults to true.
     *
     * @return void
     */
    public function setActive($active = true)
    {
        $this->setMeta('active', !! $active);
    }

    /**
     * Set a post meta value for this reservable. Returns true/false on success
     * condition, or integer if it is a new meta key/value pair.
     *
     * @since 0.1.0
     *
     * @param String $key Meta key.
     * @param mixed $value Meta value. Will be serialized if needed.
     *
     * @return Boolean|Integer
     */
    public function setMeta($key, $value)
    {
        if (strpos($key, '_wprsrv') !== 0) {
            $key = '_wprsrv_' . $key;
        }

        return update_post_meta($this->ID, $key, $value);
    }

    /**
     * Get reservable meta value for $key.
     *
     * @since 0.1.0
     *
     * @param String $key The meta key to get value of.
     *
     * @return mixed
     */
    public function getMeta($key, $single = true)
    {
        if (strpos($key, '_wprsrv') !== 0) {
            $key = '_wprsrv_' . $key;
        }

        return get_post_meta($this->ID, $key, $single);
    }

    /**
     * Clear a meta value from the database.
     *
     * @param String $key Meta key whose value to clear.
     *
     * @return void
     */
    public function clearMeta($key)
    {
        delete_post_meta($this->ID, $key);
    }

    /**
     * Is the reservable in singleday mode?
     *
     * @since 0.1.0
     * @return Boolean
     */
    public function isSingleDay()
    {
        return !! $this->getMeta('singleday');
    }

    /**
     * Set the singleday mode value.
     *
     * @since 0.1.0
     *
     * @param Boolean $singleday Optional. True or false, defaults to true.
     *
     * @return void
     */
    public function setSingleDay($singleday = true)
    {
        $this->setMeta('singleday', !! $singleday);
    }

    /**
     * Set admin panel settings disabled days data.
     *
     * @since 0.1.0
     *
     * @param mixed[]|Boolean $data Data to set. Dual arrays for date pairs
     *                              (start->end). Pass in false to clear.
     *
     * @return void
     */
    public function setDisabledDaysAdminData($data)
    {
        $this->setMeta('admin_disabled_days', $data);
    }

    /**
     * Get data for admin disabled days.
     *
     * @since 0.1.0
     * @return mixed
     */
    public function getDisabledDaysAdminData()
    {
        return $this->getMeta('admin_disabled_days', true);
    }

    /**
     * Get admin disabled weekdays data.
     *
     * @since 0.1.1
     * @return mixed
     */
    public function getDisabledWeekdaysData()
    {
        return $this->getMeta('disabled_weekdays');
    }

    /**
     * Set the disabled weekdays for this reservable.
     *
     * @since 0.1.1
     *
     * @param mixed[]|Boolean $data Data for the disabled weekdays. Array of dayname
     *                              slugs, e.g. 'monday', 'tuesday', etc. Clear this
     *                              byb passing in `false`.
     *
     * @return void
     */
    public function setDisabledWeekdaysData($data)
    {
        $this->setMeta('disabled_weekdays', $data);
    }

    /**
     * Does this reservable have a certain weekday disabled for reservations?
     *
     * @since 0.1.1
     *
     * @param String $weekday Weekday nicename, e.g. `monday`.
     *
     * @return Boolean
     */
    public function isWeekdayDisabled($weekday)
    {
        $disabledWeekdays = $this->getDisabledWeekdaysData();

        if (empty($disabledWeekdays)) {
            return false;
        }

        return in_array($weekday, $disabledWeekdays);
    }

    /**
     * Do reservations for this item require login?
     *
     * @since 0.1.0
     * @return Boolean
     */
    public function isLoginRequired()
    {
        return $this->getMeta('login_required');
    }

    /**
     * Set login requirement flag.
     *
     * @since 0.1.0
     *
     * @param bool|true $required
     *
     * @return void
     */
    public function setLoginRequired($required = true)
    {
        $this->setMeta('login_required', !! $required);
    }

    /**
     * Is a day reserved for this reservable? Counts pending and accepted reservations.
     *
     * @since 0.1.0
     *
     * @param \DateTime $date Date to check.
     * @param Boolean $acceptedOnly Disregard pending exceptions.
     *
     * @return Boolean
     */
    public function isDayReserved(\DateTime $date, $acceptedOnly = true)
    {
        $disDays = $this->getDisabledDaysData();

        $isReservedDay = false;

        if (empty($disDays)) {
            return false;
        }

        foreach ($disDays as $day) {
            // Admin disabled day, skip.
            if (!$day['reservation_id']) {
                continue;
            }

            $start = $day['start'];
            $end = $day['end'];
            $dateToCheck = $date->format('Y-m-d');

            if (($dateToCheck >= $start) && ($dateToCheck <= $end)) {
                $reservation = new Reservation($day['reservation_id']);

                if ($acceptedOnly) {
                    $statuses = ['reservation_accepted'];
                } else {
                    $statuses = ['reservation_pending', 'reservation_accepted'];
                }

                if (in_array($reservation->post_status, $statuses)) {
                    $isReservedDay = true;

                    break;
                }
            }
        }

        return $isReservedDay;
    }

    /**
     * Get reseravation for a date for this reservable.
     *
     * @since 0.1.0
     *
     * @param \DateTime $date Date to get reservation for.
     *
     * @return null|\Wprsrv\PostTypes\Objects\Reservation
     */
    public function getReservationForDate(\DateTime $date)
    {
        $reservations = $this->getReservations();

        foreach ($reservations as $reservation) {
            if ($reservation->containsDate($date)) {
                return $reservation;
            }
        }

        return null;
    }

    /**
     * Flush all cached data for this reservable. Loop through predefined cache keys and delete transients.
     *
     * @since 0.1.0
     * @return void
     */
    public function flushCache()
    {
        foreach ($this->cacheKeys as $key) {
            $key = $this->cachePrefix . $key;

            delete_transient($key);
        }

        // Flush calendars on next calendar load.
        $this->setMeta('flush_calendars', true);
    }

    /**
     * Should the calendar cache be flushed on next calendars load?
     *
     * @since 0.1.0
     * @return Boolean
     */
    public function calendarCacheFlushed()
    {
        return $this->getMeta('flush_calendars');
    }

    /**
     * Clear the calendar flush flag.
     *
     * @since 0.1.0
     * @return void
     */
    public function clearCalendarFlush()
    {
        $this->clearMeta('flush_calendars');
    }

    /**
     * Does this reservable have _any_ reservations?
     *
     * @since 0.1.0
     * @return Boolean
     */
    public function hasReservations()
    {
        $reservations = $this->getReservations();

        return !empty($reservations);
    }

    /**
     * Validate whether this reservable has either a pending or an accepted
     * reservation between two dates.
     *
     * @since 0.1.0
     *
     * @param String $start_date Y-m-d string.
     * @param String $end_date Y-m-d string.
     *
     * @return Boolean
     */
    public function hasReservationInDateRange($start_date, $end_date)
    {
        $dateStart = date_create_from_format('Y-m-d', $start_date);
        $dateEnd = date_create_from_format('Y-m-d', $end_date);

        $has = false;

        while ($dateStart <= $dateEnd) {
            $reservation = $this->getReservationForDate($dateStart);

            if ($reservation && !$reservation->isDeclined()) {
                $has = true;

                break;
            }

            $dateStart->add(new \DateInterval('P1D'));
        }

        return $has;
    }
}
