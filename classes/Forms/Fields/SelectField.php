<?php

namespace Wprsrv\Forms\Fields;

use Wprsrv\Forms\Fields\FormField;

/**
 * Class TextField
 *
 * <select><option></option></select>
 *
 * @since 0.1.0
 * @package Wprsrv\Forms\Fields
 */
class SelectField extends FormField
{
    /**
     * Select field options.
     *
     * @since 0.1.0
     * @access protected
     * @var mixed[]
     */
    protected $options = [];

    /**
     * Generate the field markup for use elsewhere.
     *
     * @since 0.1.0
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
