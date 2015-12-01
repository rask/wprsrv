<?php

namespace Wprsrv\Admin\Export;

use Wprsrv\PostTypes\Objects\Reservable;
use Wprsrv\PostTypes\Objects\Reservation;

/**
 * Class Exporter
 *
 * Defines a base for different types of exporters.
 *
 * @since 0.1.1
 * @abstract
 * @package Wprsrv\Admin
 */
abstract class Exporter
{
    /**
     * Reservable to export for, or null for global export.
     *
     * @since 0.1.1
     * @access protected
     * @var \Wprsrv\PostTypes\Objects\Reservable|null
     */
    protected $reservable = null;

    /**
     * Reservations that are going to be exported.
     *
     * @since 0.1.1
     * @access protected
     * @var \Wprsrv\PostTypes\Objects\Reservation[]
     */
    protected $reservations;

    /**
     * Export data that can be either output to a file or dumped to a browser,
     * depending on the export format.
     *
     * @since 0.1.1
     * @access protected
     * @var mixed
     */
    protected $exportData = null;

    /**
     * Exporter constructor.
     *
     * @since 0.1.1
     *
     * @param \Wprsrv\PostTypes\Objects\Reservable|null $reservable If a reservable
     *                                                  is given, export data for the
     *                                                  given reservable.
     *
     * @return void
     */
    public function __construct(Reservable $reservable = null, $dateRange = [])
    {
        $this->reservable = $reservable;
        $this->reservations = $this->getReservations();
        $this->dateRange = $dateRange;
    }

    /**
     * Gather all reservations.
     *
     * @since 0.1.1
     * @access protected
     * @return \Wprsrv\PostTypes\Objects\Reservation[]
     */
    protected function getReservations()
    {
        $reservations = [];

        $rargs = [
            'post_type' => 'reservation',
            'post_status' => [
                'reservation_pending',
                'reservation_accepted',
                'reservation_declined'
            ],
            'posts_per_page' => -1,
            'nopaging' => 1,
            'no_found_rows' => true,
            'meta_key' => '_wprsrv_reservable_id'
        ];

        if ($this->reservable) {
            $reservations = $this->reservable->getReservations();
        } else {
            $reservationsQuery = new \WP_Query($rargs);

            foreach ($reservationsQuery->posts as $rpost) {
                $reservations[] = new Reservation($rpost);
            }
        }

        return $reservations;
    }

    /**
     * Validate that the reservation to be exported fits into the export date range.
     *
     * @since 0.1.1
     * @access protected
     *
     * @param \Wprsrv\PostTypes\Objects\Reservation $reservation Reservation to
     *                                                           validate.
     *
     * @return Boolean
     */
    protected function reservationIsInDateRange(Reservation $reservation)
    {
        if (empty($this->dateRange)) {
            return true;
        }

        if (isset($this->dateRange['start']) && $reservation->getEndDate('Y-m-d') > $this->dateRange['start']) {
            return false;
        }

        if (isset($this->dateRange['end']) && $reservation->getStartDate('Y-m-d') < $this->dateRange['end']) {
            return false;
        }

        return true;
    }

    /**
     * Get all collective data for reservations.
     *
     * @since 0.1.1
     * @access protected
     * @return mixed[][]
     */
    protected function getAllReservationData()
    {
        $data = [];

        if (!empty($this->reservations)) {
            foreach ($this->reservations as $reservation) {
                if (!$this->reservationIsInDateRange($reservation)) {
                    continue;
                }

                $data[] = $this->getReservationData($reservation);
            }

            if (empty($data)) {
                throw new \OutOfBoundsException('No reservations available for exporting in the picked date range.');
            }
        }

        return $data;
    }

    /**
     * Get exportable data for a single reservable.
     *
     * @since 0.1.1
     * @access protected
     *
     * @param \Wprsrv\PostTypes\Objects\Reservation $reservation Reservation to get
     *                                                           data for.
     *
     * @return mixed[]
     */
    protected function getReservationData(Reservation $reservation)
    {
        $singleData = $this->getReservationBaseData($reservation);
        $singleData = array_merge($singleData, $this->getReservationMetaData($reservation));

        return $singleData;
    }

    /**
     * Get the meta fields that should be exported for a reservable.
     *
     * @since 0.1.1
     * @access protected
     *
     * @param \Wprsrv\PostTypes\Objects\Reservation $reservation Reservation to get
     *                                                           meta fields for.
     */
    protected function getReservationMetaFields(Reservation $reservation)
    {
        $metafields = [
            '_wprsrv_reserver_name' => _x('Reserver name', 'export column', 'wprsrv'),
            '_wprsrv_reserver_email' => _x('Reserver email', 'export column', 'wprsrv'),
            '_wprsrv_start_date' => _x('Date start', 'export column', 'wprsrv'),
            '_wprsrv_end_date' => _x('Date end', 'export column', 'wprsrv'),
            '_wprsrv_reservation_date' => _x('Date', 'export column', 'wprsrv'),
            '_wprsrv_reservable_id' => _x('Reservable ID', 'export_column', 'wprsrv')
        ];

        /**
         * Allow including custom meta fields for a reservation in an export file.
         *
         * The keys should map directly to `meta_key` values found in the post meta
         * database table. The values should indicate localized columns names that
         * should be used in a CSV export for instance.
         *
         * @since 0.1.1
         *
         * @param String[] $metafields The metafields to get. Use `meta_key` field
         *                             name.
         * @param \Wprsrv\PostTypes\Objects\Reservation $reservation The reservation
         *                                                           to get meta
         *                                                           fields for.
         * @param \Wprsrv\PostTypes\Objects\Reservable|null $reservable The
         *                                                              reservable to
         *                                                              export data
         *                                                              for.
         */
        $metafields = apply_filters('wprsrv/export_metafields', $metafields, $reservation, $this->reservable);

        return $metafields;
    }

    /**
     * Get the base (post) data for a reservation.
     *
     * @since 0.1.1
     * @access protected
     *
     * @param \Wprsrv\PostTypes\Objects\Reservation $reservation Reservation to get
     *                                                           base data for.
     *
     * @return mixed[]
     */
    protected function getReservationBaseData(Reservation $reservation)
    {
        $status = get_post_status_object($reservation->post_status);

        return [
            'ID' => $reservation->ID,
            _x('Title', 'export column', 'wprsrv') => $reservation->post_title,
            _x('Description', 'export column', 'wprsrv') => $reservation->post_content,
            _x('Status', 'export column', 'wprsrv') => $status->label
        ];
    }

    /**
     * Get the metadata for a reservation.
     *
     * @since 0.1.1
     * @access protected
     *
     * @param \Wprsrv\PostTypes\Objects\Reservation $reservation Reservation to get
     *                                                           meta data for.
     * @param String[] $metafields Meta keys and titles to get meta data for from
     *                             the post meta table.
     *
     * @return mixed[]|null
     */
    protected function getReservationMetaData(Reservation $reservation)
    {
        $metafields = $this->getReservationMetaFields($reservation);

        $data = [];

        foreach ($metafields as $field => $title) {
            $data[$title] = get_post_meta($reservation->ID, $field, true);
        }

        $data[_x('Reservable title', 'export column', 'wprsrv')] = $reservation->getReservable()->post_title;

        return $data;
    }

    /**
     * Construct the export dump into a wanted format.
     *
     * @since 0.1.1
     * @abstract
     * @access protected
     * @return mixed
     */
    abstract protected function generateExportData();

    /**
     * Output the export to the browser or similar.
     *
     * @since 0.1.1
     * @abstract
     * @return void
     */
    abstract public function dumpExport();
}
