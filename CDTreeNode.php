<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CDTreeNode
 *
 * @author AB
 */
class CDTreeNode {
    //put your code here
    private $mNodes;
    private $mTaggedNodes;
    private $mName;
    private $mData;
    private $mParentNode;

    public function __construct($id, $data)
    {
        $this->mName=$id;
        $this->mNodes=Array();
        $this->mTaggedNodes=Array();
        $this->mData=$data;
        $this->mParentNode=null;
    }

    public function setParent($parent)
    {
        $this->mParentNode=$parent;
    }

    public function hasParent()
    {
        return isset($this->mParentNode);
    }

    public function getParent()
    {
        return $this->mParentNode;
    }

    public function getData()
    {
        return $this->mData;
    }

    public function setData($data)
    {
        $this->mData=$data;
    }

   

    public function getName()
    {
        return $this->mName;
    }

    public function getNames()
    {
        $names=Array();
        $names[]=$this->getName();
        if(!$this->isLeaf())
        {
            foreach($this->mNodes as $node)
            {
                $names=array_merge((array)$names, (array)$node->getNames());
            }
        }
        return $names;
    }
    
    public function addChildNode($node)
    {
        $node->setParent($this);
        $this->mNodes[count($this->mNodes)]=$node;
        $this->mTaggedNodes[$node->getName()]=$node;
    }

    public function getChildren()
    {
        return $this->mTaggedNodes;
    }

    public function isLeaf()
    {
        return count($this->mNodes)==0;
    }

    public function getChildNodeByIndex($index)
    {
        return $this->mNodes[$index];
    }

    public function getChildNodeByName($name)
    {
        if(isset($this->mTaggedNodes[$name]))
        {
            return $this->mTaggedNodes[$name];
        }
        foreach($this->mNodes as $node)
        {
            $childnode=$node->getChildNodeByName($name);
            if(isset($childnode))
            {
                return $childnode;
            }
        }
        return null;
    }
}
?>
