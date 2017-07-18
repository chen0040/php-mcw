<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CategoryOrganizer
 *
 * @author Xianshun
 */
require_once("MObj.php");
require_once('ArticleEntryPanel.php');
require_once('CategoryEntryPanel.php');
require_once('JQueryPanel.php');

class CategoryOrganizer extends MObj{
    //put your code here
    protected $mForm;
    public function __construct($id)
    {
        parent::__construct($id);

        if(isset($_GET['category']))
        {
            $action=$_GET['category'];
            if(strcmp($action, 'application')==0)
            {
                $this->mForm=new CategoryEntryPanel($this->mId."ae", "application", "Application");
            }
            else if(strcmp($action, 'design')==0)
            {
                $this->mForm=new CategoryEntryPanel($this->mId."de", "design", "Design");
            }
            else if(strcmp($action, 'memetic')==0)
            {
                $this->mForm=new CategoryEntryPanel($this->mId."me", "memetic", "Memetic");
            }
            else if(strcmp($action, 'author')==0)
            {
                $this->mForm=new CategoryEntryPanel($this->mId."me", "author", "Author");
            }
            else if(strcmp($action, 'journal')==0)
            {
                $this->mForm=new CategoryEntryPanel($this->mId."me", "journal", "Journal");
            }
            else if(strcmp($action, 'definition')==0)
            {
                $this->mForm=new CategoryEntryPanel($this->mId."me", "definition", "Definition");
            }
        }
        else
        {
            $this->mForm=new ArticleEntryPanel($this->mId."ae");
        }

        $css=$this->mForm->getCSS();
        $scripts=$this->mForm->getScripts();

        if(isset($css))
        {
            foreach($css as $value)
            {
                $this->addCSS($value);
            }
        }
        if(isset($scripts))
        {
            foreach($scripts as $value)
            {
                $this->addScript($value);
            }
        }
    }

    public function render_header()
    {
        $this->mForm->render_header();
    }

    public function render_hidden()
    {
        $this->mForm->render_hidden();
    }

    public function render()
    {
        echo '<table border="0" cellspacing="0" cellpadding="0" bgcolor="#cccccc" width="100%">';
        echo '<tr><td valign="top"><br />';
        $this->mForm->render();
        echo '</td></tr>';
        echo '</table>';
    }
}
?>
