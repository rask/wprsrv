<?php

namespace Wprsrv\Forms;

use Wprsrv\PostTypes\Objects\Reservation;
use Wprsrv\PostTypes\PostType;

/**
 * Class ReservationForm
 *
 * A form used to create a new Reservation against a Reservable.
 *
 * @package Wprsrv\Forms
 */
class ReservationForm
{
    /**
     * The reservable object this form is used with.
     *
     * @access protected
     * @var \Wprsrv\PostTypes\Objects\Reservable
     */
    protected $reservable;

    /**
     * A reservation object for the form, in case the reservation is being edited.
     *
     * @access protected
     * @var null|\Wprsrv\PostTypes\Objects\Reservation
     */
    protected $reservation = null;

    /**
     * Form fields to display on the form.
     *
     * @access protected
     * @var array
     */
    protected $formFields = [];

    /**
     * Constructor.
     *
     * @param \WP_Post The reservable content this form is used with.
     * @param \WP_Post If editing reservation, pass in the reservation object.
     *
     * @return void
     */
    public function __construct(\WP_Post $reservablePost, \WP_Post $reservationPost = null)
    {
        $reservable = new \Wprsrv\PostTypes\Objects\Reservable($reservablePost);

        if ($reservationPost) {
            $reservation = new \Wprsrv\PostTypes\Objects\Reservation($reservationPost);
        } else {
            $reservation = null;
        }

        if ($reservable->post_type !== 'reservable') {
            throw new \InvalidArgumentException('Cannot generate reservation form for non-reservable type post.');
        } elseif ($reservation !== null && $reservation->post_type !== 'reservation') {
            throw new \InvalidArgumentException('Cannot generate reservation edit form for non-reservation type post.');
        }

        $this->reservable = $reservable;
        $this->reservation = $reservation;
        $this->setupDefaultFields();
        $this->printDisabledDaysJson();

        $this->handleFormSubmit();
        $this->enqueueScripts();
    }

    /**
     * Print disabled days for JS calendars.
     *
     * @access protected
     * @return void
     */
    protected function printDisabledDaysJson()
    {
        add_action('wp_print_footer_scripts', function () {
            $disabledDaysData = $this->reservable->getDisabledDaysData();

            $json = json_encode($disabledDaysData);

            printf('<script type="text/javascript">var reservableDisabledDays = %s;</script>', $json);
        });
    }

    /**
     * Handle a submitted reservation form.
     *
     * @fixme Not handling. Validate why.
     *
     * @return Boolean
     */
    public function handleFormSubmit()
    {
        if (!isset($_POST) || empty($_POST)) {
            return false;
        }

        $nonce = $_POST['_wpnonce'];

        if (wp_verify_nonce($nonce, 'wprsrv-form') !== 1) {
            return false;
        }

        if (isset($_POST['wpr-reservation_check']) && !empty($_POST['wpr-reservation_check'])) {
            $_POST['reservation_notice'] = _x('Invalid spam check, please validate your submission.', 'reservation form honeypot failure', 'wprsrv');
            return false;
        }

        $data = [];

        foreach ($_POST as $key => $field) {
            if (strpos($key, 'wprsrv-') === 0) {
                $data[$key] = $field;
            }
        }

        $this->createReservation($data);

        return false;
    }

    public function enqueueScripts()
    {
        $assetsUrl = \Wprsrv\wprsrv()->pluginUrl . '/src';

        $pikadayCss = apply_filters('wprsrv/pikaday/css_url', $assetsUrl . '/lib/pikaday/css/pikaday.css');

        wp_enqueue_script('momentjs', $assetsUrl . '/lib/moment/min/moment.min.js', [], null, true);
        wp_enqueue_script('pikaday', $assetsUrl . '/lib/pikaday/pikaday.js', ['momentjs'], null, true);
        wp_enqueue_style('pikaday', $pikadayCss);

        wp_enqueue_script('wprsrv-calendars', $assetsUrl . '/js/calendars.js', ['customevent', 'pikaday'], null, true);

        wp_enqueue_style('wprsrv-reservation-form', $assetsUrl . '/css/reservation-form.css');

        wp_localize_script('wprsrv-calendars', 'WprsrvL10n', $this->getCalendarLocalization());
    }

    /**
     * Default "catch-all" form fields for generic results.
     *
     * @access protected
     * @return void
     */
    protected function setupDefaultFields()
    {
        $fields = [
            'wprsrv-reserver-name' => [
                'type' => 'text',
                'label' => _x('Your name', 'reservation form field label', 'wprsrv'),
                'value' => ''
            ],
            'wprsrv-reserver-email' => [
                'type' => 'email',
                'label' => _x('Your email address', 'reservation form field label', 'wprsrv'),
                'value' => ''
            ],
            'wprsrv-reservation-date' => [
                'type' => $this->reservable->isSingleDay() ? 'calendar' : 'calendar-range',
                'label' => $this->reservable->isSingleDay() ? _x('Day to reserve', 'reservation form field label', 'wprsrv') : _x('Date range to reserve', 'reservation form field label', 'wprsrv')
            ],
            'wprsrv-reservation-description' => [
                'type' => 'textarea',
                'label' => _x('Additional information', 'reservation form field label', 'wprsrv'),
                'value' => ''
            ]
        ];

        $this->formFields = $fields;
    }

    /**
     * Generate form fields markup.
     *
     * @access protected
     * @return String
     */
    protected function generateFormFieldsMarkup()
    {
        $this->formFields = apply_filters('reserve/reservation_form/fields_data', $this->formFields, $this->reservable);

        if (empty($this->formFields)) {
            throw new \InvalidArgumentException('No form field data to generate form field markup with.');
        }

        $html = '';

        foreach ($this->formFields as $nameAttr => $fieldData) {
            $fieldData = apply_filters('reserve/reservation_form/field_data', $fieldData, $nameAttr, $this->reservable);

            $label = sprintf('<label for="rfs-%s">%s</label>', $nameAttr, $fieldData['label']);

            switch ($fieldData['type']) {
                case 'calendar':
                    $input = (new Fields\CalendarField($nameAttr, $fieldData))->generateMarkup();
                    break;
                case 'calendar-start':
                    $input = (new Fields\CalendarStartField($nameAttr, $fieldData))->generateMarkup();
                    break;
                case 'calendar-end':
                    $input = (new Fields\CalendarEndField($nameAttr, $fieldData))->generateMarkup();
                    break;
                case 'calendar-range':
                    $input = (new Fields\CalendarRangeField($nameAttr, $fieldData))->generateMarkup();
                    break;
                case 'select':
                    $input = (new Fields\SelectField($nameAttr, $fieldData))->generateMarkup();
                    break;
                case 'checkbox':
                    $input = (new Fields\CheckboxGroup($nameAttr, $fieldData))->generateMarkup();
                    break;
                case 'radio':
                    $input = (new Fields\RadioGroup($nameAttr, $fieldData))->generateMarkup();
                    break;
                case 'textarea':
                    $input = (new Fields\TextareaField($nameAttr, $fieldData))->generateMarkup();
                    break;
                case 'text':
                default:
                    $input = (new Fields\TextField($nameAttr, $fieldData))->generateMarkup();
                    break;
            }

            $html .= sprintf('<p>%s %s</p>', $label, $input);
        }

        $html = apply_filters('reserve/reservation_form/fields_html', $html, $this->reservable);

        return $html;
    }

    /**
     * Hidden form fields.
     *
     * @access protected
     *
     * @param Boolean $echo Echo or return.
     *
     * @return String|void
     */
    public function hiddenFormFields($echo = true)
    {
        $html = <<<FIELDS
<input type="text" name="wprsrv-reservation_check" value="" style="display:none !important;">
<input type="hidden" name="wprsrv-reservable_id" value="{reservable_id}">
<input type="hidden" name="wprsrv-reservation_id" value="{reservation_id}">
{wpnonce}
FIELDS;

        $values = [
            'reservation_id' => !$this->reservation ? 0 : $this->reservation->ID,
            'reservable_id' => $this->reservable->ID,
            'wpnonce' => wp_nonce_field('wprsrv-form', '_wpnonce', true, false)
        ];

        foreach ($values as $key => $val) {
            $html = str_replace('{' . $key . '}', $val, $html);
        }

        if ($echo) {
            echo $html;

            return;
        }

        return $html;
    }

    /**
     * Render the form. The included template has $this available.
     *
     * @return void
     */
    public function render()
    {
        if (!$this->reservable->isActive()) {
            $this->reservationDisabledNotice();
            return;
        }

        try {
            $this->formFieldMarkup = $this->generateFormFieldsMarkup();
        } catch (\Exception $e) {
            echo $e->getMessage();
            return;
        }

        $themeTemplateFile = get_stylesheet_directory() . RDS . 'wprsrv' . 'reservation-form.php';
        $pluginTemplateFile = \Wprsrv\wprsrv()->templateDirectory . RDS . 'frontend' . RDS . 'reservation-form.php';

        // If a theme overrides the template file, load it. Otherwise use the plugin's own template.
        if (file_exists($themeTemplateFile)) {
            $templateFile = $themeTemplateFile;
        } else {
            $templateFile = $pluginTemplateFile;
        }

        $templateFile = apply_filters('wprsrv/reservation_form/template_file', $templateFile);

        include($templateFile);
    }

    /**
     * Create a new reservation from the form.
     *
     * @access protected
     *
     * @param mixed[] $data Data for reservation.
     *
     * @return void
     */
    protected function createReservation($data)
    {
        $reservable = $this->reservable;

        $reservation_meta_data = [
            'reservable_id' => $reservable->ID,
            'reserver_email' => $data['wprsrv-reserver-email'],
            'reserver_name' => $data['wprsrv-reserver-name']
        ];

        if ($reservable->isSingleDay()) {
            $reservation_meta_data['start_date'] = $data['wprsrv-reservation-date'];
            $reservation_meta_data['end_date'] = $data['wprsrv-reservation-date'];
        } else {
            $reservation_meta_data['start_date'] = $data['wprsrv-reservation-date-start'];
            $reservation_meta_data['end_date'] = $data['wprsrv-reservation-date-end'];
        }

        $reservationTitle = [
            $reservable->post_title,
            ': ',
            $reservation_meta_data['start_date'],
            ' to ',
            $reservation_meta_data['end_date'],
            ', by ',
            $reservation_meta_data['reserver_email']
        ];

        $reservationTitle = implode('', $reservationTitle);

        $reservation_post_data = [
            'post_type' => 'reservation',
            'post_title' => $reservationTitle,
            'post_status' => 'reservation_pending',
            'post_content' => $data['wprsrv-reservation-description']
        ];

        try {
            $reservation = Reservation::create($reservation_post_data, $reservation_meta_data);
        } catch (\InvalidArgumentException $iae) {
            $_POST['reservation_notice'] = _x('The data you gave looked invalid, please check your fields and try again.', 'reservation form error', 'wprsrv');
            return;
        } catch (\Exception $e) {
            $_POST['reservation_notice'] = _x('Sorry, something went wrong in the reservation system, please try again.', 'reservation form error', 'wprsrv');
            return;
        }

        if ($reservation instanceof Reservation) {
            $this->reservation = $reservation;
        } else {
            return;
        }

        $_POST['reservation_success'] = _x('Thank you for your reservation. You will get a confirmation email in a few moments.', 'reservation form success', 'wprsrv');
    }

    /**
     * Print notice to inform users of disabled reservation.
     *
     * @access protected
     * @return void
     */
    protected function reservationDisabledNotice()
    {
        $message = _x('Reservations are disabled for this item at the moment.', 'reservation form disabled for reservable', 'wprsrv');

        $message = apply_filters('wprsrv/reservation_form/disabled_message', $message, $this->reservable);

        printf('<p class="reservation-disabled-notice">%s</p>', $message);
    }

    /**
     * Reservation form localization for JS.
     *
     * @access protected
     * @return array
     */
    protected function getCalendarLocalization()
    {
        $months = [
            __('January'), __('February'), __('March'),
            __('April'), __('May'), __('June'),
            __('July'), __('August'), __('September'),
            __('October'), __('November'), __('December')
        ];

        $weekdays = [__('Sunday'), __('Monday'), __('Tuesday'), __('Wednesday'), __('Thursday'), __('Friday'), __('Saturday')];

        $weekdaysShort = [__('Sun'), __('Mon'), __('Tue'), __('Wed'), __('Thu'), __('Fri'), __('Sat')];

        $l10n = [
            'Calendars' => [
                'l10n' => [
                    'pikaday' => [
                        'previousMonth' => _x('Previous Month', 'pikaday calendars', 'wprsrv'),
                        'nextMonth'     => _x('Next Month', 'pikaday calendars', 'wprsrv'),
                        'months'        => $months,
                        'weekdays'      => $weekdays,
                        'weekdaysShort' => $weekdaysShort
                    ],
                    'already_reserved' => _x('You cannot reserve dates that are already reserved.', 'reservation form validation', 'wprsrv')
                ]
            ]
        ];

        return $l10n;
    }
}
