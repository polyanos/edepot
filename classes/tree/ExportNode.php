<?php
/**
 * Created by PhpStorm.
 * User: Gregor
 * Date: 14-12-2017
 * Time: 16:46
 */

class ExportNode
{
    const file = 0;
    const directory = 1;

    private $type;
    private $id;
    private $name;

    private $parent;
    private $children;

    private $fileIndex;
    private $directoryIndex;

    public function __construct($type, $id, $name, ExportNode $parent)
    {
        if(!is_integer($type)){
            throw new InvalidArgumentException('$type needs to be an integer. Use the provided constants or directly specify a valid integer');
        }
        if(!is_string($id)){
            throw new InvalidArgumentException('$id needs to be a string.');
        }
        if(!is_string($name)){
            throw new InvalidArgumentException('$name needs to be a string.');
        }

        $this->fileIndex = array();
        $this->directoryIndex = array();

        $this->type = $type;
        $this->id = $id;
        $this->name = $name;
        $this->parent = $parent;

        $this->children = array();

        $parent->addChild($this);
    }

    public static function createDirectoryNode($id, $name, ExportNode $parent){
        return new ExportNode(ExportNode::directory, $id, $name, $parent);
    }

    public static function createFileNode($id, $name, ExportNode $parent){
        return new ExportNode(ExportNode::file, $id, $name, $parent);
    }

    public function isFile(){
        return $this->type == ExportNode::file;
    }

    public function isDirectory(){
        return $this->type == ExportNode::directory;
    }

    public function isRoot(){
        return $this->parent == null;
    }

    public function addChild(ExportNode $child){
        if($this->isDirectory() && isset($child)){
            $this->children[$child->id] = $child;
            $this->addToIndex($child);
            return true;
        }
        return false;
    }

    public function removeChild(ExportNode $child){
        if(isset($child)) {
            return $this->removeChildById($child->id);
        }
        return false;
    }

    public function removeChildById($id){
        if(is_string($id)){
            $this->removeFromIndex($this->children[$id]);
            unset($this->children[$id]);
            return true;
        }
        return false;
    }

    public function getAllChildren(){
        return $this->children;
    }

    public function getFileChildren(){
        $children = array();
        foreach(array_keys($this->fileIndex) as $key){
            $children[$key] = $this->children[$key];
        }
        return $children;
    }

    public function getDirectoryChildren(){
        $children = array();
        foreach(array_keys($this->directoryIndex) as $key){
            $children[$key] = $this->children[$key];
        }
        return $children;
    }

    public function getParent(){
        return $this->parent;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    private function addToIndex(ExportNode $child){
        if($this->isDirectory()){
            $this->directoryIndex[$child->id] = "";
        }else{
            $this->fileIndex[$child->id] = "";
        }
    }

    private function removeFromIndex(ExportNode $child){
        if($this->isDirectory()){
            unset($this->directoryIndex[$child->id]);
        }else{
            unset($this->fileIndex[$child->id]);
        }
    }
}