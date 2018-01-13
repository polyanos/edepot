<?php
/**
 * Created by PhpStorm.
 * User: Gregor
 * Date: 21-12-2017
 * Time: 12:15
 */

class ComplexElement implements iElement
{
    private $name;
    private $elements;
    private $multiple;
    private $specification;

    /**
     * ComplexElement constructor.
     * @param $name
     * @param $specification
     */
    public function __construct($specification)
    {
        $this->specification = $specification;

        $this->name = strval($specification["name"]);
        $this->mandatory = boolval($specification["mandatory"]);
        $this->multiple = boolval($specification["multiple"]);
        $this->elements = [];
    }

    public function addElement(iElement $element){
        if($this->multiple){
            if($element->getElementType() === iElement::complex && $element->getName() === $this->getName()){
                $this->elements[] = $element;
            }else{
                trigger_error("Error");
            }
        }else {
            if($element->getElementType() === iElement::simple) {
                $this->elements[$element->getName()] = $element;
            }else{
                trigger_error("Error");
            }
        }
    }

    public function validate()
    {
        $validated = true;
        if($this->mandatory){
            if(empty($this->elements)){
                $validated = false;
                trigger_error("Error");
            }
        }



        return $validated;
    }

    private function validateAgainstSpec(){
        foreach($this->specification["elements"] as $elementSpec){
            if(isset($this->elements[$elementSpec["name"]]))
        }
    }

    public function getXml()
    {
        if($this->multiple){
            $this->getChildrenXml();
        } else {
            $xml = "<{$this->name}>";
            $this->getChildrenXml();
            $xml .= "</{$this->name}>";
        }

        return $xml;
    }

    private function getChildrenXml(){
        $xml = "";
        foreach ($this->elements as $element){
            $xml .= $element->getXml();
        }
        return $xml;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getElementType()
    {
        return iElement::complex;
    }

    public function getChildren(){
        return $this->elements;
    }
}