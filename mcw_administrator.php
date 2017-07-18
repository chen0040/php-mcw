<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Administrator
 *
 * @author Xianshun
 */
require_once("MasterPage.php");
require_once("AdministratorPanel.php");

class Administrator extends MasterPage{
    //put your code here
    private $mAdministratorPanel;
    public function __construct()
    {
        parent::__construct("Administrator");

        $this->setTitle("Administrator");

        $this->setLayout(new GridLayout("AdministratorLayout", 5, 1));
        $this->add($this->mCaption, 0, 0);
        $this->add($this->mTopBanner, 1, 0);
        $this->add($this->mTopMenuBar, 2, 0);
        

        $this->mAdministratorPanel=new AdministratorPanel("AdministratorPanel");
        $this->add($this->mAdministratorPanel, 3, 0);
        
        $this->add($this->mFooter, 4, 0);

        $this->mTopMenuBar->highlight("Administrator");
    }
}

$page=new Administrator();
if(!($page->hasLoginned() && $page->hasRole('admin')))
{
    header("Location: index.php");
}
$page->render();
?>
