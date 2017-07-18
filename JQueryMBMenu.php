<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MBMenu
 *
 * @author Xianshun
 */
require_once('MObj.php');
require_once('CDTree.php');

class JQueryMBMenu extends MObj{
    //put your code here
    private $mTree;
    private $mMinMenuItemWidth;
    private $mMaxMenuItemWidth;
    private $mQueryName;
    private $mTopLevelIcon;
    private $mTopLevelSelectedIcon;
    private $mIsVertical;
    private $mTitle;
    private $mImg;
    
    public function __construct($id, $vertical)
    {
        parent::__construct($id);

        $this->addCSS("codezone/css/superfish.css");

        $this->addScript("superfish/js/hoverIntent.js");
		$this->addScript("superfish/js/superfish.js");
        $this->addScript("superfish/js/supersubs.js");

        $this->mMaxMenuItemWidth=27;
        $this->mMinMenuItemWidth=12;

        $this->mQueryName="menuitem";

        $this->mTopLevelIcon='';
        $this->mTopLevelSelectedIcon='';
        
        $this->mIsVertical=$vertical;
        if($vertical)
        {
            $this->addCSS("codezone/css/superfish-vertical.css");
        }

        $this->mTitle='';
        $this->mImg='';
    }

    public function setTitle($title)
    {
        $this->mTitle=$title;
    }

    public function setTitleIcon($ico)
    {
        $this->mImg=$ico;
    }

    public function setTopLevelIcon($icon)
    {
        $this->mTopLevelIcon=$icon;
    }

    public function setTopLevelSelectedIcon($icon)
    {
        $this->mTopLevelSelectedIcon=$icon;
    }

    public function setTree($tree)
    {
        $this->mTree=$tree;
    }

    public function getTree()
    {
        return $this->mTree;
    }

    public function setQueryName($name)
    {
        $this->mQueryName=$name;
    }

    public function getSelectedMenuItem()
    {
        $items=Array();
        if(isset($_GET[$this->mQueryName]))
        {
            $sQueries=$_GET[$this->mQueryName];
            foreach($sQueries as $sQuery)
            {
               $items[]=$sQuery;
            }
            return $items;
        }

        return null;
    }

    public function render_header()
    {
		if(strcmp($this->mTitle, '')!=0)
		{
			echo '$(function(){
				$("#'.$this->mId.'_title").corner("bevel top");
			});';
		}
		
        $content='
        $(function(){
                $("#'.$this->mId.'_menubar").supersubs({
                    minWidth:    '.$this->mMinMenuItemWidth.',
                    maxWidth:    '.$this->mMaxMenuItemWidth.',
                    extraWidth:  1
                }).superfish({
                animation: {height: "show"}
            });
        });
        ';
        echo $content;
    }

    private function render_node($treenode)
    {
        echo '<li>';
        $menuitemtext=$treenode->getData();

        $items=$this->getSelectedMenuItem();
        $selected=false;
        if(isset($items))
        {
            foreach($items as $item)
            {
                if(strcmp($treenode->getname(), $item)==0)
                {
                    $selected=true;
                    break;
                }
                if($treenode->getChildNodeByName($item))
                {
                    $selected=true;
                    break;
                }
            }
        }

        $img='';
        $state='ui-state-default'; //ui-state-default
        if($selected)
        {
            $state='ui-state-active';
        }
        $format=' class="'.$state.' ui-corner-all"';
        
        if($treenode->getParent()->hasParent())
        {
            if($selected)
            {
                //$img='<img src="codezone/css/images/mbmenu/iconDone.png" align="middle" border="0" /> ';
				$img='';
            }
            else
            {
                //$img='<img src="codezone/css/images/mbmenu/unchecked.png" align="middle" border="0" /> ';
				$img='';
            }
        }
        else
        {
            if($selected)
            {
                if(strcmp($this->mTopLevelSelectedIcon, '')!=0)
                {
                    //$img='<img src="'.$this->mTopLevelSelectedIcon.'" align="middle" border="0" /> ';
					$img='';
                }

            }
            else
            {
                if(strcmp($this->mTopLevelIcon, '')!=0)
                {
                    //$img='<img src="'.$this->mTopLevelIcon.'" align="middle" border="0" /> ';
					$img='';
                }
                
            }
        }

        $names=$treenode->getNames();
        $sQuery='';
        $name_count=count($names);
        for($i=0; $i<$name_count; ++$i)
        {
            if($i != 0)
            {
                $sQuery.='&';
            }
            $sQuery.=($this->mQueryName.'[]='.$names[$i]);
        }
        
        echo '<a href="'.basename($_SERVER['PHP_SELF']).'?'.$sQuery.'"'.$format.'>'.$img.$menuitemtext.'</a>';
       
        $children=$treenode->getChildren();
        if(count($children)>0)
        {
            echo '<ul>';
            foreach($children as $nodeName => $node)
            {
                $this->render_node($node);
            }
            echo '</ul>';
        }

        

        echo '</li>';
        
        echo '';
    }

    public function render()
    {
        if(strcmp($this->mTitle, '')!=0)
        {
            $img='';
            if(strcmp($this->mImg, '')!=0)
            {
                $img='<img src="'.$this->mImg.'" border="0" align="absmiddle" /> ';
            }
            echo '<div style="padding-top:5px;padding-bottom:5px;padding-left:5px;background: url(codezone/css/images/top_bar.png);" id="'.$this->mId.'_title">'.$img.$this->mTitle.'</div>'; //#6af
        }
        
        if($this->mIsVertical)
        {
            echo '<ul id="'.$this->mId.'_menubar" class="sf-menu sf-vertical" >';
        }
        else
        {
            echo '<ul id="'.$this->mId.'_menubar" class="sf-menu" >';
        }
        $root=$this->mTree->getRoot();
        $children=$root->getChildren();
        foreach($children as $nodeName => $node)
        {
            $this->render_node($node);
        }
        
        
        $img='';
        $state='ui-state-default';
        $items=$this->getSelectedMenuItem();
        if(!isset($items))
        {
            if(strcmp($this->mTopLevelSelectedIcon, '')!=0)
            {
                //$img='<img src="'.$this->mTopLevelSelectedIcon.'" align="middle" border="0" /> ';
				$img='';
            }
            $state='ui-state-active';
        }
        else
        {
            if(strcmp($this->mTopLevelIcon, '')!=0)
            {
                //$img='<img src="'.$this->mTopLevelIcon.'" align="middle" border="0" /> ';
				$img='';
            }
        }
        $format=' class=" '.$state.' ui-corner-all"';
        echo '<li>';
        echo '<a href="'.basename($_SERVER['PHP_SELF']).'" '.$format.'>'.$img.'All</a>';
        echo '</li>';

        echo '</ul>';
    }

    
}
?>
