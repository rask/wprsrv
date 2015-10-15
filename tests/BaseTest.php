<?php

namespace Wprsrv\Tests;

use PHPUnit_Framework_TestCase;

/**
 * Class BaseTest
 *
 * Base testing to make sure WordPress and the plugin load and are available.
 *
 * @package Wprsrv\Tests
 */
class BaseTest extends PHPUnit_Framework_TestCase
{
    public function testWordPressLoads()
    {
        $this->assertTrue(defined('ABSPATH'));
        $this->assertTrue(class_exists('\\WP_Query'));
        $this->assertTrue(function_exists('get_bloginfo'));
    }

    public function testPluginAvailable()
    {
        $this->assertTrue(function_exists('\\Wprsrv\\wprsrv'));
        $this->assertTrue(class_exists('\\Wprsrv\\Plugin'));

        $pluginActive = is_plugin_active('wprsrv/wprsrv.php');

        $this->assertTrue($pluginActive);
    }
}
