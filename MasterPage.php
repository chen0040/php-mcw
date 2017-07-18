<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MasterPage
 *
 * @author Xianshun
 */
require_once("Page.php");
require_once('Image.php');
require_once('JQueryMenuBar.php');
require_once('Footer.php');
require_once('CaptionLogo.php');
 
class MasterPage extends Page{
    //put your code here
    protected $mTopMenuBar;
    protected $mTopBanner;
    protected $mFooter;
    protected $mCaption;
    
    public function __construct()
    {
        parent::__construct();

        //background-image:url(themes/black-tie/images/ui-bg_diagonals-thick_8_333333_40x40.png);
        $this->mTopBanner=new Image("banner", "codezone/css/images/logon.png", "height:90px;padding:0px;");
	$this->mTopBanner->setBGColor("#0174BB");
		
        $this->mTopMenuBar=new JQueryMenuBar("topmenu", false);
        $this->mTopMenuBar->addMenuItem("mnuHome", "Home", "index.php", "Main page of the Memetic Computation"); //, "codezone/css/images/menu/home-red-icon.png");
        $this->mTopMenuBar->addMenuItem("mnuApplications", "Applications", "mcw_applications.php", "View problem domains for memetic computation application"); //, "codezone/css/images/menu/Desktop-icon.png");
        $this->mTopMenuBar->addMenuItem("mnuDesigns", "Design Issues", "mcw_designs.php", "Discuss the design issues related to memetic computation"); //, "codezone/css/images/menu/Desktop-icon.png");
        $this->mTopMenuBar->addMenuItem("mnuMemetics", "Memetic Issues", "mcw_memetics.php", "Discuss the memetic aspects of memetic computation"); //, "codezone/css/images/menu/Desktop-icon.png");
        $this->mTopMenuBar->addMenuItem("mnuAuthors", "Authors", "mcw_authors.php", "List researchers in the field of memetic computation"); //, "codezone/css/images/menu/Desktop-icon.png");
        $this->mTopMenuBar->addMenuItem("mnuJournals", "Journals", "mcw_journals.php", "List journal and publication in the field of memetic computation"); //, "codezone/css/images/menu/Desktop-icon.png");
        $this->mTopMenuBar->addMenuItem("mnuDefinitions", "Definitions", "mcw_definitions.php", "List definition related to memetic computation"); //, "codezone/css/images/menu/Desktop-icon.png");
        $this->mTopMenuBar->addMenuItem("mnuAdvSearch", "Search", "mcw_advanced_search.php", "Perform advanced search"); //, "codezone/css/images/menu/Desktop-icon.png");
        $this->mTopMenuBar->addMenuItem("mnuMathHelp", "Math Help", "mcw_math_help.php", "List of mathematical symbols and formula for editing"); //, "codezone/css/images/menu/Desktop-icon.png");
        //$this->mTopMenuBar->addMenuItem("mnuPublish", "Publish", "mcw_publish.php", "View submitted changes"); //, "codezone/css/images/menu/Admin-tools-icon.png");
	$this->mTopMenuBar->setBackground("codezone/css/images/footer2.png");

        $setup_enabled=false;
        $handler=new MySQLHandler();
        $handler->connect();
        $filename=mysql_real_escape_string(getcwd()."\\setup.cfg");
        $handler->disconnect();
        if(file_exists($filename))
        {
            $doc = new DOMDocument();
            $doc->load($filename);

            $setups = $doc->getElementsByTagName("setup");
            foreach($setups as $setup)
            {
                if(strcmp($setup->getAttribute("enabled"), "true")==0)
                {
                    $setup_enabled=true;
                    break;
                }
            }
        }

        if($setup_enabled || $this->hasRole("admin"))
        {
            $this->mTopMenuBar->addMenuItem("mnuSetup", "Setup", "mcw_setup.php", ""); //"codezone/css/images/menu/Netdrive-icon.png");
        }

        
        if($this->hasRole("admin"))
        {
            $this->mTopMenuBar->addMenuItem("mnuAdmin", "Administrator", "mcw_administrator.php", ""); //"codezone/css/images/menu/Admin-tools-icon.png");
        }
            
        if($this->hasLoginned())
        {
            $this->mTopMenuBar->addMenuItem("mnuLogout", "Logout", "logout.php", ""); //"codezone/css/images/menu/Log-Out-icon.png");

        }


        $this->mFooter=new Footer("Footer");
        $this->mCaption=new CaptionLogo("CaptionLogo");
		
    }
}
?>
