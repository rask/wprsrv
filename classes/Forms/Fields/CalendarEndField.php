<?php

namespace Wprsrv\Forms\Fields;

use Wprsrv\Forms\Fields\CalendarField;

/**
 * Class CalendarField
 *
 * Months calendar.
 *
 * @since 0.1.0
 * @package Wprsrv
 */
class CalendarEndField extends CalendarField
{
    /**
     * Generate HTML for field.
     *
     * @since 0.1.0
     * @return string
     */
    public function generateMarkup()
    {
        return sprintf('<input type="hidden" placeholder="%s" id="datepicker-field-end" class="datepicker-field" name="%s" value="%s">', _x('To ...', 'calendar placeholder', 'wprsrv'), $this->name, $this->value);
    }
}
