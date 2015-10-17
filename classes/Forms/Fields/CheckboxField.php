<?php

namespace Wprsrv\Forms\Fields;

use Wprsrv\Forms\Fields\FormField;

/**
 * Class CheckboxField
 *
 * <input type="checkbox">
 *
 * @since 0.1.0
 * @package Wprsrv\Forms\Fields
 */
class CheckboxField extends FormField
{
    /**
     * Option label.
     *
     * @since 0.1.0
     * @access protected
     * @var String
     */
    protected $label = '';

    /**
     * Group number.
     *
     * @since 0.1.0
     * @access protected
     * @var Integer
     */
    protected $groupNumbering = 0;

    /**
     * Generate field HTML markup. Return as a string.
     *
     * @return String
     */
    public function generateMarkup()
    {
        $repl = [
            $this->name,
            $this->value,
            $this->id,
            $this->value,
            $this->value,
            $this->id,
            $this->value,
            $this->label
        ];

        return vsprintf('<input type="checkbox" name="%s-%s" id="%s-%s" value="%s"> <label for="%s-%s">%s</label>', $repl);
    }
}
