<?php
/**
 * Created by PhpStorm.
 * User: Gregor
 * Date: 21-12-2017
 * Time: 15:14
 */

class AttributeSpec
{
    private $name;
    private $value;
    private $mandatory;

    /**
     * AttributeSpec constructor.
     * @param $name
     * @param $value
     * @param $mandatory
     */
    public function __construct($name, $mandatory)
    {
        $this->name = $name;
        $this->mandatory = $mandatory;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getMandatory()
    {
        return $this->mandatory;
    }
}