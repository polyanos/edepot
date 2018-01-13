<?php
/**
 * Created by PhpStorm.
 * User: Gregor
 * Date: 12-12-2017
 * Time: 17:24
 */

$testDictionary = ["Key1"=>"Value1", "Key2"=>"Value2"];
$testArray = ["AValue1", "AValue2", $testDictionary];

echo "Dictionary has a 0 key: " . var_export(isset($testDictionary[0]), 1) . "<br/>";
echo "Array has a 0 key: " . var_export(isset($testArray[0]), 1) . "<br/>";