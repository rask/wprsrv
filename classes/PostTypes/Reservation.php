<?php

namespace Wprsrv\PostTypes;

use Wprsrv\Email;
use Wprsrv\PostTypes\Objects\Reservation as ReservationObj;

/**
 * Class Reservation
 *
 * Post type registration and global logic for reservations.
 *
 * @since 0.1.0
 * @package Wprsrv\PostTypes
 */
class Reservation extends PostType
{
    /**
     * Post type ID.
     *
     * @since 0.1.0
     * @access protected
     * @var String
     */
    protected $postTypeSlug = 'reservation';

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
         * The capability type for the post type reservation.
         *
         * Allows filtering the base capability type for the `reservation` post type.
         * Defaults to `post`, which means the capabilities of posts are used on
         * reservations too.
         *
         * @since 0.1.0
         *
         * @param String $capabilityType The post type base capability.
         */
        $capabilityType = apply_filters('wprsrv/reservation/capability_type', 'post');

        $labels = [
            'name' => _x('Reservations', 'post type labels', 'wprsrv'),
            'singular_name' => _x('reservation', 'post type labels', 'wprsrv'),
            'add_new' => _x('Add new', 'post type labels', 'wprsrv'),
            'add_new_item' => _x('Add new reservation', 'post type labels', 'wprsrv'),
            'edit_item' => _x('Edit reservation', 'post type labels', 'wprsrv'),
            'new_item' => _x('New reservation', 'post type labels', 'wprsrv'),
            'view_item' => _x('Show reservation', 'post type labels', 'wprsrv'),
            'search_items' => _x('Find reservations', 'post type labels', 'wprsrv'),
            'not_found' => _x('No reservations found', 'post type labels', 'wprsrv'),
            'not_found_in_trash' => _x('No reservations found in trash', 'post type labels', 'wprsrv')
        ];

        $args = [
            'labels' => $labels,
            'public' => false,
            'show_ui' => true,
            'show_in_admin_bar' => false,
            'menu_position' => '24.666',
            'menu_icon' => 'dashicons-calendar-alt',
            'rewrite' => ['slug' => _x('reservations', 'post type rewrite slug', 'wprsrv')],
            'capability_type' => $capabilityType,
            'has_archive' => false,
            'query_var' => false,
            'register_meta_box_cb' => [$this, 'editFormMetaBox'],
            'show_in_menu' => 'wprsrv',
            'map_meta_cap' => true,
            'capabilities' => [
                'create_posts' => false
            ],
            'supports' => [
                'title',
                'custom-fields'
            ]
        ];

        /**
         * Filter the post type arguments before registering.
         *
         * Allows filtering the arguments used to register the `reservation` post
         * type.
         *
         * @since 0.1.0
         *
         * @param mixed[] $args The post type arguments.
         */
        $args = apply_filters('wprsrv/reservation/post_type_args', $args);

        register_post_type($this->postTypeSlug, $args);

        add_action('save_post_reservation', [$this, 'saveReservationStatuses'], 25, 3);

        $this->registerPostTypeStatuses();
        $this->disableQuickEdit();
        $this->adminColumns();
        $this->pruning();
    }

    /**
     * Save reservation status updates in wp-admin.
     *
     * @since 0.2.0
     * @see:wphook save_post_$post_type
     *
     * @param Integer $reservationId Post ID for reservation.
     * @param \WP_Post $reservationPost Reservation WP_Post object.
     * @param Boolean $update Is this a new reservation or an update?
     *
     * @return void
     */
    public function saveReservationStatuses($reservationId, $reservationPost, $update)
    {
        if (!$update) {
            return;
        }

        $reservation = new \Wprsrv\PostTypes\Objects\Reservation($reservationPost);

        $shouldAccept = isset($_POST['accept-reservation']);
        $shouldDecline = isset($_POST['decline-reservation']);

        // Prevent infinite loop.
        remove_action('save_post_reservation', [$this, 'saveReservationStatuses'], 25);

        if ($shouldAccept) {
            $reservation->accept();
        } elseif ($shouldDecline) {
            $reservation->decline();
        }

        // Infinite loop danger over, re-enable.
        add_action('save_post_reservation', [$this, 'saveReservationStatuses'], 25, 3);
    }

    /**
     * Add and remove metaboxes in the edit screen.
     *
     * @since 0.1.0
     *
     * @param \WP_Post $reservation Reservation post object to create metaboxes for.
     *
     * @return void
     */
    public function editFormMetaBox($reservation)
    {
        // Remove stock metaboxes.
        remove_meta_box('slugdiv', 'reservation', 'normal');
        remove_meta_box('postcustom', 'reservation', 'normal');
        remove_meta_box('submitdiv', 'reservation', 'side');

        add_action('admin_enqueue_scripts', function () {
            wp_enqueue_style('edit-reservation');
        });

        add_meta_box('reservationinfo', _x('Reservation information', 'metabox title', 'wprsrv'), function ($reservation) {
            $this->metaBoxCallback('info', $reservation);
        }, 'reservation', 'normal', 'high', [$reservation]);

        add_meta_box('reservationactions', _x('Reservation actions', 'metabox title', 'wprsrv'), function ($reservation) {
            $this->metaBoxCallback('actions', $reservation);
        }, 'reservation', 'normal', 'default', [$reservation]);

        add_meta_box('reservationnotes', _x('Reservation notes', 'metabox title', 'wprsrv'), function ($reservation) {
            $this->metaBoxCallback('notes', $reservation);
        }, 'reservation', 'normal', 'default', [$reservation]);

        add_meta_box('submitdiv', _x('Publish', 'metabox title', 'wprsrv'), function ($reservation) {
            $this->metaBoxCallback('publish', $reservation);
        }, 'reservation', 'side', 'high', [$reservation]);
    }

    /**
     * Setup reservation admin columns.
     *
     * @since 0.1.0
     * @access protected
     * @return void
     */
    protected function adminColumns()
    {
        add_action('current_screen', function ($screen) {
            if ($screen->base === 'edit' && $screen->post_type === $this->postTypeSlug) {
                add_action('restrict_manage_posts', [$this, 'reservationAdminFilters']);
                add_filter("manage_{$this->postTypeSlug}_posts_columns", [$this, 'customizeAdminColumns']);
                add_action("manage_{$this->postTypeSlug}_posts_custom_column", [$this, 'customizeAdminColumnContent'], 25, 2);
            }
        });

        add_filter('parse_query', [$this, 'parseReservationAdminListFilters']);
    }

    /**
     * Parse the filtering options for custom reservation list filters.
     *
     * @since 0.1.0
     * @see self::adminColumns()
     * @see:wphook parse_query
     *
     * @return \WP_Query
     */
    public function parseReservationAdminListFilters($query)
    {
        if (!is_admin()) {
            return $query;
        }

        if (!isset($_GET['post_type']) || $_GET['post_type'] !== 'reservation') {
            return $query;
        }

        if (!isset($_GET['reservable_id']) || empty($_GET['reservable_id']) || !$_GET['reservable_id']) {
            return $query;
        }

        $query->set('meta_key', '_wprsrv_reservable_id');
        $query->set('meta_value', (int) $_GET['reservable_id']);

        return $query;
    }

    /**
     * Custom filters for filtering the admin reservations list.
     *
     * @since 0.1.0
     * @see self::adminColumns()
     * @see:wphook restrict_manage_posts
     *
     * @return void
     */
    public function reservationAdminFilters()
    {
        if (!isset($_GET['post_type']) || $_GET['post_type'] !== 'reservation') {
            return;
        }

        $reservables = \Wprsrv\get_reservables_with_reservations();

        if (!empty($reservables)) {
            $tmplPathParts = [
                \Wprsrv\wprsrv()->pluginDirectory,
                'includes',
                'templates',
                'admin',
                'reservations',
                'reservable-filterbox.php'
            ];

            include(implode(RDS, $tmplPathParts));
        }
    }

    /**
     * Custom columns for reservations.
     *
     * @since 0.1.0
     * @see:wphook manage_{$post_type}_posts_columns
     *
     * @param String[] $columns Columns that have been defined for the table.
     *
     * @return String[]
     */
    public function customizeAdminColumns($columns)
    {
        $date = array_pop($columns);

        $columns['reservation-status'] = __('Status', 'wprsrv');
        $columns['reservable'] = __('Reserved item', 'wprsrv');
        $columns['date'] = $date;

        return $columns;
    }

    /**
     * Custom content for reservation custom columns.
     *
     * @since 0.1.0
     * @see:wphook manage_{$post_type}_posts_custom_column
     *
     * @param String $colName Column slug/name.
     * @param Integer $post_id Reservation post ID.
     *
     * @return void
     */
    public function customizeAdminColumnContent($colName, $reservation_id)
    {
        $reservation = new ReservationObj($reservation_id);

        switch ($colName) {
            case 'reservation-status':
                echo get_post_status_object($reservation->post_status)->label;
                break;

            case 'reservable':
                try {
                    echo $reservation->getReservable()->post_title;
                } catch (\DomainException $de) {
                    printf('<strong>&times; %s</strong>', _x('No reservable determined', 'reservation admin column', 'wprsrv'));
                }
                break;
        }
    }

    /**
     * Custom post statuses for reservations. Should not be used in querying content.
     *
     * @since 0.1.0
     * @access protected
     * @return void
     */
    protected function registerPostTypeStatuses()
    {
        // Pending reservations.
        register_post_status('reservation_pending', [
            'label'                     => _x('Pending reservation', 'post status', 'wprsrv' ),
            'public'                    => is_admin(),
            'exclude_from_search'       => true,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop('Pending reservations <span class="count">(%s)</span>', 'Pending reservations <span class="count">(%s)</span>', 'wprsrv')
        ]);

        // Accepted reservations.
        register_post_status('reservation_accepted', [
            'label'                     => _x('Accepted reservation', 'post status', 'wprsrv'),
            'public'                    => is_admin(),
            'exclude_from_search'       => true,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop('Accepted reservations <span class="count">(%s)</span>', 'Accepted reservations <span class="count">(%s)</span>', 'wprsrv')
        ]);

        // Declined reservations.
        register_post_status('reservation_declined', [
            'label'                     => _x('Declined reservation', 'post status', 'wprsrv'),
            'public'                    => is_admin(),
            'exclude_from_search'       => true,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop('Declined reservations <span class="count">(%s)</span>', 'Declined reservations <span class="count">(%s)</span>', 'wprsrv')
        ]);

        $this->postStatusActions();
    }

    /**
     * Callback to rendering (including) metabox templates.
     *
     * @since 0.1.0
     * @access protected
     *
     * @param String $string
     * @param \WP_Post $reservation
     *
     * @return void
     */
    protected function metaBoxCallback($string, $reservation)
    {
        $tmplBase = \Wprsrv\wprsrv()->templateDirectory . RDS . 'admin' . RDS . 'reservations' . RDS;

        $templateFiles = [
            'info' => $tmplBase . 'info-metabox.php',
            'actions' => $tmplBase . 'actions-metabox.php',
            'notes' => $tmplBase . 'notes-metabox.php',
            'publish' => $tmplBase . 'publish-metabox.php'
        ];

        include($templateFiles[$string]);
    }

    /**
     * Actions to trigger and make when reservation statuses change.
     *
     * @since 0.1.0
     * @access protected
     * @return void
     */
    protected function postStatusActions()
    {
        $accepted = [
            'reservation_accepted', 'reservation_pending', 'reservation_declined', 'trash'
        ];

        // Force pending status from other statuses than reservation statuses.
        add_action('transition_post_status', function ($new, $old, $post) use ($accepted) {
            if ($new === 'trash' && $post->post_type === $this->postTypeSlug) {
                // Flush cache so we get updated data on a removed reservation.
                $reservation = new ReservationObj($post);

                try {
                    $reservation->getReservable()->flushCache();
                } catch (\Exception $e) {
                    \Wprsrv\wprsrv()->logger->warning('Error when trashing a reservation and flushing reservable caches: {msg}', ['msg' => $e->getMessage()]);
                }
            }

            if ($post->post_type !== $this->postTypeSlug || in_array($new, $accepted)) {
                return;
            }

            $postData = [
                'ID' => $post->ID,
                'post_status' => 'reservation_pending'
            ];

            // This will fire the currently running hook again.
            $reservationId = wp_update_post($postData);
        }, 25, 3);

        add_action('wprsrv/reservation/created', [$this, 'newPendingReservation'], 25, 2);
        add_action('wprsrv/reservation/accepted', [$this, 'newAcceptedReservation'], 25, 2);
        add_action('wprsrv/reservation/declined', [$this, 'newDeclinedReservation'], 25, 2);
    }

    /**
     * Actions to run when new reservation is made.
     *
     * @since 0.1.0
     *
     * @param String $id Post ID.
     * @param \WP_Post $post Post object.
     *
     * @return void
     */
    public function newPendingReservation($id, $post)
    {
        if ($post->post_type !== $this->postTypeSlug) {
            return;
        }

        if ($post instanceof ReservationObj) {
            $reservation = $post;
        } else {
            $reservation = new ReservationObj($post);
        }

        /**
         * New pending reservation.
         *
         * @since 0.1.0
         *
         * @param \Wprsrv\PostTypes\Objects\Reservation $reservation Reservation
         *                                                           object.
         */
        do_action('wprsrv/reservation/new_pending_reservation', $reservation);

        $this->sendReservationNoticeEmail('pending', $reservation);
        $this->sendReservationNoticeEmail('admin_pending', $reservation);

        $reservation->addNote(_x('Reservation marked as <em>pending</em>.', 'note content for new pending reservation', 'wprsrv'));
    }

    /**
     * Actions to run when a reservation is accepted.
     *
     * @since 0.1.0
     *
     * @param String $id Post ID.
     * @param \WP_Post $post Post object.
     *
     * @return void
     */
    public function newAcceptedReservation($id, $post)
    {
        if ($post->post_type !== $this->postTypeSlug) {
            return;
        }

        if ($post instanceof ReservationObj) {
            $reservation = $post;
        } else {
            $reservation = new ReservationObj($post);
        }


        /**
         * New accepted reservation.
         *
         * @since 0.1.0
         *
         * @param \Wprsrv\PostTypes\Objects\Reservation $reservation Reservation
         *                                                           object.
         */
        do_action('wprsrv/reservation/new_accepted_reservation', $reservation);

        $this->sendReservationNoticeEmail('accepted', $reservation);

        $reservation->addNote(_x('Reservation marked as <em>accepted</em>.', 'note content for new accepted reservation', 'wprsrv'));
    }

    /**
     * Actions to run when a reservation is declined.
     *
     * @since 0.1.0
     *
     * @param String $id Post ID.
     * @param \WP_Post $post Post object.
     *
     * @return void
     */
    public function newDeclinedReservation($id, $post)
    {
        if ($post->post_type !== $this->postTypeSlug) {
            return;
        }

        if ($post instanceof ReservationObj) {
            $reservation = $post;
        } else {
            $reservation = new ReservationObj($post);
        }

        /**
         * New declined reservation.
         *
         * @since 0.1.0
         *
         * @param \Wprsrv\PostTypes\Objects\Reservation $reservation Reservation
         *                                                           object.
         */
        do_action('wprsrv/reservation/new_declined_reservation', $reservation);

        $this->sendReservationNoticeEmail('declined', $reservation);

        $reservation->addNote(_x('Reservation marked as <em>declined</em>.', 'note content for new declined reservation', 'wprsrv'));
    }

    /**
     * Send a notification email about a reservation.
     *
     * @throws \InvalidArgumentException If invalid notice type is given.
     * @since 0.1.0
     * @access protected
     *
     * @param String $noticeType Type of email to send. Can be `accepted`, `declined`, `pending`, or `admin_pending`.
     * @param ReservationObj $reservation The reservation post.
     *
     * @return void
     */
    protected function sendReservationNoticeEmail($noticeType, ReservationObj $reservation)
    {
        $allowedNoticeTypes = ['accepted', 'declined', 'pending', 'admin_pending'];

        if (!in_array($noticeType, $allowedNoticeTypes)){
            throw new \InvalidArgumentException('Cannot send reservation notice email: invalid notice type `' . strval($noticeType) . '`');
        }

        // Generate base for the email.
        $email = new Email('reservation_' . $noticeType);
        $email->setFailureCallback([$this, 'emailSendFailed'], [$noticeType, $reservation]);

        $adminEmail = \Wprsrv\wprsrv()->make('settings')->email['admin_email'];

        /**
         * Allow adjusting the admin email address, where notifications of new
         * pending reservations are sent to.
         *
         * @since 0.2.0
         *
         * @param String $adminEmail The admin email address.
         * @param \Wprsrv\PostTypes\Objects\Reservation $reservation The reservation
         *                                                           the current
         *                                                           notice is being
         *                                                           sent for.
         */
        $adminEmail = apply_filters('wprsrv/notification_admin_email', $adminEmail, $reservation);

        // General data used for templates.
        $emailData = [
            'from_date' => $reservation->getStartDate('Y-m-d H:i:s'),
            'to_date' => $reservation->getEndDate('Y-m-d H:i:s'),
            'reservable_title' => $reservation->getReservable()->post_title,
            'site_name' => get_bloginfo('name'),
            'site_url' => get_bloginfo('url'),
            'reservation_edit_url' => $reservation->getEditLink(),
            'user_email' => $reservation->getReserverEmail()
        ];

        // Per notice type config.
        switch ($noticeType) {

            case 'accepted':
                $email
                    ->setSubject(_x('Your reservation has been accepted', 'reservation email', 'wprsrv'))
                    ->setTo($reservation->getReserverEmail())
                    ->setTemplate('new-accepted-reservation.php');
                break;

            case 'declined':
                $email
                    ->setSubject(_x('Your reservation has been declined', 'reservation email', 'wprsrv'))
                    ->setTo($reservation->getReserverEmail())
                    ->setTemplate('new-declined-reservation.php');
                break;

            case 'pending':
                $email
                    ->setSubject(_x('You have a new pending reservation', 'reservation email', 'wprsrv'))
                    ->setTo($reservation->getReserverEmail())
                    ->setTemplate('new-pending-reservation.php');
                break;

            case 'admin_pending':
                $email
                    ->setSubject(sprintf(_x('New pending reservation at %s', 'reservation email', 'wprsrv'), $emailData['site_name']))
                    ->setTo($adminEmail)
                    ->setTemplate('admin-new-reservation.php');
                break;

        }

        // Send the mail!
        $email->sendWith($emailData);
    }

    /**
     * Handle failed email sends.
     *
     * @since 0.1.0
     *
     * @param String $emailType Type of email that failed.
     * @param ReservationObj $reservation Reservation for which the email failed.
     *
     * @return void
     */
    public function emailSendFailed($emailType, $reservation)
    {
        $reservation->addNote(sprintf(_x('Reservation email notification failed. Attempted to send a `%s` email.', 'note content for new declined reservation', 'wprsrv'), $emailType));
    }

    /**
     * Setup pruning schedule for old reservations.
     *
     * @since 0.1.0
     * @access protected
     * @return void
     */
    protected function pruning()
    {
        // Action to run prune method on.
        add_action('wprsrv/reservation/daily_prune', [$this, 'pruneReservations']);

        // Schedule if not already scheduled.
        if (wp_next_scheduled('wprsrv/reservation/daily_prune') === false) {
            wp_schedule_event(time(), 'daily', 'wprsrv/reservation/daily_prune');
        }
    }

    /**
     * Prune all reservations that have expired according to their `prune_date` meta
     * value.
     *
     * @since 0.1.0
     * @see self::pruning()
     * @return void
     */
    public function pruneReservations()
    {
        global $wpdb;

        // Posts statuses to prune.
        $pruneStatuses = ['"reservation_pending"', '"reservation_declined"'];

        // Query for reservations which have `prune_date` set and where `prune_date` is less than today.
        $query = vsprintf(
            'SELECT ID FROM %s p LEFT JOIN %s pm ON (p.ID = pm.post_id) WHERE p.post_type = "%s" AND p.post_status IN (%s) AND pm.meta_key = "%s" AND pm.meta_value < "%s"',
            [
                $wpdb->posts,
                $wpdb->postmeta,
                'reservation',
                implode(', ', $pruneStatuses),
                '_wprsrv_prune_date',
                date('Y-m-d H:i:s')
            ]
        );

        $reservationIds = $wpdb->get_col($query);

        foreach ($reservationIds as $id) {
            /**
             * Should a reservation be pruned although prunde date has passed.
             *
             * Allows skipping pruning for single reservations.
             *
             * @since 0.1.0
             *
             * @param Boolean $shouldBePruned True to allow pruning, false to
             *                                disallow.
             * @param Integer $id The reservation post ID.
             */
            $shouldBePruned = (bool) apply_filters('wprsrv/reservation/prune_reservation', true, $id);

            /**
             * Set the method of deletion for pruned reservations.
             *
             * If returns true, pruned reservations will be trashed instead of fully
             * deleted. Returning false (default) will delete pruned reservations
             * completely.
             *
             * @since 0.1.0
             *
             * @param Boolean $trashInstead Trash it on true, delete on false.
             * @param Integer $id Reservation post ID.
             */
            $trashInstead = (bool) apply_filters('wprsrv/reservation/prune_to_trash', false, $id);

            if ($shouldBePruned) {
                // Force delete the reservation, skipping trash unless set to trash.
                $deleted = wp_delete_post($id, !$trashInstead);

                if ($deleted === false) {
                    \Wprsrv\wprsrv()->logger->error('Could not prune reservation {id}: wp_delete_post failed.', ['id' => $id]);
                }
            }
        }
    }

    /**
     * Disable the wp-admin quick edit.
     *
     * @since 0.1.0
     * @access protected
     * @return void
     */
    protected function disableQuickEdit()
    {
        /**
         * Disable or enable reservation quick edit.
         *
         * @since 0.1.0
         *
         * @param Boolean $disable Should the quickedit be disabled.
         */
        $shouldDisable = apply_filters('wprsrv/reservation/disable_quickedit', true);

        if (!$shouldDisable) {
            return;
        }

        add_filter('post_row_actions', function ($actions) {
            global $current_screen;

            if ($current_screen->post_type !== $this->postTypeSlug) {
                return $actions;
            }

            // Disable!
            unset($actions['inline hide-if-no-js']);

            return $actions;
        }, 25);
    }
}
