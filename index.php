<?php
require_once("MasterPage.php");
require_once('ArticleViewer.php');
require_once('LoginForm.php');
require_once('JQueryPanel.php');

class Home extends MasterPage
{
    private $mArticleViewer;
    private $mEventLogForm;
	private $mLinkForm;
    private $mLogin;
    private $mDescPanel;
	private $mLeftPanel;
	private $mContentPanel;
    
    public function __construct()
    {
        parent::__construct();

        $this->setTitle("Memetic Computing: Home");
        
        $this->mArticleViewer=new ArticleViewer("ArticleViewer");
        $this->mArticleViewer->setTitle('<b>Articles on Memetic Computing</b>');
        //$this->mThemeLogo=new Image("ThemeLogo", "codezone/css/images/brain.png", "");
		
		
        $this->mLogin=new LoginForm("Login");
		
        $this->mDescPanel=new JQueryPanel('MAIntroduction');
        $this->mDescPanel->setTitle('<b>Memetic Computing</b>');
        $this->mDescPanel->setIcon('codezone/css/images/Automator-icon.png');
        //$this->mDescPanel->setWidth(1000);
        $this->mDescPanel->setHeight(400);
        $this->mDescPanel->setUrl('introduction.html');
       
		$this->mEventLogForm=new JQueryPanel("EventLogForm");
		$this->mEventLogForm->setTitle('<b>Recent Events</b>');
        $this->mEventLogForm->setIcon('codezone/css/images/Automator-icon.png');
        $this->mEventLogForm->setWidth(240);
        $this->mEventLogForm->setHeight(400);
        $this->mEventLogForm->setUrl('news.html');
		
		$this->mLinkForm=new JQueryPanel("LinkForm");
		$this->mLinkForm->setTitle('<b>Links</b>');
        $this->mLinkForm->setIcon('codezone/css/images/Automator-icon.png');
        $this->mLinkForm->setWidth(240);
        $this->mLinkForm->setHeight(400);
        $this->mLinkForm->setUrl('links.html');
		
		$this->mAboutForm=new JQueryPanel("AboutForm");
		$this->mAboutForm->setTitle('<b>About Us</b>');
        $this->mAboutForm->setIcon('codezone/css/images/Automator-icon.png');
        $this->mAboutForm->setWidth(240);
        $this->mAboutForm->setHeight(400);
        $this->mAboutForm->setUrl('about.html');
		
		$this->setLayout(new GridLayout("CurrentLayout", 6, 2));
        $this->add($this->mCaption, 0, 0, 2);
        $this->add($this->mTopBanner, 1, 0, 2);
        $this->add($this->mTopMenuBar, 2, 0, 2);
        //$this->add($this->mThemeLogo, 2, 0);
		
		$this->mContentPanel=new GridLayout("ContentPanel", 2, 2);
		$this->mContentPanel->setCellPadding(8);
		$this->mContentPanel->setBorder(0);
		$this->add($this->mContentPanel, 3, 0, 2);
		
		$this->mLeftPanel=new GridLayout("LeftPanel", 4, 1);
		$this->mLeftPanel->setBorder(0);
		$this->mLeftPanel->setCellPadding(4);
		
		$this->mContentPanel->add($this->mLeftPanel, 0, 0, 1, 2);
		
        $this->mLeftPanel->add($this->mLogin, 0, 0, 1, 1);
		$this->mLeftPanel->add($this->mEventLogForm, 1, 0, 1, 1);
		$this->mLeftPanel->add($this->mLinkForm, 2, 0, 1, 1);
		$this->mLeftPanel->add($this->mAboutForm, 3, 0, 1, 1);
		
		$this->mScriptlet->add($this->mLogin);
		$this->mScriptlet->add($this->mEventLogForm);
		$this->mScriptlet->add($this->mLinkForm);
		$this->mScriptlet->add($this->mAboutForm);
		
        $this->mContentPanel->add($this->mDescPanel, 0, 1);
        $this->mContentPanel->add($this->mArticleViewer, 1, 1);
		
		$this->mScriptlet->add($this->mDescPanel);
		$this->mScriptlet->add($this->mArticleViewer);
		$this->mStylet->add($this->mArticleViewer);
		
        $this->add($this->mFooter, 5, 0, 2);

        $this->mTopMenuBar->highlight('Home');
    }
}
$page=new Home();
$page->render();
?>
