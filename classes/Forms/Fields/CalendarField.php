<?php

namespace Wprsrv\Forms\Fields;

use Wprsrv\Forms\Fields\FormField;

/**
 * Class CalendarField
 *
 * Months calendar.
 *
 * @package Wprsrv
 */
class CalendarField extends FormField
{
    /**
     * Generate HTML for field.
     *
     * @return string
     */
    public function generateMarkup()
    {
        $noscript = sprintf('<noscript>%s</noscript>', 'Format using YYYY-MM-DD');
        return sprintf('<input type="text" id="datepicker-field" class="datepicker-field" name="%s" value="%s"> %s', $this->name, $this->value, $noscript);
    }
}
