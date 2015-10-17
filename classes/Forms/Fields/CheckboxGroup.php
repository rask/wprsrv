<?php

namespace Wprsrv\Forms\Fields;

/**
 * Class CheckboxGroup
 *
 * <input type="checkbox">
 *
 * @since 0.1.0
 * @package Wprsrv\Forms\Fields
 */
class CheckboxGroup extends FormField
{
    /**
     * Options for the group.
     *
     * @since 0.1.0
     * @access protected
     * @var mixed[]
     */
    protected $options = [];

    /**
     * Generate field HTML markup. Return as a string.
     *
     * @since 0.1.0
     * @return String
     */
    public function generateMarkup()
    {
        $output = '';
        $i = 0;

        foreach ($this->options as $optkey => $optval) {
            $singleArgs = [
                'name' => $this->name,
                'id' => $this->id,
                'value' => $optkey,
                'label' => $optval,
                'groupNumbering' => $i
            ];

            $output .= (new CheckboxField($this->name, $singleArgs))->generateMarkup();

            $i++;
        }

        return $output;
    }
}
