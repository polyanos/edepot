<?php
/**
 * Created by PhpStorm.
 * User: Gregor
 * Date: 21-12-2017
 * Time: 13:47
 */

include_once "ElementSpec.php";

class SimpleElementSpec extends ElementSpec
{
    private $empty;
    private $valueType;

    private $registeredValues = ["any" => "", "string" => ""];
    /**
     * SimpleElementSpec constructor.
     * @param string $name
     * @param string $type
     * @param string $valueType
     * @param bool $empty
     * @param bool $required
     * @param bool $multiple
     * @param array $attributes
     * @param ElementSpec|null $parent
     */public function __construct($name, $type, $required = false, $multiple = false, $parent = null, $attributes = array(), $empty = true, $valueType = "any")
    {
        parent::__construct($name, $type, $required, $multiple, $attributes, $parent);

        $this->empty = is_bool($empty) ? $empty : true;
        $this->valueType = is_string($valueType) && $this->isRegisteredValueType($valueType) ? $valueType : "any";
    }

    public function allowEmpty(){
        return $this->empty;
    }

    private function isRegisteredValueType($valueType){
        return isset($this->registeredValues[$valueType]);
    }
}