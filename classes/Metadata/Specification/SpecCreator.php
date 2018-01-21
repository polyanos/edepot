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
include_once "iElementSpec.php";

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

        $nameData = array();
        $this->checkDuplicateElementNames($rootName, $parsedSpec[$rootName], $nameData, "root");

        $this->debugMessage(print_r($nameData, true));
        if(isset($nameData["duplicates"])){
            $this->debugMessage("There are naming conflicts in the spec file, these need to be resolved before parsing can continue. Check the spec file and resolve the following conflicts:");
            foreach($nameData["duplicates"] as $duplicate){
                if($duplicate["type"] = "mapping") {
                    $this->debugMessage("Duplicate name detected in mappinggroup {$duplicate["group"]}, the mapping name {$duplicate["name"]} already exists as a element or mapping name");
                } else{
                    $this->debugMessage("Duplicate name detected in mappinggroup {$duplicate["group"]}, the element name {$duplicate["name"]} already exists as a element or mapping name");
                }
            }
            throw new LogicException("Duplicate names detected in spec file, see previous messages.");
        }

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
                    $parent,
                    $this->getSpecValue($specData, "attributes", array()),
                    $this->getSpecValue($specData, "groupName", null)
                );

                if(isset($specData["children"]) && is_array($specData["children"])){
                    foreach($specData["children"] as $childName => $childData){
                        $child = $this->constructElement($element, $childName, $childData);
                        $element->addChild($child);
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

    private function checkDuplicateElementNames($elementName, $elementData, &$nameData, $currentGroup){
        $this->debugMessage("Checking element '$elementName' in group '$currentGroup'");
        if(!isset($nameData[$currentGroup])){
            $nameData[$currentGroup] = array();
        }

        $mappingName = isset($elementData["mappingname"]) ? [$elementData["mappingname"], "mapping"] : [$elementName, "name"];
        if(isset($nameData[$currentGroup][$mappingName[0]])){
            $nameData["duplicates"][] = ["group" => $currentGroup, "name" => $mappingName[0], "type" => $mappingName[1]];
        } else{
            $nameData[$currentGroup][$mappingName[0]] = "";
        }

        if($elementData["type"] === iElementSpec::complex){
            if(isset($elementData["mappinggroup"])){
                $currentGroup = isset($elementData["mappinggroupname"]) ? $elementData["mappinggroupname"] : $elementName;
            }
            if(isset($elementData["children"])){
                foreach($elementData["children"] as $elementName => $elementData){
                    $this->checkDuplicateElementNames($elementName, $elementData, $nameData, $currentGroup);
                }
            }
        }
    }

    public function getSpecification(){
        return $this->specRoot;
    }
}