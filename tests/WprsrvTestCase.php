<?php

namespace Wprsrv\Tests;

use Wprsrv\PostTypes\Objects\Reservable;
use Wprsrv\PostTypes\Objects\Reservation;

/**
 * Class WprsrvTestCase
 *
 * @package Wprsrv\Tests
 */
class WprsrvTestCase extends \WP_UnitTestCase
{
    protected $generatedReservables;
    protected $generatedReservations;

    protected function getWpRoot()
    {
        return getWpRootDir();
    }

    protected function generateReservables()
    {
        if (!empty($this->generatedReservables)) {
            return;
        }

        $resData = [
            [
                'post' => [
                    'post_title' => 'Test reservable 0001',
                    'post_type' => 'reservable',
                    'post_content' => 'Post content for test reservable 1.'
                ],
                'meta' => [
                    'reservable_active' => true,
                    'reservable_disabled_days' => [
                        'start' => ['2016-01-10', '2015-01-15'],
                        'end' => ['2016-01-12', '2016-01-20']
                    ]
                ]
            ],
            [
                'post' => [
                    'post_title' => 'Test reservable 0002',
                    'post_type' => 'reservable',
                    'post_content' => 'Post content for test reservable 2.'
                ],
                'meta' => [
                    'reservable_active' => false,
                    'reservable_singleday' => true
                ]
            ],
            [
                'post' => [
                    'post_title' => 'Test reservable 0003',
                    'post_type' => 'reservable',
                    'post_content' => 'Post content for test reservable 3.'
                ],
                'meta' => [
                    'reservable_active' => false,
                    'reservable_loggedin_only' => true
                ]
            ]
        ];

        foreach ($resData as $reservableData) {
            $_POST['wprsrv'] = $reservableData['meta'];

            $resId = wp_insert_post($reservableData['post']);

            $reservable = new Reservable($resId);

            $this->generatedReservables[] = $reservable;

            unset($_POST['wprsrv']);
        }
    }

    protected function generateReservations()
    {
        if (empty($this->generatedReservables)) {
            $this->generateReservables();
        }

        $resData = [
            [
                'post' => [
                    'post_title' => 'Test reservation'
                ],
                'meta' => [
                    'start_date' => '2016-01-07',
                    'end_date' => '2016-01-10',
                    'reserver_email' => 'someone@laigdhadiglhaldg.add',
                    'user_id' => 1
                ],
                'reservable' => $this->generatedReservables[0]
            ],
            [
                'post' => [
                    'post_title' => 'Test reservation 2'
                ],
                'meta' => [
                    'start_date' => '2016-02-10',
                    'end_date' => '2016-02-15',
                    'reserver_email' => 'someone@laigdhadiglhaldg.add',
                    'user_id' => 1
                ],
                'reservable' => $this->generatedReservables[array_rand($this->generatedReservables)]
            ]
        ];

        foreach ($resData as $reservationData) {
            $reservation = Reservation::create(
                $reservationData['post'],
                $reservationData['meta'],
                $reservationData['reservable']
            );

            $this->generatedReservations[] = $reservation;
        }
    }
}
