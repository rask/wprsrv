<?php

namespace Wprsrv;
use Wprsrv\PostTypes\Objects\Reservable;

/**
 * Class Plugin
 *
 * Main plugin class.
 *
 * @since 0.1.0
 * @package WordPress\Plugins\Reserve
 */
class Plugin
{
    /**
     * Plugin version.
     *
     * @const
     * @since 0.1.0
     * @var String
     */
    const PLUGIN_VERSION = '0.1.0';

    /**
     * Plugin database version.
     *
     * @const
     * @since 0.1.0
     * @var Integer
     */
    const PLUGIN_DB_VERSION = 1;

    /**
     * Has the plugin class been initialized?
     *
     * @since 0.1.0
     * @access protected
     * @var Boolean
     */
    protected $initialized = false;

    /**
     * Directory path for this plugin.
     *
     * @since 0.1.0
     * @var String
     */
    public $pluginDirectory = '';

    /**
     * URL to the plugin directory.
     *
     * @since 0.1.0
     * @var String
     */
    public $pluginUrl = '';

    /**
     * Classmap.
     *
     * @since 0.1.0
     * @access protected
     * @var String[]
     */
    protected $classMap = [];

    /**
     * Class instances.
     *
     * @since 0.1.0
     * @access protected
     * @var Object[]
     */
    protected $classInstances = [];

    /**
     * Logger instance.
     *
     * @since 0.1.0
     * @var Logger
     */
    public $logger = null;

    /**
     * Constructor.
     *
     * @since 0.1.0
     * @return void
     */
    public function __construct($do_init = false)
    {
        $this->pluginDirectory = WPRSRV_DIR;
        $this->pluginUrl = plugins_url(basename(dirname(__DIR__)));
        $this->templateDirectory = WPRSRV_DIR . RDS . 'includes' . RDS . 'templates';

        $this->classMap = [
            'reservable' => function () {
                return new PostTypes\Reservable();
            },
            'reservation' => function () {
                return new PostTypes\Reservation();
            },
            'admin_menu' => function () {
                return new Admin\AdminMenu();
            },
            'settings' => function () {
                return new Settings();
            },
            'logger' => function () {
                // Load logging settings.
                $logConfig = $this->make('settings')->logging;

                return new Logger($logConfig);
            }
        ];

        add_action('plugins_loaded', function () {
            load_plugin_textdomain('wprsrv', false, basename(WPRSRV_DIR) . RDS . 'languages');
        });

        /**
         * Allow filtering the used classes for the plugin.
         *
         * NOTE: be wary with this filter. You're bound to break things if you don't
         * know what you're doing.
         *
         * @since 0.1.0
         *
         * @param callable[] $classMap Array of callbacks to generate classes.
         */
        $this->classMap = apply_filters('wprsrv/class_map', $this->classMap);

        if (!$this->logger) {
            $this->setupLogger();
        }

        if ($do_init) {
            add_action('init', [$this, 'initialize']);
        }
    }

    /**
     * Setup plugin logger. Follows PSR.
     *
     * @since 0.1.0
     * @access protected
     * @return void
     */
    protected function setupLogger()
    {
        $this->logger = $this->make('logger');
    }

    /**
     * Initializations.
     *
     * Sets up general plugin inits and call either admin or frontend inits depending
     * whether we are in admin or not.
     *
     * @see self::__construct()
     * @see:wphook init
     * @since 0.1.0
     * @return void
     */
    public function initialize()
    {
        if ($this->initialized) {
            return;
        }

        $this->setupPostTypes();

        $this->registerScriptsAndStyles();

        if ($this->isAdmin()) {
            $this->adminInit();
        } else {
            $this->frontendInit();
        }

        /**
         * Wprsrv's own init hook.
         *
         * Helpful for hooking to stuff only if this plugin has loaded.
         *
         * @since 0.1.0
         */
        do_action('wprsrv/init');

        $this->initialized = true;
    }

    /**
     * Register plugin-specific scripts and styles. Enqueue them elsewhere when
     * needed.
     *
     * @since 0.2.0
     * @access protected
     * @return void
     */
    protected function registerScriptsAndStyles()
    {
        $purl = $this->pluginUrl . '/assets';

        /**
         * The Pikaday datepicker library CSS stylesheet file.
         *
         * Pikaday comes with its own CSS stylesheet. Developers can use their own
         * Pikaday styles using this filter.
         *
         * @since 0.1.0
         *
         * @param String $pikadayCss Stylesheet URL.
         */
        $pikadayCssUrl = apply_filters('wprsrv/pikaday/css_url', $purl . '/lib/pikaday/css/pikaday.css');

        // Lib scripts.
        wp_register_script('customevent', $purl . '/js/customevent.js');
        wp_register_script('momentjs', $purl . '/lib/moment/min/moment.min.js', [], null, true);
        wp_register_script('pikaday', $purl . '/lib/pikaday/pikaday.js', ['momentjs'], null, true);

        // Plugin scripts.
        wp_register_script('wprsrv-calendars', $purl . '/js/calendars.js', ['customevent', 'pikaday'], null, true);
        wp_register_script('wprsrv-admin', $purl . '/js/admin.js', ['jquery', 'pikaday'], null, true);

        // Lib styles.
        wp_register_style('pikaday', $pikadayCssUrl);

        // Plugin styles.
        wp_register_style('wprsrv-reservation-form', $purl . '/css/reservation-form.css');
        wp_register_style('edit-reservable', $purl . '/css/admin/edit-reservable.css');
        wp_register_style('edit-reservation', $purl . '/css/admin/edit-reservation.css');
        wp_register_style('wprsrv-export', $purl . '/css/admin/export.css');
    }

    /**
     * Frontend initializations.
     *
     * @since 0.1.0
     * @access protected
     * @return void
     */
    public function frontendInit()
    {
    }

    /**
     * Inits for admin area.
     *
     * @since 0.1.0
     * @access protected
     * @return void
     */
    public function adminInit()
    {
        $this->make('admin_menu');

        wp_enqueue_script('wprsrv-admin');
    }

    /**
     * Setup post types required by this plugin.
     *
     * @since 0.1.0
     * @access protected
     * @return void
     */
    protected function setupPostTypes()
    {
        $this->make('reservable');
        $this->make('reservation');
    }

    /**
     * Fired on WP plugin activation hook. No output allowed.
     *
     * @static
     * @since 0.1.0
     * @return void
     */
    public static function activate()
    {
        // Begin output buffering.
        ob_start();

        $self = new self(false);

        try {
            // Make sure we load post types.
            $self->setupPostTypes();

            // Then regenerate rewrite rules to account for our new post types.
            flush_rewrite_rules();

            $self->logger->notice('Wprsrv plugin activated.');
        } catch (\Exception $e) {
            $self->logger->emergency('Cannot activate plugin: {msg}', ['msg' => $e->getMessage()]);
        }

        // Clean the output buffer.
        ob_end_clean();
    }

    /**
     * Fired on WP plugin deactivation hook. No output allowed.
     *
     * @todo Refactor plugin cache flushing to a separate method, or maybe even a
     *       class.
     * @static
     * @since 0.1.0
     * @return void
     */
    public static function deactivate()
    {
        // Begin output buffering.
        ob_start();

        $self = new self(false);

        $self->logger->notice('Deactivating wprsrv plugin...');

        $reservables = new \WP_Query([
            'post_type' => 'reservable',
            'post_status' => 'all',
            'posts_per_page' => -1,
            'no_found_rows' => 1,
            'nopaging' => 1,
            'fields' => 'ids'
        ]);

        if ($reservables->have_posts()) {
            foreach ($reservables->posts as $post_id) {
                $reservable = new Reservable($post_id);

                $reservable->flushCache();
            }
        }

        // Flush rewrite rules.
        flush_rewrite_rules();

        // Get rid of buffer.
        ob_end_clean();
    }

    /**
     * Instance container method
     *
     * Allows creating and fetching class instances on the fly. A singleton wrapper
     * basically. Not all classes are available as singletons.
     *
     * @see self::classMap
     * @see self::classInstances
     * @since 0.1.0
     * @throws \InvalidArgumentException If invalid class name ID is given for
     *                                   making.
     *
     * @param String $class String ID for class to make.
     *
     * @return mixed
     */
    public function make($class)
    {
        if (!array_key_exists($class, $this->classMap)) {
            throw new \InvalidArgumentException('Invalid class id given.');
        }

        if (!array_key_exists($class, $this->classInstances)) {
            if (is_callable($this->classMap[$class])) {
                $this->classInstances[$class] = $this->classMap[$class]();
            } else {
                $this->classInstances[$class] = new $this->classMap[$class];
            }
        }

        return $this->classInstances[$class];
    }

    /**
     * Has this plugin instance been initialized?
     *
     * @since 0.1.0
     * @return Boolean
     */
    public function isInitialized()
    {
        return $this->initialized;
    }

    /**
     * Are we in admin?
     *
     * @since 0.1.0
     * @return Boolean
     */
    public function isAdmin()
    {
        return is_admin();
    }
}
