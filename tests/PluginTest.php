<?php

namespace Wprsrv\Tests;

use PHPUnit_Framework_TestCase;
use Wprsrv\Plugin;

/**
 * Class PluginTest
 *
 * @package Wprsrv\Tests
 */
class PluginTest extends WprsrvTestCase
{
    protected $pluginInstance = null;

    public function setUp()
    {
        $this->pluginInstance = new Plugin();
    }

    public function tearDown()
    {
        $this->pluginInstance = null;
    }

    public function testInitialization()
    {
        $this->pluginInstance->initialize();

        $this->assertInstanceOf('\\Wprsrv\\Plugin', $this->pluginInstance);
        $this->assertTrue($this->pluginInstance->isInitialized());
    }

    public function testActivationAndDeactivation()
    {
        global $wp_rewrite;

        include_once($this->getWpRoot() . '/wp-admin/includes/plugin.php');

        if (!is_plugin_active('wprsrv/wprsrv.php')) {
            activate_plugins('wprsrv/wprsrv.php');
        }

        deactivate_plugins('wprsrv/wprsrv.php');
        activate_plugins('wprsrv/wprsrv.php');

        $this->assertTrue(is_plugin_active('wprsrv/wprsrv.php'));
    }

    public function testAdminInit()
    {
        global $menu, $submenu;

        $this->pluginInstance->adminInit();

        do_action('admin_menu');

        $this->assertEquals('wprsrv', array_shift($menu)[2]);
    }
}
