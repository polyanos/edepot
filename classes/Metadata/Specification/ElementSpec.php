<?php
/**
 * Created by PhpStorm.
 * User: Gregor
 * Date: 21-12-2017
 * Time: 13:59
 */

include_once "iElementSpec.php";
include_once "AttributeSpec.php";

class ElementSpec implements iElementSpec
{
    private $name;
    private $mandatory;
    private $type;
    private $attributes;

    /**
     * ElementSpec constructor.
     * @param $specification
     */
    public function __construct($specification)
    {
        if(empty($specification) || !is_array($specification)){
            throw new InvalidArgumentException("");
        }

        $this->name = $specification["naam"];
        $this->mandatory = $specification["verplicht"];
        $this->type = $specification["type"];

        $this->attributes = [];
        if(isset($specification["attributen"])) {
            foreach ($specification["attributen"] as $attributeSpec) {
                $this->attributes = new AttributeSpec($attributeSpec["naam"], $attributeSpec["verplicht"]);
            }
        }
    }


    public function getName()
    {
        return $this->name;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getMandatory()
    {
        return $this->mandatory;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }
}