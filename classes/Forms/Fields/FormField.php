<?php

namespace Wprsrv\Forms\Fields;

/**
 * Class FormField
 *
 * Base class for form fields.
 *
 * @package Wprsrv\Forms\Fields
 */
abstract class FormField
{
    /**
     * Field name attribute.
     *
     * @access protected
     * @var String
     */
    protected $name;

    /**
     * Field HTML ID attribute.
     *
     * @access protected
     * @var String
     */
    protected $id;

    /**
     * Field value.
     *
     * @access protected
     * @var String
     */
    protected $value;

    /**
     * Constructor.
     *
     * @param $name
     * @param array $fieldData
     *
     * @return void
     */
    public function __construct($name, Array $fieldData)
    {
        $this->name = $name;
        $this->id = 'rfs-' . $this->name;

        foreach ($fieldData as $key => $value) {
            $this->{$key} = $value;
        }
    }

    /**
     * Generate field HTML markup. Return as a string.
     *
     * @return String
     */
    public abstract function generateMarkup();
}
