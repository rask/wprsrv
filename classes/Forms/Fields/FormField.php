<?php

namespace Wprsrv\Forms\Fields;

/**
 * Class FormField
 *
 * Base class for form fields.
 *
 * @since 0.1.0
 * @package Wprsrv\Forms\Fields
 */
abstract class FormField
{
    /**
     * Field name attribute.
     *
     * @since 0.1.0
     * @access protected
     * @var String
     */
    protected $name;

    /**
     * Field HTML ID attribute.
     *
     * @since 0.1.0
     * @access protected
     * @var String
     */
    protected $id;

    /**
     * Field value.
     *
     * @since 0.1.0
     * @access protected
     * @var String
     */
    protected $value;

    /**
     * Constructor.
     *
     * @since 0.1.0
     *
     * @param String $name Field name attribute.
     * @param array $fieldData Data for field and its attributes.
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
     * @abstract
     * @since 0.1.0
     * @return String
     */
    public abstract function generateMarkup();
}
