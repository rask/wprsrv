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
class CalendarStartField extends CalendarField
{
    /**
     * Generate HTML for field.
     *
     * @since 0.1.0
     * @return string
     */
    public function generateMarkup()
    {
        return sprintf('<input type="text" placeholder="%s" id="datepicker-field-start" class="datepicker-field" name="%s" value="%s">', _x('From ...', 'calendar placeholder', 'wprsrv'), $this->name, $this->value);
    }
}
