<?php

namespace Wprsrv\PostTypes\Objects;

use Wprsrv\Traits\CastsToPost;

class Reservable
{
    use CastsToPost;

    protected $cacheKeys = [
        'reservations',
        'calendars',
        'disdays'
    ];

    /**
     * Get all reservations that have been mapped for this reservable.
     *
     * @global $wpdb
     *
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
     * Get the data for all disabled days for a reservable item. This includes blocked time which has been already
     * reserved.
     *
     * @return mixed
     */
    public function getDisabledDaysData()
    {
        $ranges = get_transient($this->cachePrefix . 'disdays');

        if ($ranges !== false) {
            return $ranges;
        }

        $disabledRanges = $this->getDisabledDaysAdminData();

        $reservations = $this->getReservations();

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
     * Is the reservable set as active.
     *
     * @return Boolean
     */
    public function isActive()
    {
        return !! $this->getMeta('active');
    }

    /**
     * Set the active state for this reservable.
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
     * @param String $key Meta key.
     * @param mixed $value Meta value. Will be serialized if needed.
     *
     * @return Boolean|Integer
     */
    protected function setMeta($key, $value)
    {
        if (strpos($key, '_wprsrv') !== 0) {
            $key = '_wprsrv_' . $key;
        }

        return update_post_meta($this->ID, $key, $value);
    }

    /**
     * Get reservable meta value for $key.
     *
     * @access protected
     *
     * @param String $key The meta key to get value of.
     *
     * @return mixed
     */
    protected function getMeta($key, $single = true)
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
     * @return Boolean
     */
    public function isSingleDay()
    {
        return !! $this->getMeta('singleday');
    }

    /**
     * Set the singleday mode value.
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
     * @return mixed
     */
    public function getDisabledDaysAdminData()
    {
        return $this->getMeta('admin_disabled_days', true);
    }

    /**
     * Do reservations for this item require login?
     *
     * @return Boolean
     */
    public function isLoginRequired()
    {
        return $this->getMeta('login_required', true);
    }

    /**
     * Set login requirement flag.
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
     * @return Boolean
     */
    public function calendarCacheFlushed()
    {
        return $this->getMeta('flush_calendars');
    }

    /**
     * Clear the calendar flush flag.
     *
     * @return void
     */
    public function clearCalendarFlush()
    {
        $this->clearMeta('flush_calendars');
    }

    /**
     * Does this reservable habe _any_ reservations?
     *
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
