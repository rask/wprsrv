<?php

namespace Wprsrv\PostTypes;

use Wprsrv\Forms\ReservationForm;
use Wprsrv\PostTypes\Objects\Reservable as ReservableObj;

/**
 * Class Reservable
 *
 * Post type main class for reservable items. See Object\Reservable for a model.
 *
 * @since 0.1.0
 * @package Wprsrv\PostTypes
 */
class Reservable extends PostType
{
    /**
     * Post type ID.
     *
     * @since 0.1.0
     * @access protected
     * @var String
     */
    protected $postTypeSlug = 'reservable';

    /**
     * Register the post type.
     *
     * @since 0.1.0
     * @access protected
     * @return void
     */
    protected function registerPostType()
    {
        /**
         * The capability type for the post type reservable.
         *
         * Allows filtering the base capability type for the `reservable` post type.
         * Defaults to `post`, which means the capabilities of posts are used on
         * reservables too.
         *
         * @since 0.1.0
         *
         * @param String $capabilityType The post type base capability.
         */
        $capabilityType = apply_filters('wprsrv/reservable/capability_type', 'post');

        $labels = [
            'name' => _x('Reservables', 'post type labels', 'wprsrv'),
            'singular_name' => _x('Reservable', 'post type labels', 'wprsrv'),
            'add_new' => _x('Add new', 'post type labels', 'wprsrv'),
            'add_new_item' => _x('Add new reservable', 'post type labels', 'wprsrv'),
            'edit_item' => _x('Edit reservable', 'post type labels', 'wprsrv'),
            'new_item' => _x('New reservable', 'post type labels', 'wprsrv'),
            'view_item' => _x('Show reservable', 'post type labels', 'wprsrv'),
            'search_items' => _x('Find reservables', 'post type labels', 'wprsrv'),
            'not_found' => _x('No reservables found', 'post type labels', 'wprsrv'),
            'not_found_in_trash' => _x('No reservables found in trash', 'post type labels', 'wprsrv')
        ];

        $args = [
            'labels' => $labels,
            'public' => true,
            'menu_position' => '24.666',
            'menu_icon' => 'dashicons-carrot',
            'rewrite' => ['slug' => _x('reservables', 'rewrite slug for archives', 'reserve')],
            'capability_type' => $capabilityType,
            'has_archive' => true,
            'register_meta_box_cb' => [$this, 'editFormMetaBox'],
            'map_meta_cap' => true,
            'show_in_menu' => 'wprsrv',
            'supports' => [
                'thumbnail',
                'editor',
                'title',
                'custom-fields'
            ]
        ];

        /**
         * Filter the post type arguments before registering.
         *
         * Allows filtering the arguments used to register the `reservable` post
         * type.
         *
         * @since 0.1.0
         *
         * @param mixed[] $args The post type arguments.
         */
        $args = apply_filters('wprsrv/reservable/post_type_args', $args);

        register_post_type($this->postTypeSlug, $args);

        $this->addHooks();
    }

    /**
     * Add various hooks to accomplish various reservable related goals. Wow sounded quite the business talk.
     *
     * @since 0.1.0
     * @access protected
     * @return void
     */
    protected function addHooks()
    {
        add_action('init', [$this, 'generateReservationForm']);
        add_action('save_post', [$this, 'saveReservable'], 25, 3);
    }

    /**
     * Custom saving logic for reservables.
     *
     * @see self::addHooks()
     * @see:wphook save_post
     * @since 0.1.0
     * @todo Make this method simpler and extract some parts elsewhere.
     *
     * @param Integer $post_id Post ID being saved.
     * @param \WP_Post $post Post being saved.
     * @param Boolean $update Is this an update?
     *
     * @return void
     */
    public function saveReservable($post_id, $post, $update)
    {
        if (wp_is_post_revision($post_id)) {
            return;
        }

        if (get_post_type($post_id) !== 'reservable') {
            return;
        }

        $postData = $_POST;

        if (!isset($postData['wprsrv'])) {
            return;
        }

        $wprsrvData = $postData['wprsrv'];

        $reservable = new ReservableObj($post);

        // Flush caches on save.
        if ($update) {
            $reservable->flushCache();
        }

        $keysAvailable = [
            'reservable_active',
            'reservable_singleday',
            'reservable_disabled_days',
            'reservable_loggedin_only'
        ];

        // Set null values if not given on update.
        foreach ($keysAvailable as $key) {
            if (array_key_exists($key, $wprsrvData)) {
                continue;
            }

            switch ($key) {
                case 'reservable_active':
                    $reservable->setActive(false);
                    break;

                case 'reservable_singleday':
                    $reservable->setSingleDay(false);
                    break;

                case 'reservable_disabled_days':
                    $reservable->setDisabledDaysAdminData(false);
                    break;

                case 'reservable_loggedin_only':
                    $reservable->setLoginRequired(false);
                    break;
            }
        }

        // Update values with given POST.
        foreach ($wprsrvData as $key => $value) {
            switch ($key) {
                case 'reservable_active':
                    $reservable->setActive($value === 'on' ? true : false);
                    break;

                case 'reservable_singleday':
                    $reservable->setSingleDay($value === 'on' ? true : false);
                    break;

                case 'reservable_disabled_days':
                    $disabled = [];

                    for ($i = 0; $i < count($value['start']); $i++) {
                        if (empty($value['start'][$i])) {
                            continue;
                        }

                        $range = [
                            'start' => $value['start'][$i],
                            'end' => $value['end'][$i],
                            'reservation_id' => 0
                        ];

                        $disabled[] = $range;
                    }

                    $reservable->setDisabledDaysAdminData($disabled);

                    break;

                case 'reservable_loggedin_only':
                    $reservable->setLoginRequired($value === 'on' ? true : false);
                    break;
            }
        }

        /**
         * Hook fired when a reservable is saved to the database.
         *
         * Allows doing additional saving logic for reservables.
         *
         * @since 0.1.0
         *
         * @param Integer $id The reservable post ID.
         * @param ReservableObj $reservable The reservable object.
         * @param Boolean $update Is this an update or a new reservable?
         */
        do_action('wprsrv/reservable/save', $reservable->ID, $reservable, $update);
    }

    /**
     * Generate a reservation form object for a reservable.
     *
     * @see self::addHooks()
     * @see:wphook init
     * @since 0.1.0
     *
     * @return void
     */
    public function generateReservationForm()
    {
        global $reservation_form;
        global $post;

        if (is_admin() || !is_single() || get_post_type() !== 'reservable') {
            return;
        }

        $form = new ReservationForm($post);

        /**
         * Filter a new reservation form instance.
         *
         * @since 0.1.0
         *
         * @param ReservationForm $form
         */
        $form = apply_filters('wprsrv/reservation_form_instance', $form);

        $reservation_form = $form;
    }

    /**
     * Spawn edit screen metaboxes for reservables.
     *
     * @since 0.1.0
     *
     * @param ReservableObj|\WP_Post $reservable The reservable object to make
     *                                           metaboxes for.
     *
     * @return void
     */
    public function editFormMetaBox($reservable)
    {
        add_action('admin_enqueue_scripts', function () {
            wp_enqueue_style('edit-reservable', \Wprsrv\wprsrv()->pluginUrl . '/assets/css/admin/edit-reservable.css');
        });

        add_meta_box('reservablesettings', _x('Reservable settings', 'reservable metabox title', 'wprsrv'), function ($reservable) {
            $this->metaBoxCallback('settings', $reservable);
        }, 'reservable', 'normal', 'default', [$reservable]);

        add_meta_box('reservablereservations', _x('Reservation calendar', 'reservable metabox title', 'wprsrv'), function ($reservable) {
            $this->metaBoxCallback('calendars', $reservable);
        }, 'reservable', 'normal', 'default', [$reservable]);

        add_meta_box('reservableactions', _x('Reservable actions', 'reservable metabox title', 'wprsrv'), function ($reservable) {
            $this->metaBoxCallback('actions', $reservable);
        }, 'reservable', 'side', 'default', [$reservable]);
    }

    /**
     * Spawn metaboxes for the post type.
     *
     * @since 0.1.0
     * @access protected
     *
     * @param $string
     * @param $reservable
     *
     * @return void
     */
    protected function metaBoxCallback($string, $reservable)
    {
        $tmplBase = \Wprsrv\wprsrv()->templateDirectory . RDS . 'admin' . RDS . 'reservables' . RDS;

        $templateFiles = [
            'settings' => $tmplBase . 'settings-metabox.php',
            'calendars' => $tmplBase . 'calendars-metabox.php',
            'actions' => $tmplBase . 'actions-metabox.php'
        ];

        include($templateFiles[$string]);
    }
}
