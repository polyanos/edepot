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
    private $multiple;
    private $childSpec;

    public function __construct($specification, $fullSpec)
    {
        parent::__construct($specification);

        $this->multiple = $specification["multiple"];
        $this->childSpec = createChildSpec();
    }

    private function createChildrenSpec($jsonSpec, $fullJsonSpec){
        $children = [];

        foreach($jsonSpec["children"] as $childJsonSpec){
            if($this->isShortcut($childJsonSpec)){
                $childJsonSpec = $fullJsonSpec[$childJsonSpec];
            }
            if(is_array($childJsonSpec)){
                if(isset($childJsonSpec[0])){
                    $children[] = $this->createChoiceSpec($childJsonSpec, $fullJsonSpec);
                } else {
                    $children[] = $this->createChildSpec($childJsonSpec, $fullJsonSpec);
                }
            } else {
                trigger_error("");
            }
        }
    }

    private function isShortcut($val){
        $regEx = "/^\:\:[\w]{4,}$/";
        if(is_string($val) && preg_match($regEx, $val)){
            return true;
        } else {
            return false;
        }
    }

    private function createChoiceSpec($childJsonChoiceSpec, $fullJsonSpec){
        $childChoice = [];
        foreach($childJsonChoiceSpec as $childJsonSpec){
            if($this->isShortcut($childJsonSpec)){
                $childChoice[] = $this->createChildSpec($fullJsonSpec["$childJsonSpec"], $fullJsonSpec);
            }else {
                $childChoice[] = $this->createChildSpec($childJsonSpec, $fullJsonSpec);
            }
        }

        return $childChoice;
    }

    private function createChildSpec($childJsonSpec, $fullJsonSpec){
        switch($childJsonSpec["type"]){
            case iElementSpec::simple:
                $child = new SimpleElementSpec($childJsonSpec);
                break;
            case iElementSpec::complex:
                $child = new ComplexElementSpec($childJsonSpec, $fullJsonSpec);
                break;
            default:
                $child = null;
        }

        return $child;
    }
}