<?php
/**
 * Created by PhpStorm.
 * User: Gregor
 * Date: 21-12-2017
 * Time: 12:15
 */

class ComplexXmlElement extends XmlElement
{
    private $childs;

    public function __construct($name, XmlElement $parent = null)
    {
        parent::__construct($name, iElement::complex, $parent);

        $this->childs = array();
    }

    public function getXml(DOMNode $xmlParent)
    {
        if(is_null($xmlParent)){

        }
        $element = $xmlParent->ownerDocument->createElement($this->name);
        $xmlParent->appendChild($element);
        $this->getAttributeXml($element);

        foreach($this->childs as $child){
            if(is_array($child)){
                foreach ($child as $item){
                    $item->getXml($element);
                }
                $child->getXml($element);
            }
        }
        return $element;
    }

    public function validate(iElementSpec $specification)
    {
        // TODO: Implement validate() method.
    }

    public function addChild(XmlElement $child){
        $name = $child->getName();
        if(isset($this->childs[$name])){
            if(is_array($this->childs[$name])){
                $this->childs[] = $child;
            } else {
                $childArray = array();
                $childArray[] = $this->childs[$name];
                $childArray[] = $child;
                $this->childs = $childArray;
            }
        } else {
            $this->childs[$name] = $child;
        }
    }
}