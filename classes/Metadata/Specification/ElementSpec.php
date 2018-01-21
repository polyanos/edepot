<?php
/**
 * Created by PhpStorm.
 * User: Gregor
 * Date: 21-12-2017
 * Time: 13:59
 */

include_once "iElementSpec.php";
include_once "AttributeSpec.php";

/**
 * Class ElementSpec
 */
class ElementSpec implements iElementSpec
{
    private $name;
    private $required;
    private $type;
    private $attributes;
    private $multiple;
    private $parent;

    /**
     * ElementSpec constructor.
     * @param string $name
     * @param string $type
     * @param bool $required
     * @param bool $multiple
     * @param array $attributes
     * @param ElementSpec|null $parent
     */
    public function __construct($name, $type, $required = false, $multiple = false, $attributes = array(), ElementSpec $parent = null)
    {
        //Mandatory arguments
        if(is_string($name) && !empty($name)){
            $this->name = $name;
        } else{
            throw new InvalidArgumentException("Name must be a string and cannot be empty");
        }
        if(is_string($type) && in_array($type, array(iElementSpec::simple, iElementSpec::complex), true)){
            $this->type = $type;
        } else{
            throw new InvalidArgumentException("Type needs to be either 'simple' or 'complex'");
        }
        if(!is_array($attributes)){
            $this->attributes = array();
            trigger_error("Invalid value type given for \$attributes expected an array, defaulting to an empty array.", E_WARNING);
        }

        //Optional arguments
        $this->required = is_bool($required) ? $required : false;
        $this->multiple = is_bool($multiple) ? $multiple : false;
        $this->parent = $parent;

        $this->attributes = array();
        foreach ($attributes as $name => $data) {
            $this->attributes[] = new AttributeSpec($name, $data["required"]);
        }
    }


    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * @return AttributeSpec[]
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @return bool
     */
    public function getMultiple()
    {
        return $this->multiple;
    }

    /**
     * @return ElementSpec|null
     */
    public function getParent()
    {
        return $this->parent;
    }
}