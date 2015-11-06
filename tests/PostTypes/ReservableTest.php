<?php

namespace Wprsrv\Tests\PostTypes;

use Wprsrv\Admin\ReservableCalendar;
use Wprsrv\PostTypes\Objects\Reservable;
use Wprsrv\Tests\WprsrvTestCase;

/**
 * Class ReservableTest
 *
 * @package Wprsrv\Tests\PostTypes
 */
class ReservableTest extends WprsrvTestCase
{
    public function testSavingReservable()
    {
        $reservablePostData = [
            'post_type' => 'reservable',
            'post_title' => 'Test Reservable 0001',
            'post_content' => 'Test content'
        ];

        $reservableMetaData = [
            'reservable_active' => 'on'
        ];

        $_POST['wprsrv'] = $reservableMetaData;

        wp_insert_post($reservablePostData);

        $reservablePost = get_page_by_title('Test Reservable 0001', OBJECT, 'reservable');
        $reservableObj = new Reservable($reservablePost);

        $this->assertInstanceOf('\Wprsrv\PostTypes\Objects\Reservable', $reservableObj);
        $this->assertTrue($reservableObj->isActive());
        $this->assertFalse($reservableObj->isSingleDay());
    }

    public function testCastsToPost()
    {
        $this->generateReservables();

        $reservable = $this->generatedReservables[0];

        $this->assertInstanceOf(Reservable::class, $reservable);
        $this->assertInternalType('integer', $reservable->ID);

        $reservable->ID = 12345;

        $this->assertEquals(12345, $reservable->ID);
    }

    public function testAdminCalendar()
    {
        $this->generateReservables();
        $this->generateReservations();

        $date = date_create_from_format('Y-m', '2016-01');
        $reservable = $this->generatedReservables[0];

        $calendar = new ReservableCalendar($reservable, $date);

        $this->assertInstanceOf(ReservableCalendar::class, $calendar);

        ob_start();

        $calendar->render(true, true);

        $output = ob_get_clean();

        $this->assertRegExp('%2016%', $output);
        $this->assertRegExp('%<table .*>%', $output);
        $this->assertRegExp('%span class="reservation%', $output);
    }
}
