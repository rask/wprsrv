<?php

namespace Wprsrv\Forms\Fields;

use Wprsrv\Forms\Fields\FormField;

/**
 * Class TextField
 *
 * <input type="text">
 *
 * @package Wprsrv\Forms\Fields
 */
class SelectField extends FormField
{
    /**
     * Select field options.
     *
     * @access protected
     * @var mixed[]
     */
    protected $options = [];

    /**
     * Generate the field markup for use elsewhere.
     *
     * @return String
     */
    public function generateMarkup()
    {
        $optionsHtml = '';

        foreach ($this->options as $val => $label) {
            $optionsHtml .= sprintf('<option value="%s">%s</option>', $val, $label);
        }

        $input = sprintf('<select name="%s" id="%s">%s</select>', $this->name, $this->id, $optionsHtml);

        return $input;
    }
}
