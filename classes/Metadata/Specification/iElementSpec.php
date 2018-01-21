<?php
/**
 * Created by PhpStorm.
 * User: Gregor
 * Date: 21-12-2017
 * Time: 13:42
 */

interface iElementSpec
{
    const complex = "complex";
    const simple = "simple";

    public function getName();
    public function getType();
    public function getRequired();
    public function getMultiple();
    public function getAttributes();
}