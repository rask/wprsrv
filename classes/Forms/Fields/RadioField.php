<?php

namespace Wprsrv\Forms\Fields;

use Wprsrv\Forms\Fields\FormField;

/**
 * Class RadioField
 *
 * <input type="radio">
 *
 * @since 0.1.0
 * @package Wprsrv\Forms\Fields
 */
class RadioField extends FormField
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
            $this->id,
            $this->groupNumbering,
            $this->value,
            $this->id,
            $this->groupNumbering,
            $this->label
        ];

        return vsprintf('<input type="radio" name="%s" id="%s-%s" value="%s"> <label for="%s-%s">%s</label>', $repl);
    }
}
