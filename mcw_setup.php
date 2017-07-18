<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Setup
 *
 * @author Xianshun
 */

 require_once('MasterPage.php');
 require_once('DBSetup.php');
 require_once('DBBackup.php');

class Setup extends MasterPage{
    //put your code here
    private $mDBSetup;
    private $mDBBackup;
    public function __construct()
    {
        parent::__construct();

        $this->setTitle("Memetic Computing: Setup");
        
        $this->setLayout(new GridLayout("SetupLayout", 5, 2));
        $this->add($this->mCaption, 0, 0, 2);
        $this->add($this->mTopBanner, 1, 0, 2);
        $this->add($this->mTopMenuBar, 2, 0, 2);

        $this->mTopMenuBar->highlight("Setup");

        $this->mDBSetup=new DBSetup("DBSetup");
        $this->add($this->mDBSetup, 3, 0);
        $this->mLayout->setCellVAlignment(3, 0, "top");
        $this->mLayout->setCellBGColor(3, 0, "white");

        $this->mDBBackup=new DBBackup("DBBackup");
        $this->add($this->mDBBackup, 3, 1);
        $this->mLayout->setCellVAlignment(3, 1, "top");
        $this->mLayout->setCellBGColor(3, 1, "white");

        $this->add($this->mFooter, 4, 0, 2);
    }
}

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

$page=new Setup();

if($setup_enabled || $page->hasRole("admin"))
{
    $page->render();
}
else
{
    header("Location: index.php");
}
?>
