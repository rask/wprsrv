<?php

namespace Wprsrv\Forms\Fields;

use Wprsrv\Forms\Fields\FormField;

/**
 * Class TextareaField
 *
 * <textarea></textarea>
 *
 * @package Wprsrv\Forms\Fields
 */
class TextareaField extends FormField
{
    /**
     * Generate the field markup for use elsewhere.
     *
     * @return String
     */
    public function generateMarkup()
    {
        return sprintf('<textarea rows="8" name="%s" id="%s">%s</textarea>', $this->name, $this->id, $this->value);
    }
}
