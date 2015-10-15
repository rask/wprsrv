<?php

namespace Wprsrv;

/**
 * Class Settings
 *
 * Settings handler for the plugin.
 *
 * @property mixed $logging
 * @property mixed $email
 *
 * @package Wprsrv
 */
class Settings
{
    protected $settings = [];

    /**
     * Constructor.
     *
     * Load settings from database or set defaults if they're not available.
     *
     * @return void
     */
    public function __construct()
    {
        $this->loadSettings();
    }

    /**
     * Magic settings getter.
     *
     * @param $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        if (!isset($this->{$key})) {
            if (array_key_exists($key, $this->settings)) {
                return $this->settings[$key];
            }
        }

        return null;
    }

    /**
     * Get all settings.
     *
     * @return mixed[]
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * Load default settings in case none are set.
     *
     * @access protected
     * @return void
     */
    protected function setupDefaultSettings()
    {
        $this->settings = include(wprsrv()->pluginDirectory . RDS . 'config' . RDS . 'defaults.php');

        // Persist defaults.
        update_option('wprsrv', $this->settings);
    }

    /**
     * Load settings from database.
     *
     * @access protected
     * @return void
     */
    protected function loadSettings()
    {
        $settings = get_option('wprsrv', []);

        if (empty($settings)) {
            $this->setupDefaultSettings();
            return;
        }

        $this->settings = $settings;
    }
}
