<?php
/**
 * Created by PhpStorm.
 * User: Gregor
 * Date: 14-12-2017
 * Time: 16:35
 */
include __DIR__."/ExportNode.php";
class ExportNodeIndex
{
    private $directoryIndexArray;
    private $fileIndexArray;
    private $root;

    public function __construct(ExportNode $rootNode)
    {
        if(!isset($rootNode)){
            throw new InvalidArgumentException("The root node cannot be null");
        }

        $this->directoryIndexArray = array();
        $this->root = $rootNode;
    }

    public function getFromIndexById($id){
        if(!is_string($id)){
            throw new InvalidArgumentException('$id needs to be a string.');
        }

        if(isset($this->directoryIndexArray[$id])){
            return $this->directoryIndexArray[$id];
        }elseif(isset($this->fileIndexArray[$id])){
            return $this->fileIndexArray[$id];
        }else{
            return null;
        }
    }

    public function addNodeToIndex(ExportNode $node){
        if(!isset($node)){
            throw new InvalidArgumentException('$node cannot be null');
        }

        $id = $node->getId();
        if($node->isDirectory()){
            $this->directoryIndexArray[$id] = $node;
        }else{
            $this->fileIndexArray[$id] = $node;
        }
    }

    public function removeNodeFromIndex(ExportNode $node){
        if(!isset($node)){
            throw new InvalidArgumentException('$node cannot be null');
        }

        $this->removeFromIndexById($node->getId());
    }

    public function removeFromIndexById($id){
        if(!is_string($id)){
            throw new InvalidArgumentException('$id needs to be a string and cannot be null');
        }
        if(isset($this->directoryIndexArray[$id])) {
            unset($this->directoryIndexArray[$id]);
        }elseif(isset($this->fileIndexArray[$id])) {
            unset($this->fileIndexArray[$id]);
        }
    }

    public function getNodeById($id){
        $indexResult = $this->getFromIndexById($id);
        if(isset($indexResult)){
            return $indexResult;
        }else{
            return $this->searchForNodeById($this->root, $id);
        }
    }

    public function getNodeTree(){
        return $this->root;
    }

    public function searchForNodeById(ExportNode $parent, $id){
        if(!isset($parent)){
            throw new InvalidArgumentException('$parent cannot be null.');
        }
        $result = null;
        foreach($parent->getAllChildren() as $child){
            if($child->getId() === $id){
                $result = $child;
            }else{
                $this->searchForNodeById($child, $id);
            }
        }

        return $result;
    }
}