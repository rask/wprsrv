<?php

namespace Wprsrv\Forms\Fields;

use Wprsrv\Forms\Fields\FormField;

/**
 * Class TextField
 *
 * <input type="text">
 *
 * @since 0.1.0
 * @package Wprsrv\Forms\Fields
 */
class TextField extends FormField
{
    /**
     * Generate the field markup for use elsewhere.
     *
     * @since 0.1.0
     * @return String
     */
    public function generateMarkup()
    {
        return sprintf('<input type="%s" name="%s" id="%s" value="%s">', $this->type, $this->name, $this->id, $this->value);
    }
}
