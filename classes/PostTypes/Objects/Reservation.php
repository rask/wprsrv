<?php

namespace Wprsrv\PostTypes\Objects;

use Wprsrv\Email;
use Wprsrv\Traits\CastsToPost;

/**
 * Class Reservation
 *
 * "Extended" WP_Post (see trait) for reservation post objects.
 *
 * @package Wprsrv\PostTypes\Objects
 */
class Reservation
{
    use CastsToPost;

    /**
     * Create a new instance of Reservation from given data.
     *
     * @fixme Validate single day mode works.
     *
     * @param mixed[] $postData Post data values to use. Passed to wp_insert_post.
     * @param mixed[] $metaData Metadata values to use.
     *
     * @return \Wprsrv\PostTypes\Objects\Reservation|Boolean
     */
    public static function create(Array $postData, Array $metaData)
    {
        // Force post type and status.
        $postData['post_type'] = 'reservation';
        $postData['post_status'] = 'reservation_pending';

        if (!isset($metaData['reservable_id'])) {
            throw new \InvalidArgumentException('Could not create a new reservation with the given post data.');
        }

        $metaErrors = static::validateMeta($metaData);

        if (!empty($metaErrors)) {
            $_POST['reservation_notice'] = sprintf('<ul><li>%s</li></ul>', implode('</li><li>', $metaErrors));
            //throw new \InvalidArgumentException('Could not create a new reservation with the given post meta.');
            return false;
        }

        $id = wp_insert_post($postData);

        if (!$id || is_wp_error($id)) {
            if (is_wp_error($id)) {
                var_dump($id, $id->get_error_messages());
            }

            throw new \InvalidArgumentException('Could not create a new reservation with the given post data.');
        }

        $self = new static($id);

        $date = new \DateTime('now');
        $year = new \DateInterval('P1Y');
        $pruneDate = $date->add($year);

        $metaData['prune_date'] = $pruneDate->format('Y-m-d H:i:s');

        // Save meta values.
        foreach ($metaData as $key => $data) {
            if ($key == 'reservation_date_start') {
                $data = preg_replace('%[0-9][0-9]:[0-9][0-9]:[0-9][0-9]$%', '00:00:00', $data);
            } elseif ($key == 'reservation_date_end') {
                $data = preg_replace('%[0-9][0-9]:[0-9][0-9]:[0-9][0-9]$%', '23:59:59', $data);
            }

            $self->setMeta($key, $data);
        }

        $self->getReservable()->flushCache();

        do_action('reservation_pending_post', $id, $self->post);
        do_action('wprsrv/reservation/created', $self->ID, $self);

        return $self;
    }

    /**
     * Validate given metadata for a new reservation.
     *
     * @static
     *
     * @param mixed[] $meta Metadata to validate.
     *
     * @return Boolean
     */
    protected static function validateMeta($meta)
    {
        $required = [
            'reserver_email' => ['email', _x('email address', 'validation error label', 'wprsrv')],
            'start_date' => ['date', _x('starting date or time', 'validation error label', 'wprsrv')],
            'end_date' => ['date', _x('ending date or time', 'validation error label', 'wprsrv')]
        ];

        $errors = [];

        foreach ($required as $key => $type) {
            if (!array_key_exists($key, $meta)) {
                $errors[] = sprintf(_x('Missing field: %s', 'validation error', 'wprsrv'), $type[1]);
            }

            $value = $meta[$key];

            switch ($type) {
                case 'integer':
                    if (!preg_match('%^[0-9]+$%', $value)) {
                        $errors[] = sprintf(_x('Invalid number value for %s', 'validation error', 'wprsrv'), $type[1]);
                    }
                    break;

                case 'date':
                    if (!is_numeric(strtotime($value)) || strtotime($value) < 1000) {
                        $errors[] = sprintf(_x('Invalid date or time value for %s', 'validation error', 'wprsrv'), $type[1]);
                    }
                    break;

                case 'email':
                    if (!is_email($value)) {
                        $errors[] = sprintf(_x('Invalid email value for %s', 'validation error', 'wprsrv'), $type[1]);
                    }
                    break;
            }
        }

        $reservable = new Reservable($meta['reservable_id']);

        if ($reservable->hasReservationInDateRange($meta['start_date'], $meta['end_date'])) {
            $errors[] = _x('Given reservation date range contains a date that has already been reserved', 'validation error', 'wprsrv');
        }

        return $errors;
    }

    /**
     * Return the reservation's post status.
     *
     * @return Boolean|String
     */
    public function getReservationStatus()
    {
        return get_post_status($this->ID);
    }

    /**
     * Get the reservations reservable item ID.
     *
     * @throws \DomainException If no reservable is available.
     *
     * @return \Wprsrv\PostTypes\Objects\Reservable
     */
    public function getReservable()
    {
        $reservableId = (int) $this->getMeta('reservable_id');

        if (!$reservableId) {
            throw new \DomainException(sprintf('Reservation with ID %d does not have a reservable set!', $this->ID));
        }

        $reservablePost = get_post($reservableId);

        $reservable = new Reservable($reservablePost);

        return $reservable;
    }

    /**
     * Get the email address of the person who reserved.
     *
     * @return mixed
     */
    public function getReserverEmail()
    {
        $metaKey = 'reserver_email';

        $emailAddress = $this->getMeta($metaKey);

        if (!$emailAddress) {
            throw new \DomainException(sprintf('Reservation with ID %d has no reserver email set!', $this->ID));
        } elseif (!is_email($emailAddress)) {
            throw new \DomainException(sprintf('Reservation with ID %d has malformed reserver email set!', $this->ID));
        }

        return $emailAddress;
    }

    /**
     * Get starting timestamp.
     *
     * @throws \Exception If no start date is set.
     *
     * @param String|Integer $format Optional. Date format. Use `'object'` if you want
     *                               the DateTime object itself.
     *
     * @return String
     */
    public function getStartDate($format = 'Y-m-d')
    {
        $startDate = date_create_from_format('Y-m-d', $this->getMeta('start_date'));

        if (!$startDate) {
            throw new \Exception('Invalid start date for reservation ' . $this->ID);
        }

        if ($format === 'object') {
            return $startDate;
        }

        return $startDate->format($format);
    }

    /**
     * Get ending timestamp.
     *
     * @throws \Exception If no end date is set.
     *
     * @param String|Integer $format Optional. Date format. Use `'object'` if you want
     *                               the DateTime object itself.
     *
     * @return String
     */
    public function getEndDate($format = 'Y-m-d')
    {
        $endDate = date_create_from_format('Y-m-d', $this->getMeta('end_date'));

        if (!$endDate) {
            throw new \Exception('Invalid end date for reservation ' . $this->ID);
        }

        if ($format === 'object') {
            return $endDate;
        }

        return $endDate->format($format);
    }

    /**
     * Get the wp-admin editing URL link for this reservation.
     *
     * @return String|Boolean
     */
    public function getEditLink()
    {
        $requestString = sprintf('post.php?post=%s&action=edit', $this->ID);

        $url = admin_url($requestString);

        return $url;
    }

    /**
     * Set a post meta value for this reservation. Returns true/false on success condition, or integer if it is a new
     * meta key/value pair.
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
     * Get reservation meta value for $key.
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
     * Adds a short note about a reservation. Uses time() as the timestamp.
     *
     * @param String $note The note string content.
     *
     * @return void
     */
    public function addNote($note, $userId = 0)
    {
        if (!$userId) {
            $userId = wp_get_current_user()->ID;
        }

        $date = date('Y-m-d H:i:s');

        $note = strip_tags($note);
        $note = trim($note);

        $notes = $this->getMeta('notes');

        if (!$notes || empty($notes)) {
            $notes = [];
        }

        $notes[] = ['timestamp' => $date, 'note' => $note, 'user_id' => $userId ? $userId : 0];

        $this->setMeta('notes', $notes);
    }

    /**
     * Get all notes attached to this reservation.
     *
     * @return mixed
     */
    public function getNotes()
    {
        $notes = $this->getMeta('notes');

        @uasort($notes, function ($a, $b) {
            if ($a['timestamp'] > $b['timestamp']) {
                return -1;
            } elseif ($a['timestamp'] < $b['timestamp']) {
                return 1;
            }

            return 0;
        });

        return $notes;
    }

    /**
     * Accept this reservation.
     *
     * @return void
     */
    public function accept()
    {
        $updated = wp_update_post([
            'ID' => (int) $this->ID,
            'post_status' => 'reservation_accepted'
        ]);

        do_action('wprsrv/reservation/new_accepted_reservation', $updated, $this);

        // Clear the prune date value to prevent accidentally pruning during cron.
        $this->clearMeta('prune_date');
    }

    /**
     * Decline a reservation.
     *
     * @return void
     */
    public function decline()
    {
        $updated = wp_update_post([
            'ID' => (int) $this->ID,
            'post_status' => 'reservation_declined'
        ]);

        $date = new \DateTime('now');
        $month = new \DateInterval('P1M');
        $pruneDate = $date->add($month);

        // Declined reservations are pruned after a while.
        $this->setMeta('prune_date', $pruneDate->format('Y-m-d H:i:s'));
    }

    /**
     * Is the reservation declined?
     *
     * @return Boolean
     */
    public function isDeclined()
    {
        if ($this->post_status === 'reservation_declined') {
            return true;
        }

        return false;
    }

    /**
     * Is the reservation pending?
     *
     * @return Boolean
     */
    public function isPending()
    {
        if ($this->post_status === 'reservation_pending') {
            return true;
        }

        return false;
    }

    /**
     * Is the reservation accepted?
     *
     * @return Boolean
     */
    public function isAccepted()
    {
        if ($this->post_status === 'reservation_accepted') {
            return true;
        }

        return false;
    }

    /**
     * Does this reservation contain a date within the reservation range.
     *
     * @param \DateTime $date The date to check.
     *
     * @return Boolean
     */
    public function containsDate($date)
    {
        $start = $this->getStartDate('object');
        $end = $this->getEndDate('object');

        if ($start <= $date && $date <= $end) {
            return true;
        }

        return false;
    }
}
