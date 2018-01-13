<?php
/**
* Created by PhpStorm.
* User: Gregor
* Date: 8-12-2017
* Time: 14:41
*/

$superGroupName = IMS_SuperGroupName();
$userName = SHIELD_CurrentUser($superGroupName);

$form = array();
$form["input"]["gn"] = $superGroupName;
$form["input"]["un"] = $userName;
$form['title'] = 'Export bevestiging';
$form['formtemplate'] = file_get_contents("{$sipRoot}/html/assistant_code_template.html");
$form['precode'] = "include(\"{$sipRoot}/php/assistant_code_pre.php\");";

$result = FORMS_URL($form);