<?php

namespace Wprsrv\Forms\Fields;

class CheckboxGroup extends FormField
{
    /**
     * Options for the group.
     *
     * @access protected
     * @var mixed[]
     */
    protected $options = [];

    /**
     * Generate field HTML markup. Return as a string.
     *
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
