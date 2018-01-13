<?php
/**
 * Created by PhpStorm.
 * User: Gregor
 * Date: 21-12-2017
 * Time: 13:47
 */

include_once "ElementSpec.php";

class SimpleElementSpec extends ElementSpec
{
    private $empty;

    /**
     * SimpleElementSpec constructor.
     * @param $specification
     */
    public function __construct($specification)
    {
        parent::__construct($specification);

        $this->empty = $specification["empty"];
    }

    public function allowEmpty(){
        return $this->empty;
    }
}