<?php
/**
 * Created by PhpStorm.
 * User: Gregor
 * Date: 15-1-2018
 * Time: 17:33
 */

class XmlMapper
{
    private $mappingSpec;
    private $retrievalMethod;
    private $debug = true;

    /**
     * XmlMapper constructor.
     * @param $mappingYamlFile
     * @param callable $retrievalMethod Method needs to return the value based on the given location string.
     */
    public function __construct($mappingYamlFile, callable $retrievalMethod)
    {
        $this->mappingSpec = yaml_parse($mappingYamlFile);
        $this->retrievalMethod = $retrievalMethod;

        if($this->debug){
            echo "<h1>XMLMapper parsed mapping spec start:</h1><pre>".var_export($this->mappingSpec)."</pre><h1>Mapping spec end</h1>";
        }
    }

    public function getMappedValue($group, $name){
        if($this->debug === true){
            return "$group:$name";
        }

        if(isset($this->mappingSpec[$group]) && isset($this->mappingSpec[$group][$name])){
            $method = $this->retrievalMethod;
            return $method($this->mappingSpec[$group][$name]);
        } else {
            return "Value not found";
        }
    }
}