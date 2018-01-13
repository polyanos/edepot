<?php
/**
 * Created by PhpStorm.
 * User: Gregor
 * Date: 21-12-2017
 * Time: 15:02
 */

include_once "ComplexElementSpec.php";

class SpecCreator
{
    public static function createSpecification($jsonSpecification){
        if(!is_string($jsonSpecification)){
            throw new InvalidArgumentException("");
        }
        $decodedSpecification = json_decode($jsonSpecification);

        return new ComplexElementSpec($decodedSpecification["root"], $decodedSpecification);
    }
}