<?php
/**
 * Created by PhpStorm.
 * User: Gregor
 * Date: 15-1-2018
 * Time: 17:32
 */

class XmlGenerator
{
    private $specification;
    private $mapper;

    /**
     * XmlGenerator constructor.
     * @param $specification
     * @param $mapper
     */
    public function __construct($specification, $mapper)
    {
        $this->specification = $specification;
        $this->mapper = $mapper;
    }

    private function validateMapperSpecification(){

    }
}