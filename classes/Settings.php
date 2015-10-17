<?php

namespace Wprsrv;

/**
 * Class Settings
 *
 * Settings handler for the plugin.
 *
 * @property mixed $logging
 * @property mixed $email
 * @since 0.1.0
 * @package Wprsrv
 */
class Settings
{
    /**
     * Currently loaded plugin settings.
     *
     * @since 0.1.0
     * @access protected
     * @var mixed[]
     */
    protected $settings = [];

    /**
     * Constructor.
     *
     * Load settings from database or set defaults if they're not available.
     *
     * @since 0.1.0
     * @return void
     */
    public function __construct()
    {
        $this->loadSettings();
    }

    /**
     * Magic settings getter.
     *
     * If this class itself does not have the property, try the settings array.
     *
     * @since 0.1.0
     *
     * @param String $key Property name key.
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
     * @since 0.1.0
     * @return mixed[]
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * Load default settings in case none are set.
     *
     * @since 0.1.0
     * @access protected
     * @return void
     */
    protected function setupDefaultSettings()
    {
        $defaultsFile = wprsrv()->pluginDirectory . RDS . 'config' . RDS . 'defaults.php';

        /**
         * From which PHP file should be load plugin settings from.
         *
         * The file should contain a returned PHP array for settings. The param
         * should be an absolute file path.
         *
         * @since 0.1.0
         *
         * @param String $defaultsFile
         */
        $defaultsFile = apply_filters('wprsrv/settings/defaults_file', $defaultsFile);

        $this->settings = include($defaultsFile);

        // Persist defaults.
        update_option('wprsrv', $this->settings);
    }

    /**
     * Load settings from database.
     *
     * If the database has no settings set, load the defaults.
     *
     * @since 0.1.0
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
