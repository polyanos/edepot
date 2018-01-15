<?php
/**
 * Created by PhpStorm.
 * User: Gregor
 * Date: 21-12-2017
 * Time: 15:34
 */

include_once (dirname(__FILE__)."\SpecCreator.php");
$file = file_get_contents(dirname(__FILE__)."\specfiles\ToPX_File_Spec.yaml");
$sc = new SpecCreator($file);

echo "<pre>".var_export($sc->getSpecification(), true)."</pre>";
