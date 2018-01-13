<?php
/**
 * Created by PhpStorm.
 * User: Gregor
 * Date: 7-12-2017
 * Time: 14:48
 */

class export_confirmation
{
    private $dossiers = array();
    private $filePrefix;

    function __construct($filePrefix)
    {
        $this->filePrefix = $filePrefix;
    }

    function add_dossier($name, $path){
        if(!is_string($name) || !is_string($path)){
            throw new InvalidArgumentException("the name and path needs to be strings");
        }
        $this->dossiers[] = ["name"=>$name, "path"=>$path];
    }

    function getHtml(){
        $templateHtml = file_get_contents($this->filePrefix."/html/export_confirmation_template.html");

        return str_ireplace("{{dossier_data}}", $this->createDossierHtml(), $templateHtml);
    }

    private function createDossierHtml(){
        $body = "";
        foreach($this->dossiers as $dossier){
            $body .= "<li>Naam: {$dossier["name"]}, pad: {$dossier["path"]}</li>";
        }
        $html = "<ul>{$body}</ul>";

        return $html;
    }
}