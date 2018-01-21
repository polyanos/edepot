<?php
/**
 * Created by PhpStorm.
 * User: Gregor
 * Date: 21-12-2017
 * Time: 12:16
 */

interface iElement
{
    const complex = 0;
    const simple = 1;

    public function getName();
    public function getType();

    public function addAttribute($name, $value);
    public function getAttribute($name);

    public function getXml(DOMNode $document);
    public function validate(iElementSpec $specification);
}