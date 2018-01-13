<?php
include(__DIR__ . "/MetadataLogItem.php");

header('Content-type: text/xml');

/**
 * Created by PhpStorm.
 * User: Gregor
 * Date: 20-11-2017
 * Time: 12:39
 */

//Test code
$metadata = [
    "identificatiekenmerk" => uniqid(),
    "aggregatieniveau" => "Record",
    "naam" => ["TestFile", "TestNaam"],
    "vorm" => [
        "redactieGenre" => "Test genre"
    ],
    "formaat" => [
        "identificatiekenmerk" => uniqid(),
        "bestandsnaam" => [
            "naam" => "TestFile",
            "extensie" => ".doc"
        ],
    ],
];
error_reporting(E_ALL);
$tmc = new ToPXMetadataCreator(true, $metadata, true);
$tmc->createMetadataDocument();
echo $tmc->getXmlDocument()->saveXML();

error_reporting(E_STRICT);



class ToPXMetadataCreator
{
    private $xml;
    private $is_file;
    private $metadata;
    private $success;
    private $errorList;
    private $debug;

    public function __construct($is_file, $metadata, $debug = false)
    {
        $this->xml = new DOMDocument();
        $this->is_file = $is_file;
        $this->metadata = $metadata;
        $this->errorList = array();
        $this->success = false;
        $this->debug = $debug;
    }

    /**
     *
     */
    public function createMetadataDocument()
    {
        $this->success = true;
        $ToPXDocumentRoot = $this->xml->createElementNS("http://www.nationaalarchief.nl/ToPX/v2.3", "ToPX");
        $this->xml->appendChild($ToPXDocumentRoot);

        $ToPXMetadataRoot = null;
        if ($this->is_file) {
            $ToPXMetadataRoot = $this->createEmptyElement($ToPXDocumentRoot, "aggregatie");
        } else {
            $ToPXMetadataRoot = $this->createEmptyElement($ToPXDocumentRoot, "bestand");
        }
        $ToPXDocumentRoot->appendChild($ToPXMetadataRoot);

        $this->createSimpleElement($ToPXMetadataRoot, "identificatiekenmerk", $this->metadata, false);
        $this->createSimpleElement($ToPXMetadataRoot, "aggregatieniveau", $this->metadata, false);
        $this->createSimpleElement($ToPXMetadataRoot, "naam", $this->metadata, false);

        $this->createClassificationElement($ToPXMetadataRoot, $this->metadata, true, true);
        $this->createSimpleElement($ToPXMetadataRoot, "omschrijving", $this->metadata, true);
        $this->createSimpleElement($ToPXMetadataRoot, "plaats", $this->metadata, true);

        $this->createCoverageElement($ToPXMetadataRoot, $this->metadata, true, true);
        $this->createExternalIdElement($ToPXMetadataRoot, $this->metadata, true, true);
        $this->createSimpleElement($ToPXMetadataRoot, "taal", $this->metadata, true);
        $this->createEventHistoryElement($ToPXMetadataRoot, $this->metadata, true, true);
        $this->createEventPlanElement($ToPXMetadataRoot, $this->metadata, true, true);
        $this->createRelationElement($ToPXMetadataRoot, $this->metadata, true, true);
        $this->createContextElement($ToPXMetadataRoot, $this->metadata, true, true);
        $this->createUserRightsElement($ToPXMetadataRoot, $this->metadata, true, true);
        $this->createTrustElement($ToPXMetadataRoot, $this->metadata, true, true);
        $this->createPublicityElement($ToPXMetadataRoot, $this->metadata, true, true);
        if ($this->is_file) {
            $this->createFormElement($ToPXMetadataRoot, $this->metadata, true, true);
        }
        $this->createSimpleElement($ToPXMetadataRoot, "integriteit", $this->metadata, true);
        if ($this->is_file) {
            $this->createFormatElement($ToPXMetadataRoot, $this->metadata, false, true);
        }
        $this->createGenericElement($ToPXMetadataRoot, $this->metadata, true, true);
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->success;
    }

    /**
     * @return array
     */
    public function getErrorList()
    {
        return $this->errorList;
    }

    /**
     * @return DOMDocument
     */
    public function getXmlDocument()
    {
        return $this->xml;
    }

    //Specific element creation functions
    private function createClassificationElement(DOMElement $parent, $metadata, $optional, $multiple)
    {
        $creationFunction = function($parent, $classificationMetadata) {
            $this->createSimpleElement($parent, "code", $classificationMetadata["code"], false);
            $this->createSimpleElement($parent, "omschrijving", $classificationMetadata["omschrijving"],false);
            $this->createSimpleElement($parent, "bron", $classificationMetadata["bron"],false);

            if (isset($classificationMetadata["datumOfPeriode"])) {
                $dateChild = $this->createEmptyElement($parent, "datum");
                $this->createDatePeriodElement($dateChild, $classificationMetadata["datum"]);
            }
        };

        return $this->createComplexElement($parent, $creationFunction, "classificatie", $metadata, $optional, $multiple);
    }

    private function createCoverageElement(DOMElement $parent, $metadata, $optional, $multiple)
    {
        $fillingFunction = function ($parent, $data) {
            if (isset($coverageMetadata["periode"])) {
                $periodElement = $this->createEmptyElement($parent, "inTijd");
                $this->createPeriodElement($periodElement, $data["periode"]);
            }
            $this->createSimpleElement($parent, "gebied", $data, true);
        };

        return $this->createComplexElement($parent, $fillingFunction, "dekking", $metadata, $optional, $multiple);
    }

    private function createExternalIdElement(DOMElement $parent, $metadata, $optional, $multiple){
        $fillingFunction = function($parent, $data){
            $this->createSimpleElement($parent, "kenmerkSysteem", $data,true);
            $this->createSimpleElement($parent, "nummerBinnenSysteem", $data, false);
        };

        return $this->createComplexElement($parent, $fillingFunction, "externIdentificatiekenmerk", $metadata, $optional, $multiple);
    }

    private function createEventHistoryElement(DOMElement $parent, $metadata, $optional, $multiple){
        $fillingFunction = function($parent, $data){
            $this->createDatePeriodElement($parent, $data["datumOfPeriode"]);
            $this->createSimpleElement($parent, "type", $data, false);
            $this->createSimpleElement($parent, "beschrijving", $data, true);
            $this->createSimpleElement($parent,"verantwoordelijkeFunctionaris", $data, false);
        };

        return $this->createComplexElement($parent, $fillingFunction, "eventGeschiedenis", $metadata, $optional, $multiple);
    }

    private function createEventPlanElement(DOMElement $parent, $metadata, $optional, $multiple){
        $fillingFunction = function($parent, $data){
            $this->createDatePeriodElement($parent, $data["datumOfPeriode"]);
            $this->createSimpleElement($parent, "type", $data, false);
            $this->createSimpleElement($parent, "beschrijving", $data, true);
            $this->createSimpleElement($parent, "aanleiding", $data, true);
        };

        return $this->createComplexElement($parent, $fillingFunction, "eventPlan", $metadata, $optional, $multiple);
    }

    private function createRelationElement(DOMElement $parent, $metadata, $optional, $multiple){
        $fillingFunction = function($parent, $data){
            $this->createSimpleElement($parent, "relatieID", $data["id"], false);
            $this->createSimpleElement($parent, "typeRelatie", $data["type"], false);
            if(isset($relationMetadata["datum"])){
                $dateElement = $this->createEmptyElement($parent, "datumOfPeriode");
                $this->createDatePeriodElement($dateElement, $data["datum"]);
            }
        };

        return $this->createComplexElement($parent, $fillingFunction, "relatie", $metadata, $optional, $multiple);
    }

    private function createContextElement(DOMElement $parent, $metadata, $optional, $multiple){
        $fillingFunction = function($parent, $data){
            $this->createActorElement($parent, $data, true, true);
            $this->createActivityElement($parent, $data["activiteit"], true, true);
        };

        return $this->createComplexElement($parent, $fillingFunction, "context", $metadata, $optional, $multiple);
    }

    private function createActorElement(DOMElement $parent, $metadata, $optional, $multiple){
        $fillingFunction = function($parent, $data){
            $this->createSimpleElement($parent, "identificatiekenmerk", $data, false);
            $this->createSimpleElement($parent, "aggregatieniveau", $data,true);
            $this->createSimpleElement($parent, "geautoriseerdeNaam", $data, false);
            $this->createSimpleElement($parent, "plaats", $data, true);
            $this->createSimpleElement($parent, "jurisdictie", $data, true);
        };

        return $this->createComplexElement($parent, $fillingFunction, "actor", $metadata, $optional, $multiple);
    }

    private function createActivityElement(DOMElement $parent, $metadata, $optional, $multiple){
        $fillingFunction = function($parent, $data){
            $this->createSimpleElement($parent, "identificatiekenmerk", $data, true);
            $this->createSimpleElement($parent, "aggregatieniveau", $data, true);
            $this->createSimpleElement($parent, "naam", $data, false);
        };

        return $this->createComplexElement($parent, $fillingFunction, "activiteit", $metadata, $optional, $multiple);
    }

    private function createUserRightsElement(DOMElement $parent, $metadata, $optional, $multiple){
        $fillingFunction = function($parent, $data){
            $this->createSimpleElement($parent, "omschrijvingVoorwaarden", $data, false);
            $dateElement = $this->createEmptyElement($parent, "datumOfPeriode");
            $this->createDatePeriodElement($dateElement, $data["datumOfPeriode"]);
        };
        return $this->createComplexElement($parent, $fillingFunction, "gebruiksrechten", $metadata, $optional, $multiple);
    }

    private function createTrustElement(DOMElement $parent, $metadata, $optional, $multiple){
        $fillingFunction = function($parent, $data){
            $this->createSimpleElement($parent, "classificatieNiveau", $data, false);
            $dateElement = $this->createEmptyElement($parent, "datumOfPeriode");
            $this->createDatePeriodElement($dateElement, $data["datumOfPeriode"]);
        };

        return $this->createComplexElement($parent, $fillingFunction, "vertrouwelijkheid", $metadata, $optional, $multiple);
    }

    private function createPublicityElement(DOMElement $parent, $metadata, $optional, $multiple){
        $fillingFunction = function($parent, $data){
            $this->createSimpleElement($parent, "omschrijvingBeperkingen", $data, false);
            $dateElement = $this->createEmptyElement($parent, "datumOfPeriode");
            $this->createDatePeriodElement($dateElement, $data["datumOfPeriode"]);
        };

        return $this->createComplexElement($parent, $fillingFunction, "openbaarheid", $metadata, $optional, $multiple);
    }

    private function createFormElement(DOMElement $parent, $metadata, $optional, $multiple){
        $fillingFunction = function($parent, $data){
            $this->createSimpleElement($parent, "redactieGenre", $data, false);
            $this->createSimpleElement($parent, "verschijningsvorm", $data, true);
            $this->createSimpleElement($parent, "structuur", $data, true);
        };

        return $this->createComplexElement($parent, $fillingFunction, "vorm", $metadata, $optional, $multiple);
    }

    private function createFormatElement(DOMElement $parent, $metadata, $optional, $multiple){
        $fillingFunction = function($parent, $data){
            $this->createSimpleElement($parent, "identificatiekenmerk", $data, false);
            $this->createFilenameElement($parent, $data["bestandsnaam"], false, false);
            $this->createSimpleElement($parent, "type", $data, true);
            $this->createSimpleElement($parent, "omvang", $data, true);
            $this->createSimpleElement($parent, "bestandsformaat", $data, true);
            $this->createCreationAppElement($parent, $data, true, false);
            $this->createPhysicalIntegrityElement($parent, $data, true, false);
            $this->createSimpleElement($parent,"datumAanmaak", $data, true);
            $this->createFormatEventplanElement($parent, $data, true, true);
            $this->createSimpleElement($parent, "relatie", $data, true);
        };

        return $this->createComplexElement($parent, $fillingFunction, "formaat", $metadata, $optional, $multiple);
    }

    private function createFilenameElement(DOMElement $parent, $metadata, $optional, $multiple){
        $fillingFunction = function($parent, $data) {
            $this->createSimpleElement($parent, "naam", $data, false);
            $this->createSimpleElement($parent, "extensie", $data, false);
        };

        return $this->createComplexElement($parent, $fillingFunction, "bestandsnaam", $metadata, $optional, $multiple);
    }

    private function createCreationAppElement(DOMElement $parent, $metadata, $optional, $multiple){
        $fillingFunction = function($parent, $data) {
            $this->createSimpleElement($parent, "naam", $data, true);
            $this->createSimpleElement($parent, "versie", $data, true);
            $this->createSimpleElement($parent, "datumAanmaak", $data, true);
        };

        return $this->createComplexElement($parent, $fillingFunction, "creatieApplicatie", $metadata, $optional, $multiple);
    }

    private function createPhysicalIntegrityElement(DOMElement $parent, $metadata, $optional, $multiple){
        $fillingFunction = function($parent, $data) {
            $this->createSimpleElement($parent, "algoritme", $data, false);
            $this->createSimpleElement($parent, "waarde", $data, false);
            $this->createSimpleElement($parent, "datumEnTijd", $data, false);
        };

        return $this->createComplexElement($parent, $fillingFunction, "fysiekeIntegriteit", $metadata, $optional, $multiple);
    }

    private function createFormatEventplanElement(DOMElement $parent, $metadata, $optional, $multiple){
        $fillingFunction = function($parent, $data){
            $this->createDatePeriodElement($parent, $data["datumOfPeriode"]);
            $this->createSimpleElement($parent, "type", $data, false);
            $this->createSimpleElement($parent, "beschrijving", $data, true);
            $this->createSimpleElement($parent, "aanleiding", $data, true);
        };

        return $this->createComplexElement($parent, $fillingFunction, "formaatEventPlan", $metadata, $optional, $multiple);
    }

    private function createGenericElement(DOMElement $parent, $metadata, $optional, $multiple){
        $fillingFunction = function($parent, $data){
            foreach(array_keys($data) as $key){
                $this->createSimpleElement($parent, $key, $data, false);
            }
        };

        return $this->createComplexElement($parent, $fillingFunction, "generiekeMetadata", $metadata, $optional, $multiple);
    }

    //Reusable element creation functions
    private function createDatePeriodElement(DOMElement $parent, $datumMetadata){
        $child = null;
        switch($datumMetadata){
            case is_array($datumMetadata):
                $child = $this->createEmptyElement($parent, "periode");
                $this->createPeriodElement($child, $datumMetadata);
                break;
            case preg_match('^\d{4}$', trim($datumMetadata)):
                $child = $this->createSimpleElement($parent, "jaar", $datumMetadata, false);
                break;
            case preg_match('^\d{2}-\d{2}-\d{4}$', trim($datumMetadata)):
                $child = $this->createSimpleElement($parent, "datum", $datumMetadata, false);
                break;
            case preg_match('^\d{2}-\d{2}-\d{4}T\d{2}:\d{2}:\d{2}$', trim($datumMetadata)):
                $child = $this->createSimpleElement($parent, "datumEnTijd", $datumMetadata, false);
                break;
        }
        $parent->appendChild($child);
        return $child;
    }

    private function createComplexElement(DOMElement $parent, callable $fillingFunction, $elementName, $metadata, $optional, $multiple = true, $metadataName = null){
        $metadataName = isset($metadataName) ? $metadataName : $elementName;
        if(!isset($metadata[$metadataName])){
            return null;
        }
        $metadataSubset = $metadata[$metadataName];
        $child = null;

        if(isset($metadataSubset)){
            if(isset($metadataSubset[0])){
                if($multiple){
                    foreach($metadataSubset as $subset){
                        $child = $this->createEmptyElement($parent, $elementName);
                        $fillingFunction($child, $subset);
                    }
                }else{
                    $this->log(new MetadataLogItem(MetadataLogItem::error, $elementName, "There was a error in the supplied data, multiple entries for the element {$elementName} ware given but only one is allowed."));
                    $child = $this->createEmptyElement($parent, $elementName);
                    $fillingFunction($child, $metadataSubset[0]);
                }
            } else{
                $child = $this->createEmptyElement($parent, $elementName);
                $fillingFunction($child, $metadataSubset);
            }
        }else{
            if($optional){
                $child = null;
            } else {
                $this->log(new MetadataLogItem(MetadataLogItem::error, $elementName, "There was a error in the supplied data, a null value was passed for the element {$elementName}."));
                $child = $this->createEmptyElement($parent, $elementName);
            }
        }

        return $child;
    }

    private function createSimpleElement(DOMElement $parent, $elementName, $metadata,  $optional, $metadataName = null){
        $metadataName = isset($metadataName) ? $metadataName : $elementName;

        if(isset($metadata[$metadataName])){
            $value = $metadata[$metadataName];
            $child = $this->createElementWithValue($parent, $elementName, $value);
        } else{
            if($optional){
                $child = null;
            } else{
                $child = $this->createEmptyElement($parent, $elementName);
                $this->log(new MetadataLogItem(MetadataLogItem::error, $elementName, "Tried to create the mandatory element {$elementName} with a null value."));
            }
        }
        return $child;
    }

    private function createElementWithValue(DOMElement $parent, $elementName, $metadataValue){
        $child = null;
        if(is_array($metadataValue)){
            if(isset($metadataValue[0])) {
                foreach ($metadataValue as $value) {
                    $child = $this->createElementWithValue($parent, $elementName, $value);
                }
            }else{
                $this->log(new MetadataLogItem(MetadataLogItem::error, $elementName, "Tried to create the element {$elementName} with non numeric array."));
            }
        }elseif(is_object($metadataValue)){
            $child = $this->createEmptyElement($parent, $elementName);
            $parent->appendChild($child);
            $this->log(new MetadataLogItem(MetadataLogItem::error, $elementName, "Tried to create the element {$elementName} with a object value instead of a scalar value."));
        }else{
            $child = $this->xml->createElement($elementName, $metadataValue);
            $parent->appendChild($child);
        }
        return $child;
    }

    private function createEmptyElement(DOMElement $parent, $name){
        $child = $this->xml->createElement($name);
        $parent->appendChild($child);
        return $child;
    }

    private function log(MetadataLogItem $errorData){
        $this->success = false;
        $this->errorList[] = $errorData;
    }

    private function createPeriodElement(DOMElement $parent, $periodeMetadata){
        $beginChild = $this->createEmptyElement($parent, "begin");
        $this->createDatePeriodElement($beginChild, $periodeMetadata["begin"]);
        $endChild = $this->createEmptyElement($parent, "eind");
        $this->createDatePeriodElement($endChild, $periodeMetadata["eind"]);
    }
}