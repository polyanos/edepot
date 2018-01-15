<?php
/**
 * Created by PhpStorm.
 * User: Gregor
 * Date: 21-12-2017
 * Time: 12:15
 */

class SimpleXmlElement extends XmlElement
{
    private $value;

    public function __construct($name, XmlElement $parent, $value)
    {
        parent::__construct($name, iElement::simple, $parent);

        if(isset($value)){
            $this->value = $value;
        } else{
            $value = "";
            trigger_error("Created a simple xml element, without a value", E_WARNING);
        }
    }


    public function getXml(DOMNode $parent)
    {
        $element = $parent->ownerDocument->createElement($this->name, $this->value);
        $parent->appendChild($element);

        $this->getAttributeXml($element);
    }

    public function validate(iElementSpec $specification)
    {
        // TODO: Implement validate() method.
    }
}