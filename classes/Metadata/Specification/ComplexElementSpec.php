<?php
/**
 * Created by PhpStorm.
 * User: Gregor
 * Date: 21-12-2017
 * Time: 13:59
 */

include_once "SimpleElementSpec.php";
include_once "ElementSpec.php";

class ComplexElementSpec extends ElementSpec
{
    private $children;

    /**
     * ComplexElementSpec constructor.
     * @param string $name
     * @param string $type
     * @param bool $required
     * @param bool $multiple
     * @param ElementSpec $parent
     */
    public function __construct($name, $type, $required = false, $multiple = false, $parent = null)
    {
        parent::__construct($name, $type, $required, $multiple, $parent);

        $this->children = array();
    }

    /**
     * @param ElementSpec $child
     */
    public function addChild(ElementSpec $child){
        if(isset($this->children[$child->getName()])){
            throw new InvalidArgumentException("This element already has a child named ".$child->getName()." and cannot have multiple children of this element");
        } else{
            $this->children[$child->getName()] = $child;
        }
    }

    /**
     * @param ElementSpec[] $children
     */
    public function addChildren($children){
        if(!is_array($children)){
            throw new InvalidArgumentException("Children needs to be an array");
        }

        foreach($children as $child){
            $this->addChild($child);
        }
    }

    public function getChild($name){
        if(isset($this->children[$name])){
            return $this->children[$name];
        } else{
            return null;
        }
    }
}