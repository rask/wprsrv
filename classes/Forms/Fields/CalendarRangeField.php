<?php

namespace Wprsrv\Forms\Fields;

use Wprsrv\Forms\Fields\CalendarEndField;
use Wprsrv\Forms\Fields\CalendarStartField;
use Wprsrv\Forms\Fields\FormField;

/**
 * Class CalendarRangeField
 *
 * Range of calendar fields.
 *
 * @since 0.1.0
 * @package Wprsrv\Forms\Fields
 */
class CalendarRangeField extends FormField
{
    /**
     * Generate field HTML markup. Return as a string.
     *
     * @since 0.1.0
     * @return String
     */
    public function generateMarkup()
    {
        $startData = [
            'type' => 'calendar-start',
            'label' => '',
            'value' => ''
        ];

        $endData = [
            'type' => 'calendar-end',
            'label' => '',
            'value' => ''
        ];

        $html = (new CalendarStartField($this->name . '-start', $startData))->generateMarkup();

        $html .= (new CalendarEndField($this->name . '-end', $endData))->generateMarkup();

        $html .= '<span class="validation"></span>';

        return $html;
    }
}
