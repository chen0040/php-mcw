<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require('CategoryOrganizer.php');
require_once("MasterPage.php");

class CategoryOrganizationPage extends MasterPage{
    //put your code here
    private $mCategoryOrganizerPanel;
    public function __construct()
    {
        parent::__construct("CategoryOrganizer");

        $this->setTitle("CategoryOrganizer");

        $this->setLayout(new GridLayout("CategoryOrganizerLayout", 1, 1));

        $this->mCategoryOrganizerPanel=new CategoryOrganizer("CategoryOrganizer");
        $this->add($this->mCategoryOrganizerPanel, 0, 0);

        $this->setBGColor('white');
    }
}

$page=new CategoryOrganizationPage();
//if(!$page->hasLoginned())
//{
//    header("Location: index.php");
//}
$page->render();
?>
