<?php
/**
 * Created by PhpStorm.
 * User: Gregor
 * Date: 18-12-2017
 * Time: 17:51
 */

uuse("reports");

$superGroupName = $input["gn"];
$userName = $input["un"];

$objectTable = "ims_{$superGroupName}_objects";
$selectedTable = "local_selected_{$superGroupName}";
$treeTable = "ims_trees";
$treeRecord = $superGroupName."_documents";

$selectedFileList = MB_Load($selectedTable, $userName);
$directoryTree = MB_Load($treeTable, $treeRecord);
$selectedFolders = [];
$selectedDirectoryTree = [];
$filesToBeExported = [];

MB_MultiLoad($objectTable, array_keys($selectedFileList));
foreach($selectedFileList as $selectedFileKey => $d){
    $selectedFile = MB_Load("ims_{$superGroupName}_objects", $selectedFileKey);
    if($selectedFile["objecttype"] ==  "document"){
        $selectedFolders[$selectedFile["directory"]] = "";
    }
}
$data = var_export($selectedFileList, true).var_export($selectedFolders, true);
$formtemplate = str_replace("{{data}}",$data, $formtemplate);