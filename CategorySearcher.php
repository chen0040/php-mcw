<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CategorySearcher
 *
 * @author AB
 */
require_once('JQueryMBMenu.php');
require_once('ArticleViewer.php');
require_once('JQueryPanel.php');
require_once('JQueryChart.php');
require_once('MySQLHandler.php');

class CategorySearcher extends ArticleViewer{
    //put your code here
    protected $mMBMenu;
    protected $mDescPanel;
	protected $mChart;
    protected $mNavLink;
    protected $mTblSrc;
    protected $mCategory;
    protected $mCategoryName;
    
    public function __construct($id, $tblsrc, $category, $category_name)
    {
        parent::__construct($id);

        $this->mCategory=$category;
        $this->mTblSrc=$tblsrc;
        $this->mCategoryName=$category_name;

        $this->mMBMenu=new JQueryMBMenu($id.'_mbmenu', true);
        $this->mMBMenu->setTitle("<b>".$category_name."s:Category</b>");
        $this->mMBMenu->setTitleIcon("codezone/css/images/Automator-icon.png");
        $this->mMBMenu->setTopLevelIcon('codezone/css/images/menu/Desktop-icon.png');
        $this->mMBMenu->setTopLevelSelectedIcon('codezone/css/images/buttons/add.png');
        $apptree=new CDTree();
        $apptree->createMySQLQueryTree("mc".$category."dictionary", "id", "detail", "parentid");
        $this->mMBMenu->setTree($apptree);
        $this->mMBMenu->setQueryName($category."id");
        
        
        $categoryids=$this->mMBMenu->getSelectedMenuItem();
        $ajax_source="json_table.php?id=mcarticles.id&fc=2&f0=mcarticles.title&f1=mcarticles.keywords&tc=2&t0=mcarticles&t1=mc".$category."s&jc=1&j0=mcarticles.id[mc".$category."s.articleid";

        if(isset($categoryids))
        {
            $sQuery='';
            $counter=count($categoryids);
            for($i=0; $i<$counter; ++$i)
            {
                $sQuery.=("&t1p0v".$i."=".$categoryids[$i]);
            }
            $ajax_source="json_table.php?id=mcarticles.id&fc=2&f0=mcarticles.title&f1=mcarticles.keywords&tc=2&t0=mcarticles&t1=mc".$category."s&t1c=1&t1p0=".$category."id&t1p0c=".$counter."".$sQuery."&jc=1&j0=mcarticles.id[mc".$category."s.articleid";
        }     
        $this->setAjaxSource($ajax_source);
        
        $menuCSS=$this->mMBMenu->getCSS();
        $menuScripts=$this->mMBMenu->getScripts();
        
        if(isset($menuCSS))
        {
            foreach($menuCSS as $value)
            {
                $this->addCSS($value);
            }
        }
        if(isset($menuScripts))
        {
            foreach($menuScripts as $value)
            {
                $this->addScript($value);
            }
        }

        $this->createDescPanel($this->mId."_desc", $categoryids[0]);
		
		$this->mChart=new JQueryChart($this->mId."_chart");
		$this->mChart->setTitle('<img src="codezone/css/images/navlink.png" border="0" align="absmiddle" /> Chart');
		$this->mDescPanel->setIcon('codezone/css/images/Automator-icon.png');
    }
    
    private function createDescPanel($id, $categoryid)
    {
        $this->mDescPanel=new JQueryPanel($id);
        //$this->mDescPanel->setHeight(400);

        $handler=new MySQLHandler();
        $handler->connect();
        $handler->select_database();

        $sQuery="SELECT * FROM mc".$this->mCategory."dictionary WHERE id='".$categoryid."'";
        $rResult=mysql_query($sQuery) or die(mysql_error());
        $rRow=mysql_fetch_array($rResult);

        if(isset($rRow))
        {
            $desc=htmlspecialchars_decode($rRow['description']);
        }
        else
        {
            $desc="";
        }
        
        $handler->disconnect();

        $node=$this->mMBMenu->getTree()->getNode($categoryid);
        $this->mNavLink="";
        if(isset($node))
        {
            while($node->hasParent())
            {
                $text=$node->getData();
                if(strcmp($text, '')!=0)
                {
                    if(strcmp($this->mNavLink, '')==0)
                    {
                        $this->mNavLink='<b>'.$text.'</b>';
                    }
                    else
                    {
                        $this->mNavLink='<b>'.$text.'</b> :: '.$this->mNavLink; //<img src="codezone/css/images/navlink.png" border="0" align="absmiddle" />
                    }
                }
                else
                {
                    $this->mNavLink='<b>'.$this->mCategoryName.'</b> <img src="codezone/css/images/navlink.png" border="0" align="absmiddle" /> '.$this->mNavLink;
                }
                $node=$node->getParent();
            }
        }

        if(strcmp($this->mNavLink, "")==0)
        {
            $this->mNavLink="<b>".$this->mCategoryName."s</b>";
        }

        if(strcmp($desc, '')==0)
        {
            $this->mDescPanel->setUrl('category_'.$this->mCategory.'.html');
        }
        else
        {
            $this->mDescPanel->setContent($desc);
        }
        
        $this->mDescPanel->setIcon('codezone/css/images/Automator-icon.png');


        $menuCSS=$this->mDescPanel->getCSS();
        $menuScripts=$this->mDescPanel->getScripts();

        if(isset($menuCSS))
        {
            foreach($menuCSS as $value)
            {
                $this->addCSS($value);
            }
        }
        if(isset($menuScripts))
        {
            foreach($menuScripts as $value)
            {
                $this->addScript($value);
            }
        }
		
		if($this->isEditable())
        {
            $this->setTitle($this->mNavLink." : <b>Articles</b> <a href='mcw_edit_categories.php' target='_blank'><img src='codezone/css/images/buttons/edit.gif' border='0' align='right' style='padding-right:10px' alt='Edit' title='Edit' /> </a>");
        }
        else
        {
            $this->setTitle($this->mNavLink.' : <b>Articles</b>');
        }
    }

    public function render_hidden()
    {
        parent::render_hidden();
        $this->mMBMenu->render_hidden();
    }
    
    public function render_header()
    {
        parent::render_header();
        $this->mMBMenu->render_header();
        $this->mDescPanel->render_header();
	$this->mChart->render_header();
        /*
        echo '
            var win=null;
            function NewWindow(mypage,myname,w,h,pos,infocus)
            {
                if(pos=="center"){
                    myleft=(screen.width)?(screen.width-w)/2:100;mytop=(screen.height)?(screen.height-h)/2:100;
                 }
                else if((pos!="center" && pos!="random") || pos==null)
                {
                    myleft=0;mytop=20
                }
                settings="width=" + w + ",height=" + h + ",top=" + mytop + ",left=" + myleft + ",scrollbars=no,location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=no";
                win=window.open(mypage,myname,settings);
                win.focus();
            }
        ';*/
    }
    
    public function render()
    {
        if($this->isEditable())
        {
            $this->mMBMenu->setTitle("<b>".$this->mCategoryName."s : Category</b><a href='mcw_edit_categories.php?category=".$this->mCategory."' target='_blank'><img src='codezone/css/images/buttons/edit.gif' border='0' align='right' style='padding-right:10px' alt='Edit' title='Edit' /> </a>");


            $categoryids=$this->mMBMenu->getSelectedMenuItem();
            
            if(isset($categoryids))
            {
                $categoryid=$categoryids[0];

                //<a href="javascript:NewWindow('','Edit','640','480','custom','front');">LinkText</a>

                $imgLink="";
                //$imgLink.="<a href=\"javascript:NewWindow('mcw_edit_category.php?category=".$this->mCategory."&category_id=".$categoryid."','Edit','640','480','custom','front');\"><img src='codezone/css/images/buttons/edit.gif' border='0' align='right' style='padding-right:10px' alt='Edit' title='Edit' /> </a>";
                $imgLink.="<a href='mcw_edit_category.php?category=".$this->mCategory."&category_id=".$categoryid."' target='_blank'><img src='codezone/css/images/buttons/edit.gif' border='0' align='right' style='padding-right:10px' alt='Edit' title='Edit' /> </a>";
                $imgLink.="<a href='mcw_latex_category.php?category=".$this->mCategory."&category_id=".$categoryid."' target='_blank'><img src='codezone/css/images/icons/LaTeXlogo.jpg' border='1' align='right' style='padding-right:10px' alt='Latex' title='Latex' /> </a>";
                
                $this->mDescPanel->setTitle($this->mNavLink." : <b>Description</b>".$imgLink);
            }

            $this->setTitle($this->mNavLink." : <b>Articles</b> <a href='mcw_edit_categories.php' target='_blank'><img src='codezone/css/images/buttons/edit.gif' border='0' align='right' style='padding-right:10px' alt='Edit' title='Edit' /> </a>");
        }
        else
        {
            $categoryids=$this->mMBMenu->getSelectedMenuItem();

            if(isset($categoryids))
            {
                $categoryid=$categoryids[0];
                $imgLink='';
                $imgLink.="<a href='mcw_latex_category.php?category=".$this->mCategory."&category_id=".$categoryid."' target='_blank'><img src='codezone/css/images/icons/LaTeXlogo.jpg' border='1' align='right' style='padding-right:10px' alt='Latex' title='Latex' /> </a>";
                $this->mDescPanel->setTitle($this->mNavLink." : <b>Description</b>".$imgLink);
            }
            else
            {
                 $this->mDescPanel->setTitle($this->mNavLink." : <b>Description</b>");
            }
            $this->setTitle($this->mNavLink.' : <b>Articles</b>');
        }
        echo '<table border="0" cellspacing="10px" cellpadding="0" width="100%">'; //bgcolor="#cccccc"
        echo '<tr><td valign="top" rowspan="2" width="200px">';
		echo '<table border="0"><tr><td>';
        $this->mMBMenu->render();
		echo '</td></tr><tr><td>';
		$this->mChart->render();
		echo '</td></tr></table>';
        echo '</td>';
        echo '<td valign="top">';
        $this->mDescPanel->render();
        echo '</td></tr>';
        echo '<tr><td valign="top"><br />';
        parent::render();
        echo '</td></tr>';
        echo '</table>';
    }
}
?>
