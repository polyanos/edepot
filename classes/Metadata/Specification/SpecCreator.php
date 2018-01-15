<?php
/**
 * Created by PhpStorm.
 * User: Gregor
 * Date: 21-12-2017
 * Time: 15:02
 */

include_once "SimpleElementSpec.php";
include_once "ComplexElementSpec.php";
include_once "ElementSpec.php";

class SpecCreator
{
    private $specRoot;
    private $debug = true;

    public function __construct($yamlSpec){
        $parsedSpec = yaml_parse($yamlSpec);

        $this->specRoot = $this->createSpecification($parsedSpec);
    }

    private function createSpecification($parsedSpec){
        if(count($parsedSpec) > 1){
            throw new InvalidArgumentException("The root level of the array cannot contain more than 1 element.");
        }

        $rootName = array_keys($parsedSpec)[0];
        $root = $this->constructElement(null, $rootName, $parsedSpec[$rootName]);
        return $root;
    }

    private function constructElement($parent, $name, $specData){
        switch($specData["type"]){
            case iElementSpec::complex:
                $element = new ComplexElementSpec(
                    $name,
                    $this->getManSpecValue($specData, "type"),
                    $this->getSpecValue($specData, "required", false),
                    $this->getSpecValue($specData, "multiple", false),
                    $parent
                );

                if(isset($specData["children"]) && is_array($specData["children"])){
                    foreach($specData["children"] as $childName => $childData){
                        $this->constructElement($element, $childName, $childData);
                    }
                }
                break;
            case iElementSpec::simple:
                $element = new SimpleElementSpec(
                    $name,
                    $this->getManSpecValue($specData, "type"),
                    $this->getSpecValue($specData, "required", false),
                    $this->getSpecValue($specData, "multiple", false),
                    $parent,
                    $this->getSpecValue($specData, "attributes", array()),
                    $this->getSpecValue($specData, "empty", true),
                    $this->getSpecValue($specData, "valueType", "any")
                    );
                break;
            default:
                throw new InvalidArgumentException("Invalid value for the element type. Provided data is: ".var_export($specData, true));
        }
        return $element;
    }

    private function getSpecValue($array, $name, $default){
        $value = $default;
        if(isset($array[$name])){
            $value = $array[$name];
        }
        return $value;
    }

    private function getManSpecValue($array, $name){
        $value = $this->getSpecValue($array, $name, null);
        if($value === null){
            throw new LogicException("A mandatory value was not provided, check your spec file");
        }
        return $value;
    }

    private function debugMessage($message){
        if($this->debug){
            echo "<pre>".$message."</pre>";
        }
    }

    public function getSpecification(){
        return $this->specRoot;
    }
}

$file = file_get_contents(dirname(__FILE__)."\specfiles\ToPX_File_Spec.yaml");
$sc = new SpecCreator($file);

echo "<pre>".print_r($sc->getSpecification(), true)."</pre>";