<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AdministratorPanel
 *
 * @author Xianshun
 */
require_once("MObj.php");
require_once('JQueryMenuBar.php');
require_once('ArticleEntryPanel.php');
require_once('CategoryEntryPanel.php');
require_once('JQueryPanel.php');
require_once('GUIDesignPanel.php');
require_once('AccountList.php');
require_once('PublisherPanel.php');

class AdministratorPanel extends MObj{
    //put your code here
    protected $mTopMenuBar;
    protected $mForm;
    protected $mDescPanel;
    public function __construct($id)
    {
        parent::__construct($id);

        $this->mTopMenuBar=new JQueryMenuBar($id."_menubar", false);
        $this->mTopMenuBar->setTitle("<b>Commands</b>");
        $this->mTopMenuBar->setTitleIcon("codezone/css/images/Automator-icon.png");
        $this->mTopMenuBar->addMenuItem($id."_mnuArticleEntry", "Article Entry", basename($_SERVER['PHP_SELF']).'?action=articleentry', ""); //"codezone/css/images/menu/Admin-tools-icon.png");
        $this->mTopMenuBar->addMenuItem($id."_mnuApplicationEntry", "Application", basename($_SERVER['PHP_SELF']).'?action=applicationentry', ""); //"codezone/css/images/menu/Admin-tools-icon.png");
        $this->mTopMenuBar->addMenuItem($id."_mnuDesignEntry", "Design", basename($_SERVER['PHP_SELF']).'?action=designentry', ""); //"codezone/css/images/menu/Admin-tools-icon.png");
        $this->mTopMenuBar->addMenuItem($id."_mnuMemeticEntry", "Memetic", basename($_SERVER['PHP_SELF']).'?action=memeticentry', ""); //"codezone/css/images/menu/Admin-tools-icon.png");
        $this->mTopMenuBar->addMenuItem($id."_mnuAuthorEntry", "Author", basename($_SERVER['PHP_SELF']).'?action=authorentry', ""); //"codezone/css/images/menu/Admin-tools-icon.png");
        $this->mTopMenuBar->addMenuItem($id."_mnuJournalEntry", "Journal", basename($_SERVER['PHP_SELF']).'?action=journalentry', ""); //"codezone/css/images/menu/Admin-tools-icon.png");
        $this->mTopMenuBar->addMenuItem($id."_mnuDefinitionEntry", "Definition", basename($_SERVER['PHP_SELF']).'?action=definitionentry', ""); //"codezone/css/images/menu/Admin-tools-icon.png");
        $this->mTopMenuBar->addMenuItem($id."_mnuAccountEntry", "Account Entry", basename($_SERVER['PHP_SELF']).'?action=accountentry', ""); //"codezone/css/images/menu/Admin-tools-icon.png");
        $this->mTopMenuBar->addMenuItem($id."_mnuGUISettings", "GUI Settings", basename($_SERVER['PHP_SELF']).'?action=gui', ""); //"codezone/css/images/menu/Admin-tools-icon.png");

        if(isset($_GET['action']))
        {
            $action=$_GET['action'];
            if(strcmp($action, 'articleentry')==0)
            {
                $this->mForm=new ArticleEntryPanel($this->mId."ae");
                $this->mTopMenuBar->highlight('Article Entry');
                $this->createDescPanel($this->mId."_desc", 'Article', 'introduction.html');
            }
            else if(strcmp($action, 'applicationentry')==0)
            {
                $this->mForm=new CategoryEntryPanel($this->mId."ae", "application", "Application");
                $this->mTopMenuBar->highlight('Category: Application');
                $this->createDescPanel($this->mId."_desc", 'Category:Application', 'category_application.html');
            }
            else if(strcmp($action, 'designentry')==0)
            {
                $this->mForm=new CategoryEntryPanel($this->mId."de", "design", "Design");
                $this->mTopMenuBar->highlight('Category: Design');
                $this->createDescPanel($this->mId."_desc", 'Category:Design Issue', 'category_design.html');
            }
            else if(strcmp($action, 'memeticentry')==0)
            {
                $this->mForm=new CategoryEntryPanel($this->mId."me", "memetic", "Memetic");
                $this->mTopMenuBar->highlight('Category: Memetic');
                $this->createDescPanel($this->mId."_desc", 'Category:Memetic Issue', 'category_memetic.html');
            }
            else if(strcmp($action, 'authorentry')==0)
            {
                $this->mForm=new CategoryEntryPanel($this->mId."me", "author", "Author");
                $this->mTopMenuBar->highlight('Category: Author');
                $this->createDescPanel($this->mId."_desc", 'Category:Author', 'category_author.html');
            }
            else if(strcmp($action, 'journalentry')==0)
            {
                $this->mForm=new CategoryEntryPanel($this->mId."me", "journal", "Journal");
                $this->mTopMenuBar->highlight('Category: Journal');
                $this->createDescPanel($this->mId."_desc", 'Category:Journal', 'category_journal.html');
            }
            else if(strcmp($action, 'definitionentry')==0)
            {
                $this->mForm=new CategoryEntryPanel($this->mId."me", "definition", "Definition");
                $this->mTopMenuBar->highlight('Category: Definition');
                $this->createDescPanel($this->mId."_desc", 'Category:Definition', 'category_definition.html');
            }
            else if(strcmp($action, 'gui')==0)
            {
                $this->mForm=new GUIDesignPanel($this->mId."gui");
                $this->mTopMenuBar->highlight('GUI Settings');
                $this->createDescPanel($this->mId."_desc", 'GUI', 'gui_settings.html');
            }
            else if(strcmp($action, 'accountentry')==0)
            {
                $this->mForm=new AccountList($this->mId."ae");
                $this->mTopMenuBar->highlight('Account Entry');
                $this->createDescPanel($this->mId."_desc", 'Article', 'introduction.html');
            }
        }
        else
        {
            $this->mForm=new ArticleEntryPanel($this->mId."ae");
            $this->mTopMenuBar->highlight('Article Entry');
            $this->createDescPanel($this->mId."_desc", 'Article', 'introduction.html');
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

    private function createDescPanel($id, $title, $url)
    {
        $this->mDescPanel=new JQueryPanel($id);

        $this->mDescPanel->setTitle("Administrator:<b>".$title."</b>");
        $this->mDescPanel->setContent('');
        $this->mDescPanel->setIcon('codezone/css/images/Automator-icon.png');
        $this->mDescPanel->setUrl($url);
        $this->mDescPanel->setHeight(300);


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
    }

    public function render_header()
    {
        $this->mTopMenuBar->render_header();
        $this->mForm->render_header();
    }

    public function render_hidden()
    {
        $this->mTopMenuBar->render_hidden();
        $this->mForm->render_hidden();
    }

    public function render()
    {
        echo '<table border="0" cellspacing="0" cellpadding="0" bgcolor="#cccccc" width="100%">';
        echo '<tr><td valign="top"><br />';
        $this->mTopMenuBar->render();
        echo '</td></tr>';
        echo '<tr><td valign="top"><br />';
        $this->mDescPanel->render();
        echo '</td></tr>';
        echo '<tr><td><br />';
        $this->mForm->render();
        echo '</td></tr>';
        echo '</table>';
    }
}
?>
