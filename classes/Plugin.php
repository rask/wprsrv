<?php

namespace Wprsrv;
use Wprsrv\PostTypes\Objects\Reservable;

/**
 * Class Plugin
 *
 * Main plugin class.
 *
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
        add_action('plugins_loaded', function () {
            load_plugin_textdomain('wprsrv', false, __DIR__ . RDS . 'languages');
        });

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
            }
        ];

        if (!$this->logger) {
            $this->setupLogger();
        }

        $this->pluginDirectory = dirname(__DIR__);
        $this->pluginUrl = plugins_url(basename(dirname(__DIR__)));
        $this->templateDirectory = $this->pluginDirectory . RDS . 'includes' . RDS . 'templates';

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
        // Load logging settings.
        $logConfig = $this->make('settings')->logging;

        $this->logger = new Logger($logConfig);
    }

    /**
     * Initializations.
     *
     * @since 0.1.0
     * @return void
     */
    public function initialize()
    {
        if ($this->initialized) {
            return;
        }

        $this->setupPostTypes();

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
     * Frontend initializations.
     *
     * @since 0.1.0
     * @access protected
     * @return void
     */
    protected function frontendInit()
    {
        wp_register_script('customevent', $this->pluginUrl . '/assets/lib/customevent.js');
    }

    /**
     * Inits for admin area.
     *
     * @since 0.1.0
     * @access protected
     * @return void
     */
    protected function adminInit()
    {
        $this->make('admin_menu');

        wp_enqueue_script('wprsrv-admin', $this->pluginUrl . '/assets/js/admin.js', ['jquery'], null, true);
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
     * @throws \InvalidArgumentException If invalid class name ID is given for
     *                                   making.
     * @see {$this->classMap}
     * @since 0.1.0
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
