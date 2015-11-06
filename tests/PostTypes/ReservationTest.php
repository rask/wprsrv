<?php

namespace Wprsrv\Tests\PostTypes;

use Wprsrv\PostTypes\Objects\Reservation;
use Wprsrv\Tests\WprsrvTestCase;

/**
 * Class ReservationTest
 *
 * @package Wprsrv\Tests
 */
class ReservationTest extends WprsrvTestCase
{
    public function testCreatingReservation()
    {
        $this->generateReservables();

        $reservable = $this->generatedReservables[0];

        $reservationData = [
            'post_type' => 'reservation',
            'post_title' => 'Test reservation',
            'post_status' => 'reservation_pending'
        ];

        $reservationMeta = [
            'start_date' => '2016-01-07',
            'end_date' => '2016-01-08',
            'reserver_email' => 'test@example.com'
        ];

        $reservation = Reservation::create($reservationData, $reservationMeta, $reservable);

        $this->assertInstanceOf(Reservation::class, $reservation);

        $reservation->accept();

        $this->assertTrue($reservation->isAccepted());
    }
}
