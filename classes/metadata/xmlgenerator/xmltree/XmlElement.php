<?php
/**
 * Created by PhpStorm.
 * User: Gregor
 * Date: 15-1-2018
 * Time: 14:43
 */

abstract class XmlElement implements iElement
{
    protected $name;
    protected $type;
    protected $attributes;

    /**
     * XmlElement constructor.
     * @param string $name
     * @param string $type
     */
    public function __construct($name, $type)
    {
        if(is_string($name) && !empty($name)){
            $this->name = $name;
        } else{
            throw new InvalidArgumentException("Tried to create a element while providing no or a empty name");
        }
        if(is_string($type) && in_array($type, array(iElement::complex, iElement::simple))){
            $this->type = $type;
        } else{
            throw new InvalidArgumentException("Tried to create an element while providing an invalid type");
        }

        $this->attributes = array();
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
     * @param string $name
     * @param mixed $value
     */
    public function addAttribute($name, $value)
    {
        if(is_string($name) && isset($value)) {
            $this->attributes[$name] = (string)$value;
        } else{
            throw new InvalidArgumentException("Tried to add a attribute with an invalid name or value");
        }
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function getAttribute($name)
    {
        if(isset($name) && isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }else{
            return null;
        }
    }

    protected function getAttributeXml(DOMElement $node){
        foreach ($this->attributes as $attributeName => $attributeValue){
            $node->setAttribute($attributeName, $attributeValue);
        }
    }
}