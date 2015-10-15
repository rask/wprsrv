<?php

namespace Wprsrv\Tests;

use PHPUnit_Framework_TestCase;
use Wprsrv\Plugin;

class PluginTest extends PHPUnit_Framework_TestCase
{
    public function testInitialization()
    {
        $pluginInstance = new Plugin();
        $pluginInstance->initialize();

        $this->assertInstanceOf('\\Wprsrv\\Plugin', $pluginInstance);
        $this->assertTrue($pluginInstance->isInitialized());
    }
}
