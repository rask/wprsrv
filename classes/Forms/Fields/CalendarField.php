<?php

namespace Wprsrv\Forms\Fields;

use Wprsrv\Forms\Fields\FormField;

/**
 * Class CalendarField
 *
 * Months calendar.
 *
 * @since 0.1.0
 * @package Wprsrv
 */
class CalendarField extends FormField
{
    /**
     * Generate HTML for field.
     *
     * @since 0.1.0
     * @return string
     */
    public function generateMarkup()
    {
        $noscript = sprintf('<noscript>%s</noscript>', __('Format using YYYY-MM-DD', 'wprsrv'));
        return sprintf('<input type="text" id="datepicker-field" class="datepicker-field" name="%s" value="%s"> %s', $this->name, $this->value, $noscript);
    }
}
