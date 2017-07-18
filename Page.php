<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Page
 *
 * @author Xianshun
 */
 require_once("MObj.php");
 require_once("Scriptlet.php");
 require_once("Stylet.php");
 require_once("MySQLHandler.php");
 require_once("DocType.php");
 require_once("MetaData.php");
 require_once("JQueryDataTable.php");
 require_once("GridLayout.php");
 
class Page extends MObj{
    //put your code here
    protected $mScriptlet;
    protected $mStylet;
    private $mDocType;
    private $mMetaData;
    private $mTitle;
    protected $mLayout;
    private $mBody_bgcolor;
    private $mBody_fgcolor;

    public function __construct()
    {
        parent::__construct("Page");
        
        $this->mDocType=new DocType();
        $this->mScriptlet=new Scriptlet();
        $this->mStylet=new Stylet();
        $this->mMetaData=new MetaData();

        $this->mLayout=new GridLayout("DefaultGridLayout", 3, 2);

        $this->mBody_bgcolor='';
        $this->mBody_fgcolor='';
    }

    public function setBGColor($color)
    {
        $this->mBody_bgcolor=$color;
    }

    public function setFGColor($color)
    {
        $this->mBody_fgcolor=$color;
    }
    
    public function setLayout($layout)
    {
        $this->mLayout=$layout;
    }

    public function getLayout()
    {
        return $this->mLayout;
    }

    public function add($obj, $row, $col, $colspan=1, $rowspan=1)
    {
        $original=$this->mLayout->get($row, $col);
        if(isset($original))
        {
            $this->mStylet->remove($original);
            $this->mScriptlet->remove($original);
        }
        $this->mStylet->add($obj);
        $this->mScriptlet->add($obj);
        $this->mLayout->add($obj, $row, $col, $colspan, $rowspan);
    }

    public function setTitle($title)
    {
        $this->mTitle=$title;
    }

    public function render_header()
    {        
		
    }

    public function prerender_header()
    {
        echo '<head>';
        $this->mMetaData->render();
        echo '<title>' . $this->mTitle . '</title>';
        $this->mStylet->render();
        $this->mScriptlet->render();
    }

    public function postrender_header()
    {
        echo '</head>';
    }

    public function render_body()
    {
        if(isset($this->mLayout))
        {
            $this->mLayout->render();
        }
    }

    public function prerender_body()
    {
        $style='';
        if(strcmp($this->mBody_bgcolor, '')!=0)
        {
            $style.='background-color:'.$this->mBody_bgcolor.';';
        }
        if(strcmp($this->mBody_fgcolor, '')!=0)
        {
            $style.='color:'.$this->mBody_fgcolor.';';
        }
        if(strcmp($style, '')!=0)
        {
            echo '<body style="'.$style.'">';
        }
        else
        {
            echo '<body>';
        }
    }

    public function postrender_body()
    {
        echo '</body>';
    }

    public function prerender()
    {
        $this->mDocType->render();
        echo '<html>';
    }

    public function postrender()
    {
        echo '</html>';
    }

    public function render()
    {
        $this->prerender();
        
        $this->prerender_header();
        $this->render_header();
        $this->postrender_header();
        
        $this->prerender_body();
        $this->render_body();
        $this->postrender_body();
        
        $this->postrender();
    }
}
?>
