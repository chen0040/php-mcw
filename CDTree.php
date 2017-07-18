<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CDTree
 *
 * @author AB
 */
require_once('CDTreeNode.php');
require_once('MySQLHandler.php');

class CDTree {
    //put your code here
    private $mRoot;
    private $mNodes;
    public function __construct()
    {
        $this->mRoot=null;
    }
    
    public function getRoot()
    {
        return $this->mRoot;
    }

    public function getNode($nodeName)
    {
        if(array_key_exists($nodeName, $this->mNodes))
        {
            return $this->mNodes[$nodeName];
        }
        return null;
    }

    public function createMySQLQueryTree($tableName, $nodeIdTag, $nodeNameTag, $parentNodeIdTag)
    {
        $handler=new MySQLHandler();
        $handler->connect();
        $handler->select_database();

        $sQuery="SELECT DISTINCT ".$nodeIdTag.", ".$nodeNameTag.", ".$parentNodeIdTag." FROM ".$tableName;
        $rResult=mysql_query($sQuery) or die(mysql_error());

        $nodeNames=Array();
        $nodeParentIds=Array();

        while($rRow=mysql_fetch_array($rResult))
        {
            $nodeId=$rRow[$nodeIdTag];
            $nodeNames[$nodeId]=$rRow[$nodeNameTag];
            $nodeParentIds[$nodeId]=$rRow[$parentNodeIdTag];
        }

        $handler->disconnect();

        $this->mNodes=Array();
        foreach($nodeNames as $nodeId => $nodeName)
        {
            $this->mNodes[$nodeId]=new CDTreeNode($nodeId, $nodeName);
            
        }
        
        foreach($this->mNodes as $nodeId => $node)
        {
            $parentNodeId=$nodeParentIds[$nodeId];
            if(isset($this->mNodes[$parentNodeId]))
            {
                $parentNode=$this->mNodes[$parentNodeId];
                $parentNode->addChildNode($node);
            }
        }

        $this->mRoot=new CDTreeNode($tableName, null);
        foreach($this->mNodes as $nodeId => $node)
        {
            if(! $node->hasParent())
            {
                $this->mRoot->addChildNode($node);
            }
        }       
    }

    public function createRootNode($id, $data)
    {
        $this->mRoot=createNode($id, $data);
        return $this->mRoot;
    }

    public function createNode($id, $data)
    {
        return new CDTreeNode($id, $data);
    }

    public function getPath($selectedId)
    {
        $path=Array();
        if(isset($this->mNodes[$selectedId]))
        {
            $node=$this->mNodes[$selectedId];
            $path[]=$node->getData();
            while($node->hasParent())
            {
                $node=$node->getParent();
                if($node->hasParent())
                {
                    $path[]=$node->getData();
                }
            }
        }
        return $path;
    }

    private function node2option($treenode, $level)
    {
        $type='level';
        if($treenode->isLeaf())
        {
            $type='leaf';
        }

        $gap='';
        for($i=0; $i<$level; ++$i)
        {
            $gap.='--';
        }

        $content='<option value="'.$treenode->getName().'">'.$gap.$treenode->getData().'</option>';
        
        $children=$treenode->getChildren();
        if(count($children)>0)
        {
            foreach($children as $nodeName => $node)
            {
                $content.=$this->node2option($node, $level+1);
            }
        }

        return $content;
    }

    function tree2select($rootName)
    {
        $content='';
        $root=$this->getRoot();
        $children=$root->getChildren();
        $content.=('<option value="0">'.$rootName.'</option>');
        foreach($children as $nodeName => $node)
        {
            $content.=$this->node2option($node, 1);
        }
        return $content;
    }
}
?>
