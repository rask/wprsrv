<?php

namespace Wprsrv\Tests\PostTypes;

use Wprsrv\PostTypes\Objects\Reservable;

class ReservableTest extends \PHPUnit_Framework_TestCase
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
}
