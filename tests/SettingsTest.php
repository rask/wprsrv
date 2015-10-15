<?php

namespace Wprsrv\Tests;

use PHPUnit_Framework_TestCase;
use Wprsrv\Settings;

class SettingsTest extends PHPUnit_Framework_TestCase
{
    public function testInitializingSettings()
    {
        $settings = new Settings();

        $this->assertInstanceOf('\\Wprsrv\\Settings', $settings);
    }

    public function testGetSettings()
    {
        $settings = new Settings();

        $gotSettings = $settings->getSettings();

        $this->assertTrue(is_array($gotSettings));
        $this->assertNotEmpty($gotSettings);

        $this->assertNotEmpty($settings->email);
        $this->assertNull($settings->someSettingWhichDoesNotExist);
    }

    public function testSettingDefaultSettings()
    {
        delete_option('wprsrv');

        $this->testGetSettings();

        $savedSettings = get_option('wprsrv');

        $this->assertNotEmpty($savedSettings);
    }
}
