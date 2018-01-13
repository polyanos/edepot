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

    public function validate();
    public function getXml();
    public function getName();
    public function getElementType();
}